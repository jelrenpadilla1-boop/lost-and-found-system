@extends('layouts.app')

@section('title', 'Messages - Foundify')

@section('content')
<div class="messages-wrapper">
    <!-- Page Header -->
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
                        $isActive = request()->route('conversation')?->id == $conversation->id;
                    @endphp
                    
                    <a href="{{ route('messages.show', $conversation) }}" class="conversation-link">
                        <div class="conversation-card {{ $isActive ? 'active' : '' }} {{ $unreadCount > 0 ? 'unread' : '' }}">
                            <div class="card-avatar">
                                <div class="avatar-circle" style="background: linear-gradient(135deg, {{ '#' . substr(md5($otherUser->name), 0, 6) }}, {{ '#' . substr(md5($otherUser->name . 'salt'), 0, 6) }})">
                                    {{ substr($otherUser->name, 0, 1) }}
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
                                <i class="fas fa-comments"></i>
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
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h2>Your Messages</h2>
                    <p class="welcome-text">Select a conversation or start a new one</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <i class="fas fa-bolt feature-icon" style="color: #3b82f6;"></i>
                            <span>Real-time messaging</span>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-check-circle feature-icon" style="color: #10b981;"></i>
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
                    <i class="fas fa-users me-2" style="color: #3b82f6;"></i>
                    Start New Conversation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <!-- Search -->
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
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
                                            <div class="user-avatar" style="background: linear-gradient(135deg, {{ '#' . substr(md5($user->name), 0, 6) }}, {{ '#' . substr(md5($user->name . 'salt'), 0, 6) }})">
                                                {{ substr($user->name, 0, 1) }}
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

<style>
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
    color: #0f172a;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon {
    color: #3b82f6;
    background: #eff6ff;
    padding: 10px;
    border-radius: 14px;
    font-size: 20px;
}

.header-subtitle {
    font-size: 15px;
    color: #64748b;
    margin: 0;
}

.btn-new-conversation {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
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
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-new-conversation:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
}

.btn-new-conversation i {
    font-size: 18px;
}

/* Messages Container */
.messages-container {
    display: grid;
    grid-template-columns: 340px 1fr;
    background: white;
    border-radius: 24px;
    border: 1px solid #f1f5f9;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    min-height: 700px;
}

/* Conversations Panel */
.conversations-panel {
    background: #ffffff;
    border-right: 1px solid #f1f5f9;
}

.panel-header {
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #0f172a;
}

.panel-title i {
    color: #3b82f6;
    font-size: 16px;
}

.conversations-badge {
    background: #f1f5f9;
    color: #475569;
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
    background: #f8fafc;
}

.conversations-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.conversations-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
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
    transition: all 0.2s ease;
    position: relative;
}

.conversation-card:hover {
    background: #f8fafc;
    transform: translateX(4px);
}

.conversation-card.active {
    background: #f0f9ff;
}

.conversation-card.active::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 40px;
    background: #3b82f6;
    border-radius: 4px;
}

.conversation-card.unread {
    background: #fff7ed;
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
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 14px;
    height: 14px;
    background: #10b981;
    border: 3px solid white;
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
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
}

.card-time {
    font-size: 11px;
    color: #94a3b8;
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
    color: #64748b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-prefix {
    color: #94a3b8;
    font-size: 12px;
}

.preview-placeholder {
    color: #94a3b8;
    font-style: italic;
}

.unread-counter {
    background: #3b82f6;
    color: white;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 30px;
    min-width: 24px;
    text-align: center;
}

/* Chat Panel */
.chat-panel {
    background: #fafbfc;
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
    background: #eff6ff;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
}

.welcome-icon-wrapper i {
    font-size: 44px;
    color: #3b82f6;
}

.welcome-panel h2 {
    font-size: 28px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 12px;
}

.welcome-text {
    font-size: 16px;
    color: #64748b;
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
    background: white;
    border-radius: 40px;
    border: 1px solid #e2e8f0;
    font-size: 14px;
    color: #334155;
    transition: all 0.2s ease;
}

.feature-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    border-color: #3b82f6;
}

.feature-icon {
    font-size: 16px;
}

.btn-start-chat {
    background: white;
    color: #3b82f6;
    border: 2px solid #3b82f6;
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
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 24px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid #f1f5f9;
    padding: 24px 28px;
}

.modal-header .modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
}

.modal-body {
    padding: 28px;
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
    color: #94a3b8;
    font-size: 16px;
}

.search-input {
    width: 100%;
    padding: 16px 16px 16px 48px;
    border: 2px solid #f1f5f9;
    border-radius: 20px;
    font-size: 15px;
    transition: all 0.2s ease;
    background: #f8fafc;
}

.search-input:focus {
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    outline: none;
}

/* Users Container */
.users-container {
    max-height: 450px;
    overflow-y: auto;
    padding-right: 4px;
}

.users-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
}

.users-header span:first-child {
    font-weight: 600;
    color: #0f172a;
    font-size: 15px;
}

.users-counter {
    background: #f1f5f9;
    color: #475569;
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
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
    background: #f8fafc;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.user-card:hover {
    background: white;
    border-color: #e2e8f0;
    transform: translateX(4px);
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
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.user-online {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 12px;
    height: 12px;
    background: #10b981;
    border: 3px solid white;
    border-radius: 50%;
}

.user-details {
    flex: 1;
}

.user-name {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 4px 0;
}

.user-email {
    font-size: 13px;
    color: #64748b;
}

.status-badge {
    font-size: 11px;
    padding: 4px 10px;
    background: #e8f5e9;
    color: #10b981;
    border-radius: 30px;
    font-weight: 600;
}

.btn-message {
    background: white;
    border: 2px solid #e2e8f0;
    color: #3b82f6;
    padding: 10px 18px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-message:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(59, 130, 246, 0.2);
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
    background: #f1f5f9;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.empty-icon i {
    font-size: 32px;
    color: #94a3b8;
}

.empty-conversations h5,
.empty-users h5 {
    font-size: 18px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
}

.empty-conversations p,
.empty-users p {
    font-size: 14px;
    color: #94a3b8;
    margin: 0;
}

.empty-users i {
    font-size: 48px;
    color: #cbd5e1;
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
        border-bottom: 1px solid #f1f5f9;
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
    color: #3b82f6;
}

.conversation-card.unread .card-name {
    color: #ea580c;
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
                        <i class="fas fa-search"></i>
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
});
</script>
@endpush
@endsection