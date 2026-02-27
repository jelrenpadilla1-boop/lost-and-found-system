<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foundify')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            /* Black and Pink Theme */
            --primary: #ff1493;
            --primary-light: #ff69b4;
            --primary-dark: #c71585;
            --primary-glow: rgba(255, 20, 147, 0.3);
            --secondary: #a0a0a0;
            --light: #2a2a2a;
            --dark: #ffffff;
            --border: #333333;
            --success: #00fa9a;
            --danger: #ff4444;
            --warning: #ffa500;
            --info: #ff69b4;
            
            /* Dark sidebar with pink accents */
            --sidebar-bg: #000000;
            --sidebar-text: #ffffff;
            --sidebar-text-muted: #a0a0a0;
            --sidebar-hover: #1a1a1a;
            --sidebar-active: #ff1493;
            --sidebar-border: #333333;
            
            --sidebar-width: 200px;
            --header-height: 60px;
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            background: #0a0a0a;
            color: #ffffff;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ========== BLACK & PINK HEADER ========== */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: #000000;
            border-bottom: 1px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
            box-shadow: 0 2px 10px var(--primary-glow);
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
            color: #ffffff;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo:hover .logo-icon {
            transform: rotate(360deg);
        }

        .logo-icon {
            color: var(--primary);
            font-size: 18px;
            transition: transform 0.5s ease;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
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
            color: #a0a0a0;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-btn:hover {
            background: #1a1a1a;
            color: var(--primary);
            transform: translateY(-2px);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: var(--primary);
            color: white;
            font-size: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            border: 2px solid #000000;
            box-shadow: 0 0 10px var(--primary-glow);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* User Profile with Dropdown */
        .user-profile-wrapper {
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 8px 4px 4px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: transparent;
            border: 1px solid transparent;
        }

        .user-profile:hover {
            background: #1a1a1a;
            border-color: var(--primary);
            box-shadow: 0 0 15px var(--primary-glow);
        }

        .user-profile.active {
            background: #1a1a1a;
            border-color: var(--primary);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: 13px;
            box-shadow: 0 0 15px var(--primary-glow);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .user-avatar.has-image {
            background: none;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .avatar-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 50%;
        }

        .user-profile:hover .user-avatar .avatar-image {
            transform: scale(1.1);
        }

        .user-profile:hover .avatar-initial {
            transform: scale(1.1);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            font-size: 13px;
            color: #ffffff;
        }

        .user-role {
            font-size: 11px;
            color: var(--primary);
        }

        .user-dropdown-icon {
            color: var(--primary);
            font-size: 10px;
            margin-left: 4px;
            transition: transform 0.3s ease;
        }

        .user-profile:hover .user-dropdown-icon {
            transform: rotate(180deg);
        }

        /* Dropdown Menu */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 220px;
            background: var(--bg-card);
            border: 1px solid var(--primary);
            border-radius: 16px;
            box-shadow: 0 10px 30px var(--primary-glow);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1001;
            overflow: hidden;
        }

        .user-profile-wrapper:hover .user-dropdown,
        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-header);
        }

        .dropdown-user-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .dropdown-user-email {
            font-size: 11px;
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
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 13px;
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: var(--bg-header);
            color: var(--primary);
            transform: translateX(4px);
        }

        .dropdown-item i {
            width: 16px;
            color: var(--primary);
            font-size: 14px;
        }

        .dropdown-item.logout:hover {
            color: var(--danger);
        }

        .dropdown-item.logout:hover i {
            color: var(--danger);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 8px 0;
        }

        /* Logout Button (hidden, now part of dropdown) */
        .logout-btn {
            display: none;
        }

        /* ========== BLACK SIDEBAR WITH PINK ACCENTS ========== */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: #000000;
            border-right: 1px solid #333333;
            padding: 16px 0;
            overflow-y: auto;
            z-index: 999;
            transition: all 0.3s ease;
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
            box-shadow: 0 0 10px var(--primary-glow);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        /* Sidebar Search */
        .sidebar-search {
            padding: 0 16px;
            margin-bottom: 20px;
        }

        .search-container {
            position: relative;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 14px;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .search-input {
            width: 100%;
            padding: 10px 10px 10px 38px;
            background: #1a1a1a;
            border: 2px solid #333333;
            border-radius: 30px;
            color: #ffffff;
            font-size: 13px;
            transition: all 0.3s ease;
            outline: none;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            background: #222222;
        }

        .search-input::placeholder {
            color: #666666;
            font-size: 12px;
        }

        .search-shortcut {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: #333333;
            color: #a0a0a0;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #444444;
            pointer-events: none;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 8px;
            background: #1a1a1a;
            border: 1px solid #333333;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
            display: none;
            max-height: 300px;
            overflow-y: auto;
        }

        .search-results.show {
            display: block;
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

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 1px solid #333333;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background: #222222;
        }

        .search-result-item:hover .search-result-title {
            color: var(--primary);
        }

        .search-result-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 20, 147, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 14px;
        }

        .search-result-info {
            flex: 1;
        }

        .search-result-title {
            font-size: 13px;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .search-result-subtitle {
            font-size: 11px;
            color: #a0a0a0;
        }

        .search-result-badge {
            background: rgba(255, 20, 147, 0.1);
            border: 1px solid var(--primary);
            color: var(--primary);
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 30px;
        }

        .search-no-results {
            padding: 20px;
            text-align: center;
            color: #a0a0a0;
            font-size: 12px;
        }

        .search-no-results i {
            font-size: 24px;
            color: var(--primary);
            opacity: 0.5;
            margin-bottom: 8px;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            padding: 0 16px;
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-title i {
            font-size: 12px;
        }

        .nav-title::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 16px;
            width: 30px;
            height: 2px;
            background: var(--primary);
            box-shadow: 0 0 10px var(--primary-glow);
            transition: width 0.3s ease;
        }

        .nav-section:hover .nav-title::after {
            width: 50px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #a0a0a0;
            text-decoration: none;
            border-radius: 0;
            transition: all 0.3s ease;
            font-weight: 400;
            font-size: 13px;
            position: relative;
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: #1a1a1a;
            color: var(--primary);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: #1a1a1a;
            color: var(--primary);
            font-weight: 500;
            box-shadow: inset 0 0 20px rgba(255, 20, 147, 0.1);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary);
            box-shadow: 0 0 10px var(--primary-glow);
        }

        .nav-icon {
            width: 18px;
            font-size: 14px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .nav-link:hover .nav-icon {
            transform: scale(1.2);
            color: var(--primary);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 500;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 0 10px var(--primary-glow);
        }

        /* Messages Styles */
        .message-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-left: 2px solid transparent;
            margin-bottom: 2px;
            text-decoration: none;
            color: inherit;
        }

        .message-item:hover {
            background: #1a1a1a;
            border-left-color: var(--primary);
            transform: translateX(5px);
        }

        .message-item.unread {
            background: rgba(255, 20, 147, 0.1);
        }

        .message-item.unread .message-sender {
            color: var(--primary);
            font-weight: 600;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: 12px;
            flex-shrink: 0;
            position: relative;
            box-shadow: 0 0 15px var(--primary-glow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .message-avatar.has-image {
            background: none;
        }

        .message-avatar .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .message-item:hover .message-avatar .avatar-image {
            transform: scale(1.1);
        }

        .online-status {
            width: 8px;
            height: 8px;
            background: #00fa9a;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
            border: 2px solid #000000;
            box-shadow: 0 0 10px #00fa9a;
        }

        .message-content {
            flex: 1;
            min-width: 0;
        }

        .message-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .message-sender {
            font-size: 12px;
            font-weight: 500;
            color: #ffffff;
        }

        .message-time {
            font-size: 9px;
            color: #666666;
        }

        .message-preview {
            font-size: 11px;
            color: #a0a0a0;
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
            box-shadow: 0 0 10px var(--primary-glow);
            animation: pulse 2s infinite;
        }

        .view-all-messages {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            color: #a0a0a0;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s ease;
            margin-top: 4px;
        }

        .view-all-messages:hover {
            color: var(--primary);
            background: #1a1a1a;
            transform: translateX(5px);
        }

        .view-all-messages i {
            font-size: 10px;
            transition: transform 0.3s ease;
        }

        .view-all-messages:hover i {
            transform: translateX(3px);
        }

        .ms-auto {
            margin-left: auto;
        }

        /* New Conversation Button in Sidebar */
        .new-message-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: var(--primary);
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s ease;
            margin: 8px 0 4px 0;
            background: transparent;
            border: 1px solid var(--primary);
            border-radius: 30px;
            cursor: pointer;
            width: calc(100% - 32px);
            margin-left: 16px;
            justify-content: center;
        }

        .new-message-btn:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--primary-glow);
            border-color: transparent;
        }

        .new-message-btn i {
            font-size: 14px;
        }

        /* User List Modal */
        .user-list-modal {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 320px;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px var(--primary-glow);
            z-index: 2000;
            display: none;
            border: 1px solid var(--primary);
            overflow: hidden;
        }

        .user-list-modal.show {
            display: block;
            animation: slideUp 0.3s ease;
        }

        .user-list-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-header);
        }

        .user-list-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-list-close {
            background: none;
            border: none;
            color: #666666;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-list-close:hover {
            background: var(--primary);
            color: white;
            transform: rotate(90deg);
        }

        .user-list-search {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .user-list-search .search-box {
            position: relative;
        }

        .user-list-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 14px;
        }

        .user-list-search .search-input {
            width: 100%;
            padding: 10px 10px 10px 36px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            font-size: 13px;
            background: var(--bg-header);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .user-list-search .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            outline: none;
            background: var(--bg-card);
        }

        .user-list-content {
            max-height: 350px;
            overflow-y: auto;
            padding: 8px;
        }

        .user-list-content::-webkit-scrollbar {
            width: 4px;
        }

        .user-list-content::-webkit-scrollbar-track {
            background: var(--bg-header);
        }

        .user-list-content::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        .user-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            margin-bottom: 4px;
        }

        .user-list-item:hover {
            background: var(--bg-header);
            border-color: var(--primary);
            transform: translateX(4px);
            box-shadow: 0 5px 15px var(--primary-glow);
        }

        .user-list-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            position: relative;
            box-shadow: 0 0 15px var(--primary-glow);
            overflow: hidden;
        }

        .user-list-avatar.has-image {
            background: none;
        }

        .user-list-avatar .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-list-info {
            flex: 1;
            min-width: 0;
        }

        .user-list-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .user-list-email {
            font-size: 11px;
            color: var(--text-muted);
        }

        .user-list-status {
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 20px;
            background: rgba(0, 250, 154, 0.1);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .user-list-empty {
            text-align: center;
            padding: 30px 20px;
            color: var(--text-muted);
        }

        .user-list-empty i {
            font-size: 40px;
            color: var(--primary);
            opacity: 0.5;
            margin-bottom: 10px;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: calc(100vh - var(--header-height));
            background: #0a0a0a;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ========== DASHBOARD STYLES ========== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #333333;
        }

        .page-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title h1 i {
            color: var(--primary);
            animation: pulse 3s infinite;
        }

        .page-title p {
            font-size: 14px;
            color: #a0a0a0;
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 12px;
        }

        /* Stats Cards */
        .stats-card {
            background: #1a1a1a;
            border: 1px solid #333333;
            border-radius: var(--radius-lg);
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .stats-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px var(--primary-glow);
        }

        .stats-card:hover::before {
            opacity: 0.1;
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
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            box-shadow: 0 0 20px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .stats-card:hover .icon {
            transform: scale(1.1) rotate(360deg);
        }

        .stats-card .count {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stats-card .label {
            font-size: 14px;
            color: #a0a0a0;
            font-weight: 500;
        }

        /* Recent Items */
        .recent-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #333333;
            text-decoration: none;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .recent-item:hover {
            background-color: #1a1a1a;
            transform: translateX(5px);
        }

        .recent-item:last-child {
            border-bottom: none;
        }

        .recent-item-image {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            background: #2a2a2a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .recent-item:hover .recent-item-image {
            background: var(--primary);
            transform: scale(1.1);
        }

        .recent-item-image i {
            color: var(--primary);
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .recent-item:hover .recent-item-image i {
            color: white;
        }

        .recent-item-content h6 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 4px 0;
            color: #ffffff;
        }

        .recent-item-meta {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #a0a0a0;
        }

        .recent-item-meta i {
            margin-right: 4px;
            color: var(--primary);
        }

        /* Match Card */
        .match-card {
            padding: 16px;
            border: 1px solid #333333;
            border-radius: var(--radius-md);
            background: #1a1a1a;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .match-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px var(--primary-glow);
        }

        .match-score {
            display: inline-block;
            padding: 4px 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-size: 11px;
            font-weight: 600;
            border-radius: 20px;
            margin-bottom: 12px;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        .match-items {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .match-item {
            font-size: 13px;
            color: #ffffff;
        }

        .match-item small {
            color: #a0a0a0;
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
            border: 1px solid #333333;
            border-radius: var(--radius-lg);
            background: #1a1a1a;
            text-decoration: none;
            color: #ffffff;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .quick-action::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: var(--primary-glow);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .quick-action:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            color: var(--primary);
        }

        .quick-action:hover::before {
            width: 300px;
            height: 300px;
        }

        .quick-action i {
            font-size: 24px;
            margin-bottom: 12px;
            color: var(--primary);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .quick-action:hover i {
            transform: scale(1.2);
        }

        .quick-action span {
            font-size: 14px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* System Status */
        .status-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #1a1a1a;
            border: 1px solid #333333;
            border-radius: var(--radius-lg);
            transition: all 0.3s ease;
        }

        .status-item:hover {
            border-color: var(--primary);
            transform: translateX(5px);
            box-shadow: 0 5px 20px var(--primary-glow);
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
            transition: all 0.3s ease;
        }

        .status-item:hover .status-icon {
            transform: scale(1.1) rotate(360deg);
        }

        .status-icon.success {
            background: linear-gradient(135deg, #00fa9a, #00ff7f);
            box-shadow: 0 0 20px rgba(0, 250, 154, 0.3);
        }

        .status-icon.info {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .status-icon.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .status-content h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 4px 0;
            color: #ffffff;
        }

        .status-content p {
            font-size: 13px;
            color: #a0a0a0;
            margin: 0;
        }

        /* ========== CARDS ========== */
        .card {
            background: #1a1a1a;
            border: 1px solid #333333;
            border-radius: var(--radius-lg);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 20px var(--primary-glow);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #333333;
            background: transparent;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i {
            font-size: 14px;
            color: var(--primary);
        }

        .card-body {
            padding: 20px;
        }

        /* ========== BUTTONS ========== */
        .btn {
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
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
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--primary-glow);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff4444, #ff6b6b);
            color: white;
            box-shadow: 0 0 20px rgba(255, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 68, 68, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #00fa9a, #00ff7f);
            color: black;
            box-shadow: 0 0 20px rgba(0, 250, 154, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 250, 154, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--primary-glow);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }

        .btn-block {
            width: 100%;
        }

        /* ========== ALERTS ========== */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            margin-bottom: 16px;
            font-size: 13px;
            background: #1a1a1a;
            border-color: #333333;
            color: #ffffff;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-success {
            background: rgba(0, 250, 154, 0.1);
            border-color: #00fa9a;
            color: #00fa9a;
        }

        .alert-error {
            background: rgba(255, 68, 68, 0.1);
            border-color: #ff4444;
            color: #ff4444;
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
            color: #a0a0a0;
            cursor: pointer;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: #1a1a1a;
            color: var(--primary);
            transform: scale(1.1);
        }

        /* ========== FOOTER ========== */
        .footer {
            margin-left: var(--sidebar-width);
            padding: 16px 20px;
            background: #000000;
            border-top: 1px solid #333333;
            text-align: center;
            color: #a0a0a0;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .footer:hover {
            color: var(--primary);
        }

        .footer span {
            color: var(--primary);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
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
        .text-muted { color: #a0a0a0 !important; }
        .text-primary { color: var(--primary) !important; }
        .text-success { color: #00fa9a !important; }
        .text-danger { color: #ff4444 !important; }
        .text-info { color: var(--primary-light) !important; }

        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-5 { margin-bottom: 20px; }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Global Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
            box-shadow: 0 0 10px var(--primary-glow);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        /* Loading Spinner */
        .message-loading {
            text-align: center;
            padding: 20px;
            color: var(--text-muted);
        }

        .message-loading i {
            color: var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Empty State */
        .message-empty {
            text-align: center;
            padding: 20px;
            color: var(--text-muted);
        }

        .message-empty i {
            font-size: 24px;
            color: var(--primary);
            opacity: 0.5;
            margin-bottom: 8px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Black & Pink Header -->
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
                    <span class="notification-badge" id="notificationBadge" style="display: none;">{{ $totalUnread ?? 0 }}</span>
                </button>
            </div>

            @auth
            <div class="user-profile-wrapper">
                <div class="user-profile" id="userProfileBtn">
                    <div class="user-avatar {{ Auth::user()->profile_photo ? 'has-image' : '' }}">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="avatar-image">
                        @else
                            <div class="avatar-initial">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ Auth::user()->isAdmin() ? 'Admin' : 'User' }}</span>
                    </div>
                    <i class="fas fa-chevron-down user-dropdown-icon"></i>
                </div>

                <!-- Dropdown Menu -->
                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <div class="dropdown-user-name">{{ Auth::user()->name }}</div>
                        <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="dropdown-menu-items">
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Account Settings</span>
                        </a>
                        <a href="{{ route('matches.my-matches') }}" class="dropdown-item">
                            <i class="fas fa-handshake"></i>
                            <span>My Matches</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="dropdown-item">
                            <i class="fas fa-users-cog"></i>
                            <span>Admin Dashboard</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </header>

    <!-- Black Sidebar with Pink Accents -->
    <nav class="sidebar" id="sidebar">
        <!-- Sidebar Search -->
        <div class="sidebar-search">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="sidebarSearch" placeholder="Search..." autocomplete="off">
                <span class="search-shortcut">⌘K</span>
                
                <!-- Search Results Dropdown -->
                <div class="search-results" id="searchResults">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-title">
                <i class="fas fa-home"></i>
                Main
            </div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home nav-icon"></i>
                Dashboard
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-title">
                <i class="fas fa-box"></i>
                Items
            </div>
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
            @if(!Auth::user()->isAdmin())
    
        <div class="nav-section">
            <div class="nav-title">
                <i class="fas fa-user"></i>
                My Account
            </div>
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
    @endif

        <!-- Admin Section - Only visible to admins -->
        @if(Auth::user()->isAdmin())
        <div class="nav-section">
            <div class="nav-title">
                <i class="fas fa-shield-alt"></i>
                Administration
            </div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i>
                Manage Users
                @if(isset($pendingUsersCount) && $pendingUsersCount > 0)
                    <span class="nav-badge">{{ $pendingUsersCount }}</span>
                @endif
            </a>
        </div>
        @endif

        <!-- Messages Section -->
        <div class="nav-section">
            <div class="nav-title">
                <i class="fas fa-comments"></i>
                Messages
                @if(($totalUnread ?? 0) > 0)
                    <span class="nav-badge" id="totalUnreadBadge">{{ $totalUnread }}</span>
                @else
                    <span class="nav-badge" id="totalUnreadBadge" style="display: none;">0</span>
                @endif
            </div>
            <a href="{{ route('messages.index') }}" class="view-all-messages">
                <i class="fas fa-envelope"></i>
                View All Messages
                <i class="fas fa-chevron-right ms-auto"></i>
            </a>
        </div>
        @endauth
    </nav>

    <!-- User List Modal -->
    <div class="user-list-modal" id="userListModal">
        <div class="user-list-header">
            <h6>
                <i class="fas fa-users"></i>
                Start New Conversation
            </h6>
            <button class="user-list-close" onclick="closeUserListModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="user-list-search">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="modalSearchUsers" placeholder="Search users...">
            </div>
        </div>
        <div class="user-list-content" id="userListContent">
            <!-- Users will be loaded here dynamically -->
            <div class="message-loading">
                <i class="fas fa-spinner fa-spin"></i> Loading users...
            </div>
        </div>
    </div>

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

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">
                &copy; {{ date('Y') }} <span>Foundify</span>
                <span class="text-muted ms-2">v1.0</span>
            </p>
        </div>
    </footer>

    <!-- Notifications Container for Toasts -->
    <div id="notificationsContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

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
                    background: rgba(0,0,0,0.8);
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

        // User Profile Dropdown
        const userProfileBtn = document.getElementById('userProfileBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userProfileBtn) {
            userProfileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
                userProfileBtn.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!userProfileBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                    userProfileBtn.classList.remove('active');
                }
            });

            // Prevent dropdown from closing when clicking inside it
            userDropdown.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Sidebar Search Functionality
        const sidebarSearch = document.getElementById('sidebarSearch');
        const searchResults = document.getElementById('searchResults');

        if (sidebarSearch) {
            // Sample search data - in production, this would come from your backend
            const searchData = [
                { type: 'lost', title: 'Black iPhone 13', subtitle: 'Lost on Mar 15, 2024', icon: 'fa-exclamation-circle', url: '/lost-items/1', badge: 'Lost' },
                { type: 'found', title: 'Blue Wallet', subtitle: 'Found in Central Park', icon: 'fa-check-circle', url: '/found-items/1', badge: 'Found' },
                { type: 'match', title: 'Match #1234', subtitle: '85% match score', icon: 'fa-exchange-alt', url: '/matches/1', badge: 'High' },
                { type: 'user', title: 'John Doe', subtitle: 'john@example.com', icon: 'fa-user', url: '/profile/1', badge: 'Online' },
                { type: 'message', title: 'Sarah Johnson', subtitle: 'Re: Found your wallet', icon: 'fa-comment', url: '/messages/1', badge: 'New' },
            ];

            sidebarSearch.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                
                if (searchTerm.length < 2) {
                    searchResults.classList.remove('show');
                    return;
                }

                // Filter results
                const filteredResults = searchData.filter(item => 
                    item.title.toLowerCase().includes(searchTerm) || 
                    item.subtitle.toLowerCase().includes(searchTerm)
                );

                displaySearchResults(filteredResults);
                searchResults.classList.add('show');
            });

            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!sidebarSearch.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.remove('show');
                }
            });

            // Keyboard shortcut (⌘K / Ctrl+K)
            document.addEventListener('keydown', function(e) {
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    sidebarSearch.focus();
                }
            });
        }

        function displaySearchResults(results) {
            if (!searchResults) return;

            if (results.length === 0) {
                searchResults.innerHTML = `
                    <div class="search-no-results">
                        <i class="fas fa-search"></i>
                        <p>No results found</p>
                    </div>
                `;
                return;
            }

            let html = '';
            results.forEach(result => {
                let badgeColor = '';
                switch(result.type) {
                    case 'lost': badgeColor = '#ff4444'; break;
                    case 'found': badgeColor = '#00fa9a'; break;
                    case 'match': badgeColor = '#ff1493'; break;
                    case 'user': badgeColor = '#8b5cf6'; break;
                    default: badgeColor = '#a0a0a0';
                }

                html += `
                    <div class="search-result-item" onclick="window.location.href='${result.url}'">
                        <div class="search-result-icon" style="color: ${badgeColor};">
                            <i class="fas ${result.icon}"></i>
                        </div>
                        <div class="search-result-info">
                            <div class="search-result-title">${result.title}</div>
                            <div class="search-result-subtitle">${result.subtitle}</div>
                        </div>
                        <span class="search-result-badge">${result.badge}</span>
                    </div>
                `;
            });

            searchResults.innerHTML = html;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.startsWith(href.replace(/\/$/, ''))) {
                    link.classList.add('active');
                }
            });

            // Update notification badge visibility based on unread count
            const totalUnread = {{ $totalUnread ?? 0 }};
            const notificationBadge = document.getElementById('notificationBadge');
            if (totalUnread > 0) {
                notificationBadge.style.display = 'flex';
                notificationBadge.textContent = totalUnread;
            }

            // Load users for modal
            loadUsers();

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.3s';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 4000);
        });

        // User List Modal Functions
        function openUserListModal() {
            document.getElementById('userListModal').classList.add('show');
            setTimeout(() => {
                document.getElementById('modalSearchUsers').focus();
            }, 300);
        }

        function closeUserListModal() {
            document.getElementById('userListModal').classList.remove('show');
        }

        function loadUsers() {
            const userListContent = document.getElementById('userListContent');
            
            // Show loading
            userListContent.innerHTML = '<div class="message-loading"><i class="fas fa-spinner fa-spin"></i> Loading users...</div>';
            
            // In production, this would be an AJAX call
            setTimeout(() => {
                // This would be an API call in production
                // fetch('/api/users')
                //     .then(response => response.json())
                //     .then(users => displayUsers(users));
                
                // Sample users - in production, this would come from your backend
                const sampleUsers = [
                    { id: 1, name: 'John Doe', email: 'john@example.com', online: true },
                    { id: 2, name: 'Jane Smith', email: 'jane@example.com', online: true },
                    { id: 3, name: 'Mike Wilson', email: 'mike@example.com', online: false },
                    { id: 4, name: 'Sarah Johnson', email: 'sarah@example.com', online: true },
                    { id: 5, name: 'David Brown', email: 'david@example.com', online: false },
                    { id: 6, name: 'Emily Davis', email: 'emily@example.com', online: true },
                ];
                
                displayUsers(sampleUsers);
            }, 500);
        }

        function displayUsers(users) {
            const userListContent = document.getElementById('userListContent');
            
            if (!users || users.length === 0) {
                userListContent.innerHTML = `
                    <div class="user-list-empty">
                        <i class="fas fa-users-slash"></i>
                        <p>No users found</p>
                    </div>
                `;
                return;
            }

            let html = '';
            users.forEach(user => {
                html += `
                    <div class="user-list-item" onclick="startConversation(${user.id})">
                        <div class="user-list-avatar">
                            ${user.name.charAt(0)}
                            ${user.online ? '<span class="online-status"></span>' : ''}
                        </div>
                        <div class="user-list-info">
                            <div class="user-list-name">${user.name}</div>
                            <div class="user-list-email">${user.email}</div>
                        </div>
                        ${user.online ? '<span class="user-list-status">Online</span>' : ''}
                    </div>
                `;
            });

            userListContent.innerHTML = html;

            // Add search functionality
            const searchInput = document.getElementById('modalSearchUsers');
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const userItems = document.querySelectorAll('.user-list-item');
                
                userItems.forEach(item => {
                    const name = item.querySelector('.user-list-name').textContent.toLowerCase();
                    const email = item.querySelector('.user-list-email').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        function startConversation(userId) {
            // In production, this would redirect to the conversation
            window.location.href = `/messages/start/${userId}`;
        }

        const notificationBtn = document.getElementById('notificationBtn');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', () => {
                const badge = document.getElementById('notificationBadge');
                badge.style.display = 'none';
                
                const totalBadge = document.getElementById('totalUnreadBadge');
                if (totalBadge) totalBadge.style.display = 'none';
            });
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('notificationsContainer');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center border-0 mb-2`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
            const bgColor = type === 'success' ? '#00fa9a' : type === 'error' ? '#ff4444' : 'var(--primary)';
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${icon}" style="color: ${bgColor};"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            container.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 3000
            });
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', function () {
                toast.remove();
            });
        }

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            const modal = document.getElementById('userListModal');
            
            if (modal && modal.classList.contains('show')) {
                const isClickInside = modal.contains(e.target);
                
                if (!isClickInside) {
                    closeUserListModal();
                }
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('show');
                const overlay = document.querySelector('.sidebar-overlay');
                if (overlay) overlay.remove();
            }
        });

        document.body.style.opacity = 0;
        setTimeout(() => {
            document.body.style.transition = 'opacity 0.2s';
            document.body.style.opacity = 1;
        }, 50);

        document.querySelectorAll('.stats-card, .quick-action, .match-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
    @stack('scripts')
</body>
</html>