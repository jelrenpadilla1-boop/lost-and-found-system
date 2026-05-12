<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    /**
     * Show the reset password form.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.password.reset')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset the given user's password.
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)
                    ->max(64)
                    ->mixedCase()       // at least one uppercase + one lowercase
                    ->numbers()         // at least one number
                    ->symbols()         // at least one special character
                    ->uncompromised(),  // not in known data breaches
            ],
        ], [
            'password.required'         => 'A new password is required.',
            'password.confirmed'        => 'The password confirmation does not match.',
            'password.min'              => 'The password must be at least 8 characters.',
            'password.max'              => 'The password may not be greater than 64 characters.',
            'password.mixedcase'        => 'The password must contain at least one uppercase and one lowercase letter.',
            'password.numbers'          => 'The password must contain at least one number.',
            'password.symbols'          => 'The password must contain at least one special character (e.g., !@#$%^&*).',
            'password.uncompromised'    => 'This password appears in a data breach. Please choose a different one.',
            'email.required'            => 'Your email address is required.',
            'email.email'               => 'Please provide a valid email address.',
            'token.required'            => 'The password reset token is missing.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Reset the password
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password reset successfully! You can now login with your new password.');
        }

        // If the reset fails (invalid token, email not found, etc.)
        return back()
            ->withErrors(['email' => trans($response)])
            ->withInput();
    }
}