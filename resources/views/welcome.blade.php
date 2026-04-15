<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundify — Lost & Found platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #141414;
            --bg-secondary: #0a0a0a;
            --bg-card: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
            --bg-card-solid: #1a1a1a;
            --text-primary: #ffffff;
            --text-secondary: #e5e5e5;
            --text-muted: #b3b3b3;
            --border-color: #2a2a2a;
            --header-bg: linear-gradient(180deg, #000000 0%, rgba(0,0,0,0.95) 50%, rgba(0,0,0,0) 100%);
            --header-scrolled: #0a0a0a;
            --feature-bg: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
            --testimonial-bg: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
            --stat-bg: linear-gradient(135deg, #0a0a0a 0%, #141414 100%);
            --footer-bg: #0a0a0a;
            --shadow-color: rgba(0,0,0,0.6);
        }

        body.light {
            --bg-primary: #f5f5f5;
            --bg-secondary: #ffffff;
            --bg-card: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
            --bg-card-solid: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #333333;
            --text-muted: #666666;
            --border-color: #e0e0e0;
            --header-bg: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,0.95) 50%, rgba(255,255,255,0) 100%);
            --header-scrolled: #ffffff;
            --feature-bg: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
            --testimonial-bg: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
            --stat-bg: linear-gradient(135deg, #f0f0f0 0%, #fafafa 100%);
            --footer-bg: #f8f8f8;
            --shadow-color: rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        ::-webkit-scrollbar { width: 8px; background: var(--bg-primary); }
        ::-webkit-scrollbar-track { background: var(--border-color); }
        ::-webkit-scrollbar-thumb { background: #e50914; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #f6121d; }

        /* ── Header ── */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--header-bg);
            z-index: 1000;
            padding: 1.2rem 4%;
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: var(--header-scrolled);
            box-shadow: 0 2px 20px var(--shadow-color);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo - NOT in caps lock */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.8rem;
            font-weight: 900;
            letter-spacing: -0.02em;
            color: #e50914;
            text-transform: none;
            transition: transform 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }

        .logo:hover { transform: scale(1.05); }

        .logo-icon { font-size: 1.8rem; line-height: 1; }

        .logo-suffix {
            color: #ffffff;
        }

        body.light .logo-suffix {
            color: #1a1a1a;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: #e5e5e5;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
            position: relative;
            text-decoration: none;
        }

        body.light .nav-link {
            color: #333333;
        }

        .nav-link:hover { color: var(--text-primary); }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #e50914;
            transition: width 0.3s;
        }

        .nav-link:hover::after { width: 100%; }

        .theme-toggle {
            background: rgba(229, 9, 20, 0.15);
            border: 1px solid rgba(255,255,255,0.15);
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

        body.light .theme-toggle {
            background: rgba(229, 9, 20, 0.08);
            border-color: #e0e0e0;
            color: #e50914;
        }

        .theme-toggle:hover {
            background: #e50914;
            color: white;
            transform: scale(1.05);
            border-color: #e50914;
        }

        .btn-nav {
            background: #e50914;
            color: white;
            border: none;
            padding: 0.6rem 1.8rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-nav:hover {
            background: #f6121d;
            transform: scale(1.05);
        }

        .btn-outline-nav {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.6);
            color: #e5e5e5;
            padding: 0.5rem 1.4rem;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.9rem;
        }

        body.light .btn-outline-nav {
            border-color: #333333;
            color: #333333;
        }

        .btn-outline-nav:hover {
            background: rgba(229, 9, 20, 0.1);
            border-color: #e50914;
            color: #e50914;
            transform: scale(1.05);
        }

        /* ── Hero ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 100%);
            padding: 120px 20px 80px;
        }

        body.light .hero {
            background: linear-gradient(135deg, rgba(245,245,245,0.92) 0%, rgba(240,240,240,0.85) 100%);
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(229, 9, 20, 0.9);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .hero-title span { color: #e50914; }

        .hero-desc {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.5;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
        }

        .btn-primary {
            background: #e50914;
            color: white;
            padding: 0.9rem 2.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #f6121d;
            transform: scale(1.05);
        }

        .btn-secondary {
            background: rgba(109, 109, 110, 0.7);
            color: white;
            padding: 0.9rem 2.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        body.light .btn-secondary {
            background: rgba(50,50,50,0.15);
            color: #1a1a1a;
        }

        .btn-secondary:hover {
            background: rgba(109, 109, 110, 0.9);
            transform: scale(1.05);
        }

        body.light .btn-secondary:hover {
            background: rgba(50,50,50,0.25);
        }

        /* ── Stats ── */
        .stats-section {
            background: var(--stat-bg);
            padding: 4rem 4%;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .stats-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            text-align: center;
        }

        .stat-item { transition: transform 0.3s; }
        .stat-item:hover { transform: translateY(-5px); }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 900;
            color: #e50914;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── Row Sections ── */
        .row-section {
            padding: 4rem 4%;
            position: relative;
            z-index: 10;
        }

        .row-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 1.5rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .row-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .row-link {
            color: var(--text-muted);
            font-size: 0.9rem;
            transition: color 0.2s;
            text-decoration: none;
        }

        .row-link:hover { color: #e50914; }

        .row-scroll {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding-bottom: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .row-scroll::-webkit-scrollbar { height: 8px; }
        .row-scroll::-webkit-scrollbar-track { background: var(--border-color); border-radius: 4px; }
        .row-scroll::-webkit-scrollbar-thumb { background: #e50914; border-radius: 4px; }

        /* ── Netflix Card ── */
        .netflix-card {
            flex: 0 0 280px;
            background: var(--bg-card-solid);
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            position: relative;
            border: 1px solid var(--border-color);
        }

        .netflix-card:hover {
            transform: scale(1.05);
            z-index: 20;
            box-shadow: 0 20px 40px var(--shadow-color);
            border-color: #e50914;
        }

        .card-image { position: relative; height: 380px; overflow: hidden; }
        .card-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
        .netflix-card:hover .card-image img { transform: scale(1.1); }

        .card-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(0deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .netflix-card:hover .card-overlay { opacity: 1; }

        .card-badge {
            position: absolute;
            top: 10px; right: 10px;
            background: #e50914;
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .card-badge.lost { background: #e50914; }
        .card-badge.found { background: #2e7d32; }

        .card-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.3rem; color: white; }
        .card-location { font-size: 0.75rem; color: #b3b3b3; display: flex; align-items: center; gap: 0.3rem; }

        /* ── Features Grid ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card {
            background: var(--feature-bg);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            border: 1px solid var(--border-color);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: #e50914;
            box-shadow: 0 10px 30px rgba(229, 9, 20, 0.2);
        }

        .feature-icon {
            width: 70px; height: 70px;
            background: rgba(229, 9, 20, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #e50914;
            transition: all 0.3s;
        }

        .feature-card:hover .feature-icon {
            background: #e50914;
            color: white;
            transform: scale(1.1);
        }

        .feature-card h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-primary); }
        .feature-card p { font-size: 0.85rem; color: var(--text-muted); line-height: 1.4; }

        /* ── Testimonials ── */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .testimonial-card {
            background: var(--testimonial-bg);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }

        .testimonial-card:hover { transform: translateY(-5px); border-color: #e50914; }

        .stars { color: #ffd700; margin-bottom: 1rem; }

        .quote { font-size: 0.95rem; line-height: 1.5; margin-bottom: 1.5rem; color: var(--text-secondary); }

        .testimonial-author { display: flex; align-items: center; gap: 0.8rem; }

        .author-avatar {
            width: 45px; height: 45px;
            background: #e50914;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
        }

        .author-info h4 { font-size: 0.9rem; font-weight: 700; margin-bottom: 0.2rem; color: var(--text-primary); }
        .author-info p { font-size: 0.75rem; color: var(--text-muted); }

        /* ── CTA Banner ── */
        .cta-banner {
            background: linear-gradient(135deg, #e50914 0%, #b00710 100%);
            margin: 3rem auto;
            padding: 3rem;
            border-radius: 12px;
            text-align: center;
            position: relative;
            overflow: hidden;
            max-width: 1200px;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .cta-banner h2 { font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; position: relative; z-index: 1; color: white; }
        .cta-banner p { font-size: 1rem; margin-bottom: 1.5rem; opacity: 0.9; position: relative; z-index: 1; color: white; }

        .btn-cta {
            background: white;
            color: #e50914;
            padding: 0.9rem 2.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            z-index: 1;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cta:hover { transform: scale(1.05); box-shadow: 0 5px 20px rgba(0,0,0,0.3); }

        /* ── Footer ── */
        .footer {
            background: var(--footer-bg);
            padding: 3rem 4% 2rem;
            margin-top: 3rem;
            border-top: 1px solid var(--border-color);
        }

        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .footer-col h4 { font-size: 0.9rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary); }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a { color: var(--text-muted); font-size: 0.8rem; text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: #e50914; }

        .social-icons { display: flex; gap: 1rem; margin-top: 1rem; }

        .social-icons a {
            width: 36px; height: 36px;
            background: var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            transition: all 0.2s;
            text-decoration: none;
        }

        .social-icons a:hover { background: #e50914; color: white; transform: translateY(-3px); }

        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .testimonials-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .features-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; gap: 1.5rem; }
            .hero-buttons { flex-direction: column; align-items: center; }
            .row-header { flex-direction: column; gap: 0.5rem; }
            .footer-grid { grid-template-columns: 1fr; text-align: center; }
            .social-icons { justify-content: center; }
            .cta-banner h2 { font-size: 1.5rem; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in { animation: fadeInUp 0.6s ease forwards; }
    </style>
</head>
<body>

<header class="header" id="header">
    <div class="nav-container">
        <a href="/" class="logo"><i class="fas fa-compass logo-icon"></i> Found<span class="logo-suffix">ify</span></a>
        <div class="nav-links">
            <div class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon" id="themeIcon"></i>
            </div>
            <a href="{{ route('login') }}" class="btn-outline-nav">Sign In</a>
            <a href="{{ route('register') }}" class="btn-nav">Join Now</a>
        </div>
    </div>
</header>

<main>
    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title fade-in">Lost something? <span>Find it fast</span> with Foundify</h1>
            <p class="hero-desc fade-in">Join thousands of users who have reunited with their lost items. Smart matching, real-time alerts, and a community that cares.</p>
            <div class="hero-buttons fade-in">
                <a href="{{ route('register') }}" class="btn-primary"><i class="fas fa-play"></i> Get Started Free</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">12K+</div>
                <div class="stat-label">Items Reunited</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">~19h</div>
                <div class="stat-label">Avg. Response</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">99%</div>
                <div class="stat-label">Satisfaction</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">800+</div>
                <div class="stat-label">Cities</div>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="row-section">
        <div class="row-header">
            <h2 class="row-title">Why Choose Foundify</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-brain"></i></div>
                <h3>AI Smart Matching</h3>
                <p>Advanced algorithms match lost and found items with 95% accuracy</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bell"></i></div>
                <h3>Instant Alerts</h3>
                <p>Get notified immediately when potential matches are found</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
                <h3>Interactive Map</h3>
                <p>Visualize lost and found hotspots in your area</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Privacy First</h3>
                <p>Your contact info stays hidden until you confirm a match</p>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="row-section">
        <div class="row-header">
            <h2 class="row-title">Success Stories</h2>
            <a href="#" class="row-link">Read More <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="quote">"I left my backpack at a coffee shop in Austin. Within 6 hours Foundify matched me with someone who found it. Incredibly smooth and fast!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar">SK</div>
                    <div class="author-info">
                        <h4>Samira K.</h4>
                        <p>Austin, TX</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="quote">"Found a wallet with no ID, posted it on Foundify. The owner messaged me the same day — she was so thankful. This platform is truly magical."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">ML</div>
                    <div class="author-info">
                        <h4>Marcus L.</h4>
                        <p>Brooklyn, NY</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Banner -->
    <div class="cta-banner">
        <h2>Start Your Journey Today</h2>
        <p>Join over 12,000 users who have already reunited with their lost items</p>
        <a href="{{ route('register') }}" class="btn-cta"><i class="fas fa-play"></i> Get Started Free</a>
    </div>
</main>

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <h4>Foundify</h4>
            <ul class="footer-links">
                <li><a href="#">About Us</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Press</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Support</h4>
            <ul class="footer-links">
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Safety Tips</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Legal</h4>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Cookie Policy</a></li>
                <li><a href="#">Accessibility</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Connect</h4>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
    <div class="copyright">
        <p>&copy; 2024 Foundify. All rights reserved. Making reunions happen.</p>
    </div>
</footer>

<script>
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    const savedTheme = localStorage.getItem('foundify-theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
    } else if (savedTheme === 'dark') {
        document.body.classList.remove('light');
        themeIcon.classList.replace('fa-sun', 'fa-moon');
    } else {
        const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
        if (prefersLight) {
            document.body.classList.add('light');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('foundify-theme', 'light');
        }
    }

    themeToggle.addEventListener('click', () => {
        if (document.body.classList.contains('light')) {
            document.body.classList.remove('light');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('foundify-theme', 'dark');
        } else {
            document.body.classList.add('light');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('foundify-theme', 'light');
        }
    });

    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 50);
    });

    document.querySelectorAll('.row-scroll').forEach(scrollContainer => {
        scrollContainer.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                scrollContainer.scrollLeft += e.deltaY;
            }
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.row-section, .stats-section, .cta-banner').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });

    setTimeout(() => {
        document.querySelectorAll('.row-section, .stats-section, .cta-banner').forEach(el => {
            if (el.getBoundingClientRect().top < window.innerHeight) {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }
        });
    }, 100);
</script>
</body>
</html>