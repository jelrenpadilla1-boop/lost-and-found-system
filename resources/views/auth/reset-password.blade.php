@extends('layouts.auth')
@section('title', 'Reset Password - Foundify')

@section('content')
<div class="reset-container">
    <!-- Left Panel - Brand & Info -->
    <div class="left-panel">
        <div class="brand-wrapper">
            <div class="logo-circle">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="brand-name">Foundify</h1>
            <p class="brand-tagline">Create your new password</p>
        </div>

        <div class="quote">
            <p>"Almost there! Choose a strong password."</p>
        </div>
    </div>

    <!-- Right Panel - Reset Password Form -->
    <div class="right-panel">
        <div class="form-wrapper">
            <div class="form-header">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
                <h2>Set New Password</h2>
                <p>Your new password must be different from previous ones</p>
            </div>

            @if($errors->any())
                <div class="alert-box error">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="reset-form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ $email ?? old('email') }}"
                           placeholder="Enter your email"
                           required
                           readonly>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> New Password
                    </label>
                    <div class="password-field">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Enter new password"
                               required>
                        <button type="button" class="toggle-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-hint">
                        <i class="fas fa-info-circle"></i>
                        Minimum 8 characters
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <div class="password-field">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Confirm new password"
                               required>
                        <button type="button" class="toggle-password" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Reset Password</span>
                    <i class="fas fa-check-circle btn-icon"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .reset-container {
        display: flex;
        min-height: 100vh;
    }

    .password-hint {
        font-size: 12px;
        color: var(--gray);
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    input[readonly] {
        background: var(--light-gray);
        cursor: not-allowed;
        opacity: 0.8;
    }

    .reset-form {
        background: var(--white);
        padding: 32px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }

    @media (max-width: 992px) {
        .reset-container {
            flex-direction: column;
        }
    }

    @media (max-width: 640px) {
        .left-panel {
            display: none;
        }

        .reset-form {
            padding: 24px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility for multiple fields
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                
                if (input) {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                }
            });
        });

        // Auto-focus password field
        setTimeout(() => {
            document.getElementById('password').focus();
        }, 300);
    });
</script>
@endsection