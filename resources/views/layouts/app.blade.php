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
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: #1a1a1a;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: 13px;
            box-shadow: 0 0 15px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .user-profile:hover .user-avatar {
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

        /* Logout Button */
        .logout-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid #333333;
            border-radius: var(--radius-md);
            color: #a0a0a0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--primary-glow);
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
            display: inline-block;
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

        /* Messages Section Styles */
        .messages-section {
            margin-top: 16px;
            border-top: 1px solid #333333;
            padding-top: 16px;
        }

        .message-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-left: 2px solid transparent;
        }

        .message-preview:hover {
            background: #1a1a1a;
            border-left-color: var(--primary);
            transform: translateX(5px);
        }

        .message-preview.unread {
            background: rgba(255, 20, 147, 0.1);
        }

        .message-preview.unread .message-sender {
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
        }

        .message-preview:hover .message-avatar {
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

        .message-sender {
            font-size: 12px;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .message-time {
            font-size: 9px;
            color: #666666;
        }

        .message-text {
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
        }

        .view-all-messages {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: #a0a0a0;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s ease;
            margin-top: 8px;
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

        /* Message Modal */
        .message-modal {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 320px;
            background: #1a1a1a;
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px var(--primary-glow);
            z-index: 2000;
            display: none;
            border: 1px solid var(--primary);
        }

        .message-modal.show {
            display: block;
            animation: slideUp 0.3s ease;
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

        .message-modal-header {
            padding: 16px;
            border-bottom: 1px solid #333333;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .message-modal-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
            color: var(--primary);
        }

        .message-modal-close {
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

        .message-modal-close:hover {
            background: var(--primary);
            color: white;
            transform: rotate(90deg);
        }

        .message-modal-body {
            max-height: 300px;
            overflow-y: auto;
            padding: 16px;
        }

        /* Modal Scrollbar */
        .message-modal-body::-webkit-scrollbar {
            width: 4px;
        }

        .message-modal-body::-webkit-scrollbar-track {
            background: #2a2a2a;
        }

        .message-modal-body::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        .message-bubble {
            margin-bottom: 12px;
            max-width: 80%;
            animation: fadeIn 0.3s ease;
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

        .message-bubble.sent {
            margin-left: auto;
        }

        .message-bubble.received {
            margin-right: auto;
        }

        .bubble-content {
            padding: 10px 14px;
            border-radius: var(--radius-md);
            font-size: 12px;
            word-wrap: break-word;
        }

        .message-bubble.sent .bubble-content {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 5px 15px var(--primary-glow);
        }

        .message-bubble.received .bubble-content {
            background: #2a2a2a;
            color: #ffffff;
            border: 1px solid #333333;
        }

        .message-time {
            font-size: 9px;
            color: #666666;
            margin-top: 4px;
            text-align: right;
        }

        .message-modal-footer {
            padding: 12px 16px;
            border-top: 1px solid #333333;
            display: flex;
            gap: 8px;
        }

        .message-input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #333333;
            border-radius: var(--radius-md);
            font-size: 12px;
            outline: none;
            background: #2a2a2a;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .message-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 15px var(--primary-glow);
        }

        .message-send-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 14px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        .message-send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--primary-glow);
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

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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

    <!-- Black Sidebar with Pink Accents -->
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

        <!-- Messages Section -->
        <div class="messages-section">
            <div class="nav-title">
                <i class="fas fa-comments me-1"></i>
                Messages
                <span class="nav-badge" id="totalMessagesBadge" style="display: none;">0</span>
            </div>
            
            <div id="messagePreviews">
                <div class="text-center text-muted p-3 small">
                    <i class="fas fa-spinner fa-spin me-2" style="color: var(--primary);"></i>
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

    <!-- Message Modal -->
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
                &copy; {{ date('Y') }} <span>Foundify</span>
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

        // Message System Variables
        let currentConversationId = null;
        let messageCheckInterval = null;
        
        let mockMessages = {};
        const conversations = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.startsWith(href.replace(/\/$/, ''))) {
                    link.classList.add('active');
                }
            });

            updateBadges();
            loadMessages();
            startMessageChecking();

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.3s';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 4000);

            loadRecentMessages();
            loadUnreadCount();
            
            setInterval(() => {
                loadRecentMessages();
                loadUnreadCount();
            }, 10000);
        });

        function loadMessages() {
            const messagePreviews = document.getElementById('messagePreviews');
            if (!messagePreviews) return;

            messagePreviews.innerHTML = '<div class="text-center text-muted p-3 small">No messages yet</div>';

            const totalBadge = document.getElementById('totalMessagesBadge');
            totalBadge.style.display = 'none';
        }

        function loadRecentMessages() {
            loadMessages();
        }

        function loadUnreadCount() {
            const notificationBadge = document.getElementById('notificationBadge');
            notificationBadge.style.display = 'none';
        }

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

        const notificationBtn = document.getElementById('notificationBtn');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', () => {
                const badge = document.getElementById('notificationBadge');
                badge.style.display = 'none';
            });
        }

        function openConversation(conversationId, recipientName) {
            currentConversationId = conversationId;
            
            document.getElementById('modalRecipientName').textContent = recipientName;
            loadConversationMessages(conversationId);
            document.getElementById('messageModal').classList.add('show');
        }

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
            
            modalBody.scrollTop = modalBody.scrollHeight;
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message || !currentConversationId) return;
            
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
            loadConversationMessages(currentConversationId);
            input.value = '';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.remove('show');
            currentConversationId = null;
        }

        function startMessageChecking() {
            messageCheckInterval = setInterval(() => {}, 5000);
        }

        window.addEventListener('beforeunload', () => {
            if (messageCheckInterval) {
                clearInterval(messageCheckInterval);
            }
        });

        document.addEventListener('click', (e) => {
            const modal = document.getElementById('messageModal');
            const isClickInside = modal.contains(e.target);
            const isMessagePreview = e.target.closest('.message-preview');
            
            if (!isClickInside && !isMessagePreview && modal.classList.contains('show')) {
                closeMessageModal();
            }
        });

        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

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

        document.getElementById('messageInput')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>