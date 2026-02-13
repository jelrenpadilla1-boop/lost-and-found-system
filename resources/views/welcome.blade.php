<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Foundify') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Custom Styles -->
        <style>
            :root {
                --white: #ffffff;
                --off-white: #fafafa;
                --light-gray: #f5f5f5;
                --medium-gray: #e5e5e5;
                --gray: #a3a3a3;
                --dark-gray: #737373;
                --darker-gray: #404040;
                --black: #171717;
                --border: #e5e5e5;
                --radius: 8px;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Inter', -apple-system, sans-serif;
            }

            body {
                background: var(--white);
                color: var(--black);
                font-size: 15px;
                line-height: 1.6;
                min-height: 100vh;
            }

            /* ========== HEADER ========== */
            .header {
                background: var(--white);
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
                color: var(--black);
            }

            .logo-icon {
                width: 36px;
                height: 36px;
                background: var(--black);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--white);
                font-size: 18px;
            }

            .logo-text {
                font-size: 20px;
                font-weight: 600;
                color: var(--black);
            }

            .nav-links {
                display: flex;
                gap: 24px;
                align-items: center;
            }

            .nav-link {
                color: var(--dark-gray);
                text-decoration: none;
                font-weight: 500;
                font-size: 14px;
                transition: color 0.2s;
            }

            .nav-link:hover {
                color: var(--black);
            }

            /* ========== HERO ========== */
            .hero {
                padding: 100px 20px;
                background: var(--off-white);
                text-align: center;
                border-bottom: 1px solid var(--border);
            }

            .hero-container {
                max-width: 800px;
                margin: 0 auto;
            }

            .hero-icon {
                width: 72px;
                height: 72px;
                background: var(--white);
                border: 1px solid var(--border);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 32px;
                color: var(--black);
                font-size: 28px;
            }

            .hero-title {
                font-size: 42px;
                font-weight: 600;
                color: var(--black);
                margin-bottom: 20px;
                line-height: 1.2;
            }

            .hero-subtitle {
                font-size: 18px;
                color: var(--dark-gray);
                margin-bottom: 40px;
                line-height: 1.6;
            }

            /* ========== BUTTONS ========== */
            .btn {
                padding: 14px 32px;
                border-radius: var(--radius);
                font-size: 15px;
                font-weight: 500;
                text-decoration: none;
                border: 1px solid transparent;
                transition: all 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
            }

            .btn-primary {
                background: var(--black);
                color: var(--white);
                border: 1px solid var(--black);
            }

            .btn-primary:hover {
                background: var(--darker-gray);
                border-color: var(--darker-gray);
            }

            .btn-outline {
                background: var(--white);
                color: var(--black);
                border-color: var(--border);
            }

            .btn-outline:hover {
                background: var(--off-white);
                border-color: var(--gray);
            }

            /* ========== FEATURES ========== */
            .features {
                padding: 100px 20px;
                background: var(--white);
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
                font-size: 32px;
                font-weight: 600;
                color: var(--black);
                margin-bottom: 16px;
            }

            .section-subtitle {
                font-size: 16px;
                color: var(--dark-gray);
                max-width: 600px;
                margin: 0 auto;
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
                background: var(--white);
                border: 1px solid var(--border);
                border-radius: var(--radius);
                padding: 30px;
                transition: all 0.2s;
            }

            .feature-card:hover {
                border-color: var(--black);
                background: var(--off-white);
            }

            .feature-icon {
                width: 48px;
                height: 48px;
                background: var(--off-white);
                border: 1px solid var(--border);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
                color: var(--black);
                font-size: 20px;
            }

            .feature-title {
                font-size: 18px;
                font-weight: 600;
                color: var(--black);
                margin-bottom: 12px;
            }

            .feature-description {
                font-size: 15px;
                color: var(--dark-gray);
                line-height: 1.5;
            }

            /* ========== STEPS ========== */
            .steps-section {
                padding: 100px 20px;
                background: var(--off-white);
                border-top: 1px solid var(--border);
                border-bottom: 1px solid var(--border);
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
            }

            .step-number {
                width: 40px;
                height: 40px;
                background: var(--white);
                color: var(--black);
                border: 1px solid var(--border);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                font-weight: 600;
                font-size: 18px;
            }

            .step-title {
                font-size: 18px;
                font-weight: 600;
                color: var(--black);
                margin-bottom: 12px;
            }

            .step-description {
                font-size: 15px;
                color: var(--dark-gray);
                line-height: 1.5;
            }

            /* ========== STATS ========== */
            .stats {
                padding: 80px 20px;
                background: var(--white);
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
            }

            .stat-number {
                font-size: 40px;
                font-weight: 600;
                color: var(--black);
                display: block;
                margin-bottom: 8px;
                line-height: 1;
            }

            .stat-label {
                font-size: 14px;
                color: var(--dark-gray);
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* ========== CTA ========== */
            .cta {
                padding: 100px 20px;
                background: var(--off-white);
                text-align: center;
                border-top: 1px solid var(--border);
                border-bottom: 1px solid var(--border);
            }

            .cta-container {
                max-width: 600px;
                margin: 0 auto;
            }

            .cta-title {
                font-size: 32px;
                font-weight: 600;
                color: var(--black);
                margin-bottom: 20px;
            }

            .cta-subtitle {
                font-size: 16px;
                color: var(--dark-gray);
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
                color: var(--white);
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
                transition: color 0.2s;
                font-size: 14px;
            }

            .footer-links a:hover {
                color: var(--white);
            }

            .copyright {
                text-align: center;
                padding-top: 40px;
                border-top: 1px solid var(--darker-gray);
                color: var(--gray);
                font-size: 14px;
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
            }

            /* ========== UTILITY ========== */
            .border-bottom {
                border-bottom: 1px solid var(--border);
            }
            
            .border-top {
                border-top: 1px solid var(--border);
            }
        </style>
    </head>
    <body>
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
                <h1 class="hero-title">Reunite Lost Items with Their Owners</h1>
                <p class="hero-subtitle">
                    Foundify connects people who've lost items with those who've found them. 
                    A simple, effective platform for returning lost belongings.
                </p>
                
                <div style="margin-top: 40px;">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">
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
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="feature-title">Connect & Return</h3>
                        <p class="feature-description">
                            Get notified of matches and connect to arrange the return of items.
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
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Sign In
                        </a>
                        
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline">
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
                // Smooth page load
                document.body.style.opacity = '0';
                setTimeout(() => {
                    document.body.style.transition = 'opacity 0.3s';
                    document.body.style.opacity = '1';
                }, 100);

                // Add subtle hover effects
                const cards = document.querySelectorAll('.feature-card, .step, .stat');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-2px)';
                        this.style.transition = 'transform 0.2s';
                    });
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            });
        </script>
    </body>
</html>