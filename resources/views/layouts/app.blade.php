<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foundify')</title>
    
    <!-- Favicon - Matching navbar logo -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
    
    <!-- Optional: SVG favicon for modern browsers (matches your exact icon) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #eff6ff;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --light: #f8fafc;
            --dark: #1e293b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #0ea5e9;
            
            /* Clean sidebar */
            --sidebar-bg: #0f172a;
            --sidebar-text: #e2e8f0;
            --sidebar-text-muted: #94a3b8;
            --sidebar-hover: #1e293b;
            --sidebar-active: #3b82f6;
            --sidebar-border: #334155;
            
            --sidebar-width: 200px;
            --header-height: 60px;
            --radius-sm: 4px;
            --radius-md: 6px;
            --radius-lg: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            background: #f8fafc;
            color: var(--dark);
            font-size: 14px;
            line-height: 1.5;
        }

        /* ========== CLEAN HEADER ========== */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 16px;
        }

        .logo-icon {
            color: var(--primary);
            font-size: 18px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Notification */
        .notification-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            color: var(--secondary);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .notification-btn:hover {
            background: var(--light);
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            border: 2px solid white;
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px;
            border-radius: var(--radius-md);
            cursor: pointer;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--info));
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: 13px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            font-size: 13px;
            color: var(--dark);
        }

        .user-role {
            font-size: 11px;
            color: var(--secondary);
        }

        /* Logout Button */
        .logout-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--secondary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: var(--light);
            color: var(--danger);
            border-color: var(--danger);
        }

        /* ========== CLEAN DARK SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            padding: 16px 0;
            overflow-y: auto;
            z-index: 999;
        }

        /* Hide scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 0;
            background: transparent;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--sidebar-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            padding: 0 16px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 0;
            transition: all 0.2s;
            font-weight: 400;
            font-size: 13px;
            position: relative;
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .nav-link.active {
            background: var(--sidebar-hover);
            color: white;
            font-weight: 500;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--sidebar-active);
        }

        .nav-icon {
            width: 18px;
            font-size: 14px;
            text-align: center;
            opacity: 0.8;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 500;
            min-width: 20px;
            text-align: center;
        }

        /* Messages Section Styles */
        .messages-section {
            margin-top: 16px;
            border-top: 1px solid var(--sidebar-border);
            padding-top: 16px;
        }

        .message-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            border-left: 2px solid transparent;
        }

        .message-preview:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--primary);
        }

        .message-preview.unread {
            background: rgba(59, 130, 246, 0.1);
        }

        .message-preview.unread .message-sender {
            color: white;
            font-weight: 600;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: 12px;
            flex-shrink: 0;
            position: relative;
        }

        .online-status {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
            border: 2px solid var(--sidebar-bg);
        }

        .message-content {
            flex: 1;
            min-width: 0;
        }

        .message-sender {
            font-size: 12px;
            font-weight: 500;
            color: var(--sidebar-text);
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .message-time {
            font-size: 9px;
            color: var(--sidebar-text-muted);
        }

        .message-text {
            font-size: 11px;
            color: var(--sidebar-text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-unread-badge {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            margin-left: 6px;
            display: inline-block;
        }

        .view-all-messages {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: var(--sidebar-text-muted);
            text-decoration: none;
            font-size: 12px;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .view-all-messages:hover {
            color: white;
            background: var(--sidebar-hover);
        }

        .view-all-messages i {
            font-size: 10px;
        }

        /* Message Modal */
        .message-modal {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 320px;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 2000;
            display: none;
            border: 1px solid var(--border);
        }

        .message-modal.show {
            display: block;
            animation: slideUp 0.3s ease;
        }

        .message-modal-header {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .message-modal-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
        }

        .message-modal-close {
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            font-size: 14px;
        }

        .message-modal-body {
            max-height: 300px;
            overflow-y: auto;
            padding: 16px;
        }

        .message-bubble {
            margin-bottom: 12px;
            max-width: 80%;
        }

        .message-bubble.sent {
            margin-left: auto;
        }

        .message-bubble.received {
            margin-right: auto;
        }

        .bubble-content {
            padding: 8px 12px;
            border-radius: var(--radius-md);
            font-size: 12px;
            word-wrap: break-word;
        }

        .message-bubble.sent .bubble-content {
            background: var(--primary);
            color: white;
        }

        .message-bubble.received .bubble-content {
            background: var(--light);
            color: var(--dark);
        }

        .message-time {
            font-size: 9px;
            color: var(--secondary);
            margin-top: 4px;
            text-align: right;
        }

        .message-modal-footer {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 8px;
        }

        .message-input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 12px;
            outline: none;
        }

        .message-input:focus {
            border-color: var(--primary);
        }

        .message-send-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 8px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .message-send-btn:hover {
            background: var(--primary-dark);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: calc(100vh - var(--header-height));
            background: #f8fafc;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ========== CLEAN DASHBOARD STYLES ========== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .page-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title h1 i {
            color: var(--primary);
        }

        .page-title p {
            font-size: 14px;
            color: var(--secondary);
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 12px;
        }

        /* Clean Stats Cards */
        .stats-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            transition: all 0.2s;
        }

        .stats-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .stats-card .icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            margin-bottom: 16px;
        }

        .stats-card .count {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stats-card .label {
            font-size: 14px;
            color: var(--secondary);
            font-weight: 500;
        }

        /* Recent Items */
        .recent-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: var(--dark);
            transition: background-color 0.2s;
        }

        .recent-item:hover {
            background-color: var(--light);
        }

        .recent-item:last-child {
            border-bottom: none;
        }

        .recent-item-image {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .recent-item-image i {
            color: var(--secondary);
            font-size: 20px;
        }

        .recent-item-content h6 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 4px 0;
            color: var(--dark);
        }

        .recent-item-meta {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: var(--secondary);
        }

        .recent-item-meta i {
            margin-right: 4px;
        }

        /* Match Card */
        .match-card {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: white;
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .match-card:hover {
            border-color: var(--primary);
        }

        .match-score {
            display: inline-block;
            padding: 4px 8px;
            background: var(--success);
            color: white;
            font-size: 11px;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .match-items {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .match-item {
            font-size: 13px;
        }

        .match-item small {
            color: var(--secondary);
            font-size: 11px;
            display: block;
            margin-bottom: 2px;
        }

        /* Quick Actions */
        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            background: white;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.2s;
            text-align: center;
        }

        .quick-action:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            color: var(--primary);
        }

        .quick-action i {
            font-size: 24px;
            margin-bottom: 12px;
        }

        .quick-action span {
            font-size: 14px;
            font-weight: 500;
        }

        /* System Status */
        .status-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
        }

        .status-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .status-icon.success {
            background: var(--success);
        }

        .status-icon.info {
            background: var(--info);
        }

        .status-icon.primary {
            background: var(--primary);
        }

        .status-content h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 4px 0;
            color: var(--dark);
        }

        .status-content p {
            font-size: 13px;
            color: var(--secondary);
            margin: 0;
        }

        /* ========== CARDS ========== */
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            background: transparent;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i {
            font-size: 14px;
        }

        .card-body {
            padding: 20px;
        }

        /* ========== BUTTONS ========== */
        .btn {
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-info:hover {
            background: #0284c7;
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border-color: var(--border);
        }

        .btn-outline:hover {
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-block {
            width: 100%;
        }

        /* ========== ALERTS ========== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            margin-bottom: 16px;
            font-size: 13px;
            background: white;
            border-color: var(--border);
        }

        .alert-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        /* ========== MOBILE TOGGLE ========== */
        .menu-toggle {
            display: none;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            border-radius: var(--radius-md);
        }

        .menu-toggle:hover {
            background: var(--light);
        }

        /* ========== FOOTER ========== */
        .footer {
            margin-left: var(--sidebar-width);
            padding: 16px 20px;
            background: white;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--secondary);
            font-size: 12px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.2s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .footer {
                margin-left: 0;
            }

            .user-details {
                display: none;
            }

            .menu-toggle {
                display: flex;
            }

            .page-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }

            .page-actions {
                width: 100%;
            }

            .page-actions .btn {
                flex: 1;
            }

            .message-modal {
                width: calc(100% - 32px);
                right: 16px;
                left: 16px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 16px;
            }

            .main-content {
                padding: 12px;
            }

            .stats-card .count {
                font-size: 24px;
            }

            .match-items {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .status-item {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
        }

        /* ========== UTILITY ========== */
        .text-muted { color: var(--secondary); }
        .text-primary { color: var(--primary); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .text-info { color: var(--info); }

        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-5 { margin-bottom: 20px; }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Clean Header -->
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="logo">
                <i class="fas fa-search-location logo-icon"></i>
                <span>Foundify</span>
            </a>
        </div>

        <div class="header-right">
            <div class="notification-wrapper">
                <button class="notification-btn" id="notificationBtn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </button>
            </div>

            @auth
            <div class="user-profile">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="user-details">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ Auth::user()->isAdmin() ? 'Admin' : 'User' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </header>

    <!-- Clean Dark Sidebar with Messages -->
    <nav class="sidebar" id="sidebar">
        <div class="nav-section">
            <div class="nav-title">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home nav-icon"></i>
                Dashboard
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-title">Items</div>
            <a href="{{ route('lost-items.index') }}" class="nav-link {{ Request::routeIs('lost-items.*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-circle nav-icon"></i>
                Lost Items
            </a>
            <a href="{{ route('found-items.index') }}" class="nav-link {{ Request::routeIs('found-items.*') ? 'active' : '' }}">
                <i class="fas fa-check-circle nav-icon"></i>
                Found Items
            </a>
            <a href="{{ route('matches.index') }}" class="nav-link {{ Request::routeIs('matches.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt nav-icon"></i>
                Matches
                <span class="nav-badge" id="matchesBadge" style="display: none;">0</span>
            </a>
            <a href="{{ route('map.index') }}" class="nav-link {{ Request::routeIs('map.*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt nav-icon"></i>
                Map View
            </a>
        </div>

        @auth
        <div class="nav-section">
            <div class="nav-title">My Account</div>
            <a href="{{ route('lost-items.my-items') }}" class="nav-link {{ Request::routeIs('lost-items.my-items') ? 'active' : '' }}">
                <i class="fas fa-box nav-icon"></i>
                My Lost Items
            </a>
            <a href="{{ route('found-items.my-items') }}" class="nav-link {{ Request::routeIs('found-items.my-items') ? 'active' : '' }}">
                <i class="fas fa-box-open nav-icon"></i>
                My Found Items
            </a>
            <a href="{{ route('matches.my-matches') }}" class="nav-link {{ Request::routeIs('matches.my-matches') ? 'active' : '' }}">
                <i class="fas fa-handshake nav-icon"></i>
                My Matches
            </a>
        </div>

        <!-- Messages Section - Real Time Messaging -->
        <div class="messages-section">
            <div class="nav-title">
                <i class="fas fa-comments me-1"></i>
                Messages
                <span class="nav-badge" id="totalMessagesBadge" style="display: none;">0</span>
            </div>
            
            <!-- Message Previews - Will be populated dynamically from API -->
            <div id="messagePreviews">
                <div class="text-center text-muted p-3 small">
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    Loading messages...
                </div>
            </div>

            @if(Route::has('messages.index'))
                <a href="{{ route('messages.index') }}" class="view-all-messages">
                    <i class="fas fa-envelope"></i>
                    View All Messages
                    <i class="fas fa-chevron-right ms-auto"></i>
                </a>
            @else
                <a href="#" class="view-all-messages" onclick="alert('Messages feature coming soon!'); return false;">
                    <i class="fas fa-envelope"></i>
                    View All Messages
                    <i class="fas fa-chevron-right ms-auto"></i>
                </a>
            @endif
        </div>
        @endauth
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- Notifications -->
            @if(session('success'))
                <div class="alert alert-success fade-in mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error fade-in mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Message Modal for Real-Time Chat -->
    <div class="message-modal" id="messageModal">
        <div class="message-modal-header">
            <h6 id="modalRecipientName">Loading...</h6>
            <button class="message-modal-close" onclick="closeMessageModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="message-modal-body" id="messageModalBody">
            <!-- Messages will appear here -->
        </div>
        <div class="message-modal-footer">
            <input type="text" class="message-input" id="messageInput" placeholder="Type a message...">
            <button class="message-send-btn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">
                &copy; {{ date('Y') }} Foundify
                <span class="text-muted ms-2">v1.0</span>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            
            if (sidebar.classList.contains('show')) {
                const overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                overlay.style.cssText = `
                    position: fixed;
                    top: 60px;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0,0,0,0.4);
                    z-index: 998;
                `;
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    overlay.remove();
                });
                document.body.appendChild(overlay);
            } else {
                const overlay = document.querySelector('.sidebar-overlay');
                if (overlay) overlay.remove();
            }
        });

        // Message System Variables
        let currentConversationId = null;
        let messageCheckInterval = null;
        
        // Initialize empty messages (no mock data)
        let mockMessages = {};
        
        // Empty conversations array (no mock data)
        const conversations = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Set active nav
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.startsWith(href.replace(/\/$/, ''))) {
                    link.classList.add('active');
                }
            });

            // Update badges
            updateBadges();
            
            // Initialize messages - will show loading state then empty
            loadMessages();
            
            // Start real-time checking
            startMessageChecking();

            // Auto-remove alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.3s';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 4000);

            // Load recent messages from API (empty for now)
            loadRecentMessages();
            
            // Load unread count
            loadUnreadCount();
            
            // Poll for updates every 10 seconds
            setInterval(() => {
                loadRecentMessages();
                loadUnreadCount();
            }, 10000);
        });

        // Load messages into sidebar
        function loadMessages() {
            const messagePreviews = document.getElementById('messagePreviews');
            if (!messagePreviews) return;

            // Show empty state with no mock data
            messagePreviews.innerHTML = '<div class="text-center text-muted p-3 small">No messages yet</div>';

            // Update total messages badge to 0
            const totalBadge = document.getElementById('totalMessagesBadge');
            totalBadge.style.display = 'none';
        }

        // Load recent messages (will be replaced with API call)
        function loadRecentMessages() {
            // This will be replaced with actual API call
            // For now, just show empty state
            loadMessages();
        }

        // Load unread count
        function loadUnreadCount() {
            // This will be replaced with actual API call
            const notificationBadge = document.getElementById('notificationBadge');
            notificationBadge.style.display = 'none';
        }

        // Update badges
        function updateBadges() {
            const matchesBadge = document.getElementById('matchesBadge');
            
            setTimeout(() => {
                const count = Math.floor(Math.random() * 3);
                
                if (matchesBadge && count > 0) {
                    matchesBadge.textContent = count;
                    matchesBadge.style.display = 'inline-block';
                }
            }, 1200);
        }

        // Notification click
        const notificationBtn = document.getElementById('notificationBtn');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', () => {
                const badge = document.getElementById('notificationBadge');
                badge.style.display = 'none';
            });
        }

        // Open conversation in modal
        function openConversation(conversationId, recipientName) {
            currentConversationId = conversationId;
            
            // Update modal header
            document.getElementById('modalRecipientName').textContent = recipientName;
            
            // Load messages
            loadConversationMessages(conversationId);
            
            // Show modal
            document.getElementById('messageModal').classList.add('show');
        }

        // Load conversation messages
        function loadConversationMessages(conversationId) {
            const modalBody = document.getElementById('messageModalBody');
            const messages = mockMessages[conversationId] || [];
            
            if (messages.length === 0) {
                modalBody.innerHTML = '<div class="text-center text-muted p-3">No messages yet. Start the conversation!</div>';
            } else {
                let html = '';
                messages.forEach(msg => {
                    html += `
                        <div class="message-bubble ${msg.type}">
                            <div class="bubble-content">${escapeHtml(msg.content)}</div>
                            <div class="message-time">${msg.time}</div>
                        </div>
                    `;
                });
                modalBody.innerHTML = html;
            }
            
            // Scroll to bottom
            modalBody.scrollTop = modalBody.scrollHeight;
        }

        // Send message
        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message || !currentConversationId) return;
            
            // Add message to mock data
            if (!mockMessages[currentConversationId]) {
                mockMessages[currentConversationId] = [];
            }
            
            const newMessage = {
                id: mockMessages[currentConversationId].length + 1,
                sender: 'You',
                content: message,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                type: 'sent'
            };
            
            mockMessages[currentConversationId].push(newMessage);
            
            // Update UI
            loadConversationMessages(currentConversationId);
            
            // Clear input
            input.value = '';
        }

        // Close message modal
        function closeMessageModal() {
            document.getElementById('messageModal').classList.remove('show');
            currentConversationId = null;
        }

        // Start real-time message checking
        function startMessageChecking() {
            // No mock data, so just set an empty interval
            messageCheckInterval = setInterval(() => {
                // Do nothing - waiting for real API
            }, 5000);
        }

        // Clean up interval when page unloads
        window.addEventListener('beforeunload', () => {
            if (messageCheckInterval) {
                clearInterval(messageCheckInterval);
            }
        });

        // Click outside to close modal
        document.addEventListener('click', (e) => {
            const modal = document.getElementById('messageModal');
            const isClickInside = modal.contains(e.target);
            const isMessagePreview = e.target.closest('.message-preview');
            
            if (!isClickInside && !isMessagePreview && modal.classList.contains('show')) {
                closeMessageModal();
            }
        });

        // Escape HTML to prevent XSS
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Responsive
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('show');
                const overlay = document.querySelector('.sidebar-overlay');
                if (overlay) overlay.remove();
            }
        });

        // Smooth load
        document.body.style.opacity = 0;
        setTimeout(() => {
            document.body.style.transition = 'opacity 0.2s';
            document.body.style.opacity = 1;
        }, 50);

        // Add hover effects
        document.querySelectorAll('.stats-card, .quick-action, .match-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });

        // Handle Enter key in message input
        document.getElementById('messageInput')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>