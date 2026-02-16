@extends('layouts.auth')
@section('title', 'Foundify - Login')

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
            <div class="quote-decoration"></div>
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
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
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
    /* CSS Variables - Black & Pink Theme (Matching welcome.blade.php) */
    :root {
        --black: #000000;
        --black-light: #1a1a1a;
        --black-lighter: #2a2a2a;
        --pink: #ff1493;
        --pink-light: #ff69b4;
        --pink-dark: #c71585;
        --pink-glow: rgba(255, 20, 147, 0.3);
        --white: #ffffff;
        --off-white: #f5f5f5;
        --gray: #a0a0a0;
        --dark-gray: #666666;
        --border: #333333;
        --error: #ff4444;
        --error-bg: rgba(255, 68, 68, 0.1);
        --error-border: #ff4444;
        --border-radius: 16px;
        --border-radius-sm: 12px;
        --shadow: 0 10px 30px rgba(255, 20, 147, 0.15);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--black);
        color: var(--white);
        line-height: 1.5;
    }

    /* Layout */
    .login-container {
        display: flex;
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .login-container::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, var(--pink-glow) 0%, transparent 70%);
        opacity: 0.1;
        animation: pulse 8s infinite;
        pointer-events: none;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.1); opacity: 0.15; }
    }

    /* Left Panel - Black & Pink Theme */
    .left-panel {
        flex: 1;
        background: var(--black);
        color: var(--white);
        padding: 60px 48px;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 1;
        border-right: 1px solid var(--border);
    }

    .brand-wrapper {
        text-align: center;
        margin-bottom: 64px;
        position: relative;
    }

    .logo-circle {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, var(--pink), var(--pink-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 36px;
        color: var(--white);
        box-shadow: 0 0 30px var(--pink-glow);
        transition: var(--transition);
        animation: float 6s infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .logo-circle:hover {
        transform: scale(1.1) rotate(360deg);
        box-shadow: 0 0 50px var(--pink-glow);
    }

    .brand-name {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
        color: var(--white);
        background: linear-gradient(135deg, var(--white), var(--pink-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .brand-tagline {
        font-size: 15px;
        color: var(--gray);
        max-width: 300px;
        margin: 0 auto;
        position: relative;
        display: inline-block;
    }

    .brand-tagline::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 2px;
        background: var(--pink);
        box-shadow: 0 0 10px var(--pink-glow);
        animation: expand 3s infinite;
    }

    @keyframes expand {
        0%, 100% { width: 50px; }
        50% { width: 80px; }
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
        padding: 12px;
        border-radius: 16px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .feature-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 20, 147, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .feature-item:hover {
        transform: translateX(10px);
        background: var(--black-light);
    }

    .feature-item:hover::before {
        left: 100%;
    }

    .feature-item:hover .feature-icon {
        transform: rotate(360deg);
        background: var(--pink);
        box-shadow: 0 0 30px var(--pink-glow);
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        background: var(--black-light);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        color: var(--pink);
        border: 1px solid var(--border);
        transition: var(--transition);
        box-shadow: 0 0 15px var(--pink-glow);
    }

    .feature-text h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--white);
        transition: color 0.3s ease;
    }

    .feature-item:hover .feature-text h4 {
        color: var(--pink);
    }

    .feature-text p {
        font-size: 14px;
        color: var(--gray);
        line-height: 1.5;
        transition: color 0.3s ease;
    }

    .feature-item:hover .feature-text p {
        color: var(--off-white);
    }

    .quote {
        padding: 28px;
        background: var(--black-light);
        border-radius: 24px;
        text-align: center;
        font-style: italic;
        font-size: 16px;
        border: 1px solid var(--border);
        color: var(--white);
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .quote:hover {
        border-color: var(--pink);
        box-shadow: 0 0 30px var(--pink-glow);
        transform: translateY(-5px);
    }

    .quote p {
        position: relative;
        z-index: 1;
    }

    .quote-decoration {
        position: absolute;
        bottom: -20px;
        right: -20px;
        width: 100px;
        height: 100px;
        background: var(--pink);
        border-radius: 50%;
        opacity: 0.1;
        transition: var(--transition);
    }

    .quote:hover .quote-decoration {
        transform: scale(1.5);
        opacity: 0.2;
    }

    /* Right Panel */
    .right-panel {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px;
        background: var(--black);
        position: relative;
        z-index: 1;
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
        font-size: 36px;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 12px;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, var(--white), var(--pink-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .form-header p {
        color: var(--gray);
        font-size: 15px;
    }

    /* Alert Box */
    .alert-box {
        background: var(--error-bg);
        border: 1px solid var(--error-border);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        gap: 16px;
        margin-bottom: 32px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-icon {
        color: var(--error);
        font-size: 20px;
        flex-shrink: 0;
    }

    .alert-content h4 {
        font-size: 14px;
        font-weight: 600;
        color: var(--error);
        margin-bottom: 4px;
    }

    .alert-content p {
        font-size: 13px;
        color: #ff6b6b;
    }

    /* Form Styles */
    .login-form {
        background: var(--black-light);
        padding: 40px;
        border-radius: 24px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .login-form::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, var(--pink-glow) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }

    .login-form:hover {
        border-color: var(--pink);
        box-shadow: 0 0 40px var(--pink-glow);
        transform: translateY(-5px);
    }

    .login-form:hover::before {
        opacity: 0.1;
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
        color: var(--white);
        margin-bottom: 10px;
    }

    .form-group label i {
        color: var(--pink);
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid var(--border);
        border-radius: 14px;
        font-size: 15px;
        transition: var(--transition);
        background: var(--black);
        color: var(--white);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--pink);
        background: var(--black-light);
        box-shadow: 0 0 20px var(--pink-glow);
    }

    .form-group input::placeholder {
        color: var(--dark-gray);
    }

    .password-field {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        font-size: 16px;
        padding: 8px;
        transition: var(--transition);
        border-radius: 8px;
    }

    .toggle-password:hover {
        color: var(--pink);
        background: var(--black-lighter);
    }

    .error-message {
        display: block;
        color: var(--error);
        font-size: 13px;
        margin-top: 8px;
        font-weight: 500;
        animation: fadeIn 0.3s ease;
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
        color: var(--gray);
        transition: var(--transition);
    }

    .checkbox-label:hover {
        color: var(--white);
    }

    .checkbox-label input {
        display: none;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border);
        border-radius: 6px;
        display: inline-block;
        position: relative;
        transition: var(--transition);
        background: var(--black);
    }

    .checkbox-label:hover .checkbox-custom {
        border-color: var(--pink);
        box-shadow: 0 0 15px var(--pink-glow);
    }

    .checkbox-label input:checked + .checkbox-custom {
        background: var(--pink);
        border-color: var(--pink);
        box-shadow: 0 0 15px var(--pink-glow);
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
        color: var(--gray);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: var(--transition);
        position: relative;
    }

    .forgot-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background: var(--pink);
        transition: width 0.3s ease;
        box-shadow: 0 0 10px var(--pink-glow);
    }

    .forgot-link:hover {
        color: var(--pink);
    }

    .forgot-link:hover::after {
        width: 100%;
    }

    /* Submit Button */
    .submit-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--pink), var(--pink-light));
        color: var(--white);
        border: none;
        border-radius: 14px;
        padding: 18px 20px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: var(--transition);
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 20px var(--pink-glow);
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 40px var(--pink-glow);
    }

    .submit-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-icon {
        transition: transform 0.3s ease;
    }

    .submit-btn:hover .btn-icon {
        transform: translateX(5px);
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
        background: linear-gradient(90deg, transparent, var(--pink), transparent);
    }

    .divider span {
        position: relative;
        background: var(--black-light);
        padding: 0 20px;
        color: var(--gray);
        font-size: 14px;
        font-weight: 500;
        border: 1px solid var(--border);
        border-radius: 30px;
    }

    /* Sign Up Link */
    .signup-link {
        text-align: center;
    }

    .signup-link p {
        color: var(--gray);
        font-size: 14px;
    }

    .signup-link a {
        color: var(--pink);
        text-decoration: none;
        font-weight: 600;
        margin-left: 4px;
        transition: var(--transition);
        position: relative;
    }

    .signup-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background: var(--pink);
        transition: width 0.3s ease;
        box-shadow: 0 0 10px var(--pink-glow);
    }

    .signup-link a:hover {
        color: var(--pink-light);
    }

    .signup-link a:hover::after {
        width: 100%;
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
            font-size: 32px;
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

        .form-header h2 {
            font-size: 28px;
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

    .login-form,
    .feature-item,
    .quote {
        animation: fadeIn 0.5s ease forwards;
    }

    /* Utility */
    .border-bottom {
        border-bottom: 1px solid var(--border);
    }
    
    .border-top {
        border-top: 1px solid var(--border);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: var(--black);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--pink);
        border-radius: 5px;
        box-shadow: 0 0 10px var(--pink-glow);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--pink-light);
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
                
                // Add glow effect on toggle
                this.style.transform = 'translateY(-50%) scale(1.1)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 200);
            });
        }

        // Auto-focus email field with animation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            setTimeout(() => {
                emailInput.focus();
                emailInput.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    emailInput.style.transform = 'scale(1)';
                }, 200);
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

        // Remove error messages on input focus with animation
        const formInputs = document.querySelectorAll('.form-group input');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                const errorMessage = this.parentElement.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                        errorMessage.style.animation = '';
                    }, 300);
                }
                
                // Add focus glow
                this.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            });
        });

        // Add floating animation to feature icons
        const featureIcons = document.querySelectorAll('.feature-icon');
        featureIcons.forEach((icon, index) => {
            icon.style.animation = `float 6s infinite ${index * 0.5}s`;
        });

        // Parallax effect on scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const leftPanel = document.querySelector('.left-panel');
            const rightPanel = document.querySelector('.right-panel');
            
            if (leftPanel) {
                leftPanel.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
            if (rightPanel) {
                rightPanel.style.transform = `translateY(${scrolled * -0.05}px)`;
            }
        });
    });
</script>
@endsection