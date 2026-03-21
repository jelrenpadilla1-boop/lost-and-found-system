<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundify — Create Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* --- LIGHT MODE (default) --- */
        :root {
            --bg-white: #ffffff;
            --bg-soft: #faf9fe;
            --bg-card: #ffffff;
            --border-light: #edeef5;
            --border-soft: #e6e8f0;
            --accent: #7c3aed;
            --accent-light: #8b5cf6;
            --accent-soft: #ede9fe;
            --text-dark: #1e1b2f;
            --text-muted: #5b5b7a;
            --text-soft: #7e7b9a;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
            --radius-card: 24px;
            --radius-sm: 60px;
            --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            --error: #ef4444;
            --error-bg: #fef2f2;
            --error-border: #fecaca;
            --success: #10b981;
            --amber: #f59e0b;
            --input-bg: #ffffff;
            --glass: rgba(0, 0, 0, 0.02);
            --glass-b: rgba(0, 0, 0, 0.04);
        }

        /* --- DARK MODE --- */
        body.dark {
            --bg-white: #0f0c1a;
            --bg-soft: #12101c;
            --bg-card: #191624;
            --border-light: #2a2438;
            --border-soft: #2d2740;
            --accent: #a78bfa;
            --accent-light: #c4b5fd;
            --accent-soft: #2d2648;
            --text-dark: #f0edfc;
            --text-muted: #b4adcf;
            --text-soft: #938bb0;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.4), 0 4px 8px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.5);
            --error-bg: rgba(239, 68, 68, 0.15);
            --error-border: #7f2d2d;
            --input-bg: #1e1a2f;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-b: rgba(255, 255, 255, 0.06);
        }

        body {
            background: var(--bg-soft);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: background-color 0.25s ease, color 0.2s ease;
        }

        /* subtle background pattern */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, var(--border-light) 1px, transparent 1px);
            background-size: 32px 32px;
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
            transition: opacity 0.2s;
        }

        body.dark::before {
            opacity: 0.2;
        }

        .register-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 520px;
            margin: 2rem;
        }

        /* main card */
        .register-card {
            background: var(--bg-card);
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            transition: background 0.2s, border-color 0.2s;
            padding: 2.5rem;
        }

        /* dark mode toggle button */
        .theme-toggle-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }

        .theme-toggle-btn {
            background: var(--accent-soft);
            border: 1px solid var(--border-light);
            border-radius: 60px;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--accent);
            font-size: 1.1rem;
        }

        .theme-toggle-btn:hover {
            background: var(--accent);
            color: white;
            transform: scale(0.96);
        }

        /* logo - centered */
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .form-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .form-logo-icon {
            width: 44px;
            height: 44px;
            background: var(--accent);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .form-logo-text {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .form-logo-text span {
            color: var(--accent);
        }

        /* form header - centered */
        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent-soft);
            padding: 0.3rem 1rem;
            border-radius: 60px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 1rem;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* alert box */
        .alert-box {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
        }

        .alert-icon {
            color: var(--error);
            font-size: 1rem;
            margin-top: 0.1rem;
        }

        .alert-content h4 {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--error);
            margin-bottom: 0.2rem;
        }

        .alert-content p {
            font-size: 0.75rem;
            color: var(--error);
            opacity: 0.9;
        }

        /* form groups */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: var(--text-soft);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .form-group input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.6rem;
            border: 1.5px solid var(--border-light);
            border-radius: 1rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: var(--transition);
            background: var(--input-bg);
            color: var(--text-dark);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 0.8rem;
            background: none;
            border: none;
            color: var(--text-soft);
            cursor: pointer;
            padding: 0.3rem;
            transition: var(--transition);
        }

        .toggle-password:hover {
            color: var(--accent);
        }

        .error-message {
            font-size: 0.7rem;
            color: var(--error);
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* password strength */
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
            background: var(--border-light);
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
            border-radius: 2px;
        }

        /* location section */
        .location-section {
            margin: 1.5rem 0;
            border: 1px solid var(--border-light);
            border-radius: 1rem;
            overflow: hidden;
            background: var(--glass);
        }

        .location-toggle {
            width: 100%;
            padding: 1rem 1.2rem;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: var(--transition);
        }

        .location-toggle:hover {
            background: var(--glass-b);
            color: var(--accent);
        }

        .location-toggle i:first-child {
            color: var(--accent);
            margin-right: 0.5rem;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .location-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .location-content.expanded {
            max-height: 280px;
        }

        .location-help {
            padding: 1rem 1.2rem 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .location-help i {
            color: var(--accent);
        }

        .location-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
            padding: 0.5rem 1.2rem;
        }

        .location-input-group label {
            display: block;
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-bottom: 0.2rem;
        }

        .location-input-group input {
            width: 100%;
            padding: 0.7rem;
            background: var(--input-bg);
            border: 1px solid var(--border-light);
            border-radius: 0.7rem;
            font-size: 0.8rem;
            color: var(--text-dark);
        }

        .location-input-group input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .location-btn {
            width: calc(100% - 2.4rem);
            margin: 0.8rem 1.2rem 1.2rem;
            padding: 0.7rem;
            background: transparent;
            border: 1px solid var(--border-light);
            color: var(--accent);
            border-radius: 0.8rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .location-btn:hover {
            background: var(--accent-soft);
            border-color: var(--accent);
        }

        /* terms */
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
            accent-color: var(--accent);
            cursor: pointer;
        }

        .terms-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        /* submit button */
        .submit-btn {
            width: 100%;
            background: var(--accent);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3);
        }

        .submit-btn:hover {
            background: var(--accent-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(124, 58, 237, 0.35);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* divider */
        .divider {
            position: relative;
            text-align: center;
            margin: 1rem 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-light);
        }

        .divider span {
            background: var(--bg-card);
            padding: 0 0.8rem;
            font-size: 0.7rem;
            color: var(--text-soft);
            position: relative;
        }

        /* login link */
        .login-link {
            text-align: center;
        }

        .login-link a {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* responsiveness */
        @media (max-width: 560px) {
            .register-wrapper {
                margin: 1rem;
            }
            .register-card {
                padding: 1.5rem;
            }
            .location-grid {
                grid-template-columns: 1fr;
            }
        }

        .reveal {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.5s ease, transform 0.4s ease;
        }
        .reveal.in {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

<div class="register-wrapper">
    <div class="register-card">
        <!-- Dark Mode Toggle -->
        <div class="theme-toggle-wrapper">
            <div class="theme-toggle-btn" id="themeToggle" aria-label="Dark mode toggle">
                <i class="fas fa-moon" id="themeIcon"></i>
            </div>
        </div>

        <!-- Logo - Centered -->
        <div class="logo-wrapper">
            <a href="/" class="form-logo">
                <div class="form-logo-icon"><i class="fas fa-compass"></i></div>
                <span class="form-logo-text">Found<span>ify</span></span>
            </a>
        </div>

        <!-- Form Header - Centered -->
        <div class="form-header">
            <div class="form-tag">
                <i class="fas fa-user-plus"></i> new member
            </div>
            <h2 class="form-title reveal">Get started</h2>
            <p class="form-subtitle reveal">Join thousands who've reunited with their belongings</p>
        </div>

        @if($errors->any())
            <div class="alert-box reveal">
                <i class="fas fa-circle-exclamation"></i>
                <div class="alert-content">
                    <h4>Unable to create account</h4>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="form-group">
                <label for="name">Full name</label>
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
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
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
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="••••••••"
                           autocomplete="new-password"
                           required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                
                <div class="password-strength">
                    <div class="strength-labels">
                        <span>Strength:</span>
                        <span id="strength-text" class="strength-text">None</span>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="••••••••"
                           autocomplete="new-password"
                           required>
                    <button type="button" class="toggle-password" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Optional location section -->
            <div class="location-section">
                <button type="button" class="location-toggle" id="locationToggle">
                    <span>
                        <i class="fas fa-map-marker-alt"></i>
                        Add location (optional)
                    </span>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </button>
                
                <div class="location-content" id="locationContent">
                    <div class="location-help">
                        <i class="fas fa-info-circle"></i>
                        <span>Better local matches with your location</span>
                    </div>
                    
                    <div class="location-grid">
                        <div class="location-input-group">
                            <label for="latitude">Latitude</label>
                            <input type="number" 
                                   step="any" 
                                   id="latitude" 
                                   name="latitude" 
                                   value="{{ old('latitude') }}"
                                   placeholder="e.g., 40.7128">
                        </div>
                        <div class="location-input-group">
                            <label for="longitude">Longitude</label>
                            <input type="number" 
                                   step="any" 
                                   id="longitude" 
                                   name="longitude" 
                                   value="{{ old('longitude') }}"
                                   placeholder="e.g., -74.0060">
                        </div>
                    </div>
                    
                    <button type="button" class="location-btn" id="getLocationBtn">
                        <i class="fas fa-location-arrow"></i>
                        Use current location
                    </button>
                </div>
            </div>

            <!-- Terms agreement -->
            <div class="terms-section">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" id="terms" required>
                    <span>I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a></span>
                </label>
                @error('terms')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span>Create account</span>
                <i class="fas fa-arrow-right"></i>
            </button>

            <div class="divider">
                <span>already have an account?</span>
            </div>

            <div class="login-link">
                <a href="{{ route('login') }}">Sign in →</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Dark/Light mode logic
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        const savedTheme = localStorage.getItem('foundify-theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else if (savedTheme === 'light') {
            document.body.classList.remove('dark');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        } else {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (prefersDark) {
                document.body.classList.add('dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('foundify-theme', 'dark');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        }

        themeToggle.addEventListener('click', () => {
            if (document.body.classList.contains('dark')) {
                document.body.classList.remove('dark');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                localStorage.setItem('foundify-theme', 'light');
            } else {
                document.body.classList.add('dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('foundify-theme', 'dark');
            }
        });

        // Password toggle
        const togglePwd = document.getElementById('togglePassword');
        const toggleConfirm = document.getElementById('toggleConfirmPassword');
        const pwd = document.getElementById('password');
        const confirmPwd = document.getElementById('password_confirmation');

        if (togglePwd && pwd) {
            togglePwd.addEventListener('click', () => {
                const type = pwd.type === 'password' ? 'text' : 'password';
                pwd.type = type;
                togglePwd.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        if (toggleConfirm && confirmPwd) {
            toggleConfirm.addEventListener('click', () => {
                const type = confirmPwd.type === 'password' ? 'text' : 'password';
                confirmPwd.type = type;
                toggleConfirm.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        // Password strength checker
        const strengthText = document.getElementById('strength-text');
        const strengthFill = document.getElementById('strengthFill');
        
        if (pwd && strengthText && strengthFill) {
            pwd.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 8) strength++;
                if (password.length >= 12) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                let level, color, width;
                if (password.length === 0) {
                    level = 'None';
                    color = 'var(--text-muted)';
                    width = '0%';
                } else if (strength <= 1) {
                    level = 'Weak';
                    color = 'var(--error)';
                    width = '20%';
                } else if (strength === 2) {
                    level = 'Fair';
                    color = 'var(--amber)';
                    width = '40%';
                } else if (strength === 3) {
                    level = 'Good';
                    color = '#10b981';
                    width = '60%';
                } else if (strength === 4) {
                    level = 'Strong';
                    color = 'var(--accent)';
                    width = '80%';
                } else {
                    level = 'Very strong';
                    color = 'var(--accent)';
                    width = '100%';
                }
                
                strengthText.textContent = level;
                strengthText.style.color = color;
                strengthFill.style.width = width;
                strengthFill.style.backgroundColor = color;
            });
        }

        // Location toggle
        const locationToggle = document.getElementById('locationToggle');
        const locationContent = document.getElementById('locationContent');
        
        if (locationToggle && locationContent) {
            const toggleIcon = locationToggle.querySelector('.toggle-icon');
            locationToggle.addEventListener('click', () => {
                locationContent.classList.toggle('expanded');
                toggleIcon.style.transform = locationContent.classList.contains('expanded') ? 'rotate(180deg)' : 'rotate(0deg)';
            });
        }

        // Get current location
        const getLocationBtn = document.getElementById('getLocationBtn');
        if (getLocationBtn) {
            getLocationBtn.addEventListener('click', function() {
                const originalText = this.innerHTML;
                if (navigator.geolocation) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';
                    this.disabled = true;
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                            document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                            getLocationBtn.innerHTML = '<i class="fas fa-check"></i> Location set';
                            setTimeout(() => {
                                getLocationBtn.innerHTML = originalText;
                                getLocationBtn.disabled = false;
                            }, 2000);
                        },
                        function() {
                            alert('Unable to retrieve location. Please enter manually.');
                            getLocationBtn.innerHTML = originalText;
                            getLocationBtn.disabled = false;
                        }
                    );
                } else {
                    alert('Geolocation is not supported.');
                }
            });
        }

        // Auto-focus name
        const nameInput = document.getElementById('name');
        if (nameInput && !nameInput.value) {
            setTimeout(() => nameInput.focus(), 200);
        }

        // Form submit validation
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
                    alert('Please agree to the Terms of Service');
                    return;
                }
                
                if (!form.checkValidity()) {
                    e.preventDefault();
                    return;
                }
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Creating account...</span> <i class="fas fa-spinner fa-spin"></i>';
                
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<span>Create account</span> <i class="fas fa-arrow-right"></i>';
                    }
                }, 6000);
            });
        }

        // Dismiss errors on input
        document.querySelectorAll('.form-group input').forEach(input => {
            input.addEventListener('input', () => {
                const err = input.closest('.form-group')?.querySelector('.error-message');
                if (err) { err.style.opacity = '0'; setTimeout(() => err.remove(), 200); }
            });
        });

        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => observer.observe(el));
        
        setTimeout(() => {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('in'));
        }, 60);
    });
</script>

</body>
</html>