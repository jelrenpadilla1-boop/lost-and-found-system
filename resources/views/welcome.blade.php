<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Foundify - Reunite Lost Items with Their Owners</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
         <!-- Favicon - Matching navbar logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
    
        <!-- Custom Styles -->
        <style>
            :root {
                /* Black and Pink Theme */
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
                --radius: 12px;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Inter', -apple-system, sans-serif;
            }

            body {
                background: var(--black);
                color: var(--white);
                font-size: 15px;
                line-height: 1.6;
                min-height: 100vh;
            }

            /* ========== HEADER ========== */
            .header {
                background: var(--black);
                border-bottom: 1px solid var(--border);
                padding: 20px 0;
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .header-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .logo {
                display: flex;
                align-items: center;
                gap: 10px;
                text-decoration: none;
                color: var(--white);
                transition: all 0.3s ease;
            }

            .logo:hover {
                transform: scale(1.05);
            }

            .logo:hover .logo-icon {
                background: var(--pink);
                box-shadow: 0 0 20px var(--pink-glow);
            }

            .logo-icon {
                width: 36px;
                height: 36px;
                background: var(--pink);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--white);
                font-size: 18px;
                transition: all 0.3s ease;
                box-shadow: 0 0 15px var(--pink-glow);
            }

            .logo-text {
                font-size: 20px;
                font-weight: 600;
                color: var(--white);
                transition: color 0.3s ease;
            }

            .logo:hover .logo-text {
                color: var(--pink);
            }

            .nav-links {
                display: flex;
                gap: 24px;
                align-items: center;
            }

            .nav-link {
                color: var(--gray);
                text-decoration: none;
                font-weight: 500;
                font-size: 14px;
                transition: all 0.3s ease;
                position: relative;
            }

            .nav-link::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 0;
                width: 0;
                height: 2px;
                background: var(--pink);
                transition: width 0.3s ease;
                box-shadow: 0 0 10px var(--pink-glow);
            }

            .nav-link:hover {
                color: var(--pink);
            }

            .nav-link:hover::after {
                width: 100%;
            }

            /* ========== HERO ========== */
            .hero {
                padding: 100px 20px;
                background: linear-gradient(135deg, var(--black) 0%, var(--black-light) 100%);
                text-align: center;
                border-bottom: 1px solid var(--border);
                position: relative;
                overflow: hidden;
            }

            .hero::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -50%;
                width: 100%;
                height: 100%;
                background: radial-gradient(circle, var(--pink-glow) 0%, transparent 70%);
                opacity: 0.1;
                animation: pulse 8s infinite;
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 0.1; }
                50% { transform: scale(1.1); opacity: 0.15; }
            }

            .hero-container {
                max-width: 800px;
                margin: 0 auto;
                position: relative;
                z-index: 1;
            }

            .hero-icon {
                width: 80px;
                height: 80px;
                background: var(--black-light);
                border: 2px solid var(--pink);
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 32px;
                color: var(--pink);
                font-size: 32px;
                transition: all 0.3s ease;
                box-shadow: 0 0 20px var(--pink-glow);
                animation: float 6s infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }

            .hero-icon:hover {
                transform: rotate(360deg) scale(1.1);
                background: var(--pink);
                color: var(--white);
            }

            .hero-title {
                font-size: 48px;
                font-weight: 700;
                color: var(--white);
                margin-bottom: 20px;
                line-height: 1.2;
                text-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            }

            .hero-title span {
                color: var(--pink);
                position: relative;
                display: inline-block;
            }

            .hero-title span::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 100%;
                height: 2px;
                background: var(--pink);
                box-shadow: 0 0 10px var(--pink-glow);
                animation: slide 3s infinite;
            }

            @keyframes slide {
                0%, 100% { width: 100%; }
                50% { width: 50%; margin-left: 25%; }
            }

            .hero-subtitle {
                font-size: 18px;
                color: var(--gray);
                margin-bottom: 40px;
                line-height: 1.6;
            }

            /* ========== BUTTONS ========== */
            .btn {
                padding: 14px 32px;
                border-radius: 30px;
                font-size: 15px;
                font-weight: 600;
                text-decoration: none;
                border: 2px solid transparent;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }

            .btn::before {
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

            .btn:hover::before {
                width: 300px;
                height: 300px;
            }

            .btn-primary {
                background: var(--pink);
                color: var(--white);
                border: 2px solid var(--pink);
                box-shadow: 0 0 20px var(--pink-glow);
            }

            .btn-primary:hover {
                background: transparent;
                color: var(--pink);
                transform: translateY(-3px);
                box-shadow: 0 10px 30px var(--pink-glow);
            }

            .btn-outline {
                background: transparent;
                color: var(--white);
                border-color: var(--border);
            }

            .btn-outline:hover {
                background: var(--pink);
                color: var(--white);
                border-color: var(--pink);
                transform: translateY(-3px);
                box-shadow: 0 10px 30px var(--pink-glow);
            }

            /* ========== FEATURES ========== */
            .features {
                padding: 100px 20px;
                background: var(--black);
                position: relative;
            }

            .features-container {
                max-width: 1200px;
                margin: 0 auto;
            }

            .section-header {
                text-align: center;
                margin-bottom: 60px;
            }

            .section-title {
                font-size: 36px;
                font-weight: 700;
                color: var(--white);
                margin-bottom: 16px;
                position: relative;
                display: inline-block;
            }

            .section-title::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 60px;
                height: 3px;
                background: var(--pink);
                box-shadow: 0 0 10px var(--pink-glow);
                animation: expand 3s infinite;
            }

            @keyframes expand {
                0%, 100% { width: 60px; }
                50% { width: 100px; }
            }

            .section-subtitle {
                font-size: 16px;
                color: var(--gray);
                max-width: 600px;
                margin: 20px auto 0;
            }

            .features-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 30px;
            }

            @media (min-width: 768px) {
                .features-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (min-width: 1024px) {
                .features-grid {
                    grid-template-columns: repeat(4, 1fr);
                }
            }

            .feature-card {
                background: var(--black-light);
                border: 1px solid var(--border);
                border-radius: 20px;
                padding: 30px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .feature-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, transparent, rgba(255, 20, 147, 0.1));
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .feature-card:hover {
                transform: translateY(-10px);
                border-color: var(--pink);
                box-shadow: 0 20px 40px rgba(255, 20, 147, 0.2);
            }

            .feature-card:hover::before {
                opacity: 1;
            }

            .feature-card:hover .feature-icon {
                background: var(--pink);
                color: var(--white);
                transform: rotate(360deg);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                background: var(--black-lighter);
                border: 1px solid var(--border);
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 25px;
                color: var(--pink);
                font-size: 24px;
                transition: all 0.5s ease;
            }

            .feature-title {
                font-size: 20px;
                font-weight: 600;
                color: var(--white);
                margin-bottom: 15px;
                transition: color 0.3s ease;
            }

            .feature-card:hover .feature-title {
                color: var(--pink);
            }

            .feature-description {
                font-size: 15px;
                color: var(--gray);
                line-height: 1.6;
                transition: color 0.3s ease;
            }

            .feature-card:hover .feature-description {
                color: var(--white);
            }

            /* ========== STEPS ========== */
            .steps-section {
                padding: 100px 20px;
                background: var(--black-light);
                border-top: 1px solid var(--border);
                border-bottom: 1px solid var(--border);
                position: relative;
            }

            .steps-container {
                max-width: 900px;
                margin: 0 auto;
            }

            .steps {
                display: flex;
                flex-direction: column;
                gap: 40px;
            }

            @media (min-width: 768px) {
                .steps {
                    flex-direction: row;
                    gap: 30px;
                }
            }

            .step {
                flex: 1;
                text-align: center;
                padding: 30px;
                background: var(--black);
                border-radius: 20px;
                border: 1px solid var(--border);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .step::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 20, 147, 0.1), transparent);
                transition: left 0.5s ease;
            }

            .step:hover {
                transform: translateY(-10px);
                border-color: var(--pink);
                box-shadow: 0 20px 40px rgba(255, 20, 147, 0.2);
            }

            .step:hover::before {
                left: 100%;
            }

            .step:hover .step-number {
                background: var(--pink);
                color: var(--white);
                box-shadow: 0 0 30px var(--pink-glow);
                transform: scale(1.1);
            }

            .step-number {
                width: 50px;
                height: 50px;
                background: var(--black-light);
                color: var(--pink);
                border: 2px solid var(--pink);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 25px;
                font-weight: 700;
                font-size: 20px;
                transition: all 0.3s ease;
                box-shadow: 0 0 15px var(--pink-glow);
            }

            .step-title {
                font-size: 20px;
                font-weight: 600;
                color: var(--white);
                margin-bottom: 15px;
                transition: color 0.3s ease;
            }

            .step:hover .step-title {
                color: var(--pink);
            }

            .step-description {
                font-size: 15px;
                color: var(--gray);
                line-height: 1.6;
                transition: color 0.3s ease;
            }

            .step:hover .step-description {
                color: var(--white);
            }

            /* ========== STATS ========== */
            .stats {
                padding: 80px 20px;
                background: var(--black);
                text-align: center;
            }

            .stats-container {
                max-width: 1200px;
                margin: 0 auto;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 40px;
            }

            @media (min-width: 768px) {
                .stats-grid {
                    grid-template-columns: repeat(4, 1fr);
                }
            }

            .stat {
                padding: 20px;
                border-radius: 15px;
                transition: all 0.3s ease;
            }

            .stat:hover {
                transform: translateY(-10px);
                background: var(--black-light);
                border: 1px solid var(--pink);
                box-shadow: 0 10px 30px rgba(255, 20, 147, 0.2);
            }

            .stat-number {
                font-size: 48px;
                font-weight: 700;
                color: var(--pink);
                display: block;
                margin-bottom: 8px;
                line-height: 1;
                text-shadow: 0 0 20px var(--pink-glow);
                transition: all 0.3s ease;
            }

            .stat:hover .stat-number {
                transform: scale(1.1);
            }

            .stat-label {
                font-size: 14px;
                color: var(--gray);
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: color 0.3s ease;
            }

            .stat:hover .stat-label {
                color: var(--white);
            }

            /* ========== CTA ========== */
            .cta {
                padding: 100px 20px;
                background: linear-gradient(135deg, var(--black-light) 0%, var(--black) 100%);
                text-align: center;
                border-top: 1px solid var(--border);
                border-bottom: 1px solid var(--border);
                position: relative;
                overflow: hidden;
            }

            .cta::before {
                content: '';
                position: absolute;
                bottom: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, var(--pink-glow) 0%, transparent 70%);
                opacity: 0.1;
                animation: rotate 20s linear infinite;
            }

            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .cta-container {
                max-width: 600px;
                margin: 0 auto;
                position: relative;
                z-index: 1;
            }

            .cta-title {
                font-size: 36px;
                font-weight: 700;
                color: var(--white);
                margin-bottom: 20px;
                position: relative;
                display: inline-block;
            }

            .cta-title::before,
            .cta-title::after {
                content: '✨';
                position: absolute;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .cta-title::before {
                left: -40px;
                transform: rotate(-20deg);
            }

            .cta-title::after {
                right: -40px;
                transform: rotate(20deg);
            }

            .cta:hover .cta-title::before,
            .cta:hover .cta-title::after {
                opacity: 1;
                animation: sparkle 1s infinite;
            }

            @keyframes sparkle {
                0%, 100% { opacity: 0.5; transform: rotate(-20deg) scale(1); }
                50% { opacity: 1; transform: rotate(-20deg) scale(1.2); }
            }

            .cta:hover .cta-title::after {
                animation: sparkle 1s infinite 0.5s;
            }

            .cta-subtitle {
                font-size: 16px;
                color: var(--gray);
                margin-bottom: 40px;
            }

            .cta-buttons {
                display: flex;
                gap: 16px;
                justify-content: center;
                flex-wrap: wrap;
            }

            /* ========== FOOTER ========== */
            .footer {
                background: var(--black);
                color: var(--white);
                padding: 80px 20px 40px;
                border-top: 1px solid var(--border);
            }

            .footer-container {
                max-width: 1200px;
                margin: 0 auto;
            }

            .footer-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 50px;
                margin-bottom: 60px;
            }

            @media (min-width: 768px) {
                .footer-grid {
                    grid-template-columns: 2fr 1fr 1fr;
                }
            }

            .footer-section h3 {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 20px;
                color: var(--pink);
                position: relative;
                display: inline-block;
            }

            .footer-section h3::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 30px;
                height: 2px;
                background: var(--pink);
                box-shadow: 0 0 10px var(--pink-glow);
                transition: width 0.3s ease;
            }

            .footer-section:hover h3::after {
                width: 100%;
            }

            .footer-links {
                list-style: none;
            }

            .footer-links li {
                margin-bottom: 12px;
            }

            .footer-links a {
                color: var(--gray);
                text-decoration: none;
                transition: all 0.3s ease;
                font-size: 14px;
                position: relative;
                display: inline-block;
            }

            .footer-links a::before {
                content: '→';
                position: absolute;
                left: -20px;
                opacity: 0;
                transition: all 0.3s ease;
                color: var(--pink);
            }

            .footer-links a:hover {
                color: var(--pink);
                transform: translateX(20px);
            }

            .footer-links a:hover::before {
                opacity: 1;
                left: 0;
            }

            .copyright {
                text-align: center;
                padding-top: 40px;
                border-top: 1px solid var(--border);
                color: var(--gray);
                font-size: 14px;
                transition: color 0.3s ease;
            }

            .copyright:hover {
                color: var(--pink);
            }

            .footer-text {
                color: var(--gray);
                font-size: 15px;
                line-height: 1.6;
                margin-top: 16px;
            }

            /* ========== RESPONSIVE ========== */
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 36px;
                }
                
                .hero-subtitle {
                    font-size: 16px;
                }
                
                .section-title {
                    font-size: 28px;
                }
                
                .cta-buttons {
                    flex-direction: column;
                    align-items: center;
                }
                
                .btn {
                    width: 100%;
                    max-width: 300px;
                    justify-content: center;
                }
                
                .nav-links {
                    display: none;
                }
                
                .hero {
                    padding: 80px 20px;
                }
                
                .features, .steps-section, .cta {
                    padding: 80px 20px;
                }
                
                .hero-icon {
                    width: 60px;
                    height: 60px;
                    font-size: 24px;
                }
                
                .stat-number {
                    font-size: 36px;
                }
            }

            /* ========== UTILITY ========== */
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

            /* Loading Animation */
            .loading {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: var(--black);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                transition: opacity 0.5s ease;
            }

            .loading.hidden {
                opacity: 0;
                pointer-events: none;
            }

            .loading-spinner {
                width: 50px;
                height: 50px;
                border: 3px solid var(--border);
                border-top-color: var(--pink);
                border-radius: 50%;
                animation: spin 1s linear infinite;
                box-shadow: 0 0 20px var(--pink-glow);
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <!-- Loading Animation -->
        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
        </div>

        <!-- Header -->
        <header class="header">
            <div class="header-container">
                <a href="/" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <span class="logo-text">Foundify</span>
                </a>
                
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 10px 20px;">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-container">
                <div class="hero-icon">
                    <i class="fas fa-search-location"></i>
                </div>
                <h1 class="hero-title">Reunite <span>Lost Items</span> with Their Owners</h1>
                <p class="hero-subtitle">
                    Foundify connects people who've lost items with those who've found them. 
                    A simple, effective platform for returning lost belongings.
                </p>
                
                <div style="margin-top: 40px;">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="fas fa-rocket"></i>
                            Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="features-container">
                <div class="section-header">
                    <h2 class="section-title">How It Works</h2>
                    <p class="section-subtitle">Simple steps to reunite lost items with their owners</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h3 class="feature-title">Report Lost</h3>
                        <p class="feature-description">
                            Report lost items with photos and details. The more information, the better the chance of recovery.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="feature-title">Report Found</h3>
                        <p class="feature-description">
                            Found something? Report it with details to help find the owner.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3 class="feature-title">Smart Matching</h3>
                        <p class="feature-description">
                            Our system matches lost and found reports based on descriptions and locations.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="feature-title">Instant Notifications</h3>
                        <p class="feature-description">
                            Get notified immediately when potential matches are found.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Steps Section -->
        <section class="steps-section">
            <div class="steps-container">
                <div class="section-header">
                    <h2 class="section-title">Get Started in Minutes</h2>
                    <p class="section-subtitle">Join thousands who've successfully reunited items</p>
                </div>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Create Account</h3>
                        <p class="step-description">
                            Sign up for a free account in under a minute.
                        </p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3 class="step-title">Report Item</h3>
                        <p class="step-description">
                            Report your lost or found item with details.
                        </p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Get Matches</h3>
                        <p class="step-description">
                            Our system will notify you of potential matches.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="stats">
            <div class="stats-container">
                <div class="stats-grid">
                    <div class="stat">
                        <span class="stat-number">2,847+</span>
                        <span class="stat-label">Items Reunited</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">15.2K</span>
                        <span class="stat-label">Active Users</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">94%</span>
                        <span class="stat-label">Success Rate</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">28h</span>
                        <span class="stat-label">Avg Recovery Time</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <div class="cta-container">
                <h2 class="cta-title">Ready to Begin?</h2>
                <p class="cta-subtitle">
                    Join our community and help reunite lost items with their owners.
                </p>
                
                <div class="cta-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Sign In
                        </a>
                        
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline">
                                <i class="fas fa-user-plus"></i>
                                Create Account
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-grid">
                    <div class="footer-section">
                        <h3>Foundify</h3>
                        <p class="footer-text">
                            Reuniting lost items with their owners through community collaboration and smart technology.
                        </p>
                    </div>
                    
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="/">Home</a></li>
                            @auth
                                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Sign In</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
                            @endauth
                        </ul>
                    </div>
                    
                    <div class="footer-section">
                        <h3>Support</h3>
                        <ul class="footer-links">
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="copyright">
                    &copy; {{ date('Y') }} Foundify. All rights reserved.
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hide loading animation
                const loading = document.getElementById('loading');
                setTimeout(() => {
                    loading.classList.add('hidden');
                }, 500);

                // Smooth page load
                document.body.style.opacity = '0';
                setTimeout(() => {
                    document.body.style.transition = 'opacity 0.5s';
                    document.body.style.opacity = '1';
                }, 100);

                // Add subtle hover effects to cards
                const cards = document.querySelectorAll('.feature-card, .step, .stat');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-10px)';
                        this.style.transition = 'transform 0.3s';
                    });
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });

                // Animate numbers on scroll
                const stats = document.querySelectorAll('.stat-number');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const target = entry.target;
                            const value = target.innerText.replace(/[^0-9]/g, '');
                            if (value) {
                                animateNumber(target, parseInt(value));
                            }
                        }
                    });
                }, { threshold: 0.5 });

                stats.forEach(stat => observer.observe(stat));

                function animateNumber(element, final) {
                    let current = 0;
                    const increment = final / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= final) {
                            element.innerText = element.innerText.includes('+') ? 
                                final + '+' : final + (element.innerText.includes('%') ? '%' : '');
                            clearInterval(timer);
                        } else {
                            element.innerText = Math.floor(current) + 
                                (element.innerText.includes('+') ? '+' : '');
                        }
                    }, 20);
                }

                // Parallax effect on hero
                window.addEventListener('scroll', () => {
                    const scrolled = window.pageYOffset;
                    const hero = document.querySelector('.hero');
                    if (hero) {
                        hero.style.transform = `translateY(${scrolled * 0.5}px)`;
                    }
                });
            });
        </script>
    </body>
</html>