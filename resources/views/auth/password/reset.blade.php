@extends('layouts.auth')

@section('title', 'Reset Password - Foundify')

@section('content')
<div class="reset-container">
    <!-- Left Panel - Brand & Info -->
    <div class="left-panel">
        <div class="brand-wrapper">
            <div class="logo-icon">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="brand-name">Foundify</h1>
            <p class="brand-tagline">Create your new password</p>
        </div>

        <div class="quote-box">
            <i class="fas fa-quote-left"></i>
            <p>Almost there! Choose a strong password you'll remember.</p>
        </div>

        <div class="password-tips">
            <div class="tip-item">
                <div class="tip-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="tip-text">
                    <h4>Strong Password</h4>
                    <p>Mix of letters, numbers & symbols</p>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-icon">
                    <i class="fas fa-key"></i>
                </div>
                <div class="tip-text">
                    <h4>Easy to Remember</h4>
                    <p>Make it memorable but hard to guess</p>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="tip-text">
                    <h4>Secure Account</h4>
                    <p>Protect your account with a strong password</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel - Reset Password Form -->
    <div class="right-panel">
        <div class="form-container">
            <div class="form-header">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to login
                </a>
                <h2>Set new password</h2>
                <p class="text-secondary">Your new password must be different from previous ones</p>
            </div>

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

            <form method="POST" action="{{ route('password.update') }}" class="reset-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email address</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ $email ?? old('email') }}" 
                               placeholder="your@email.com"
                               readonly 
                               required>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">New password</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Enter new password"
                               required>
                        <button type="button" class="toggle-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Enter password</div>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Confirm new password"
                               required>
                        <button type="button" class="toggle-password" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-match" id="passwordMatch"></div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn" id="submitBtn">
                    <span>Reset password</span>
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
    --primary-glow: rgba(255, 20, 147, 0.2);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-input: #222;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --text-muted: #666;
    --success: #00fa9a;
    --error: #ff4444;
    --warning: #ffa500;
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

/* Reset Container */
.reset-container {
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

.quote-box {
    background: rgba(255, 20, 147, 0.1);
    border-left: 4px solid var(--primary);
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 48px;
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
    line-height: 1.6;
}

.password-tips {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.tip-item {
    display: flex;
    align-items: center;
    gap: 16px;
}

.tip-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 20, 147, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 18px;
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
    max-width: 440px;
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
    margin-bottom: 24px;
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

/* Error Message */
.error-message {
    background: rgba(255, 68, 68, 0.1);
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
}

.error-message p {
    color: var(--error);
    font-size: 13px;
    margin: 0;
}

.error-message p:not(:last-child) {
    margin-bottom: 4px;
}

/* Reset Form */
.reset-form {
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
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 16px;
    color: var(--text-muted);
    font-size: 16px;
    pointer-events: none;
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

.input-group input[readonly] {
    background: rgba(34, 34, 34, 0.5);
    cursor: not-allowed;
    opacity: 0.8;
}

.toggle-password {
    position: absolute;
    right: 16px;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    transition: var(--transition);
}

.toggle-password:hover {
    color: var(--primary);
}

/* Password Strength */
.password-strength {
    margin-top: 10px;
}

.strength-bar {
    height: 4px;
    background: var(--border-color);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 6px;
}

.strength-fill {
    height: 100%;
    width: 0;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-text {
    font-size: 11px;
    color: var(--text-muted);
    text-align: right;
}

/* Password Match */
.password-match {
    font-size: 12px;
    margin-top: 8px;
    min-height: 20px;
}

.password-match.match-success {
    color: var(--success);
}

.password-match.match-error {
    color: var(--error);
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
    margin-top: 16px;
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
    .reset-container {
        flex-direction: column;
    }
    
    .left-panel {
        padding: 40px 32px;
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
        padding: 20px;
    }
    
    .reset-form {
        padding: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
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

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const confirmInput = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');

    function checkPasswordStrength() {
        if (!passwordInput || !strengthFill || !strengthText) return;
        
        const password = passwordInput.value;
        let strength = 0;
        
        // Length check
        if (password.length >= 8) strength += 25;
        
        // Uppercase check
        if (/[A-Z]/.test(password)) strength += 25;
        
        // Lowercase check
        if (/[a-z]/.test(password)) strength += 25;
        
        // Number or special character
        if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;
        
        // Update strength bar
        strengthFill.style.width = strength + '%';
        
        // Update color and text
        if (strength < 50) {
            strengthFill.style.backgroundColor = '#ff4444';
            strengthText.innerHTML = 'Weak password';
            strengthText.style.color = '#ff4444';
        } else if (strength < 75) {
            strengthFill.style.backgroundColor = '#ffa500';
            strengthText.innerHTML = 'Medium password';
            strengthText.style.color = '#ffa500';
        } else {
            strengthFill.style.backgroundColor = '#00fa9a';
            strengthText.innerHTML = 'Strong password!';
            strengthText.style.color = '#00fa9a';
        }
        
        checkPasswordMatch();
    }

    function checkPasswordMatch() {
        if (!confirmInput || !passwordMatch) return;
        
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        
        if (confirm.length === 0) {
            passwordMatch.innerHTML = '';
        } else if (password === confirm) {
            passwordMatch.innerHTML = '✓ Passwords match';
            passwordMatch.className = 'password-match match-success';
        } else {
            passwordMatch.innerHTML = '✗ Passwords do not match';
            passwordMatch.className = 'password-match match-error';
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', checkPasswordStrength);
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }

    // Form submission loading state
    const form = document.querySelector('.reset-form');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
            submitBtn.disabled = true;
        });
    }
});
</script>
@endsection