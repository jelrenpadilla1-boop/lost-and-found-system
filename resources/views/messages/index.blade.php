@extends('layouts.app')

@section('title', 'Messages - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<style>
/* ── MODERN DESIGN SYSTEM (matches dashboard) ───────────────── */
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
    --radius-card: 20px;
    --radius-sm: 12px;
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    --success: #10b981;
    --success-soft: #d1fae5;
    --warning: #f59e0b;
    --warning-soft: #fef3c7;
    --error: #ef4444;
    --error-soft: #fee2e2;
    --info: #3b82f6;
    --info-soft: #dbeafe;
    --glass: rgba(0, 0, 0, 0.02);
    --glass-b: rgba(0, 0, 0, 0.04);
    --glass-hover: rgba(0, 0, 0, 0.06);
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
    --success-soft: rgba(16, 185, 129, 0.15);
    --warning-soft: rgba(245, 158, 11, 0.15);
    --error-soft: rgba(239, 68, 68, 0.15);
    --info-soft: rgba(59, 130, 246, 0.15);
    --glass: rgba(255, 255, 255, 0.03);
    --glass-b: rgba(255, 255, 255, 0.06);
    --glass-hover: rgba(255, 255, 255, 0.08);
}

/* Messages Wrapper */
.messages-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
    padding: 28px 32px;
}

/* Header Styles */
.messages-header {
    margin-bottom: 28px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-title {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.02em;
}

.header-icon {
    color: var(--accent);
    background: var(--accent-soft);
    padding: 10px;
    border-radius: 16px;
    font-size: 20px;
}

.header-subtitle {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.btn-new-conversation {
    font-size: 13px;
    font-weight: 600;
    background: var(--accent);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.btn-new-conversation:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(124, 58, 237, 0.35);
}

/* Messages Container */
.messages-container {
    display: grid;
    grid-template-columns: 340px 1fr;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    min-height: 600px;
}

/* Conversations Panel */
.conversations-panel {
    background: var(--bg-card);
    border-right: 1px solid var(--border-light);
}

.panel-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bg-soft);
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-muted);
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.panel-title i {
    color: var(--accent);
    font-size: 14px;
}

.conversations-badge {
    background: var(--accent-soft);
    color: var(--accent);
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
}

.conversations-list {
    height: calc(600px - 73px);
    overflow-y: auto;
    padding: 12px;
}

.conversations-list::-webkit-scrollbar {
    width: 6px;
}

.conversations-list::-webkit-scrollbar-track {
    background: var(--bg-soft);
}

.conversations-list::-webkit-scrollbar-thumb {
    background: var(--border-light);
    border-radius: 3px;
}

.conversations-list::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
}

/* Conversation Cards */
.conversation-link {
    text-decoration: none;
    color: inherit;
    display: block;
    margin-bottom: 8px;
}

.conversation-card {
    display: flex;
    gap: 12px;
    padding: 14px;
    border-radius: var(--radius-sm);
    transition: var(--transition);
    background: var(--bg-soft);
    border: 1px solid transparent;
}

.conversation-card:hover {
    background: var(--glass);
    border-color: var(--border-light);
    transform: translateX(4px);
}

.conversation-card.active {
    background: var(--accent-soft);
    border-color: var(--accent-soft);
}

.conversation-card.unread {
    background: var(--accent-soft);
}

.card-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    overflow: hidden;
    background: var(--accent);
    color: white;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.2);
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent);
    color: white;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.online-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 10px;
    height: 10px;
    background: var(--success);
    border: 2px solid var(--bg-card);
    border-radius: 50%;
}

.card-content {
    flex: 1;
    min-width: 0;
}

.card-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.card-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.card-time {
    font-size: 10px;
    color: var(--text-muted);
}

.card-preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.card-preview {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-prefix {
    color: var(--accent);
    font-size: 11px;
    font-weight: 600;
}

.preview-placeholder {
    color: var(--text-muted);
    font-style: italic;
}

.unread-counter {
    background: var(--accent);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    min-width: 22px;
    text-align: center;
}

/* Chat Panel */
.chat-panel {
    background: var(--bg-card);
    display: flex;
    align-items: center;
    justify-content: center;
}

.welcome-panel {
    text-align: center;
    padding: 40px;
}

.welcome-icon-wrapper {
    width: 80px;
    height: 80px;
    background: var(--accent-soft);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.welcome-icon-wrapper i {
    font-size: 32px;
    color: var(--accent);
}

.welcome-panel h2 {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 12px;
}

.welcome-text {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 28px;
}

/* Features Grid */
.features-grid {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 32px;
    flex-wrap: wrap;
}

.feature-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: 40px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    transition: var(--transition);
}

.feature-card:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

.feature-icon {
    font-size: 12px;
    color: var(--accent);
}

.btn-start-chat {
    font-size: 13px;
    font-weight: 600;
    background: transparent;
    color: var(--accent);
    border: 1px solid var(--accent);
    padding: 12px 28px;
    border-radius: 40px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-start-chat:hover {
    background: var(--accent);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

/* Modal Styles */
.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-lg);
}

.modal-header {
    border-bottom: 1px solid var(--border-light);
    padding: 20px 24px;
    background: var(--bg-soft);
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-title i {
    color: var(--accent);
}

.modal-header .btn-close {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 20px;
    cursor: pointer;
    transition: var(--transition);
}

.modal-header .btn-close:hover {
    color: var(--error);
    transform: rotate(90deg);
}

.modal-body {
    padding: 24px;
}

/* Search Container */
.search-container {
    margin-bottom: 24px;
}

.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--accent);
    font-size: 14px;
    z-index: 1;
}

