@extends('layouts.app')

@section('title', 'Messages - Foundify')

@section('content')
<div class="messages-wrapper">
    <!-- Page Header -->
    <div class="messages-header">
        <div class="header-content">
            <div>
                <h1 class="header-title">
                    <i class="fas fa-comments header-icon" style="color: var(--primary);"></i>
                    Messages
                </h1>
                <p class="header-subtitle">Connect with people about lost and found items</p>
            </div>
            <button class="btn-new-conversation" data-bs-toggle="modal" data-bs-target="#newConversationModal">
                <i class="fas fa-plus-circle"></i>
                <span>New Conversation</span>
            </button>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="messages-container">
        <!-- Conversations Sidebar -->
        <div class="conversations-panel">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fas fa-comment-dots" style="color: var(--primary);"></i>
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
                        $isActive = request()->route('conversation')?->id == $conversation->id;
                    @endphp
                    
                    <a href="{{ route('messages.show', $conversation) }}" class="conversation-link">
                        <div class="conversation-card {{ $isActive ? 'active' : '' }} {{ $unreadCount > 0 ? 'unread' : '' }}">
                            <div class="card-avatar">
                                <div class="avatar-circle {{ $otherUser->profile_photo ? 'has-image' : '' }}">
                                    @if($otherUser->profile_photo)
                                        <img src="{{ asset('storage/' . $otherUser->profile_photo) }}" 
                                             alt="{{ $otherUser->name }}" 
                                             class="avatar-image">
                                    @else
                                        <div class="avatar-initial" style="background: linear-gradient(135deg, {{ '#' . substr(md5($otherUser->name), 0, 6) }}, var(--primary));">
                                            {{ substr($otherUser->name, 0, 1) }}
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
                                            {{ Str::limit($conversation->lastMessage->content, 35) }}
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
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-comments" style="color: var(--primary);"></i>
                            </div>
                            <h5>No conversations yet</h5>
                            <p>Click "New Conversation" to start chatting with someone</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-panel">
            <div class="welcome-panel">
                <div class="welcome-content">
                    <div class="welcome-icon-wrapper">
                        <i class="fas fa-comment-dots" style="color: var(--primary);"></i>
                    </div>
                    <h2>Your Messages</h2>
                    <p class="welcome-text">Select a conversation or start a new one</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <i class="fas fa-bolt feature-icon" style="color: var(--primary);"></i>
                            <span>Real-time messaging</span>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-check-circle feature-icon" style="color: #00fa9a;"></i>
                            <span>Read receipts</span>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-shield-alt feature-icon" style="color: #8b5cf6;"></i>
                            <span>Private & secure</span>
                        </div>
                    </div>

                    <button class="btn-start-chat" data-bs-toggle="modal" data-bs-target="#newConversationModal">
                        <i class="fas fa-plus-circle"></i>
                        Start New Conversation
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Conversation Modal -->
<div class="modal fade" id="newConversationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-users me-2" style="color: var(--primary);"></i>
                    Start New Conversation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            
            <div class="modal-body">
                <!-- Search -->
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search search-icon" style="color: var(--primary);"></i>
                        <input type="text" class="search-input" id="searchUsers" placeholder="Search users by name or email...">
                    </div>
                </div>

                <!-- Users List -->
                <div class="users-container">
                    <div class="users-header">
                        <span>All Users</span>
                        <span class="users-counter">{{ $users->count() }} available</span>
                    </div>
                    
                    <div class="users-grid">
                        @forelse($users as $user)
                            @if($user->id !== Auth::id())
                                <div class="user-card" data-user-name="{{ strtolower($user->name) }}" data-user-email="{{ strtolower($user->email) }}">
                                    <div class="user-card-content">
                                        <div class="user-avatar-wrapper">
                                            <div class="user-avatar {{ $user->profile_photo ? 'has-image' : '' }}">
                                                @if($user->profile_photo)
                                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                                         alt="{{ $user->name }}" 
                                                         class="avatar-image">
                                                @else
                                                    <div class="avatar-initial" style="background: linear-gradient(135deg, {{ '#' . substr(md5($user->name), 0, 6) }}, var(--primary));">
                                                        {{ substr($user->name, 0, 1) }}
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
                                        @csrf
                                        <button type="submit" class="btn-message">
                                            <i class="fas fa-comment"></i>
                                            <span>Message</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @empty
                            <div class="empty-users">
                                <i class="fas fa-users-slash" style="color: var(--primary);"></i>
                                <p>No other users found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-dark: #c71585;
    --primary-glow: rgba(255, 20, 147, 0.3);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-header: #222;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --text-muted: #666;
    --success: #00fa9a;
    --danger: #ff4444;
    --warning: #ffa500;
}

