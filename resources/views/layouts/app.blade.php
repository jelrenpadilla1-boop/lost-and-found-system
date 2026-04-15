<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Foundify')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/logo.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* CSS Variables for Netflix-style Light/Dark Mode */
        :root {
            --bg-primary: #141414;
            --bg-secondary: #0a0a0a;
            --bg-card: #1a1a1a;
            --bg-card-hover: #2a2a2a;
            --text-primary: #ffffff;
            --text-secondary: #e5e5e5;
            --text-muted: #b3b3b3;
            --border-color: #2a2a2a;
            --accent: #e50914;
            --accent-light: #f6121d;
            --accent-soft: rgba(229,9,20,0.15);
            --shadow-color: rgba(0,0,0,0.6);
            --header-bg: linear-gradient(180deg, #000000 0%, rgba(0,0,0,0.95) 50%, rgba(0,0,0,0) 100%);
            --header-scrolled: #0a0a0a;
            --sidebar-bg: #0a0a0a;
            --success: #2e7d32;
            --success-soft: rgba(46,125,50,0.15);
            --warning: #f5c518;
            --warning-soft: rgba(245,197,24,0.15);
            --error: #e50914;
            --error-soft: rgba(229,9,20,0.15);
            --sidebar-width: 260px;
            --header-height: 70px;
        }

        body.light {
            --bg-primary: #f5f5f5;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-card-hover: #f8f8f8;
            --text-primary: #1a1a1a;
            --text-secondary: #333333;
            --text-muted: #666666;
            --border-color: #e0e0e0;
            --accent: #e50914;
            --accent-light: #f6121d;
            --accent-soft: rgba(229,9,20,0.08);
            --shadow-color: rgba(0,0,0,0.1);
            --header-bg: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,0.95) 50%, rgba(255,255,255,0) 100%);
            --header-scrolled: #ffffff;
            --sidebar-bg: #f8f8f8;
            --success-soft: rgba(46,125,50,0.1);
            --warning-soft: rgba(245,197,24,0.1);
            --error-soft: rgba(229,9,20,0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
            overflow-x: hidden;
        }

        /* Netflix-style background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 30%, rgba(229,9,20,0.08) 0%, transparent 60%);
            z-index: -1;
            pointer-events: none;
        }

        body.light::before {
            background: radial-gradient(circle at 20% 30%, rgba(229,9,20,0.03) 0%, transparent 60%);
        }

        /* Custom scrollbar - Netflix style */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--border-color);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-light);
        }

        /* Header - Netflix style */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: var(--header-bg);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4%;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: var(--header-scrolled);
            box-shadow: 0 2px 20px var(--shadow-color);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Menu Toggle - Netflix style */
        .menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .menu-toggle:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        /* Logo - Netflix style */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 1.6rem;
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--accent);
            text-transform: uppercase;
            transition: transform 0.2s;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo-icon {
            font-size: 1.6rem;
        }

        .logo span {
            color: var(--text-primary);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Theme Toggle - Netflix style */
        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(229,9,20,0.15);
            border: 1px solid var(--border-color);
            color: var(--accent);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .theme-toggle-btn:hover {
            background: var(--accent);
            color: white;
            transform: scale(1.05);
            border-color: var(--accent);
        }

        /* User Profile - Netflix style */
        .user-profile-wrapper {
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-color);
        }

        .user-profile:hover {
            background: var(--accent-soft);
            border-color: var(--accent);
        }

        /* ✅ FIXED: User Avatar - supports both image and initials */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            background: var(--accent);
            color: white;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 0.65rem;
            color: var(--accent);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .user-dropdown-icon {
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        /* Dropdown - Netflix style */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 240px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-shadow: 0 10px 30px var(--shadow-color);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s;
            z-index: 1001;
            overflow: hidden;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-secondary);
        }

        /* ✅ FIXED: Dropdown avatar */
        .dropdown-avatar {
            width: 48px;
            height: 48px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            background: var(--accent);
            color: white;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .dropdown-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropdown-user-name {
            font-weight: 700;
            color: var(--text-primary);
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
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
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
            background: var(--border-color);
            margin: 8px 0;
        }

        /* Sidebar - Netflix style */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            padding: 20px 0;
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .nav-section {
            margin-bottom: 20px;
        }

        .nav-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
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
            border-radius: 4px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background: rgba(229,9,20,0.1);
            color: var(--accent);
            border-color: var(--border-color);
        }

        .nav-link.active {
            background: rgba(229,9,20,0.15);
            border-color: var(--accent);
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
            border-radius: 4px;
            min-width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: 30px 4%;
            min-height: calc(100vh - var(--header-height));
            position: relative;
            z-index: 1;
        }

        .content-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Alerts - Netflix style */
        .alert {
            padding: 12px 20px;
            border-radius: 4px;
            border-left: 3px solid transparent;
            margin-bottom: 24px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 12px;
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
            border-left-color: var(--success);
            color: var(--success);
        }

        .alert-error {
            background: var(--error-soft);
            border-left-color: var(--error);
            color: var(--error);
        }

        .alert i {
            font-size: 1rem;
        }

        /* Footer - Netflix style */
        .footer {
            margin-left: var(--sidebar-width);
            padding: 20px 4%;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
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
                box-shadow: 0 0 30px var(--shadow-color);
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

        /* Animations */
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
<header class="header" id="header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="logo">
            <i class="fas fa-compass logo-icon"></i>
            Found<span>ify</span>
        </a>
    </div>
    
    <div class="header-right">
        <div class="theme-toggle-btn" id="themeToggle">
            <i class="fas fa-moon" id="themeIcon"></i>
        </div>

        @auth
        @php
            $authUser = Auth::user();
            $hasProfilePhoto = $authUser->profile_photo && file_exists(public_path('storage/' . $authUser->profile_photo));
        @endphp
        <div class="user-profile-wrapper">
            <div class="user-profile" id="userProfileBtn">
                <div class="user-avatar">
                    @if($hasProfilePhoto)
                        <img src="{{ asset('storage/' . $authUser->profile_photo) }}" alt="{{ $authUser->name }}">
                    @else
                        {{ strtoupper(substr($authUser->name, 0, 1)) }}
                    @endif
                </div>
                <div class="user-details">
                    <span class="user-name">{{ $authUser->name }}</span>
                    <span class="user-role">{{ $authUser->isAdmin() ? 'ADMIN' : 'MEMBER' }}</span>
                </div>
                <i class="fas fa-chevron-down user-dropdown-icon"></i>
            </div>

            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">
                        @if($hasProfilePhoto)
                            <img src="{{ asset('storage/' . $authUser->profile_photo) }}" alt="{{ $authUser->name }}">
                        @else
                            {{ strtoupper(substr($authUser->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-user-name">{{ $authUser->name }}</div>
                    <div class="dropdown-user-email">{{ $authUser->email }}</div>
                </div>
                <div class="dropdown-menu-items">
                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                   
                    <div class="dropdown-divider"></div>
                    @if($authUser->isAdmin())
                   
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
            @if(($sidebarLostBadge ?? 0) > 0)
                <span class="nav-badge">{{ $sidebarLostBadge }}</span>
            @endif
        </a>
        <a href="{{ route('found-items.index') }}" class="nav-link {{ Request::routeIs('found-items.*') && !Request::routeIs('found-items.my-items') ? 'active' : '' }}">
            <i class="fas fa-check-circle nav-icon"></i> Found Items
            @if(($sidebarFoundBadge ?? 0) > 0)
                <span class="nav-badge">{{ $sidebarFoundBadge }}</span>
            @endif
        </a>
        <a href="{{ route('matches.index') }}" class="nav-link {{ Request::routeIs('matches.*') && !Request::routeIs('matches.my-matches') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt nav-icon"></i> Matches
            @if(($pendingMatchesCount ?? 0) > 0)
                <span class="nav-badge">{{ $pendingMatchesCount }}</span>
            @endif
        </a>
        <a href="{{ route('map.index') }}" class="nav-link {{ Request::routeIs('map.*') ? 'active' : '' }}">
            <i class="fas fa-map-marked-alt nav-icon"></i> Map View
        </a>
    </div>

    @auth
        @php
            $currentUser = Auth::user();
        @endphp
        @if(!$currentUser->isAdmin())
        <div class="nav-section">
            <div class="nav-title"><i class="fas fa-user"></i> MY ITEMS</div>
            <a href="{{ route('lost-items.my-items') }}" class="nav-link {{ Request::routeIs('lost-items.my-items') ? 'active' : '' }}">
                <i class="fas fa-box nav-icon"></i> My Lost Items
                @if(($myLostBadge ?? 0) > 0)
                    <span class="nav-badge">{{ $myLostBadge }}</span>
                @endif
            </a>
            <a href="{{ route('found-items.my-items') }}" class="nav-link {{ Request::routeIs('found-items.my-items') ? 'active' : '' }}">
                <i class="fas fa-box-open nav-icon"></i> My Found Items
                @if(($myFoundBadge ?? 0) > 0)
                    <span class="nav-badge">{{ $myFoundBadge }}</span>
                @endif
            </a>
            <a href="{{ route('matches.my-matches') }}" class="nav-link {{ Request::routeIs('matches.my-matches') ? 'active' : '' }}">
                <i class="fas fa-handshake nav-icon"></i> My Matches
                @if(($myMatchesBadge ?? 0) > 0)
                    <span class="nav-badge">{{ $myMatchesBadge }}</span>
                @endif
            </a>
        </div>
        @endif

        @if($currentUser->isAdmin())
        <div class="nav-section">
            <div class="nav-title"><i class="fas fa-shield-alt"></i> ADMIN</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i> Manage Users
                @if(($newUsersCount ?? 0) > 0)
                    <span class="nav-badge">{{ $newUsersCount }}</span>
                @endif
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
    <p>&copy; {{ date('Y') }} Foundify · All Rights Reserved · <span>❤️</span> Making Reunions Happen</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sticky header with scroll effect
        const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Dark/Light mode logic
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
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
            const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
            if (prefersLight) {
                document.body.classList.add('light');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('foundify-theme', 'light');
            }
        }

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
                setTimeout(function() { if(alert.remove) alert.remove(); }, 300);
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