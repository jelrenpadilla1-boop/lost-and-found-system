<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRules;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => [
                'required',
                'confirmed',
                PasswordRules::min(8)
                    ->max(64)
                    ->mixedCase()       // at least one upper + one lower
                    ->numbers()         // at least one number
                    ->symbols()         // at least one special character
                    ->uncompromised(),  // not in any known data breach
            ],
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'terms'     => 'required|accepted',
        ], [
            'name.required'      => 'Your name is required.',
            'email.required'     => 'An email address is required.',
            'email.unique'       => 'That email address is already registered.',
            'password.required'  => 'A password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'terms.accepted'     => 'You must accept the Terms of Service and Privacy Policy.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'role'      => 'user',
        ]);

        // Notify all admins about the new registration
        try {
            $admins = User::where('role', 'admin')->pluck('id');
            foreach ($admins as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type'    => 'info',
                    'title'   => '👤 New User Registered',
                    'body'    => "{$user->name} just created an account.",
                    'url'     => route('admin.users.show', $user->id),
                    'data'    => json_encode(['icon' => 'user-plus', 'color' => '#7c3aed']),
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to notify admins of new user: ' . $e->getMessage());
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'  => 'Registration successful! Please login with your credentials.',
            'user'     => $user,
            'token'    => $token,
            'is_admin' => $user->isAdmin(),
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Check if the email exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'errors' => [
                    'email' => ['No account exists with that email address.']
                ]
            ], 401);
        }

        // Email exists — now check the password manually
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => [
                    'password' => ['The password you entered is incorrect.']
                ]
            ], 401);
        }

        // Credentials are correct — now attempt login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'errors' => [
                    'email' => ['Unable to sign in. Please try again.']
                ]
            ], 401);
        }

        // Account is deactivated / banned
        if (!$user->isActive()) {
            Auth::logout();
            return response()->json([
                'errors' => [
                    'email' => ['Your account has been deactivated. Please contact support.']
                ]
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'  => 'Login successful!',
            'user'     => $user,
            'token'    => $token,
            'is_admin' => $user->isAdmin(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Send a password reset link to the given user.
     * POST /api/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Always return success to avoid email enumeration
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'If that email exists, a reset link has been sent.'
            ]);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent to your email.'
            ]);
        }

        return response()->json([
            'message' => 'Unable to send reset link. Please try again later.'
        ], 500);
    }

    /**
     * Reset the user's password.
     * POST /api/reset-password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required|string',
            'email'    => 'required|email',
            'password' => [
                'required',
                'confirmed',
                PasswordRules::min(8)
                    ->max(64)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ], [
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                // Revoke all existing tokens for security
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been reset successfully. Please log in again.'
            ]);
        }

        return response()->json([
            'message' => __($status)
        ], 400);
    }
}