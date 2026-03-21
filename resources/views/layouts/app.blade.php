<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Foundify')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    @stack('styles')
    
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
            --radius-card: 28px;
            --radius-sm: 60px;
            --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            --success: #10b981;
            --success-soft: #d1fae5;
            --warning: #f59e0b;
            --warning-soft: #fef3c7;
            --error: #ef4444;
            --error-soft: #fee2e2;
            --sidebar-width: 260px;
            --header-height: 70px;
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
            --success-soft: rgba(16, 185, 129, 0.15);
            --warning-soft: rgba(245, 158, 11, 0.15);
            --error-soft: rgba(239, 68, 68, 0.15);
            --glass: rgba(255, 255, 255, 0.03);
            --glass-b: rgba(255, 255, 255, 0.06);
        }

        body {
            background: var(--bg-soft);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
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
        }

        body.dark::before {
            opacity: 0.2;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1000;
            transition: background 0.2s, border-color 0.2s;
        }

        body.dark .header {
            background: rgba(15, 12, 26, 0.92);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            background: var(--glass);
            border: 1px solid var(--border-light);
            color: var(--text-muted);
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
        }

        .menu-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--accent-soft);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--accent);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .logo span {
            color: var(--accent);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Theme toggle */
        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--glass);
            border: 1px solid var(--border-light);
            color: var(--text-muted);
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
        }

        .theme-toggle-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--accent-soft);
            transform: translateY(-2px);
        }

        /* User Profile */
        .user-profile-wrapper {
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 10px 5px 5px;
            border-radius: 40px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: var(--transition);
            background: var(--glass);
        }

        .user-profile:hover {
            border-color: var(--border-light);
            background: var(--glass-b);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            background: var(--accent);
            color: white;
            box-shadow: 0 0 10px rgba(124, 58, 237, 0.3);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-dark);
        }

        .user-role {
            font-size: 0.65rem;
            color: var(--accent);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .user-dropdown-icon {
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        /* Dropdown */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 240px;
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: var(--transition);
            z-index: 1001;
            overflow: hidden;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-soft);
        }

        .dropdown-user-name {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
            font-size: 0.85rem;
        }

        .dropdown-user-email {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .dropdown-menu-items {
            padding: 8px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .dropdown-item i {
            width: 18px;
            color: var(--accent);
            font-size: 0.85rem;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-light);
            margin: 8px 0;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--bg-card);
            border-right: 1px solid var(--border-light);
            padding: 24px 0;
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease, background 0.2s;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--bg-soft);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border-light);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-title i {
            font-size: 0.7rem;
            color: var(--accent);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            margin: 2px 8px;
            border-radius: 10px;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background: var(--glass);
            border-color: var(--border-light);
            color: var(--accent);
        }

        .nav-link.active {
            background: var(--accent-soft);
            border-color: var(--border-light);
            color: var(--accent);
        }

        .nav-icon {
            width: 20px;
            font-size: 1rem;
            text-align: center;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: 28px;
            min-height: calc(100vh - var(--header-height));
            position: relative;
            z-index: 1;
        }

        .content-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 16px;
            border: 1px solid transparent;
            margin-bottom: 24px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 14px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: var(--success-soft);
            border-color: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .alert-error {
            background: var(--error-soft);
            border-color: rgba(239, 68, 68, 0.2);
            color: var(--error);
        }

        .alert i {
            font-size: 1.1rem;
        }

        /* Footer */
        .footer {
            margin-left: var(--sidebar-width);
            padding: 20px 28px;
            background: var(--bg-card);
            border-top: 1px solid var(--border-light);
            text-align: center;
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        .footer span {
            color: var(--accent);
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .menu-toggle {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: var(--shadow-lg);
            }

            .main-content,
            .footer {
                margin-left: 0;
            }

            .user-details {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 16px;
            }

            .main-content {
                padding: 20px 16px;
            }
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out;
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
    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="logo">
            <div class="logo-icon"><i class="fas fa-compass"></i></div>
            Found<span>ify</span>
        </a>
    </div>
    
    <div class="header-right">
        <div class="theme-toggle-btn" id="themeToggle">
            <i class="fas fa-moon" id="themeIcon"></i>
        </div>

        @auth
        <div class="user-profile-wrapper">
            <div class="user-profile" id="userProfileBtn">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="user-details">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ Auth::user()->isAdmin() ? 'ADMIN' : 'MEMBER' }}</span>
                </div>
                <i class="fas fa-chevron-down user-dropdown-icon"></i>
            </div>

            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                    <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
                </div>
                <div class="dropdown-menu-items">
                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="{{ route('matches.my-matches') }}" class="dropdown-item">
                        <i class="fas fa-handshake"></i> My Matches
                    </a>
                    <div class="dropdown-divider"></div>
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                        <i class="fas fa-users-cog"></i> Admin
                    </a>
                    <div class="dropdown-divider"></div>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </div>
