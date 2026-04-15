<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foundify')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/logo.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #ff1493;
            --primary-light: #ff69b4;
            --primary-dark: #c71585;
            --primary-glow: rgba(255, 20, 147, 0.3);
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
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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
            box-shadow: 0 0 10px var(--primary-glow);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        /* Auth Container Styles - Shared between forgot and reset */
        .auth-container {
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
            transition: var(--transition);
        }

        .logo-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 30px var(--primary-glow);
        }

        .brand-name {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(to right, white, var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-tagline {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .quote-box {
            background: rgba(255, 20, 147, 0.08);
            border-radius: 16px;
            padding: 24px;
            margin-top: auto;
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

        /* Form Styles */
        .auth-form {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 32px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .auth-form:hover {
            border-color: var(--primary);
            box-shadow: 0 10px 30px var(--primary-glow);
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
            letter-spacing: 0.3px;
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
            z-index: 1;
            transition: var(--transition);
        }

        .input-group:focus-within .input-icon {
            color: var(--primary);
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
            outline: none;
        }

        .input-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
            background: var(--bg-card);
        }

        .input-group input::placeholder {
            color: var(--text-muted);
            font-size: 14px;
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
            padding: 8px;
            transition: var(--transition);
            z-index: 1;
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
            font-weight: 500;
        }

        /* Password Match */
        .password-match {
            font-size: 12px;
            margin-top: 8px;
            min-height: 20px;
            font-weight: 500;
        }

        .password-match.match-success {
            color: var(--success);
        }

        .password-match.match-error {
            color: var(--error);
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
            animation: slideIn 0.3s ease;
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
            animation: slideIn 0.3s ease;
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

        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            border-radius: 40px;
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 20px var(--primary-glow);
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
            z-index: 1;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px var(--primary-glow);
        }

        .submit-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .submit-btn span, .submit-btn i {
            position: relative;
            z-index: 2;
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .submit-btn:disabled::before {
            display: none;
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
            transition: var(--transition);
        }

        .tip-item:hover .tip-icon {
            transform: scale(1.1);
            background: rgba(255, 20, 147, 0.2);
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

        .password-tips {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-form, .success-message, .error-message {
            animation: fadeIn 0.5s ease;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .auth-container {
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
            
            .auth-form {
                padding: 24px;
            }
            
            .form-header h2 {
                font-size: 24px;
            }
        }

        /* Loading Spinner */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>