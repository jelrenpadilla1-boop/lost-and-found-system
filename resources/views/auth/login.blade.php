@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="login-container">
    <!-- Left Panel - Brand & Info -->
    <div class="left-panel">
        <div class="brand-wrapper">
            <div class="logo-circle">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="brand-name">Foundify</h1>
            <p class="brand-tagline">Find what's lost, return what's found</p>
        </div>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="feature-text">
                    <h4>Secure & Safe</h4>
                    <p>Your data is protected with encryption</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="feature-text">
                    <h4>Community Driven</h4>
                    <p>Help others find their belongings</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="feature-text">
                    <h4>Quick Matching</h4>
                    <p>AI-powered match suggestions</p>
                </div>
            </div>
        </div>

        <div class="quote">
            <p>"Together we can make lost things found."</p>
        </div>
    </div>

    <!-- Right Panel - Login Form -->
    <div class="right-panel">
        <div class="form-wrapper">
            <div class="form-header">
                <h2>Welcome Back</h2>
                <p>Sign in to continue to your account</p>
            </div>

            @if($errors->any())
                <div class="alert-box">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Please check your input</h4>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="login-form">
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
                           required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="password-field">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Enter your password"
                               required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkbox-custom"></span>
                        Remember me
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Sign In</span>
                    <i class="fas fa-arrow-right btn-icon"></i>
                </button>

                <!-- Divider -->
                <div class="divider">
                    <span>or</span>
                </div>

                <!-- Sign Up Link -->
                <div class="signup-link">
                    <p>Don't have an account?
                        <a href="{{ route('register') }}">Create Account</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* CSS Variables - Black & White Theme */
    :root {
        --white: #ffffff;
        --off-white: #fafafa;
        --light-gray: #f5f5f5;
        --medium-gray: #e5e5e5;
        --gray: #a3a3a3;
        --dark-gray: #737373;
        --darker-gray: #404040;
        --black: #171717;
        --border: #e5e5e5;
        --error: #dc2626;
        --error-bg: #fef2f2;
        --error-border: #fecaca;
        --border-radius: 12px;
        --border-radius-sm: 10px;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        --transition: all 0.2s ease;
    }

    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--off-white);
        color: var(--black);
        line-height: 1.5;
    }

    /* Layout */
    .login-container {
        display: flex;
        min-height: 100vh;
    }

    /* Left Panel - Black & White */
    .left-panel {
        flex: 1;
        background: var(--black);
        color: var(--white);
        padding: 60px 48px;
        display: flex;
        flex-direction: column;
    }

    .brand-wrapper {
        text-align: center;
        margin-bottom: 64px;
    }

    .logo-circle {
        width: 80px;
        height: 80px;
        background: var(--darker-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
        border: 2px solid var(--dark-gray);
        color: var(--white);
    }

    .brand-name {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
        color: var(--white);
    }

    .brand-tagline {
        font-size: 15px;
        opacity: 0.8;
        max-width: 300px;
        margin: 0 auto;
        color: var(--gray);
    }

    .features {
        flex: 1;
        margin-bottom: 48px;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 32px;
    }

    .feature-item:last-child {
        margin-bottom: 0;
    }

    .feature-icon {
        width: 44px;
        height: 44px;
        background: var(--darker-gray);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        color: var(--white);
        border: 1px solid var(--dark-gray);
    }

    .feature-text h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--white);
    }

    .feature-text p {
        font-size: 14px;
        opacity: 0.7;
        line-height: 1.5;
        color: var(--gray);
    }

    .quote {
        padding: 24px;
        background: var(--darker-gray);
        border-radius: var(--border-radius);
        text-align: center;
        font-style: italic;
        font-size: 15px;
        border: 1px solid var(--dark-gray);
        color: var(--white);
    }

    /* Right Panel */
    .right-panel {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px;
        background: var(--white);
    }

    .form-wrapper {
        width: 100%;
        max-width: 440px;
    }

    .form-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .form-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }

    .form-header p {
        color: var(--dark-gray);
        font-size: 15px;
    }

    /* Alert Box */
    .alert-box {
        background: var(--error-bg);
        border: 1px solid var(--error-border);
        border-radius: var(--border-radius);
        padding: 20px;
        display: flex;
        gap: 16px;
        margin-bottom: 32px;
    }

    .alert-icon {
        color: var(--error);
        font-size: 20px;
        flex-shrink: 0;
    }

    .alert-content h4 {
        font-size: 14px;
        font-weight: 600;
        color: #991b1b;
        margin-bottom: 4px;
    }

    .alert-content p {
        font-size: 13px;
        color: #7f1d1d;
    }

    /* Form Styles */
    .login-form {
        background: var(--white);
        padding: 40px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }

    .form-group {
        margin-bottom: 28px;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 500;
        color: var(--darker-gray);
        margin-bottom: 10px;
    }

    .form-group label i {
        color: var(--black);
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 14px 18px;
        border: 1px solid var(--border);
        border-radius: var(--border-radius-sm);
        font-size: 15px;
        transition: var(--transition);
        background: var(--white);
        color: var(--black);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--black);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }

    .form-group input::placeholder {
        color: var(--gray);
    }

    .password-field {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        font-size: 16px;
        padding: 4px;
        transition: var(--transition);
    }

    .toggle-password:hover {
        color: var(--black);
    }

    .error-message {
        display: block;
        color: var(--error);
        font-size: 13px;
        margin-top: 8px;
        font-weight: 500;
    }

    /* Form Options */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 14px;
        color: var(--darker-gray);
    }

    .checkbox-label input {
        display: none;
    }

    .checkbox-custom {
        width: 18px;
        height: 18px;
        border: 1px solid var(--border);
        border-radius: 4px;
        display: inline-block;
        position: relative;
        transition: var(--transition);
        background: var(--white);
    }

    .checkbox-label input:checked + .checkbox-custom {
        background: var(--black);
        border-color: var(--black);
    }

    .checkbox-label input:checked + .checkbox-custom::after {
        content: '✓';
        position: absolute;
        color: var(--white);
        font-size: 12px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .forgot-link {
        color: var(--black);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: var(--transition);
    }

    .forgot-link:hover {
        color: var(--darker-gray);
        text-decoration: underline;
    }

    /* Submit Button */
    .submit-btn {
        width: 100%;
        background: var(--black);
        color: var(--white);
        border: 1px solid var(--black);
        border-radius: var(--border-radius-sm);
        padding: 16px 20px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: var(--transition);
        margin-bottom: 28px;
    }

    .submit-btn:hover {
        background: var(--darker-gray);
        border-color: var(--darker-gray);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-icon {
        transition: transform 0.3s ease;
    }

    .submit-btn:hover .btn-icon {
        transform: translateX(4px);
    }

    /* Divider */
    .divider {
        position: relative;
        text-align: center;
        margin: 32px 0;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: var(--border);
    }

    .divider span {
        position: relative;
        background: var(--white);
        padding: 0 18px;
        color: var(--dark-gray);
        font-size: 14px;
        font-weight: 500;
    }

    /* Sign Up Link */
    .signup-link {
        text-align: center;
    }

    .signup-link p {
        color: var(--dark-gray);
        font-size: 14px;
    }

    .signup-link a {
        color: var(--black);
        text-decoration: none;
        font-weight: 600;
        margin-left: 4px;
        transition: var(--transition);
    }

    .signup-link a:hover {
        color: var(--darker-gray);
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .left-panel {
            padding: 48px 32px;
        }
        
        .right-panel {
            padding: 32px;
        }
        
        .login-form {
            padding: 32px;
        }
    }

    @media (max-width: 992px) {
        .login-container {
            flex-direction: column;
        }

        .left-panel {
            padding: 48px 32px;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 32px;
        }

        .feature-item {
            flex: 1;
            min-width: 240px;
            margin-bottom: 0;
        }

        .right-panel {
            padding: 32px;
        }
    }

    @media (max-width: 768px) {
        .features {
            flex-direction: column;
        }

        .feature-item {
            width: 100%;
        }

        .login-form {
            padding: 28px;
        }

        .form-header h2 {
            font-size: 28px;
        }
    }

    @media (max-width: 640px) {
        .left-panel {
            display: none;
        }

        .right-panel {
            padding: 24px;
        }

        .form-wrapper {
            max-width: 100%;
        }

        .login-form {
            padding: 24px;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-form {
        animation: fadeIn 0.5s ease forwards;
    }

    /* Utility */
    .border-bottom {
        border-bottom: 1px solid var(--border);
    }
    
    .border-top {
        border-top: 1px solid var(--border);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        // Auto-focus email field
        const emailInput = document.getElementById('email');
        if (emailInput) {
            setTimeout(() => {
                emailInput.focus();
            }, 300);
        }

        // Form submission loading state
        const submitBtn = document.querySelector('.submit-btn');
        const loginForm = document.querySelector('.login-form');

        if (submitBtn && loginForm) {
            loginForm.addEventListener('submit', function() {
                const btnText = submitBtn.querySelector('.btn-text');
                const originalText = btnText.textContent;

                btnText.textContent = 'Signing in...';
                submitBtn.disabled = true;

                // Re-enable after 5 seconds (prevents stuck loading state)
                setTimeout(() => {
                    btnText.textContent = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        }

        // Remove error messages on input focus
        const formInputs = document.querySelectorAll('.form-group input');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                const errorMessage = this.parentElement.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection