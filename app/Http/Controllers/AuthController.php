<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => [
                'required',
                'confirmed',
                Password::min(8)
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
            return redirect()->back()->withErrors($validator)->withInput();
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

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please login with your credentials.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Check if the email exists
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No account exists with that email address.',
            ])->onlyInput('email');
        }

        // Email exists — now check the password manually
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect.',
            ])->onlyInput('email');
        }

        // Credentials are correct — now attempt login
        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors([
                'email' => 'Unable to sign in. Please try again.',
            ])->onlyInput('email');
        }

        // Account is deactivated / banned
        if (!$user->isActive()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}