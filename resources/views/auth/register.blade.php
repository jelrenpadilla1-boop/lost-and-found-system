<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Foundify — Create Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* CSS Variables for Light/Dark Mode */
        :root {
            --bg-primary: #141414;
            --bg-secondary: #0a0a0a;
            --bg-card: rgba(0,0,0,0.75);
            --text-primary: #ffffff;
            --text-secondary: #e5e5e5;
            --text-muted: #b3b3b3;
            --border-color: #404040;
            --input-bg: #333333;
            --input-border: #404040;
            --input-focus: #454545;
            --shadow-color: rgba(0,0,0,0.5);
            --divider-bg: rgba(0,0,0,0.75);
            --accent: #e50914;
            --accent-light: #f6121d;
            --accent-soft: rgba(229,9,20,0.15);
            --success: #2e7d32;
            --error: #e50914;
            --error-bg: rgba(229,9,20,0.15);
            --error-border: #7f2d2d;
            --amber: #f5c518;
        }

        body.light {
            --bg-primary: #f5f5f5;
            --bg-secondary: #ffffff;
            --bg-card: rgba(255,255,255,0.95);
            --text-primary: #1a1a1a;
            --text-secondary: #333333;
            --text-muted: #666666;
            --border-color: #e0e0e0;
            --input-bg: #f8f8f8;
            --input-border: #e0e0e0;
            --input-focus: #ffffff;
            --shadow-color: rgba(0,0,0,0.1);
            --divider-bg: rgba(255,255,255,0.95);
            --accent-soft: rgba(229,9,20,0.08);
            --error-bg: rgba(229,9,20,0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Netflix-style background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
            z-index: -2;
            transition: opacity 0.3s ease;
        }

        body.light::before {
            background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 50%, #f5f5f5 100%);
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 30%, rgba(229,9,20,0.15) 0%, transparent 60%);
            z-index: -1;
            transition: opacity 0.3s ease;
        }

        body.light::after {
            background: radial-gradient(circle at 20% 30%, rgba(229,9,20,0.08) 0%, transparent 60%);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--border-color);
        }
        ::-webkit-scrollbar-thumb {
            background: #e50914;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f6121d;
        }

        /* Register container */
        .register-container {
            width: 100%;
            max-width: 480px;
            margin: 2rem;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Netflix-style card */
        .register-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 2.5rem;
            backdrop-filter: blur(2px);
            box-shadow: 0 25px 50px -12px var(--shadow-color);
            border: 1px solid rgba(255,255,255,0.1);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        body.light .register-card {
            border: 1px solid rgba(0,0,0,0.1);
        }

        /* Theme Toggle */
        .theme-toggle-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

        .theme-toggle {
            background: rgba(229, 9, 20, 0.15);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #e50914;
            font-size: 1.1rem;
        }

        .theme-toggle:hover {
            background: #e50914;
            color: white;
            transform: scale(1.05);
            border-color: #e50914;
        }

        /* Logo */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.02em;
            transition: transform 0.2s;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo i {
            color: #e50914;
            font-size: 2rem;
        }

        .logo span {
            color: var(--text-primary);
        }

        .logo span.accent {
            color: #e50914;
        }

        /* Header */
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-badge {
            display: inline-block;
            background: rgba(229,9,20,0.9);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .form-subtitle {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Error alert */
        .error-alert {
            background: var(--error-bg);
            border-left: 3px solid #e50914;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
        }

        .error-alert i {
            color: #e50914;
            font-size: 1rem;
            margin-top: 0.1rem;
        }

        .error-alert-content h4 {
            font-size: 0.8rem;
            font-weight: 700;
            color: #e50914;
            margin-bottom: 0.2rem;
        }

        .error-alert-content p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            opacity: 0.9;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.6rem;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 4px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #e50914;
            background: var(--input-focus);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .toggle-password {
            position: absolute;
            right: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #e50914;
        }

        .error-message {
            font-size: 0.7rem;
            color: #e50914;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Password strength */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
        }

        .strength-text {
            font-weight: 600;
            transition: color 0.2s;
        }

        .strength-bar {
            width: 100%;
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
            border-radius: 2px;
        }

        .password-requirements {
            margin-top: 0.6rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.25rem 0.5rem;
        }

        .req-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.68rem;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .req-item i {
            font-size: 0.6rem;
            width: 12px;
            text-align: center;
            color: var(--border-color);
            transition: color 0.2s;
        }

        .req-item.met {
            color: #2e7d32;
        }

        .req-item.met i {
            color: #2e7d32;
        }

        /* Terms section */
        .terms-section {
            margin: 1.5rem 0;
        }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            margin-top: 0.15rem;
            accent-color: #e50914;
            cursor: pointer;
        }

        .terms-link {
            color: #e50914;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            background: #e50914;
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 1.5rem;
        }

        .submit-btn:hover {
            background: #f6121d;
            transform: scale(1.02);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Divider */
        .divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            background: var(--divider-bg);
            padding: 0 1rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            position: relative;
        }

        /* Login link */
        .login-link {
            text-align: center;
        }

        .login-link p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .login-link a {
            font-size: 0.9rem;
            font-weight: 600;
            color: #e50914;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: color 0.2s;
        }

        .login-link a:hover {
            color: #f6121d;
        }

        /* Responsive */
        @media (max-width: 560px) {
            .register-container {
                margin: 1rem;
            }
            .register-card {
                padding: 1.5rem;
            }
            .form-title {
                font-size: 1.5rem;
            }
            .password-requirements {
                grid-template-columns: 1fr;
            }
        }

        /* Loading spinner */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .fa-spinner {
            animation: spin 0.8s linear infinite;
        }

        /* Fade animations */
        .fade-item {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .fade-item.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-card">
        <!-- Theme Toggle -->
        <div class="theme-toggle-wrapper">
            <div class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon" id="themeIcon"></i>
            </div>
        </div>

        <!-- Logo -->
        <div class="logo-wrapper">
            <a href="/" class="logo">
                <i class="fas fa-compass"></i>
                <span>Found<span class="accent">ify</span></span>
            </a>
        </div>

        <!-- Form Header -->
        <div class="form-header">
            <div class="form-badge">JOIN NOW</div>
            <h1 class="form-title">Create Account</h1>
            <p class="form-subtitle">Start your journey with Foundify</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="error-alert fade-item visible">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="error-alert-content">
                    <h4>Unable to create account</h4>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="form-group fade-item">
                <label for="name">Full Name</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="John Doe"
                           autocomplete="name"
                           required>
                </div>
                @error('name')
                    <span class="error-message"><i class="fas fa-circle" style="font-size: 0.3rem;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group fade-item">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="hello@foundify.com"
                           autocomplete="email"
                           required>
                </div>
                @error('email')
                    <span class="error-message"><i class="fas fa-circle" style="font-size: 0.3rem;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group fade-item">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Create a strong password"
                           autocomplete="new-password"
                           required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="error-message"><i class="fas fa-circle" style="font-size: 0.3rem;"></i> {{ $message }}</span>
                @enderror
                
                <!-- Password Strength Meter -->
                <div class="password-strength">
                    <div class="strength-labels">
                        <span>Password strength:</span>
                        <span id="strength-text" class="strength-text">None</span>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                </div>
                
                <!-- Password Requirements -->
                <div class="password-requirements">
                    <span class="req-item" id="req-length"><i class="fas fa-circle"></i> 8+ characters</span>
                    <span class="req-item" id="req-upper"><i class="fas fa-circle"></i> Uppercase letter</span>
                    <span class="req-item" id="req-lower"><i class="fas fa-circle"></i> Lowercase letter</span>
                    <span class="req-item" id="req-number"><i class="fas fa-circle"></i> Number</span>
                    <span class="req-item" id="req-symbol"><i class="fas fa-circle"></i> Special character</span>
                </div>
            </div>

            <div class="form-group fade-item">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Confirm your password"
                           autocomplete="new-password"
                           required>
                    <button type="button" class="toggle-password" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="terms-section fade-item">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" id="terms" required>
                    <span>I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a></span>
                </label>
                @error('terms')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="submit-btn fade-item" id="submitBtn">
                <span>Create Account</span>
                <i class="fas fa-arrow-right"></i>
            </button>

            <div class="divider fade-item">
                <span>Already have an account?</span>
            </div>

            <div class="login-link fade-item">
                <a href="{{ route('login') }}">
                    Sign in to your account <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Dark/Light mode logic
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        // Check for saved preference
        const savedTheme = localStorage.getItem('foundify-theme');
        if (savedTheme === 'light') {
            document.body.classList.add('light');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else if (savedTheme === 'dark') {
            document.body.classList.remove('light');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        } else {
            // Check system preference
            const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
            if (prefersLight) {
                document.body.classList.add('light');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('foundify-theme', 'light');
            }
        }

        // Toggle function
        themeToggle.addEventListener('click', () => {
            if (document.body.classList.contains('light')) {
                document.body.classList.remove('light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                localStorage.setItem('foundify-theme', 'dark');
            } else {
                document.body.classList.add('light');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('foundify-theme', 'light');
            }
        });

        // Password visibility toggle
        const togglePwd = document.getElementById('togglePassword');
        const toggleConfirm = document.getElementById('toggleConfirmPassword');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        
        if (togglePwd && passwordInput) {
            togglePwd.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                const icon = togglePwd.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }
        
        if (toggleConfirm && confirmInput) {
            toggleConfirm.addEventListener('click', () => {
                const type = confirmInput.type === 'password' ? 'text' : 'password';
                confirmInput.type = type;
                const icon = toggleConfirm.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        // Password strength checker
        const strengthText = document.getElementById('strength-text');
        const strengthFill = document.getElementById('strengthFill');
        const reqLength = document.getElementById('req-length');
        const reqUpper = document.getElementById('req-upper');
        const reqLower = document.getElementById('req-lower');
        const reqNumber = document.getElementById('req-number');
        const reqSymbol = document.getElementById('req-symbol');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Check requirements
                const hasLength = password.length >= 8;
                const hasUpper = /[A-Z]/.test(password);
                const hasLower = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSymbol = /[^A-Za-z0-9]/.test(password);
                
                // Update requirement indicators
                updateRequirement(reqLength, hasLength);
                updateRequirement(reqUpper, hasUpper);
                updateRequirement(reqLower, hasLower);
                updateRequirement(reqNumber, hasNumber);
                updateRequirement(reqSymbol, hasSymbol);
                
                // Calculate strength
                let strength = 0;
                if (hasLength) strength++;
                if (hasUpper) strength++;
                if (hasLower) strength++;
                if (hasNumber) strength++;
                if (hasSymbol) strength++;
                
                let level, color, width;
                if (password.length === 0) {
                    level = 'None';
                    color = '#808080';
                    width = '0%';
                } else if (strength <= 2) {
                    level = 'Weak';
                    color = '#e50914';
                    width = '25%';
                } else if (strength === 3) {
                    level = 'Fair';
                    color = '#f5c518';
                    width = '50%';
                } else if (strength === 4) {
                    level = 'Good';
                    color = '#2e7d32';
                    width = '75%';
                } else {
                    level = 'Strong';
                    color = '#2e7d32';
                    width = '100%';
                }
                
                strengthText.textContent = level;
                strengthText.style.color = color;
                strengthFill.style.width = width;
                strengthFill.style.backgroundColor = color;
            });
        }
        
        function updateRequirement(element, isMet) {
            if (isMet) {
                element.classList.add('met');
            } else {
                element.classList.remove('met');
            }
        }

        // Focus name field if empty
        const nameField = document.getElementById('name');
        if (nameField && !nameField.value) {
            setTimeout(() => nameField.focus(), 100);
        }

        // Form submission with validation
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form && submitBtn) {
            form.addEventListener('submit', (e) => {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match');
                    return;
                }
                
                const termsCheckbox = document.getElementById('terms');
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Please agree to the Terms of Service and Privacy Policy');
                    return;
                }
                
                if (!form.checkValidity()) {
                    e.preventDefault();
                    return;
                }
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Creating Account...</span> <i class="fas fa-spinner fa-spin"></i>';
                
                // Safety timeout - re-enable button after 6 seconds if stuck
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<span>Create Account</span> <i class="fas fa-arrow-right"></i>';
                    }
                }, 6000);
            });
        }

        // Prevent multiple rapid submissions
        let isSubmitting = false;
        if (form) {
            form.addEventListener('submit', (e) => {
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }
                isSubmitting = true;
                setTimeout(() => { isSubmitting = false; }, 3000);
            });
        }

        // Dismiss errors on input
        document.querySelectorAll('.form-group input').forEach(input => {
            input.addEventListener('input', () => {
                const errorSpan = input.closest('.form-group')?.querySelector('.error-message');
                if (errorSpan) {
                    errorSpan.style.opacity = '0';
                    setTimeout(() => errorSpan.remove(), 200);
                }
            });
        });

        // Smooth entrance animation for form elements
        const fadeItems = document.querySelectorAll('.fade-item');
        fadeItems.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('visible');
            }, 50 + (index * 40));
        });
    });
</script>

</body>
</html>