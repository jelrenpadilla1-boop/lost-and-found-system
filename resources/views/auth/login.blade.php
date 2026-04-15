<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Foundify — Sign In</title>
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
            --social-bg: #333333;
            --error-color: #e50914;
            --error-bg: rgba(229,9,20,0.15);
            --success-color: #2e7d32;
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
            --social-bg: #f0f0f0;
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

        /* Login container */
        .login-container {
            width: 100%;
            max-width: 450px;
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
        .login-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 3rem 2.5rem;
            backdrop-filter: blur(2px);
            box-shadow: 0 25px 50px -12px var(--shadow-color);
            border: 1px solid rgba(255,255,255,0.1);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        body.light .login-card {
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
            margin-bottom: 2rem;
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

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .form-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Error alert - Netflix style */
        .error-alert {
            background: var(--error-bg);
            border-left: 3px solid #e50914;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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
            transition: color 0.2s;
        }

        .form-group input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.6rem;
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

        .form-group input.error {
            border-color: #e50914;
            animation: shake 0.3s ease-in-out;
        }

        .form-group input.error ~ .input-icon {
            color: #e50914;
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

        /* Form options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.8rem;
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #e50914;
        }

        .forgot-link {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #e50914;
            text-decoration: underline;
        }

        /* Submit button - Netflix red */
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

        /* Social buttons */
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            width: 48px;
            height: 48px;
            border-radius: 4px;
            background: var(--social-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            transition: all 0.2s;
            text-decoration: none;
            font-size: 1.2rem;
            border: 1px solid var(--border-color);
            cursor: pointer;
        }

        .social-btn:hover {
            background: #e50914;
            color: white;
            transform: translateY(-2px);
            border-color: #e50914;
        }

        /* Signup link */
        .signup-link {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .signup-link p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .signup-link a {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: color 0.2s;
        }

        .signup-link a:hover {
            color: #e50914;
        }

        /* Footer links */
        .footer-links {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            margin: 0 0.5rem;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #e50914;
        }

        /* Responsive */
        @media (max-width: 560px) {
            .login-container {
                margin: 1rem;
            }
            .login-card {
                padding: 2rem 1.5rem;
            }
            .form-title {
                font-size: 1.6rem;
            }
        }

        /* Loading spinner */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .fa-spinner {
            animation: spin 0.8s linear infinite;
        }

        /* Animation for form elements */
        .fade-item {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .fade-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Tooltip for error messages */
        .error-tooltip {
            position: absolute;
            right: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: #e50914;
            font-size: 0.8rem;
            cursor: pointer;
            background: none;
            border: none;
        }

        .error-tooltip:hover {
            transform: translateY(-50%) scale(1.1);
        }

        /* Loading overlay for social login */
        .social-loading {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
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
            <h1 class="form-title">Sign In</h1>
            <p class="form-subtitle">to continue to Foundify</p>
        </div>

        <!-- Dynamic Error Messages -->
        <div id="dynamicErrorAlert" class="error-alert fade-item" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="error-alert-content">
                <h4>Unable to sign in</h4>
                <p id="dynamicErrorMessage">Invalid email or password. Please try again.</p>
            </div>
        </div>

        <!-- Server Error Messages -->
        @if($errors->any())
            <div class="error-alert fade-item visible" id="serverErrorAlert">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="error-alert-content">
                    <h4>Unable to sign in</h4>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group fade-item" id="emailGroup">
                <label for="email">Email or Phone Number</label>
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
                <span class="error-message email-error" style="display: none;"></span>
            </div>

            <div class="form-group fade-item" id="passwordGroup">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Enter your password"
                           autocomplete="current-password"
                           required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="error-message password-error" style="display: none;"></span>
            </div>

            <div class="form-options fade-item">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="submit-btn fade-item" id="submitBtn">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>

            <div class="divider fade-item">
                <span>OR CONTINUE WITH</span>
            </div>

            <div class="social-buttons fade-item" id="socialButtons">
                <a href="#" class="social-btn" data-provider="google" aria-label="Google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-btn" data-provider="facebook" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-btn" data-provider="apple" aria-label="Apple">
                    <i class="fab fa-apple"></i>
                </a>
                <a href="#" class="social-btn" data-provider="github" aria-label="GitHub">
                    <i class="fab fa-github"></i>
                </a>
            </div>

            <div class="signup-link fade-item">
                <p>New to Foundify?</p>
                <a href="{{ route('register') }}">
                    Create account now <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Footer Links -->
    <div class="footer-links fade-item">
        <a href="#">Help</a> •
        <a href="#">Privacy</a> •
        <a href="#">Terms</a> •
        <a href="#">Contact</a>
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
        const passwordInput = document.getElementById('password');
        
        if (togglePwd && passwordInput) {
            togglePwd.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                const icon = togglePwd.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        // Focus email field if empty
        const emailField = document.getElementById('email');
        if (emailField && !emailField.value) {
            setTimeout(() => emailField.focus(), 100);
        }

        // Real-time validation and error clearing
        const emailInput = document.getElementById('email');
        const pwdInput = document.getElementById('password');
        const emailError = document.querySelector('.email-error');
        const pwdError = document.querySelector('.password-error');
        const dynamicAlert = document.getElementById('dynamicErrorAlert');
        const serverAlert = document.getElementById('serverErrorAlert');

        // Clear errors when user starts typing
        function clearFieldError(field, errorElement) {
            if (field && errorElement) {
                field.addEventListener('input', () => {
                    field.classList.remove('error');
                    errorElement.style.display = 'none';
                    errorElement.innerHTML = '';
                    
                    // Hide dynamic alert when user starts typing
                    if (dynamicAlert) {
                        dynamicAlert.style.display = 'none';
                    }
                });
            }
        }

        clearFieldError(emailInput, emailError);
        clearFieldError(pwdInput, pwdError);

        // Function to show error
        function showError(message, field = null) {
            if (dynamicAlert) {
                const errorMessageSpan = document.getElementById('dynamicErrorMessage');
                if (errorMessageSpan) {
                    errorMessageSpan.textContent = message;
                }
                dynamicAlert.style.display = 'flex';
                
                // Add shake animation
                dynamicAlert.style.animation = 'none';
                setTimeout(() => {
                    dynamicAlert.style.animation = 'shake 0.5s ease-in-out';
                }, 10);
            }
            
            if (field) {
                field.classList.add('error');
                
                // Add shake to field
                field.style.animation = 'none';
                setTimeout(() => {
                    field.style.animation = 'shake 0.3s ease-in-out';
                }, 10);
                
                // Show specific field error
                const errorElement = field === emailInput ? emailError : pwdError;
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'flex';
                }
            }
        }

        // Social Login Functionality
        const socialButtons = document.querySelectorAll('.social-btn');
        const socialContainer = document.getElementById('socialButtons');
        
        // Social login configurations
        const socialConfigs = {
            google: {
                url: '/auth/google/redirect',
                name: 'Google'
            },
            facebook: {
                url: '/auth/facebook/redirect',
                name: 'Facebook'
            },
            apple: {
                url: '/auth/apple/redirect',
                name: 'Apple'
            },
            github: {
                url: '/auth/github/redirect',
                name: 'GitHub'
            }
        };

        // Function to handle social login
        async function handleSocialLogin(provider) {
            const config = socialConfigs[provider];
            if (!config) {
                showError('Invalid social login provider');
                return;
            }

            // Show loading state
            socialContainer.classList.add('social-loading');
            const clickedButton = document.querySelector(`.social-btn[data-provider="${provider}"]`);
            const originalIcon = clickedButton.innerHTML;
            clickedButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Store original content to restore later
            const restoreButton = () => {
                clickedButton.innerHTML = originalIcon;
                socialContainer.classList.remove('social-loading');
            };

            try {
                // Check if we're in a Laravel environment with socialite routes
                // First, try to redirect to the social auth route
                const socialAuthUrl = `/auth/${provider}/redirect`;
                
                // Make a fetch request to check if the route exists
                const response = await fetch(socialAuthUrl, {
                    method: 'HEAD',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).catch(() => ({ ok: false }));
                
                if (response.ok) {
                    // Route exists, redirect to social auth
                    window.location.href = socialAuthUrl;
                } else {
                    // Route doesn't exist, show demo mode message
                    setTimeout(() => {
                        restoreButton();
                        showDemoMessage(provider);
                    }, 1000);
                }
            } catch (error) {
                restoreButton();
                // Show demo mode message
                showDemoMessage(provider);
            }
        }

        // Show demo mode message
        function showDemoMessage(provider) {
            // Create a custom alert for demo
            const demoAlert = document.createElement('div');
            demoAlert.className = 'error-alert fade-item';
            demoAlert.style.marginTop = '1rem';
            demoAlert.style.backgroundColor = 'rgba(46, 125, 50, 0.15)';
            demoAlert.style.borderLeftColor = '#2e7d32';
            demoAlert.innerHTML = `
                <i class="fas fa-info-circle" style="color: #2e7d32;"></i>
                <div class="error-alert-content">
                    <h4 style="color: #2e7d32;">Demo Mode</h4>
                    <p>${provider} login is not configured yet. In production, this would redirect to ${provider} authentication.</p>
                </div>
            `;
            
            // Insert after social buttons
            socialContainer.parentNode.insertBefore(demoAlert, socialContainer.nextSibling);
            
            // Remove after 5 seconds
            setTimeout(() => {
                demoAlert.style.opacity = '0';
                setTimeout(() => demoAlert.remove(), 300);
            }, 5000);
        }

        // Attach click handlers to social buttons
        socialButtons.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const provider = btn.getAttribute('data-provider');
                if (provider) {
                    await handleSocialLogin(provider);
                }
            });
        });

        // Form submission
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form && submitBtn) {
            form.addEventListener('submit', async (e) => {
                // Clear previous errors
                emailInput.classList.remove('error');
                pwdInput.classList.remove('error');
                if (emailError) emailError.style.display = 'none';
                if (pwdError) pwdError.style.display = 'none';
                if (dynamicAlert) dynamicAlert.style.display = 'none';
                if (serverAlert) serverAlert.style.display = 'none';
                
                const email = emailInput.value.trim();
                const password = pwdInput.value;
                
                // Basic validation
                if (!email) {
                    e.preventDefault();
                    showError('Email address is required', emailInput);
                    emailInput.focus();
                    return;
                }
                
                if (!password) {
                    e.preventDefault();
                    showError('Password is required', pwdInput);
                    pwdInput.focus();
                    return;
                }
                
                // Email format validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    showError('Please enter a valid email address', emailInput);
                    emailInput.focus();
                    return;
                }
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Signing In...</span> <i class="fas fa-spinner fa-spin"></i>';
                
                // Allow form to submit normally
                // The button will be re-enabled if there's an error via Laravel's validation
            });
        }

        // Smooth entrance animation for form elements
        const fadeItems = document.querySelectorAll('.fade-item');
        fadeItems.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('visible');
            }, 50 + (index * 50));
        });

        // Auto-hide server error after 5 seconds if it exists
        if (serverAlert) {
            setTimeout(() => {
                if (serverAlert) {
                    serverAlert.style.opacity = '0';
                    setTimeout(() => {
                        if (serverAlert) serverAlert.style.display = 'none';
                    }, 300);
                }
            }, 5000);
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
    });
</script>

</body>
</html>