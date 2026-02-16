@extends('layouts.auth')

@section('title', 'Reset Password - Foundify')

@section('content')
<div class="auth-container">
    <!-- Left Panel - Brand & Info -->
    <div class="left-panel">
        <div class="brand-wrapper">
            <div class="logo-circle">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="brand-name">Foundify</h1>
            <p class="brand-tagline">Reset your password</p>
        </div>
        
        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="feature-text">
                    <h4>Secure Reset</h4>
                    <p>Password reset with email verification</p>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="feature-text">
                    <h4>Quick Process</h4>
                    <p>Reset your password in minutes</p>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="feature-text">
                    <h4>Safe & Secure</h4>
                    <p>Your data is always protected</p>
                </div>
            </div>
        </div>
        
        <div class="quote">
            <p>"Don't worry, we'll help you get back in."</p>
            <div class="quote-decoration"></div>
        </div>
    </div>
    
    <!-- Right Panel - Reset Password Form -->
    <div class="right-panel">
        <div class="form-wrapper">
            <div class="form-header">
                <h2>Reset Password</h2>
                <p>Enter your email to receive a reset link</p>
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
                        <h4>Please check your input</h4>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                @csrf
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <div class="input-wrapper">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email"
                               required 
                               autofocus>
                        <div class="input-focus-effect"></div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Send Reset Link</span>
                    <i class="fas fa-paper-plane btn-icon"></i>
                    <div class="btn-glow"></div>
                </button>
                
                <!-- Divider -->
                <div class="divider">
                    <span>Remember your password?</span>
                </div>
                
                <!-- Login Link -->
                <div class="login-link">
                    <p>
                        <a href="{{ route('login') }}">Back to Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-dark: #c71585;
    --primary-glow: rgba(255, 20, 147, 0.3);
    --black: #000000;
    --black-light: #1a1a1a;
    --black-lighter: #2a2a2a;
    --white: #ffffff;
    --off-white: #f5f5f5;
    --gray: #a0a0a0;
    --dark-gray: #666666;
    --border: #333333;
    --success: #00fa9a;
    --error: #ff4444;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Auth Container */
.auth-container {
    display: flex;
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    background: var(--black);
}

.auth-container::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
    opacity: 0.1;
    animation: pulse 8s infinite;
    pointer-events: none;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.1; }
    50% { transform: scale(1.1); opacity: 0.15; }
}

/* Left Panel */
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
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 40px;
    color: var(--white);
    box-shadow: 0 0 30px var(--primary-glow);
    transition: var(--transition);
    animation: float 6s infinite;
    position: relative;
    overflow: hidden;
}

.logo-circle::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
}

.logo-circle:hover {
    transform: scale(1.1) rotate(360deg);
    box-shadow: 0 0 50px var(--primary-glow);
}

.logo-circle:hover::before {
    opacity: 1;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.brand-name {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 12px;
    background: linear-gradient(135deg, var(--white), var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    display: inline-block;
}

.brand-name::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: var(--primary);
    box-shadow: 0 0 10px var(--primary-glow);
    animation: expandWidth 3s infinite;
}

@keyframes expandWidth {
    0%, 100% { width: 50px; }
    50% { width: 100px; }
}

.brand-tagline {
    font-size: 16px;
    color: var(--gray);
    max-width: 300px;
    margin: 20px auto 0;
}

/* Features */
.features {
    flex: 1;
    margin-bottom: 48px;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 32px;
    padding: 16px;
    border-radius: 16px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    background: transparent;
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
    border: 1px solid var(--border);
}

.feature-item:hover::before {
    left: 100%;
}

.feature-item:hover .feature-icon {
    transform: rotate(360deg) scale(1.1);
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    box-shadow: 0 0 30px var(--primary-glow);
}

.feature-icon {
    width: 54px;
    height: 54px;
    background: var(--black-light);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    color: var(--primary);
    border: 1px solid var(--border);
    transition: var(--transition);
    box-shadow: 0 0 15px var(--primary-glow);
}

.feature-text h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--white);
    transition: color 0.3s ease;
}

.feature-item:hover .feature-text h4 {
    color: var(--primary);
}

.feature-text p {
    font-size: 14px;
    color: var(--gray);
    line-height: 1.6;
    transition: color 0.3s ease;
}

.feature-item:hover .feature-text p {
    color: var(--off-white);
}

/* Quote */
.quote {
    padding: 32px;
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
    border-color: var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
    transform: translateY(-5px);
}

.quote p {
    position: relative;
    z-index: 1;
    font-size: 18px;
    line-height: 1.6;
}

.quote-decoration {
    position: absolute;
    bottom: -30px;
    right: -30px;
    width: 150px;
    height: 150px;
    background: var(--primary);
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
    max-width: 480px;
}

.form-header {
    text-align: center;
    margin-bottom: 48px;
}

.form-header h2 {
    font-size: 42px;
    font-weight: 700;
    color: var(--white);
    margin-bottom: 16px;
    background: linear-gradient(135deg, var(--white), var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    display: inline-block;
}

.form-header h2::before,
.form-header h2::after {
    content: '✨';
    position: absolute;
    opacity: 0;
    transition: opacity 0.3s ease;
    font-size: 24px;
}

.form-header h2::before {
    left: -40px;
    transform: rotate(-20deg);
}

.form-header h2::after {
    right: -40px;
    transform: rotate(20deg);
}

.form-header:hover h2::before,
.form-header:hover h2::after {
    opacity: 1;
    animation: sparkle 1s infinite;
}

@keyframes sparkle {
    0%, 100% { opacity: 0.5; transform: rotate(-20deg) scale(1); }
    50% { opacity: 1; transform: rotate(-20deg) scale(1.2); }
}

.form-header:hover h2::after {
    animation: sparkle 1s infinite 0.5s;
}

.form-header p {
    color: var(--gray);
    font-size: 16px;
}

/* Alert Boxes */
.alert-box {
    border-radius: 20px;
    padding: 24px;
    display: flex;
    gap: 16px;
    margin-bottom: 32px;
    animation: slideIn 0.3s ease;
    position: relative;
    overflow: hidden;
}

.alert-box::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
}