/* Modern Messages Wrapper */
.messages-wrapper {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header Styles */
.messages-header {
    margin-bottom: 24px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon {
    color: var(--primary);
    background: var(--bg-header);
    padding: 10px;
    border-radius: 14px;
    font-size: 20px;
}

.header-subtitle {
    font-size: 15px;
    color: var(--text-secondary);
    margin: 0;
}

.btn-new-conversation {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px var(--primary-glow);
}

.btn-new-conversation:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--primary-glow);
}

.btn-new-conversation i {
    font-size: 18px;
}

/* Messages Container */
.messages-container {
    display: grid;
    grid-template-columns: 340px 1fr;
    background: var(--bg-card);
    border-radius: 24px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    min-height: 700px;
}

/* Conversations Panel */
.conversations-panel {
    background: var(--bg-card);
    border-right: 1px solid var(--border-color);
}

.panel-header {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bg-header);
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: var(--text-primary);
}

.panel-title i {
    color: var(--primary);
    font-size: 16px;
}

.conversations-badge {
    background: var(--border-color);
    color: var(--text-secondary);
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.conversations-list {
    height: calc(700px - 85px);
    overflow-y: auto;
    padding: 8px;
}

/* Custom Scrollbar */
.conversations-list::-webkit-scrollbar {
    width: 6px;
}

.conversations-list::-webkit-scrollbar-track {
    background: var(--bg-header);
}

.conversations-list::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
    box-shadow: 0 0 10px var(--primary-glow);
}

.conversations-list::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
}

/* Conversation Cards */
.conversation-link {
    text-decoration: none;
    color: inherit;
    display: block;
    margin-bottom: 4px;
}

.conversation-card {
    display: flex;
    gap: 14px;
    padding: 16px;
    border-radius: 16px;
    transition: all 0.3s ease;
    position: relative;
    background: var(--bg-card);
    border: 1px solid transparent;
}

.conversation-card:hover {
    background: var(--bg-header);
    border-color: var(--primary);
    transform: translateX(4px);
    box-shadow: 0 5px 20px var(--primary-glow);
}

.conversation-card.active {
    background: var(--bg-header);
    border-color: var(--primary);
}

.conversation-card.active::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 40px;
    background: var(--primary);
    border-radius: 4px;
    box-shadow: 0 0 10px var(--primary-glow);
}

.conversation-card.unread {
    background: rgba(255, 20, 147, 0.1);
    border-color: var(--primary);
}

.card-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-circle {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 20px;
    box-shadow: 0 4px 15px var(--primary-glow);
    overflow: hidden;
}

.avatar-circle.has-image {
    background: none;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 18px;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 18px;
    transition: transform 0.3s ease;
}

.conversation-card:hover .avatar-image {
    transform: scale(1.1);
}

.online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 14px;
    height: 14px;
    background: var(--success);
    border: 3px solid var(--bg-card);
    border-radius: 50%;
    box-shadow: 0 0 10px var(--success);
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
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.card-time {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 500;
}

.card-preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.card-preview {
    font-size: 13px;
    color: var(--text-secondary);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-prefix {
    color: var(--text-muted);
    font-size: 12px;
}

.preview-placeholder {
    color: var(--text-muted);
    font-style: italic;
}

.unread-counter {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 30px;
    min-width: 24px;
    text-align: center;
    box-shadow: 0 0 10px var(--primary-glow);
}

/* Chat Panel */
.chat-panel {
    background: var(--bg-dark);
    display: flex;
    align-items: center;
    justify-content: center;
}

.welcome-panel {
    text-align: center;
    padding: 40px;
}

.welcome-icon-wrapper {
    width: 100px;
    height: 100px;
    background: var(--bg-header);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    border: 2px solid var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
}

.welcome-icon-wrapper i {
    font-size: 44px;
    color: var(--primary);
}

.welcome-panel h2 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.welcome-text {
    font-size: 16px;
    color: var(--text-secondary);
    margin-bottom: 40px;
}

/* Features Grid */
.features-grid {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.feature-card {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--bg-card);
    border-radius: 40px;
    border: 1px solid var(--border-color);
    font-size: 14px;
    color: var(--text-secondary);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-2px);
    border-color: var(--primary);
    box-shadow: 0 6px 20px var(--primary-glow);
    color: var(--text-primary);
}

.feature-icon {
    font-size: 16px;
}

.btn-start-chat {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
    padding: 14px 32px;
    border-radius: 40px;
    font-size: 15px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-start-chat:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--primary-glow);
    border-color: transparent;
}

/* Modal Styles */
.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 24px;
    box-shadow: 0 25px 50px var(--primary-glow);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 24px 28px;
    background: var(--bg-header);
}

.modal-header .modal-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
}

.modal-body {
    padding: 28px;
    background: var(--bg-card);
}