.search-input {
    width: 100%;
    padding: 12px 14px 12px 42px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    font-size: 14px;
    color: var(--text-dark);
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.search-input::placeholder {
    color: var(--text-soft);
}

/* Users Container */
.users-container {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 4px;
}

.users-container::-webkit-scrollbar {
    width: 6px;
}

.users-container::-webkit-scrollbar-track {
    background: var(--bg-soft);
}

.users-container::-webkit-scrollbar-thumb {
    background: var(--border-light);
    border-radius: 3px;
}

.users-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-light);
}

.users-header span:first-child {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.users-counter {
    background: var(--accent-soft);
    color: var(--accent);
    font-size: 11px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
}

.users-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.user-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px;
    border-radius: var(--radius-sm);
    background: var(--bg-soft);
    border: 1px solid transparent;
    transition: var(--transition);
}

.user-card:hover {
    background: var(--glass);
    border-color: var(--border-light);
    transform: translateX(4px);
}

.user-card.hidden {
    display: none;
}

.user-card-content {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.user-avatar-wrapper {
    position: relative;
}

.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    overflow: hidden;
    background: var(--accent);
    color: white;
}

.user-online {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: var(--success);
    border: 2px solid var(--bg-card);
    border-radius: 50%;
}

.user-details {
    flex: 1;
}

.user-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.user-email {
    font-size: 11px;
    color: var(--text-muted);
}

.status-badge {
    font-size: 10px;
    font-weight: 600;
    padding: 4px 10px;
    background: var(--success-soft);
    color: var(--success);
    border-radius: 20px;
}

.btn-message {
    font-size: 11px;
    font-weight: 600;
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--accent);
    padding: 8px 16px;
    border-radius: 40px;
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-message:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
    transform: translateY(-2px);
}

/* Empty States */
.empty-conversations,
.empty-users {
    text-align: center;
    padding: 40px 20px;
}

.empty-icon {
    width: 60px;
    height: 60px;
    background: var(--bg-soft);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}

.empty-icon i {
    font-size: 28px;
    color: var(--accent);
}

.empty-conversations h5,
.empty-users h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.empty-conversations p,
.empty-users p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.conversation-card,
.user-card {
    animation: slideIn 0.3s ease forwards;
}

/* Responsive */
@media (max-width: 992px) {
    .messages-container {
        grid-template-columns: 300px 1fr;
    }
    
    .messages-wrapper {
        padding: 20px;
    }
}

