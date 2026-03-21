<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundify — Lost & Found platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            scroll-behavior: smooth;
            transition: background-color 0.25s ease, color 0.2s ease;
        }

        /* LIGHT MODE (default) */
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
            --radius-card: 28px;
            --radius-sm: 60px;
            --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            --header-bg: rgba(255, 255, 255, 0.92);
            --footer-bg: #fefefe;
            --icon-bg: #f5f3ff;
            --avatar-bg: #e9e4ff;
        }

        /* DARK MODE */
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
            --header-bg: rgba(15, 12, 26, 0.92);
            --footer-bg: #0e0c18;
            --icon-bg: #26213a;
            --avatar-bg: #29223f;
        }

        body {
            background: var(--bg-white);
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* header glass-lite dynamic */
        .header {
            padding: 1rem 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--header-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-light);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 700;
            font-size: 1.6rem;
            letter-spacing: -0.02em;
            color: var(--text-dark);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--accent);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: white;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .logo span {
            color: var(--accent);
            font-weight: 800;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* dark mode toggle button */
        .theme-toggle {
            background: var(--accent-soft);
            border: 1px solid var(--border-light);
            border-radius: 40px;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--accent);
            font-size: 1.2rem;
        }
        .theme-toggle:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
            transform: scale(0.96);
        }

        .nav-link {
            color: var(--text-muted);
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .btn-nav {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0.6rem 1.6rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            box-shadow: 0 2px 6px rgba(124, 58, 237, 0.2);
        }

        .btn-nav:hover {
            background: var(--accent-light);
            transform: scale(0.98);
            box-shadow: 0 6px 14px rgba(124, 58, 237, 0.25);
        }

        /* hero modern clean */
        .hero {
            padding: 5rem 6% 6rem;
            max-width: 1280px;
            margin: 0 auto;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            align-items: center;
            gap: 4rem;
        }

        .hero-left .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--accent-soft);
            border-radius: 60px;
            padding: 0.4rem 1.2rem 0.4rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--accent);
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: clamp(2.7rem, 6vw, 4.2rem);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .hero-title .accent {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .hero-desc {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 480px;
            margin-bottom: 2.4rem;
            line-height: 1.45;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 60px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: var(--transition);
            box-shadow: 0 8px 18px rgba(124, 58, 237, 0.2);
        }

        .btn-primary:hover {
            background: var(--accent-light);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border-soft);
            color: var(--text-dark);
            padding: 0.9rem 2rem;
            border-radius: 60px;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            color: var(--accent);
        }

        /* hero cards clean minimal */
        .hero-cards {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .item-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 1.1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .item-card:hover {
            border-color: var(--accent-light);
            box-shadow: var(--shadow-md);
            transform: translateX(5px);
        }

        .card-icon {
            width: 52px;
            height: 52px;
            background: var(--icon-bg);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--accent);
        }

        .card-info h4 {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
        }

        .card-info p {
            font-size: 0.8rem;
            color: var(--text-soft);
        }

        .badge-modern {
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.7rem;
            border-radius: 40px;
            margin-left: auto;
        }

        /* stats minimal clean */
        .stats-row {
            background: var(--bg-soft);
            border-block: 1px solid var(--border-light);
            padding: 2.8rem 6%;
        }

        .stats-container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .stat-block {
            text-align: center;
        }

        .stat-number {
            font-size: 2.6rem;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: -0.02em;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* sections common */
        .section {
            padding: 5rem 6%;
            max-width: 1280px;
            margin: 0 auto;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent-soft);
            border-radius: 40px;
            padding: 0.3rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 1.2rem;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 540px;
            margin-bottom: 3rem;
        }

        /* steps minimal grid */
        .steps-grid {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .step-modern {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-card);
            padding: 2rem 1.8rem;
            flex: 1 1 240px;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .step-modern:hover {
            border-color: var(--accent-light);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .step-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--accent);
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .step-modern h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.6rem;
        }

        .step-modern p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* features 2x2 fresh */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.8rem;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 28px;
            padding: 1.8rem;
            display: flex;
            gap: 1.2rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .feature-card:hover {
            border-color: var(--accent-light);
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: var(--accent-soft);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--accent);
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .feature-text p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* community block fresh */
        .community-card {
            background: var(--bg-soft);
            border-radius: 3rem;
            padding: 3.5rem 2rem;
            text-align: center;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
        }

        .avatars {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .avatar-circle {
            width: 52px;
            height: 52px;
            background: var(--avatar-bg);
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--accent);
            border: 2px solid var(--bg-white);
            margin-left: -12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .avatar-circle:first-child {
            margin-left: 0;
        }

        .btn-community {
            background: var(--accent);
            color: white;
            padding: 0.9rem 2.4rem;
            border-radius: 60px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(124,58,237,0.2);
        }

        .btn-outline-light {
            background: transparent;
            border: 1.5px solid var(--border-soft);
            padding: 0.9rem 2rem;
            border-radius: 60px;
            font-weight: 500;
            transition: var(--transition);
            color: var(--text-dark);
        }

        .btn-outline-light:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            color: var(--accent);
        }

        /* testimonials */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .testimonial-item {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 28px;
            padding: 2rem;
            transition: var(--transition);
        }

        .testimonial-item:hover {
            border-color: var(--accent-light);
            box-shadow: var(--shadow-md);
        }

        .stars i {
            color: #fbbf24;
            margin-right: 2px;
            font-size: 0.9rem;
        }

        .quote-text {
            margin: 1.2rem 0 1.5rem;
            color: var(--text-dark);
            font-weight: 450;
            line-height: 1.5;
        }

        .user-details {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .user-avatar-sm {
            width: 44px;
            height: 44px;
            background: var(--accent-soft);
            border-radius: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--accent);
        }

        /* footer clean */
        .footer {
            background: var(--footer-bg);
            border-top: 1px solid var(--border-light);
            padding: 3rem 6% 2rem;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 2rem;
        }

        .footer-col {
            max-width: 260px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
        }

        .footer-link {
            color: var(--text-muted);
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .footer-link:hover {
            color: var(--accent);
        }

        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            font-size: 0.75rem;
            color: var(--text-soft);
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.6s ease, transform 0.5s ease;
        }
        .reveal.in {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 800px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .features-grid, .testimonials-grid, .steps-grid {
                grid-template-columns: 1fr;
            }
            .stats-container {
                justify-content: center;
                gap: 1.5rem;
            }
            .header {
                padding: 0.8rem 5%;
            }
            .theme-toggle {
                width: 38px;
                height: 38px;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <a href="#" class="logo">
        <div class="logo-icon"><i class="fas fa-compass"></i></div>
        Found<span>ify</span>
    </a>
    <div class="nav-right">
        <!-- Dark/Light mode toggle button -->
        <div class="theme-toggle" id="themeToggle" aria-label="Dark mode toggle">
            <i class="fas fa-moon" id="themeIcon"></i>
        </div>
        @auth
            <a href="{{ url('/dashboard') }}" class="btn-nav"><i class="fas fa-columns"></i> Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="nav-link">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-nav">Get started <i class="fas fa-arrow-right"></i></a>
            @endif
        @endauth
    </div>
</header>

<main>
    <!-- HERO section clean modern -->
    <section class="hero">
        <div class="hero-grid">
            <div class="hero-left">
                <div class="chip reveal"><i class="fas fa-hand-peace"></i> community-driven since 2024</div>
                <h1 class="hero-title reveal d1">Lost something? <span class="accent">Find it fast</span> with your neighborhood.</h1>
                <p class="hero-desc reveal d2">Foundify makes it effortless to report lost items and connect with finders. Simple, safe, and built for real reunions.</p>
                <div class="btn-group reveal d3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary"><i class="fas fa-th-large"></i> Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary"><i class="fas fa-plus-circle"></i> Report lost item</a>
                        <a href="{{ route('login') }}" class="btn-outline"><i class="fas fa-search"></i> I found something</a>
                    @endauth
                </div>
            </div>
            <div class="hero-right reveal d2">
                <div class="hero-cards">
                    <div class="item-card">
                        <div class="card-icon"><i class="fas fa-laptop"></i></div>
                        <div class="card-info">
                            <h4>MacBook Pro</h4>
                            <p><i class="fas fa-map-pin-alt" style="font-size: 0.7rem;"></i> Central Park, NYC</p>
                        </div>
                        <div class="badge-modern">lost · 3h ago</div>
                    </div>
                    <div class="item-card">
                        <div class="card-icon"><i class="fas fa-id-card"></i></div>
                        <div class="card-info">
                            <h4>Wallet + ID</h4>
                            <p><i class="fas fa-map-pin-alt"></i> Downtown Station</p>
                        </div>
                        <div class="badge-modern" style="background:var(--accent-soft); color:var(--accent);">match 96%</div>
                    </div>
                    <div class="item-card">
                        <div class="card-icon"><i class="fas fa-keys"></i></div>
                        <div class="card-info">
                            <h4>Apartment keys</h4>
                            <p>Found at Whole Foods</p>
                        </div>
                        <div class="badge-modern">found · 1d ago</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- stats minimal -->
    <div class="stats-row reveal">
        <div class="stats-container">
            <div class="stat-block"><div class="stat-number">12k+</div><div class="stat-label">items reunited</div></div>
            <div class="stat-block"><div class="stat-number">~19h</div><div class="stat-label">avg. response</div></div>
            <div class="stat-block"><div class="stat-number">99%</div><div class="stat-label">user satisfaction</div></div>
            <div class="stat-block"><div class="stat-number">800+</div><div class="stat-label">cities</div></div>
        </div>
    </div>

    <!-- how it works -->
    <section class="section">
        <div class="section-tag reveal"><i class="fas fa-lightbulb"></i> simple steps</div>
        <h2 class="section-title reveal d1">How it works — in three clicks</h2>
        <p class="section-desc reveal d2">No paperwork, just a smooth path to get your valuables back.</p>
        <div class="steps-grid">
            <div class="step-modern reveal"><div class="step-number">01</div><h3>Describe</h3><p>Tell us what you lost or found — add a photo, location, and details in seconds.</p></div>
            <div class="step-modern reveal d1"><div class="step-number">02</div><h3>Match</h3><p>Our smart system scans reports nearby and sends instant match alerts.</p></div>
            <div class="step-modern reveal d2"><div class="step-number">03</div><h3>Reunite</h3><p>Chat securely, arrange pickup, and celebrate your reunion.</p></div>
        </div>
    </section>

    <!-- features clean grid -->
    <section class="section">
        <div class="section-tag reveal"><i class="fas fa-gem"></i> core features</div>
        <h2 class="section-title reveal d1">Designed for real connections</h2>
        <div class="features-grid">
            <div class="feature-card reveal"><div class="feature-icon"><i class="fas fa-waveform"></i></div><div class="feature-text"><h4>Smart matching</h4><p>AI-enhanced location, category & time analysis for precise matches.</p></div></div>
            <div class="feature-card reveal d1"><div class="feature-icon"><i class="fas fa-bell"></i></div><div class="feature-text"><h4>Push alerts</h4><p>Get notified instantly when a potential match appears nearby.</p></div></div>
            <div class="feature-card reveal d1"><div class="feature-icon"><i class="fas fa-map"></i></div><div class="feature-text"><h4>Interactive map</h4><p>Visualize lost & found hotspots in your area.</p></div></div>
            <div class="feature-card reveal d2"><div class="feature-icon"><i class="fas fa-lock"></i></div><div class="feature-text"><h4>Privacy first</h4><p>Your contact info stays hidden until you confirm a match.</p></div></div>
        </div>
    </section>

    <!-- community block -->
    <section class="section">
        <div class="community-card reveal">
            <div class="avatars">
                <div class="avatar-circle">L</div><div class="avatar-circle">M</div><div class="avatar-circle">J</div><div class="avatar-circle">A</div><div class="avatar-circle">+3k</div>
            </div>
            <div class="section-tag" style="background:var(--accent-soft); margin-bottom:1rem;"><i class="fas fa-users"></i> join 12k+ members</div>
            <h2 class="section-title" style="font-size:2.3rem;">Be part of something bigger — help neighbors recover what matters</h2>
            <p class="section-desc" style="margin: 0 auto 2rem auto; max-width: 500px;">Every found item is a small miracle. Join a community that cares.</p>
            <div class="btn-group" style="justify-content:center;">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-community"><i class="fas fa-heart"></i> Go to dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn-community"><i class="fas fa-user-plus"></i> Create free account</a>
                    <a href="{{ route('login') }}" class="btn-outline-light">Sign in</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- testimonials -->
    <section class="section">
        <div class="section-tag reveal"><i class="fas fa-star"></i> real stories</div>
        <h2 class="section-title reveal d1">Trusted by thousands</h2>
        <div class="testimonials-grid">
            <div class="testimonial-item reveal d1">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p class="quote-text">"I left my backpack at a coffee shop in Austin. Within 6 hours Foundify matched me with someone who found it. Incredibly smooth."</p>
                <div class="user-details"><div class="user-avatar-sm">S</div><div><strong>Samira K.</strong><div style="font-size:0.7rem; color:var(--text-soft);">Austin, TX</div></div></div>
            </div>
            <div class="testimonial-item reveal d2">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p class="quote-text">"Found a wallet with no ID, posted it on Foundify. The owner messaged me the same day — she was so thankful. This platform is magic."</p>
                <div class="user-details"><div class="user-avatar-sm">M</div><div><strong>Marcus L.</strong><div style="font-size:0.7rem; color:var(--text-soft);">Brooklyn, NY</div></div></div>
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="footer-inner">
        <div class="footer-col">
            <div class="footer-logo"><i class="fas fa-compass" style="color:var(--accent);"></i> Found<span>ify</span></div>
            <p style="color: var(--text-muted); font-size:0.85rem;">Bridge between lost & found.</p>
        </div>
        <div class="footer-col">
            <h5 style="font-weight:600; margin-bottom:0.8rem; color:var(--text-dark);">Navigate</h5>
            <a href="#" class="footer-link">Home</a>
            @auth <a href="{{ url('/dashboard') }}" class="footer-link">Dashboard</a> @else <a href="{{ route('login') }}" class="footer-link">Sign in</a> <a href="{{ route('register') }}" class="footer-link">Sign up</a> @endauth
            <a href="#" class="footer-link">Map</a>
        </div>
        <div class="footer-col">
            <h5 style="font-weight:600; margin-bottom:0.8rem; color:var(--text-dark);">Support</h5>
            <a href="#" class="footer-link">Help center</a>
            <a href="#" class="footer-link">Contact team</a>
            <a href="#" class="footer-link">Privacy policy</a>
            <a href="#" class="footer-link">Terms of use</a>
        </div>
    </div>
    <div class="copyright">&copy; {{ date('Y') }} Foundify — built with clarity & purpose. All rights reserved.</div>
</footer>

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

        // Scroll reveal observer
        const revealElements = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: "0px 0px -20px 0px" });
        revealElements.forEach(el => observer.observe(el));

        // immediate hero initial reveals for smoothness
        setTimeout(() => {
            document.querySelectorAll('.hero .reveal').forEach(el => el.classList.add('in'));
        }, 80);
    });
</script>
</body>
</html>