/* Search Container */
.search-container {
    margin-bottom: 28px;
}

.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 16px;
}

.search-input {
    width: 100%;
    padding: 16px 16px 16px 48px;
    border: 2px solid var(--border-color);
    border-radius: 20px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: var(--bg-header);
    color: var(--text-primary);
}

.search-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-glow);
    outline: none;
    background: var(--bg-card);
}

.search-input::placeholder {
    color: var(--text-muted);
}

/* Users Container */
.users-container {
    max-height: 450px;
    overflow-y: auto;
    padding-right: 4px;
}

/* Users Container Scrollbar */
.users-container::-webkit-scrollbar {
    width: 6px;
}

.users-container::-webkit-scrollbar-track {
    background: var(--bg-header);
}

.users-container::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 10px;
    box-shadow: 0 0 10px var(--primary-glow);
}

.users-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border-color);
}

.users-header span:first-child {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 15px;
}

.users-counter {
    background: var(--bg-header);
    color: var(--text-secondary);
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid var(--border-color);
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
    padding: 16px;
    border-radius: 20px;
    background: var(--bg-header);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.user-card:hover {
    background: var(--bg-card);
    border-color: var(--primary);
    transform: translateX(4px);
    box-shadow: 0 5px 20px var(--primary-glow);
}

.user-card-content {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
}

.user-avatar-wrapper {
    position: relative;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 18px;
    box-shadow: 0 4px 15px var(--primary-glow);
    overflow: hidden;
}

.user-avatar.has-image {
    background: none;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.user-avatar .avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 16px;
}

.user-avatar .avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 16px;
    transition: transform 0.3s ease;
}

.user-card:hover .avatar-image {
    transform: scale(1.1);
}

.user-online {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 12px;
    height: 12px;
    background: var(--success);
    border: 3px solid var(--bg-header);
    border-radius: 50%;
    box-shadow: 0 0 10px var(--success);
}

.user-details {
    flex: 1;
}

.user-name {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 4px 0;
}

.user-email {
    font-size: 13px;
    color: var(--text-secondary);
}

.status-badge {
    font-size: 11px;
    padding: 4px 10px;
    background: rgba(0, 250, 154, 0.1);
    color: var(--success);
    border-radius: 30px;
    font-weight: 600;
    border: 1px solid var(--success);
}

.btn-message {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    padding: 10px 18px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-message:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px var(--primary-glow);
}

/* Empty States */
.empty-conversations,
.empty-users {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--bg-header);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 2px solid var(--primary);
}

.empty-icon i {
    font-size: 32px;
    color: var(--primary);
}

.empty-conversations h5,
.empty-users h5 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.empty-conversations p,
.empty-users p {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
}

.empty-users i {
    font-size: 48px;
    color: var(--primary);
    margin-bottom: 16px;
}

/* User Search Highlight */
.user-card.hidden {
    display: none;
}

/* Responsive Design */
@media (max-width: 992px) {
    .messages-container {
        grid-template-columns: 280px 1fr;
    }
}

@media (max-width: 768px) {
    .messages-container {
        grid-template-columns: 1fr;
    }
    
    .conversations-panel {
        border-right: none;
        border-bottom: 1px solid var(--border-color);
    }
    
    .conversations-list {
        height: 350px;
    }
    
    .features-grid {
        flex-direction: column;
        align-items: center;
    }
    
    .feature-card {
        width: 100%;
        justify-content: center;
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
        gap: 16px;
    }
    
    .btn-message {
        width: 100%;
        justify-content: center;
    }
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-15px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.conversation-card,
.user-card {
    animation: slideIn 0.3s ease;
}

/* Hover Effects */
.feature-card,
.btn-message,
.btn-new-conversation,
.btn-start-chat {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Active States */
.conversation-card.active .card-name {
    color: var(--primary);
}

.conversation-card.unread .card-name {
    color: var(--warning);
}

/* Loading Spinner */
.fa-spinner {
    color: var(--primary);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
            
            // Show/hide no results message
            const usersContainer = document.querySelector('.users-grid');
            const existingNoResults = document.getElementById('noResults');
            
            if (visibleCount === 0 && searchTerm !== '') {
                if (!existingNoResults) {
                    const noResultsDiv = document.createElement('div');
                    noResultsDiv.id = 'noResults';
                    noResultsDiv.className = 'empty-users';
                    noResultsDiv.innerHTML = `
                        <i class="fas fa-search" style="color: var(--primary);"></i>
                        <p>No users found matching "${e.target.value}"</p>
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
            document.getElementById('searchUsers').focus();
        });
    }

    // Add animation delay to cards
    const conversationCards = document.querySelectorAll('.conversation-card');
    conversationCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endpush
@endsection