@media (max-width: 768px) {
    .messages-container {
        grid-template-columns: 1fr;
    }
    
    .conversations-panel {
        border-right: none;
        border-bottom: 1px solid var(--border-light);
    }
    
    .conversations-list {
        height: 350px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .btn-new-conversation {
        width: 100%;
        justify-content: center;
    }
    
    .user-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .btn-message {
        width: 100%;
        justify-content: center;
    }
    
    .features-grid {
        flex-direction: column;
        align-items: stretch;
    }
    
    .feature-card {
        justify-content: center;
    }
}
</style>

<div class="messages-wrapper">
    {{-- Page Header --}}
    <div class="messages-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-comments header-icon"></i>
                    Messages
                </h1>
                <p class="header-subtitle">Connect with people about lost and found items</p>
            </div>
            <button class="btn-new-conversation" data-bs-toggle="modal" data-bs-target="#newConversationModal">
                <i class="fas fa-plus-circle"></i>
                New Conversation
            </button>
        </div>
    </div>

    {{-- Messages Container --}}
    <div class="messages-container">
        {{-- Conversations Sidebar --}}
        <div class="conversations-panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-comment-dots"></i>
                    <span>All Conversations</span>
                </div>
                <span class="conversations-badge">{{ $conversations->count() }}</span>
            </div>
            
            <div class="conversations-list">
                @forelse($conversations as $conversation)
                    @php
                        $otherUser = $conversation->user1_id === Auth::id() ? $conversation->user2 : $conversation->user1;
                        $unreadCount = $conversation->messages()
                            ->where('user_id', '!=', Auth::id())
                            ->where('is_read', false)
                            ->count();
                        $isActive = isset($activeConversation) && $activeConversation->id == $conversation->id;
                    @endphp
                    
                    <a href="{{ route('messages.show', $conversation) }}" class="conversation-link">
                        <div class="conversation-card {{ $isActive ? 'active' : '' }} {{ $unreadCount > 0 ? 'unread' : '' }}">
                            <div class="card-avatar">
                                <div class="avatar-circle">
                                    @if($otherUser->profile_photo)
                                        <img src="{{ asset('storage/' . $otherUser->profile_photo) }}" 
                                             alt="{{ $otherUser->name }}" 
                                             class="avatar-image">
                                    @else
                                        <div class="avatar-initial">
                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                @if($otherUser->isOnline())
                                    <span class="online-dot"></span>
                                @endif
                            </div>
                            
                            <div class="card-content">
                                <div class="card-header-row">
                                    <h6 class="card-name">{{ $otherUser->name }}</h6>
                                    <span class="card-time">{{ $conversation->updated_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="card-preview-row">
                                    <p class="card-preview">
                                        @if($conversation->lastMessage)
                                            @if($conversation->lastMessage->user_id === Auth::id())
                                                <span class="preview-prefix">You: </span>
                                            @endif
                                            {{ Str::limit($conversation->lastMessage->content, 40) }}
                                        @else
                                            <span class="preview-placeholder">No messages yet</span>
                                        @endif
                                    </p>
                                    @if($unreadCount > 0)
                                        <span class="unread-counter">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-conversations">
                        <div class="empty-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h5>No Conversations</h5>
                        <p>Click "New Conversation" to start chatting</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Chat Area --}}
        <div class="chat-panel">
            <div class="welcome-panel">
                <div class="welcome-icon-wrapper">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <h2>Your Messages</h2>
                <p class="welcome-text">Select a conversation or start a new one</p>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-bolt feature-icon"></i>
                        <span>Real Time</span>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-check-circle feature-icon" style="color: var(--success);"></i>
                        <span>Read Receipts</span>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <span>Secure</span>
                    </div>
                </div>

                <button class="btn-start-chat" data-bs-toggle="modal" data-bs-target="#newConversationModal">
                    <i class="fas fa-plus-circle"></i>
                    Start New
                </button>
            </div>
        </div>
    </div>
</div>

{{-- New Conversation Modal --}}
<div class="modal fade" id="newConversationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-users"></i>
                    New Conversation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            </div>
            
            <div class="modal-body">
                {{-- Search --}}
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchUsers" placeholder="Search users...">
                    </div>
                </div>

                {{-- Users List --}}
                <div class="users-container">
                    <div class="users-header">
                        <span>All Users</span>
                        <span class="users-counter">{{ $users->count() }}</span>
                    </div>
                    
                    <div class="users-grid">
                        @forelse($users as $user)
                            @if($user->id !== Auth::id())
                                <div class="user-card" data-user-name="{{ strtolower($user->name) }}" data-user-email="{{ strtolower($user->email) }}">
                                    <div class="user-card-content">
                                        <div class="user-avatar-wrapper">
                                            <div class="user-avatar">
                                                @if($user->profile_photo)
                                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                                         alt="{{ $user->name }}" 
                                                         class="avatar-image">
                                                @else
                                                    <div class="avatar-initial">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if($user->isOnline())
                                                <span class="user-online"></span>
                                            @endif
                                        </div>
                                        
                                        <div class="user-details">
                                            <h6 class="user-name">{{ $user->name }}</h6>
                                            <span class="user-email">{{ $user->email }}</span>
                                        </div>
                                        
                                        @if($user->isOnline())
                                            <span class="status-badge">Online</span>
                                        @endif
                                    </div>
                                    
                                    <form action="{{ route('messages.start', $user) }}" method="GET" class="message-form">
                                        <button type="submit" class="btn-message">
                                            <i class="fas fa-comment"></i>
                                            <span>Message</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @empty
                            <div class="empty-users">
                                <i class="fas fa-users-slash"></i>
                                <p>No other users found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User search filter
    const searchInput = document.getElementById('searchUsers');
    const userCards = document.querySelectorAll('.user-card');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            let visibleCount = 0;
            
            userCards.forEach(card => {
                const userName = card.dataset.userName;
                const userEmail = card.dataset.userEmail;
                
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });
            
            const usersContainer = document.querySelector('.users-grid');
            const existingNoResults = document.getElementById('noResults');
            
            if (visibleCount === 0 && searchTerm !== '') {
                if (!existingNoResults) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.id = 'noResults';
                    noResultsDiv.className = 'empty-users';
                    noResultsDiv.innerHTML = `
                        <i class="fas fa-search"></i>
                        <p>No results for "${e.target.value}"</p>
                    `;
                    usersContainer.appendChild(noResultsDiv);
                }
            } else if (existingNoResults) {
                existingNoResults.remove();
            }
        });
    }

    // Auto-focus search input when modal opens
    const modal = document.getElementById('newConversationModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            const searchInput = document.getElementById('searchUsers');
            if (searchInput) searchInput.focus();
        });
    }

    // Add animation delay to conversation cards
    const conversationCards = document.querySelectorAll('.conversation-card');
    conversationCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.08}s`;
    });
});
</script>
@endpush
@endsection