</header>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="nav-section">
        <div class="nav-title"><i class="fas fa-crown"></i> MAIN</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home nav-icon"></i> Dashboard
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-title"><i class="fas fa-box"></i> ITEMS</div>
        <a href="{{ route('lost-items.index') }}" class="nav-link {{ Request::routeIs('lost-items.*') && !Request::routeIs('lost-items.my-items') ? 'active' : '' }}">
            <i class="fas fa-search nav-icon"></i> Lost Items
        </a>
        <a href="{{ route('found-items.index') }}" class="nav-link {{ Request::routeIs('found-items.*') && !Request::routeIs('found-items.my-items') ? 'active' : '' }}">
            <i class="fas fa-check-circle nav-icon"></i> Found Items
        </a>
        <a href="{{ route('matches.index') }}" class="nav-link {{ Request::routeIs('matches.*') && !Request::routeIs('matches.my-matches') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt nav-icon"></i> Matches
            @if(isset($pendingMatchesCount) && $pendingMatchesCount > 0)
                <span class="nav-badge">{{ $pendingMatchesCount }}</span>
            @endif
        </a>
        <a href="{{ route('map.index') }}" class="nav-link {{ Request::routeIs('map.*') ? 'active' : '' }}">
            <i class="fas fa-map-marked-alt nav-icon"></i> Map View
        </a>
    </div>

    @auth
        @if(!Auth::user()->isAdmin())
        <div class="nav-section">
            <div class="nav-title"><i class="fas fa-user"></i> MY ITEMS</div>
            <a href="{{ route('lost-items.my-items') }}" class="nav-link {{ Request::routeIs('lost-items.my-items') ? 'active' : '' }}">
                <i class="fas fa-box nav-icon"></i> My Lost Items
            </a>
            <a href="{{ route('found-items.my-items') }}" class="nav-link {{ Request::routeIs('found-items.my-items') ? 'active' : '' }}">
                <i class="fas fa-box-open nav-icon"></i> My Found Items
            </a>
            <a href="{{ route('matches.my-matches') }}" class="nav-link {{ Request::routeIs('matches.my-matches') ? 'active' : '' }}">
                <i class="fas fa-handshake nav-icon"></i> My Matches
            </a>
        </div>
        @endif

        @if(Auth::user()->isAdmin())
        <div class="nav-section">
            <div class="nav-title"><i class="fas fa-shield-alt"></i> ADMIN</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i> Users
            </a>
        </div>
        @endif

        <div class="nav-section">
            <div class="nav-title"><i class="fas fa-comment"></i> SOCIAL</div>
            <a href="{{ route('messages.index') }}" class="nav-link">
                <i class="fas fa-comments nav-icon"></i> Messages
                @if(($totalUnread ?? 0) > 0)
                    <span class="nav-badge">{{ $totalUnread }}</span>
                @endif
            </a>
        </div>
    @endauth
</nav>

<!-- Main Content -->
<main class="main-content">
    <div class="content-wrapper">
        @if(session('success'))
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <p>FOUNDIFY · v2.4.1 · SYSTEM_ONLINE · <span>❤️</span> COMMUNITY POWERED</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        if (menuToggle) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992 && 
                    !sidebar.contains(e.target) && 
                    !menuToggle.contains(e.target) &&
                    sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        }

        // User Profile Dropdown
        const userProfileBtn = document.getElementById('userProfileBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userProfileBtn) {
            userProfileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                userDropdown.classList.remove('show');
            });

            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Auto-hide alerts
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.transition = 'opacity 0.3s, transform 0.3s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() { alert.remove(); }, 300);
            });
        }, 4000);

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>