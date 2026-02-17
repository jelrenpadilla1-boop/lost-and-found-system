@extends('layouts.app')

@section('title', 'Conversation with ' . $conversation->otherUser->name . ' - Foundify')

@section('content')
<div class="chat-wrapper">
    <!-- Header -->
    <div class="chat-header">
        <div class="header-left">
            <a href="{{ route('messages.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="user-info">
                <div class="user-avatar-wrapper">
                    <div class="user-avatar {{ $conversation->otherUser->profile_photo ? 'has-image' : '' }}">
                        @if($conversation->otherUser->profile_photo)
                            <img src="{{ asset('storage/' . $conversation->otherUser->profile_photo) }}" 
                                 alt="{{ $conversation->otherUser->name }}" 
                                 class="avatar-image">
                        @else
                            <div class="avatar-initial" style="background: linear-gradient(135deg, {{ '#' . substr(md5($conversation->otherUser->name), 0, 6) }}, var(--primary));">
                                {{ substr($conversation->otherUser->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    @if($conversation->otherUser->isOnline())
                        <span class="online-indicator"></span>
                    @endif
                </div>
                <div class="user-details">
                    <h2 class="user-name">{{ $conversation->otherUser->name }}</h2>
                    <span class="user-status {{ $conversation->otherUser->isOnline() ? 'online' : 'offline' }}">
                        {{ $conversation->otherUser->isOnline() ? 'Online' : 'Offline' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <button class="action-button" onclick="window.location.href='{{ route('messages.index') }}'">
                <i class="fas fa-minus"></i>
            </button>
            <button class="action-button close-button" onclick="window.location.href='{{ route('messages.index') }}'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main">
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar">
            <div class="sidebar-header">
                <i class="fas fa-comments" style="color: var(--primary);"></i>
                <span>Conversations</span>
                <span class="conversations-count">{{ $conversations->count() }}</span>
            </div>
            
            <div class="conversations-list">
                @foreach($conversations as $conv)
                    @php
                        $otherUser = $conv->user1_id === Auth::id() ? $conv->user2 : $conv->user1;
                        $unreadCount = $conv->messages()
                            ->where('user_id', '!=', Auth::id())
                            ->where('is_read', false)
                            ->count();
                        $isActive = $conv->id === $conversation->id;
                    @endphp
                    
                    <a href="{{ route('messages.show', $conv) }}" class="conversation-link">
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
                                    <span class="card-time">{{ $conv->updated_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="card-preview-row">
                                    <p class="card-preview">
                                        @if($conv->lastMessage)
                                            @if($conv->lastMessage->user_id === Auth::id())
                                                <span class="preview-prefix">You: </span>
                                            @endif
                                            {{ Str::limit($conv->lastMessage->content, 25) }}
                                        @endif
                                    </p>
                                    @if($unreadCount > 0)
                                        <span class="unread-badge">{{ $unreadCount }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <div class="messages-container" id="messageContainer">
                @foreach($messages as $message)
                    <div class="message-wrapper {{ $message->user_id === Auth::id() ? 'sent' : 'received' }}" id="message-{{ $message->id }}">
                        @if($message->user_id !== Auth::id())
                            <div class="message-avatar-small {{ $message->user->profile_photo ? 'has-image' : '' }}">
                                @if($message->user->profile_photo)
                                    <img src="{{ asset('storage/' . $message->user->profile_photo) }}" 
                                         alt="{{ $message->user->name }}" 
                                         class="avatar-image">
                                @else
                                    <div class="avatar-initial" style="background: linear-gradient(135deg, {{ '#' . substr(md5($message->user->name), 0, 6) }}, var(--primary));">
                                        {{ substr($message->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="message-bubble {{ $message->user_id === Auth::id() ? 'sent' : 'received' }}">
                            <div class="message-content">
                                <p>{{ $message->content }}</p>
                            </div>
                            <div class="message-meta">
                                <span class="message-time">{{ $message->created_at->format('g:i A') }}</span>
                                @if($message->user_id === Auth::id())
                                    @if($message->is_read)
                                        <i class="fas fa-check-double read-receipt read"></i>
                                    @else
                                        <i class="fas fa-check read-receipt"></i>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="message-input-container">
                <form id="messageForm" class="input-form">
                    @csrf
                    <button type="button" class="attach-button">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <input type="text" class="message-input" id="messageInput" placeholder="Type your message..." autocomplete="off">
                    <button type="submit" class="send-button" id="sendButton">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
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
    --bg-sidebar: #1a1a1a;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --text-muted: #666;
    --success: #00fa9a;
    --danger: #ff4444;
    --warning: #ffa500;
}

/* Chat Wrapper */
.chat-wrapper {
    background: var(--bg-card);
    border-radius: 24px;
    border: 1px solid var(--border-color);
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    min-height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

/* Chat Header */
.chat-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bg-header);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.back-button {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    background: var(--bg-card);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.back-button:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateX(-3px);
    border-color: transparent;
    box-shadow: 0 5px 15px var(--primary-glow);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.user-avatar-wrapper {
    position: relative;
}

.user-avatar {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 22px;
    box-shadow: 0 4px 15px var(--primary-glow);
    overflow: hidden;
}

.user-avatar.has-image {
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

.user-avatar:hover .avatar-image {
    transform: scale(1.1);
}

.online-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 14px;
    height: 14px;
    background: var(--success);
    border: 3px solid var(--bg-header);
    border-radius: 50%;
    box-shadow: 0 0 10px var(--success);
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 4px 0;
}

.user-status {
    font-size: 13px;
    font-weight: 500;
}

.user-status.online {
    color: var(--success);
}

.user-status.offline {
    color: var(--text-muted);
}

.header-actions {
    display: flex;
    gap: 8px;
}

.action-button {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.action-button:hover {
    background: var(--bg-header);
    color: var(--text-primary);
}

.close-button:hover {
    background: var(--danger);
    color: white;
    border-color: var(--danger);
}

/* Chat Main */
.chat-main {
    display: flex;
    flex: 1;
    min-height: 0;
}

/* Conversations Sidebar */
.conversations-sidebar {
    width: 300px;
    border-right: 1px solid var(--border-color);
    background: var(--bg-sidebar);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: var(--text-primary);
    background: var(--bg-header);
}

.sidebar-header i {
    color: var(--primary);
    font-size: 16px;
}

.conversations-count {
    margin-left: auto;
    background: var(--border-color);
    color: var(--text-secondary);
    padding: 4px 8px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
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
}

.conversation-card {
    display: flex;
    gap: 12px;
    padding: 14px;
    border-radius: 16px;
    transition: all 0.3s ease;
    margin-bottom: 4px;
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
    box-shadow: 0 5px 20px var(--primary-glow);
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
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 18px;
    box-shadow: 0 4px 15px var(--primary-glow);
    overflow: hidden;
}

.avatar-circle.has-image {
    background: none;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.avatar-circle .avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 14px;
}

.avatar-circle .avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 14px;
    transition: transform 0.3s ease;
}

.conversation-card:hover .avatar-image {
    transform: scale(1.1);
}

.online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: var(--success);
    border: 2px solid var(--bg-card);
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
    margin-bottom: 4px;
}

.card-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
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
    color: var(--text-secondary);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-prefix {
    color: var(--text-muted);
}

.unread-badge {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 30px;
    min-width: 20px;
    text-align: center;
    box-shadow: 0 0 10px var(--primary-glow);
}

/* Chat Area */
.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: var(--bg-card);
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 30px;
    background: var(--bg-dark);
}

/* Message Styles */
.message-wrapper {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    margin-bottom: 20px;
    animation: fadeIn 0.3s ease;
}

.message-wrapper.sent {
    justify-content: flex-end;
}

.message-avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
    box-shadow: 0 4px 15px var(--primary-glow);
    overflow: hidden;
}

.message-avatar-small.has-image {
    background: none;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.message-avatar-small .avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 10px;
}

.message-avatar-small .avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.message-wrapper:hover .avatar-image {
    transform: scale(1.1);
}

.message-bubble {
    max-width: 60%;
    position: relative;
}

.message-bubble.sent {
    margin-left: auto;
}

.message-content {
    padding: 12px 16px;
    border-radius: 20px;
    background: var(--bg-header);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border-color);
}

.message-bubble.sent .message-content {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.message-content p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
    color: var(--text-primary);
}

.message-bubble.sent .message-content p {
    color: white;
}

.message-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    margin-top: 4px;
    padding: 0 4px;
}

.message-bubble.sent .message-meta {
    justify-content: flex-end;
}

.message-time {
    font-size: 10px;
    color: var(--text-muted);
}

.message-bubble.sent .message-time {
    color: rgba(255, 255, 255, 0.7);
}

.read-receipt {
    font-size: 10px;
    color: var(--text-muted);
}

.read-receipt.read {
    color: var(--primary);
}

.message-bubble.sent .read-receipt {
    color: rgba(255, 255, 255, 0.7);
}

.message-bubble.sent .read-receipt.read {
    color: white;
}

/* Message Input */
.message-input-container {
    padding: 20px 24px;
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
}

.input-form {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-card);
    padding: 8px 8px 8px 16px;
    border-radius: 30px;
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
}

.input-form:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-glow);
    background: var(--bg-header);
}

.attach-button {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
    font-size: 18px;
}

.attach-button:hover {
    color: var(--primary);
    background: var(--bg-card);
}

.message-input {
    flex: 1;
    border: none;
    background: none;
    padding: 12px 0;
    font-size: 14px;
    color: var(--text-primary);
    outline: none;
}

.message-input::placeholder {
    color: var(--text-muted);
}

.send-button {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border: none;
    color: white;
    width: 46px;
    height: 46px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 18px;
    box-shadow: 0 4px 15px var(--primary-glow);
}

.send-button:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px var(--primary-glow);
}

/* Animations */
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

.conversation-card {
    animation: slideIn 0.3s ease;
}

.message-wrapper {
    animation: fadeIn 0.3s ease;
}

/* Active Conversation Highlight */
.conversation-card.active .card-name {
    color: var(--primary);
}

.conversation-card.unread .card-name {
    color: var(--warning);
    font-weight: 700;
}

/* Responsive Design */
@media (max-width: 992px) {
    .conversations-sidebar {
        width: 250px;
    }
    
    .message-bubble {
        max-width: 75%;
    }
}

@media (max-width: 768px) {
    .chat-main {
        flex-direction: column;
    }
    
    .conversations-sidebar {
        width: 100%;
        height: 250px;
        border-right: none;
        border-bottom: 1px solid var(--border-color);
    }
    
    .conversations-list {
        height: 200px;
    }
    
    .message-bubble {
        max-width: 85%;
    }
    
    .header-left {
        gap: 12px;
    }
    
    .user-name {
        font-size: 16px;
    }
    
    .user-avatar {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }
    
    .header-actions {
        display: none;
    }
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
    background: var(--bg-header);
    border-radius: 20px;
    border: 1px solid var(--border-color);
    width: fit-content;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: var(--text-muted);
    border-radius: 50%;
    animation: typing 1s infinite ease-in-out;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-8px);
    }
}
</style>

