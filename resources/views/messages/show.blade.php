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
                    <div class="user-avatar" style="background: linear-gradient(135deg, {{ '#' . substr(md5($conversation->otherUser->name), 0, 6) }}, {{ '#' . substr(md5($conversation->otherUser->name . 'salt'), 0, 6) }})">
                        {{ substr($conversation->otherUser->name, 0, 1) }}
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
                <i class="fas fa-comments"></i>
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
                            <div class="message-avatar-small" style="background: linear-gradient(135deg, {{ '#' . substr(md5($message->user->name), 0, 6) }}, {{ '#' . substr(md5($message->user->name . 'salt'), 0, 6) }})">
                                {{ substr($message->user->name, 0, 1) }}
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
/* Chat Wrapper */
.chat-wrapper {
    background: white;
    border-radius: 24px;
    border: 1px solid #f1f5f9;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    min-height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

/* Chat Header */
.chat-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: white;
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
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid #f1f5f9;
}

.back-button:hover {
    background: #3b82f6;
    color: white;
    transform: translateX(-3px);
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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.online-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 14px;
    height: 14px;
    background: #10b981;
    border: 3px solid white;
    border-radius: 50%;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px 0;
}

.user-status {
    font-size: 13px;
    font-weight: 500;
}

.user-status.online {
    color: #10b981;
}

.user-status.offline {
    color: #94a3b8;
}

.header-actions {
    display: flex;
    gap: 8px;
}

.action-button {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.action-button:hover {
    background: #e2e8f0;
    color: #334155;
}

.close-button:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
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
    border-right: 1px solid #f1f5f9;
    background: #fafbfc;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #0f172a;
}

.sidebar-header i {
    color: #3b82f6;
    font-size: 16px;
}

.conversations-count {
    margin-left: auto;
    background: #e2e8f0;
    color: #475569;
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
    background: #f1f5f9;
}

.conversations-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
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
    transition: all 0.2s ease;
    margin-bottom: 4px;
    background: white;
    border: 1px solid transparent;
}

.conversation-card:hover {
    background: #f8fafc;
    border-color: #e2e8f0;
    transform: translateX(4px);
}

.conversation-card.active {
    background: #f0f9ff;
    border-color: #3b82f6;
}

.conversation-card.unread {
    background: #fff7ed;
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
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #10b981;
    border: 2px solid white;
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
    margin-bottom: 4px;
}

.card-name {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
}

.card-time {
    font-size: 10px;
    color: #94a3b8;
}

.card-preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.card-preview {
    font-size: 12px;
    color: #64748b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-prefix {
    color: #94a3b8;
}

.unread-badge {
    background: #3b82f6;
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 30px;
    min-width: 20px;
    text-align: center;
}

/* Chat Area */
.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 30px;
    background: #f8fafc;
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
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    border: 1px solid #f1f5f9;
}

.message-bubble.sent .message-content {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border: none;
}

.message-content p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
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
    color: #94a3b8;
}

.message-bubble.sent .message-time {
    color: rgba(255, 255, 255, 0.7);
}

.read-receipt {
    font-size: 10px;
    color: #94a3b8;
}

.read-receipt.read {
    color: #3b82f6;
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
    background: white;
    border-top: 1px solid #f1f5f9;
}

.input-form {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f8fafc;
    padding: 8px 8px 8px 16px;
    border-radius: 30px;
    border: 2px solid #f1f5f9;
    transition: all 0.2s ease;
}

.input-form:focus-within {
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.attach-button {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.2s ease;
    font-size: 18px;
}

.attach-button:hover {
    color: #3b82f6;
    background: #e2e8f0;
}

.message-input {
    flex: 1;
    border: none;
    background: none;
    padding: 12px 0;
    font-size: 14px;
    color: #0f172a;
    outline: none;
}

.message-input::placeholder {
    color: #94a3b8;
}

.send-button {
    background: #3b82f6;
    border: none;
    color: white;
    width: 46px;
    height: 46px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 18px;
}

.send-button:hover {
    background: #2563eb;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
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
        border-bottom: 1px solid #f1f5f9;
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

/* Active Conversation Highlight */
.conversation-card.active .card-name {
    color: #3b82f6;
}

.conversation-card.unread .card-name {
    color: #ea580c;
    font-weight: 700;
}

/* Typing Indicator (Optional) */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
    background: white;
    border-radius: 20px;
    border: 1px solid #f1f5f9;
    width: fit-content;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #94a3b8;
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
                        <div class="message-avatar-small" style="background: linear-gradient(135deg, #3b82f6, #2563eb)">
                            ${getInitials('{{ $conversation->otherUser->name }}')}
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