.alert-box:hover::before {
    opacity: 1;
}

.alert-box.success {
    background: rgba(0, 250, 154, 0.1);
    border: 1px solid var(--success);
}

.alert-box.error {
    background: rgba(255, 68, 68, 0.1);
    border: 1px solid var(--error);
}

.alert-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.alert-box.success .alert-icon {
    color: var(--success);
    animation: bounce 2s infinite;
}

.alert-box.error .alert-icon {
    color: var(--error);
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.alert-content h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
}

.alert-box.success .alert-content h4 {
    color: var(--success);
}

.alert-box.error .alert-content h4 {
    color: var(--error);
}

.alert-content p {
    font-size: 14px;
    line-height: 1.6;
}

.alert-box.success .alert-content p {
    color: rgba(0, 250, 154, 0.9);
}

.alert-box.error .alert-content p {
    color: rgba(255, 68, 68, 0.9);
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

/* Auth Form */
.auth-form {
    background: var(--black-light);
    padding: 48px;
    border-radius: 32px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.auth-form::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none;
}

.auth-form:hover {
    border-color: var(--primary);
    box-shadow: 0 0 50px var(--primary-glow);
    transform: translateY(-5px);
}

.auth-form:hover::before {
    opacity: 0.15;
}

/* Form Group */
.form-group {
    margin-bottom: 32px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    font-weight: 500;
    color: var(--white);
    margin-bottom: 12px;
}

.form-group label i {
    color: var(--primary);
    font-size: 16px;
    transition: var(--transition);
}

.form-group:hover label i {
    transform: scale(1.2);
    color: var(--primary-light);
}

.input-wrapper {
    position: relative;
}

.form-group input {
    width: 100%;
    padding: 18px 22px;
    border: 2px solid var(--border);
    border-radius: 16px;
    font-size: 16px;
    transition: var(--transition);
    background: var(--black);
    color: var(--white);
    position: relative;
    z-index: 1;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--black-light);
    transform: scale(1.02);
}

.form-group input::placeholder {
    color: var(--dark-gray);
}

.input-focus-effect {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 16px;
    background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), var(--primary-glow) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    z-index: 2;
}

.form-group input:focus ~ .input-focus-effect {
    opacity: 0.3;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border: none;
    border-radius: 16px;
    padding: 20px 24px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: var(--transition);
    margin-bottom: 32px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 20px var(--primary-glow);
}

.btn-text {
    position: relative;
    z-index: 2;
}

.btn-icon {
    position: relative;
    z-index: 2;
    transition: transform 0.3s ease;
}

.btn-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: 1;
}

.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 40px var(--primary-glow);
}

.submit-btn:hover .btn-glow {
    width: 300px;
    height: 300px;
}

.submit-btn:hover .btn-icon {
    transform: translateX(8px) scale(1.2);
}

.submit-btn:active {
    transform: translateY(-1px);
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
    background: linear-gradient(90deg, transparent, var(--primary), var(--primary-light), var(--primary), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}

.divider span {
    position: relative;
    background: var(--black-light);
    padding: 0 24px;
    color: var(--gray);
    font-size: 15px;
    font-weight: 500;
    border: 1px solid var(--border);
    border-radius: 40px;
    transition: var(--transition);
}

.divider:hover span {
    border-color: var(--primary);
    color: var(--white);
    box-shadow: 0 0 20px var(--primary-glow);
}

/* Login Link */
.login-link {
    text-align: center;
}

.login-link p {
    color: var(--gray);
    font-size: 15px;
}

.login-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    position: relative;
    padding: 4px 0;
}

.login-link a::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--primary);
    transition: width 0.3s ease;
    box-shadow: 0 0 10px var(--primary-glow);
}

.login-link a::after {
    content: '→';
    position: absolute;
    right: -20px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    transition: var(--transition);
}

.login-link a:hover {
    color: var(--primary-light);
    padding-left: 10px;
}

.login-link a:hover::before {
    width: 100%;
}

.login-link a:hover::after {
    opacity: 1;
    right: -25px;
}

/* Responsive */
@media (max-width: 992px) {
    .auth-container {
        flex-direction: column;
    }
    
    .left-panel {
        padding: 48px 32px;
    }
    
    .right-panel {
        padding: 32px;
    }
    
    .form-header h2 {
        font-size: 36px;
    }
}

@media (max-width: 768px) {
    .left-panel {
        display: none;
    }
    
    .right-panel {
        padding: 24px;
    }
    
    .form-header h2 {
        font-size: 32px;
    }
    
    .auth-form {
        padding: 32px;
    }
    
    .form-header h2::before,
    .form-header h2::after {
        display: none;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--black);
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 5px;
    box-shadow: 0 0 10px var(--primary-glow);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
}

/* Animation for form entrance */
@keyframes formFadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.auth-form {
    animation: formFadeIn 0.6s ease forwards;
}

/* Mouse move effect for input focus */
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-group input');
    
    inputs.forEach(input => {
        input.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            this.style.setProperty('--x', `${x}%`);
            this.style.setProperty('--y', `${y}%`);
        });
    });
});
</script>
@endsection