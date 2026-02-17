@extends('layouts.auth')

@section('title', 'Forgot Password - Foundify')

@section('content')
<div class="forgot-container">
    <!-- Left Panel - Brand & Info -->
    <div class="left-panel">
        <div class="brand-wrapper">
            <div class="logo-icon">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="brand-name">Foundify</h1>
            <p class="brand-tagline">Find what's lost, return what's found</p>
        </div>

        <div class="help-tips">
            <div class="tip-item">
                <div class="tip-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="tip-text">
                    <h4>Check your inbox</h4>
                    <p>We'll send reset instructions to your email</p>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="tip-text">
                    <h4>Quick process</h4>
                    <p>Reset link expires in 60 minutes</p>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="tip-text">
                    <h4>Secure reset</h4>
                    <p>Your account safety is our priority</p>
                </div>
            </div>
        </div>

        <div class="quote-box">
            <i class="fas fa-quote-left"></i>
            <p>Don't worry, we'll help you get back in.</p>
        </div>
    </div>

    <!-- Right Panel - Forgot Password Form -->
    <div class="right-panel">
        <div class="form-container">
            <div class="form-header">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to login
                </a>
                <h2>Reset password</h2>
                <p class="text-secondary">Enter your email to receive reset instructions</p>
            </div>

            @if(session('status'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
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
                    <label for="email">Email address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="your@email.com"
                               required
                               autofocus>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn" id="submitBtn">
                    <span>Send reset link</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-glow: rgba(255, 20, 147, 0.15);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-input: #222;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --text-muted: #666;
    --success: #00fa9a;
    --success-bg: rgba(0, 250, 154, 0.1);
    --error: #ff4444;
    --error-bg: rgba(255, 68, 68, 0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--bg-dark);
    color: var(--text-primary);
}

/* Forgot Container */
.forgot-container {
    display: flex;
    min-height: 100vh;
}

/* Left Panel */
.left-panel {
    flex: 1;
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    padding: 60px 48px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-right: 1px solid var(--border-color);
}

.brand-wrapper {
    margin-bottom: 60px;
}

.logo-icon {
    width: 56px;
    height: 56px;
    background: var(--primary);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 24px;
    box-shadow: 0 10px 20px var(--primary-glow);
}

.brand-name {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
    background: linear-gradient(to right, white, var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.brand-tagline {
    color: var(--text-secondary);
    font-size: 14px;
}

/* Help Tips */
.help-tips {
    display: flex;
    flex-direction: column;
    gap: 24px;
    margin-bottom: 48px;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 16px;
}

.tip-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 20, 147, 0.1);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 20px;
}

.tip-text h4 {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 4px;
    color: white;
}

.tip-text p {
    font-size: 13px;
    color: var(--text-secondary);
    line-height: 1.5;
}

/* Quote Box */
.quote-box {
    background: rgba(255, 20, 147, 0.08);
    border-radius: 16px;
    padding: 24px;
}

.quote-box i {
    color: var(--primary);
    font-size: 20px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.quote-box p {
    font-size: 18px;
    font-style: italic;
    color: white;
    line-height: 1.5;
}

/* Right Panel */
.right-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    background: var(--bg-dark);
}

.form-container {
    width: 100%;
    max-width: 420px;
}

.form-header {
    margin-bottom: 32px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 20px;
    transition: var(--transition);
}

.back-link:hover {
    color: var(--primary);
    transform: translateX(-3px);
}

.form-header h2 {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 8px;
    color: white;
}

.form-header p {
    color: var(--text-secondary);
    font-size: 14px;
}

/* Success Message */
.success-message {
    background: var(--success-bg);
    border: 1px solid var(--success);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.success-message i {
    color: var(--success);
    font-size: 20px;
}

.success-message p {
    color: var(--success);
    font-size: 14px;
    margin: 0;
}

/* Error Message */
.error-message {
    background: var(--error-bg);
    border: 1px solid var(--error);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.error-message i {
    color: var(--error);
    font-size: 20px;
    flex-shrink: 0;
}

.error-message p {
    color: var(--error);
    font-size: 13px;
    margin: 0;
}

.error-message p:not(:last-child) {
    margin-bottom: 4px;
}

/* Forgot Form */
.forgot-form {
    background: var(--bg-card);
    border-radius: 24px;
    padding: 32px;
    border: 1px solid var(--border-color);
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    color: var(--text-secondary);
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 8px;
}

.input-group {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 16px;
}

.input-group input {
    width: 100%;
    padding: 14px 16px 14px 48px;
    background: var(--bg-input);
    border: 2px solid var(--border-color);
    border-radius: 14px;
    color: white;
    font-size: 15px;
    transition: var(--transition);
}

.input-group input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-glow);
}

.input-group input::placeholder {
    color: var(--text-muted);
}

/* Submit Button */
.submit-btn {
    width: 100%;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 40px;
    padding: 16px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: var(--transition);
}

.submit-btn:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px var(--primary-glow);
}

.submit-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Responsive */
@media (max-width: 992px) {
    .forgot-container {
        flex-direction: column;
    }
    
    .left-panel {
        padding: 48px 32px;
    }
    
    .right-panel {
        padding: 32px;
    }
}

@media (max-width: 768px) {
    .left-panel {
        display: none;
    }
    
    .right-panel {
        padding: 24px;
    }
    
    .forgot-form {
        padding: 24px;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
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
    const submitBtn = document.getElementById('submitBtn');
    const forgotForm = document.querySelector('.forgot-form');

    if (submitBtn && forgotForm) {
        forgotForm.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endsection