@push('scripts')
<script>
    let lastMessageId = {{ $messages->last()->id ?? 0 }};
    let conversationId = {{ $conversation->id }};
    let messageContainer = document.getElementById('messageContainer');
    let messageForm = document.getElementById('messageForm');
    let messageInput = document.getElementById('messageInput');
    let sendButton = document.getElementById('sendButton');

    // Scroll to bottom
    function scrollToBottom() {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    scrollToBottom();

    // Send message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let message = messageInput.value.trim();
        if (!message) return;

        // Disable input and button while sending
        messageInput.disabled = true;
        sendButton.disabled = true;

        fetch('{{ route("messages.send", $conversation) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to container
                let messageHtml = `
                    <div class="message-wrapper sent" id="message-${data.message.id}">
                        <div class="message-bubble sent">
                            <div class="message-content">
                                <p>${escapeHtml(message)}</p>
                            </div>
                            <div class="message-meta">
                                <span class="message-time">Just now</span>
                                <i class="fas fa-check read-receipt"></i>
                            </div>
                        </div>
                    </div>
                `;
                messageContainer.insertAdjacentHTML('beforeend', messageHtml);
                messageInput.value = '';
                scrollToBottom();
                lastMessageId = data.message.id;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Re-enable input and button
            messageInput.disabled = false;
            sendButton.disabled = false;
            messageInput.focus();
        });
    });

    // Poll for new messages
    let pollInterval = setInterval(function() {
        fetch(`{{ route("api.messages.poll") }}?conversation_id=${conversationId}&last_message_id=${lastMessageId}`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(messages => {
            messages.forEach(message => {
                let messageHtml = `
                    <div class="message-wrapper received">
                        <div class="message-avatar-small has-image">
                            <img src="{{ asset('storage/') }}/${message.user.profile_photo}" 
                                 alt="${message.user.name}" 
                                 class="avatar-image"
                                 onerror="this.style.display='none'; this.parentElement.classList.remove('has-image'); this.parentElement.innerHTML='<div class=\'avatar-initial\' style=\'background: linear-gradient(135deg, var(--primary), var(--primary-light))\'>${getInitials(message.user.name)}</div>'">
                        </div>
                        <div class="message-bubble received">
                            <div class="message-content">
                                <p>${escapeHtml(message.content)}</p>
                            </div>
                            <div class="message-meta">
                                <span class="message-time">${message.time}</span>
                            </div>
                        </div>
                    </div>
                `;
                messageContainer.insertAdjacentHTML('beforeend', messageHtml);
                lastMessageId = Math.max(lastMessageId, message.id);
                scrollToBottom();
            });
        });
    }, 3000);

    // Get initials helper
    function getInitials(name) {
        return name.charAt(0).toUpperCase();
    }

    // Escape HTML to prevent XSS
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Clean up interval when leaving page
    window.addEventListener('beforeunload', function() {
        clearInterval(pollInterval);
    });

    // Focus input on load
    messageInput.focus();

    // Handle Enter key (allow shift+enter for new line)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            messageForm.dispatchEvent(new Event('submit'));
        }
    });

    // Mark messages as read when conversation is active
    function markAsRead() {
        fetch('{{ route("api.messages.read", $conversation) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    }

    // Call when page loads
    markAsRead();

    // Call when tab becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            markAsRead();
        }
    });
</script>
@endpush
@endsection