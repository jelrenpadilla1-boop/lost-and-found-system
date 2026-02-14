@extends('layouts.auth')
@section('title', 'Forgot Password - Foundify')

@section('content')
<div class="forgot-container">
    <!-- Left Panel - Brand & Info (same as login) -->
    <div class="left-panel">
        <div class="brand-wrapper">
            <div class="logo-circle">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="brand-name">Foundify</h1>
            <p class="brand-tagline">Find what's lost, return what's found</p>
        </div>

        <div class="quote">
            <p>"Don't worry, we'll help you get back in."</p>
        </div>
    </div>

    <!-- Right Panel - Forgot Password Form -->
    <div class="right-panel">
        <div class="form-wrapper">
            <div class="form-header">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Login
                </a>
                <h2>Reset Password</h2>
                <p>Enter your email to receive reset instructions</p>
            </div>

            @if(session('status'))
                <div class="alert-box success">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

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

            <form method="POST" action="{{ route('password.email') }}" class="forgot-form">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter your email"
                           required
                           autofocus>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Send Reset Link</span>
                    <i class="fas fa-paper-plane btn-icon"></i>
                </button>
            </form>

            <!-- Help Text -->
            <div class="help-text">
                <p>
                    <i class="fas fa-clock"></i>
                    The reset link will expire in 60 minutes
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Additional styles for forgot password page */
    .forgot-container {
        display: flex;
        min-height: 100vh;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--dark-gray);
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 20px;
        transition: var(--transition);
    }

    .back-link:hover {
        color: var(--black);
    }

    .alert-box.success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: var(--border-radius);
        padding: 16px;
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }

    .alert-box.success .alert-icon {
        color: #166534;
        font-size: 20px;
    }

    .alert-box.success .alert-content p {
        color: #166534;
        font-size: 14px;
    }

    .alert-box.error {
        background: var(--error-bg);
        border: 1px solid var(--error-border);
        border-radius: var(--border-radius);
        padding: 16px;
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }

    .forgot-form {
        background: var(--white);
        padding: 32px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }

    .help-text {
        margin-top: 24px;
        text-align: center;
    }

    .help-text p {
        color: var(--gray);
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .help-text i {
        color: var(--dark-gray);
    }

    @media (max-width: 992px) {
        .forgot-container {
            flex-direction: column;
        }

        .left-panel {
            padding: 32px;
        }

        .right-panel {
            padding: 32px;
        }
    }

    @media (max-width: 640px) {
        .left-panel {
            display: none;
        }

        .right-panel {
            padding: 20px;
        }

        .forgot-form {
            padding: 24px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus email field
        const emailInput = document.getElementById('email');
        if (emailInput) {
            setTimeout(() => {
                emailInput.focus();
            }, 300);
        }

        // Form submission loading state
        const submitBtn = document.querySelector('.submit-btn');
        const forgotForm = document.querySelector('.forgot-form');

        if (submitBtn && forgotForm) {
            forgotForm.addEventListener('submit', function() {
                const btnText = submitBtn.querySelector('.btn-text');
                btnText.textContent = 'Sending...';
                submitBtn.disabled = true;
            });
        }
    });
</script>
@endsection