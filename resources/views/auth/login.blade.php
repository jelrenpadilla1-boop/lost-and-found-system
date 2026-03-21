<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundify — Sign In</title>
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
            --input-bg: #ffffff;
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

        .login-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
            margin: 2rem;
        }

        /* main card */
        .login-card {
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

        /* error alert */
        .error-alert {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.8rem;
            align-items: flex-start;
        }

        .error-alert i {
            color: var(--error);
            font-size: 1rem;
            margin-top: 0.1rem;
        }

        .error-alert-content h4 {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--error);
            margin-bottom: 0.2rem;
        }

        .error-alert-content p {
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

        /* form options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
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
            accent-color: var(--accent);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.8rem;
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
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
            font-size: 0.95rem;
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
            margin: 1rem 0 1.2rem;
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

        /* social buttons */
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            width: 44px;
            height: 44px;
            border-radius: 1rem;
            border: 1px solid var(--border-light);
            background: var(--bg-card);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .social-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        /* signup link */
        .signup-link {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-light);
        }

        .signup-link p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
        }

        .signup-link a {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* responsiveness */
        @media (max-width: 560px) {
            .login-wrapper {
                margin: 1rem;
            }
            .login-card {
                padding: 1.5rem;
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

<div class="login-wrapper">
    <div class="login-card">
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
            <h2 class="form-title reveal">Sign in</h2>
            <p class="form-subtitle reveal">Access your account to continue reuniting</p>
        </div>

        @if($errors->any())
            <div class="error-alert reveal">
                <i class="fas fa-circle-exclamation"></i>
                <div class="error-alert-content">
                    <h4>Unable to sign in</h4>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

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
                    <span class="error-message"><i class="fas fa-circle" style="font-size: 0.3rem;"></i> {{ $message }}</span>
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
                           autocomplete="current-password"
                           required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="error-message"><i class="fas fa-circle" style="font-size: 0.3rem;"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Keep me signed in</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span>Sign in</span>
                <i class="fas fa-arrow-right"></i>
            </button>

            <div class="divider">
                <span>or continue with</span>
            </div>

            <div class="social-buttons">
                <a href="#" class="social-btn" aria-label="Google"><i class="fab fa-google"></i></a>
                <a href="#" class="social-btn" aria-label="GitHub"><i class="fab fa-github"></i></a>
                <a href="#" class="social-btn" aria-label="Apple"><i class="fab fa-apple"></i></a>
            </div>

            <div class="signup-link">
                <p>Don't have an account?</p>
                <a href="{{ route('register') }}">Create free account <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i></a>
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
        if (savedTheme === 'dark') {
            document.body.classList.add('dark');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else if (savedTheme === 'light') {
            document.body.classList.remove('dark');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        } else {
            // Check system preference
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

        // Toggle function
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
        const passwordInput = document.getElementById('password');
        if (togglePwd && passwordInput) {
            togglePwd.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                togglePwd.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        // Focus email if empty
        const emailField = document.getElementById('email');
        if (emailField && !emailField.value) {
            setTimeout(() => emailField.focus(), 200);
        }

        // Submit loading state
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        if (form && submitBtn) {
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    return;
                }
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Signing in...</span> <i class="fas fa-spinner fa-spin"></i>';
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<span>Sign in</span> <i class="fas fa-arrow-right"></i>';
                    }
                }, 5000);
            });
        }

        // scroll reveal animation
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

        // immediate reveal for first elements
        setTimeout(() => {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('in'));
        }, 60);
    });
</script>

</body>
</html>