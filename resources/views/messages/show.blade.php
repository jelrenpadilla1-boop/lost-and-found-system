@extends('layouts.app')

@section('title', 'Conversation with ' . $conversation->otherUser->name . ' - Foundify')

@section('content')
@php
    $isOnline = $conversation->otherUser->isOnline();
@endphp

<style>
/* ── NETFLIX-STYLE MESSAGES DETAIL PAGE ───────────────── */
:root {
    --netflix-red: #e50914;
    --netflix-red-dark: #b20710;
    --netflix-black: #141414;
    --netflix-dark: #0a0a0a;
    --netflix-card: #1a1a1a;
    --netflix-card-hover: #2a2a2a;
    --netflix-text: #ffffff;
    --netflix-text-secondary: #b3b3b3;
    --netflix-border: #333333;
    --netflix-success: #2e7d32;
    --netflix-warning: #f5c518;
    --netflix-info: #2196f3;
    --netflix-error: #e50914;
    --transition-netflix: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

/* Light Mode Overrides */
body.light {
    --netflix-black: #f5f5f5;
    --netflix-dark: #ffffff;
    --netflix-card: #ffffff;
    --netflix-card-hover: #f8f8f8;
    --netflix-text: #1a1a1a;
    --netflix-text-secondary: #666666;
    --netflix-border: #e0e0e0;
}

/* Chat Wrapper */
.chat-wrapper {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    background: var(--netflix-black);
    min-height: calc(100vh - 100px);
}

/* Chat Container */
.chat-container {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

/* Chat Header */
.chat-header {
    padding: 16px 24px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.back-button {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--netflix-text-secondary);
    text-decoration: none;
    transition: var(--transition-netflix);
}

.back-button:hover {
    background: rgba(229, 9, 20, 0.1);
    color: var(--netflix-red);
    border-color: var(--netflix-red);
    transform: translateX(-3px);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 14px;
}

.user-avatar-wrapper {
    position: relative;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--netflix-red);
    color: white;
    font-weight: 700;
    font-size: 20px;
    overflow: hidden;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--netflix-red);
}

.online-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: var(--netflix-success);
    border: 2px solid var(--netflix-card);
    border-radius: 50%;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0 0 4px 0;
}

.user-status {
    font-size: 12px;
    font-weight: 500;
}

.user-status.online {
    color: var(--netflix-success);
}

.user-status.offline {
    color: var(--netflix-text-secondary);
}

.header-actions {
    display: flex;
    gap: 8px;
}

.action-button {
    width: 38px;
    height: 38px;
    border-radius: 4px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-netflix);
}

.action-button:hover {
    background: rgba(229, 9, 20, 0.1);
    color: var(--netflix-red);
    border-color: var(--netflix-red);
}

/* Chat Main */
.chat-main {
    display: flex;
    flex: 1;
    min-height: 0;
    overflow: hidden;
}

/* Conversations Sidebar */
.conversations-sidebar {
    width: 320px;
    border-right: 1px solid var(--netflix-border);
    background: var(--netflix-card);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: var(--netflix-text);
    background: var(--netflix-dark);
}

.sidebar-header i {
    color: var(--netflix-red);
}

.conversations-count {
    margin-left: auto;
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
}

.conversations-list::-webkit-scrollbar {
    width: 6px;
}

.conversations-list::-webkit-scrollbar-track {
    background: var(--netflix-dark);
}

.conversations-list::-webkit-scrollbar-thumb {
    background: var(--netflix-border);
    border-radius: 3px;
}

.conversations-list::-webkit-scrollbar-thumb:hover {
    background: var(--netflix-red);
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
    padding: 12px;
    border-radius: 8px;
    transition: var(--transition-netflix);
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid transparent;
}

.conversation-card:hover {
    background: rgba(229, 9, 20, 0.05);
    border-color: var(--netflix-border);
    transform: translateX(4px);
}

.conversation-card.active {
    background: rgba(229, 9, 20, 0.1);
    border-color: rgba(229, 9, 20, 0.3);
}

.conversation-card.unread {
    background: rgba(229, 9, 20, 0.08);
}

.card-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-circle {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--netflix-red);
    color: white;
    font-weight: 600;
    font-size: 16px;
    overflow: hidden;
}

.avatar-circle .avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-circle .avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: var(--netflix-success);
    border: 2px solid var(--netflix-card);
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
    color: var(--netflix-text);
    margin: 0;
}

.card-time {
    font-size: 10px;
    color: var(--netflix-text-secondary);
}

.card-preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}

.card-preview {
    font-size: 11px;
    color: var(--netflix-text-secondary);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-prefix {
    color: var(--netflix-red);
    font-size: 10px;
    font-weight: 500;
}

.unread-badge {
    background: var(--netflix-red);
    color: white;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 4px;
    min-width: 20px;
    text-align: center;
}

/* Chat Area */
.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: var(--netflix-card);
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.messages-container::-webkit-scrollbar {
    width: 6px;
}

.messages-container::-webkit-scrollbar-track {
    background: var(--netflix-dark);
}

.messages-container::-webkit-scrollbar-thumb {
    background: var(--netflix-border);
    border-radius: 3px;
}

/* Message Styles */
.message-wrapper {
    display: flex;
    align-items: flex-end;
    gap: 10px;
}

.message-wrapper.sent {
    justify-content: flex-end;
}

.message-avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: var(--netflix-red);
    color: white;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.message-avatar-small .avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-avatar-small .avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--netflix-red);
}

.message-bubble {
    max-width: 65%;
    position: relative;
}

.message-bubble.sent {
    margin-left: auto;
}

.message-content {
    padding: 10px 14px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
}

.message-bubble.sent .message-content {
    background: var(--netflix-red);
    border: none;
    color: white;
}

.message-content p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
    color: var(--netflix-text);
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

.message-time {
    font-size: 10px;
    color: var(--netflix-text-secondary);
}

.message-bubble.sent .message-time {
    color: rgba(255, 255, 255, 0.7);
}

.read-receipt {
    font-size: 10px;
    color: var(--netflix-text-secondary);
}

.read-receipt.read {
    color: var(--netflix-red);
}

.message-bubble.sent .read-receipt {
    color: rgba(255, 255, 255, 0.7);
}

.message-bubble.sent .read-receipt.read {
    color: white;
}

/* Message Input */
.message-input-container {
    padding: 16px 24px;
    background: var(--netflix-dark);
    border-top: 1px solid var(--netflix-border);
    position: relative;
}

.input-form {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.05);
    padding: 6px 6px 6px 18px;
    border-radius: 4px;
    border: 1px solid var(--netflix-border);
    transition: var(--transition-netflix);
}

.input-form:focus-within {
    border-color: var(--netflix-red);
}

.attach-button {
    background: none;
    border: none;
    color: var(--netflix-text-secondary);
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: var(--transition-netflix);
    font-size: 16px;
}

.attach-button:hover {
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
}

.message-input {
    flex: 1;
    border: none;
    background: none;
    padding: 12px 0;
    font-size: 14px;
    color: var(--netflix-text);
    outline: none;
}

.message-input::placeholder {
    color: var(--netflix-text-secondary);
}

.send-button {
    background: var(--netflix-red);
    border: none;
    color: white;
    width: 42px;
    height: 42px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-netflix);
    font-size: 16px;
}

.send-button:hover {
    background: var(--netflix-red-dark);
    transform: scale(1.02);
}

.send-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Typing Indicator */
.typing-indicator {
    position: absolute;
    bottom: 85px;
    left: 30px;
    display: flex;
    gap: 4px;
    padding: 8px 12px;
    background: var(--netflix-card);
    border-radius: 4px;
    border: 1px solid var(--netflix-border);
    width: fit-content;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: var(--netflix-red);
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

.message-wrapper {
    animation: fadeIn 0.3s ease forwards;
}

/* Responsive */
@media (max-width: 992px) {
    .chat-main {
        flex-direction: column;
    }
    
    .conversations-sidebar {
        width: 100%;
        max-height: 300px;
        border-right: none;
        border-bottom: 1px solid var(--netflix-border);
    }
    
    .message-bubble {
        max-width: 80%;
    }
    
    .typing-indicator {
        bottom: 75px;
        left: 20px;
    }
    
    .chat-wrapper {
        padding: 16px;
    }
}

@media (max-width: 768px) {
    .chat-header {
        padding: 12px 16px;
    }
    
    .user-name {
        font-size: 16px;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .messages-container {
        padding: 16px;
    }
    
    .message-bubble {
        max-width: 85%;
    }
    
    .message-input-container {
        padding: 12px 16px;
    }
    
    .back-button {
        width: 36px;
        height: 36px;
    }
    
    .header-actions {
        display: none;
    }
}
</style>

<div class="chat-wrapper">
    <div class="chat-container">
        {{-- Chat Header --}}
        <div class="chat-header">
            <div class="header-left">
                <a href="{{ route('messages.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="user-info">
                    <div class="user-avatar-wrapper">
                        <div class="user-avatar">
                            @if($conversation->otherUser->profile_photo)
                                <img src="{{ asset('storage/' . $conversation->otherUser->profile_photo) }}" 
                                     alt="{{ $conversation->otherUser->name }}" 
                                     class="avatar-image">
                            @else
                                <div class="avatar-initial">
                                    {{ strtoupper(substr($conversation->otherUser->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        @if($isOnline)
                            <span class="online-indicator"></span>
                        @endif
                    </div>
                    <div class="user-details">
                        <h2 class="user-name">{{ $conversation->otherUser->name }}</h2>
                        <span class="user-status {{ $isOnline ? 'online' : 'offline' }}">
                            {{ $isOnline ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <button class="action-button" onclick="window.location.href='{{ route('messages.index') }}'">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="action-button" onclick="window.location.href='{{ route('messages.index') }}'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Chat Main --}}
        <div class="chat-main">
            {{-- Conversations Sidebar --}}
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
                            $lastMessage = $conv->messages()->latest()->first();
                        @endphp
                        
                        <a href="{{ route('messages.show', $conv) }}" class="conversation-link">
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
                                        <span class="card-time">{{ $conv->updated_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <div class="card-preview-row">
                                        <p class="card-preview">
                                            @if($lastMessage)
                                                @if($lastMessage->user_id === Auth::id())
                                                    <span class="preview-prefix">You: </span>
                                                @endif
                                                {{ Str::limit($lastMessage->content, 30) }}
                                            @else
                                                <span class="preview-placeholder">No messages yet</span>
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

            {{-- Chat Area --}}
            <div class="chat-area">
                <div class="messages-container" id="messageContainer">
                    @foreach($messages as $message)
                        <div class="message-wrapper {{ $message->user_id === Auth::id() ? 'sent' : 'received' }}" id="message-{{ $message->id }}">
                            @if($message->user_id !== Auth::id())
                                <div class="message-avatar-small">
                                    @if($message->user->profile_photo)
                                        <img src="{{ asset('storage/' . $message->user->profile_photo) }}" 
                                             alt="{{ $message->user->name }}" 
                                             class="avatar-image">
                                    @else
                                        <div class="avatar-initial">
                                            {{ strtoupper(substr($message->user->name, 0, 1)) }}
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

                {{-- Message Input --}}
                <div class="message-input-container">
                    <form id="messageForm" class="input-form">
                        @csrf
                        <button type="button" class="attach-button" disabled>
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <input type="text" class="message-input" id="messageInput" placeholder="Type your message..." autocomplete="off">
                        <button type="submit" class="send-button" id="sendButton">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    <div id="typingIndicator" class="typing-indicator" style="display: none;">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let lastMessageId = {{ $messages->last()->id ?? 0 }};
let conversationId = {{ $conversation->id }};
let userId = {{ Auth::id() }};
let messageContainer = document.getElementById('messageContainer');
let messageForm = document.getElementById('messageForm');
let messageInput = document.getElementById('messageInput');
let sendButton = document.getElementById('sendButton');

// Scroll to bottom
function scrollToBottom() {
    messageContainer.scrollTop = messageContainer.scrollHeight;
}
scrollToBottom();

// Escape HTML
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Send message
messageForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    let message = messageInput.value.trim();
    if (!message) return;

    messageInput.disabled = true;
    sendButton.disabled = true;

    let tempId = 'temp-' + Date.now();
    let optimisticMessage = `
        <div class="message-wrapper sent" id="message-${tempId}">
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
    messageContainer.insertAdjacentHTML('beforeend', optimisticMessage);
    messageInput.value = '';
    scrollToBottom();

    fetch('{{ route("messages.send", $conversation) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let tempElement = document.getElementById(`message-${tempId}`);
            if (tempElement) {
                tempElement.id = `message-${data.message.id}`;
                if (data.message.id > lastMessageId) {
                    lastMessageId = data.message.id;
                }
            }
            markAsRead();
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        let tempElement = document.getElementById(`message-${tempId}`);
        if (tempElement) tempElement.remove();
        alert('Failed to send message. Please try again.');
    })
    .finally(() => {
        messageInput.disabled = false;
        sendButton.disabled = false;
        messageInput.focus();
    });
});

// Poll for new messages
function pollNewMessages() {
    fetch('{{ route("api.messages.poll") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            conversation_id: conversationId,
            last_message_id: lastMessageId
        })
    })
    .then(response => response.json())
    .then(messages => {
        if (messages && messages.length > 0) {
            messages.forEach(message => {
                if (!document.getElementById(`message-${message.id}`)) {
                    let avatarHtml = '';
                    if (!message.is_mine) {
                        let initial = message.user ? message.user.name.charAt(0).toUpperCase() : 'U';
                        avatarHtml = `
                            <div class="message-avatar-small">
                                <div class="avatar-initial">${initial}</div>
                            </div>
                        `;
                    }
                    let messageHtml = `
                        <div class="message-wrapper ${message.is_mine ? 'sent' : 'received'}" id="message-${message.id}">
                            ${!message.is_mine ? avatarHtml : ''}
                            <div class="message-bubble ${message.is_mine ? 'sent' : 'received'}">
                                <div class="message-content">
                                    <p>${escapeHtml(message.content)}</p>
                                </div>
                                <div class="message-meta">
                                    <span class="message-time">${message.time || 'Just now'}</span>
                                    ${message.is_mine ? '<i class="fas fa-check read-receipt"></i>' : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    messageContainer.insertAdjacentHTML('beforeend', messageHtml);
                    if (message.id > lastMessageId) lastMessageId = message.id;
                }
            });
            scrollToBottom();
            markAsRead();
        }
    })
    .catch(error => console.error('Polling error:', error));
}

let pollInterval = setInterval(pollNewMessages, 2000);

window.addEventListener('beforeunload', function() {
    clearInterval(pollInterval);
});

messageInput.focus();

messageInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        messageForm.dispatchEvent(new Event('submit'));
    }
});

function markAsRead() {
    fetch('{{ route("api.messages.read", $conversation) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).catch(error => console.error('Error marking as read:', error));
}

markAsRead();

document.addEventListener('visibilitychange', function() {
    if (!document.hidden) markAsRead();
});
</script>
@endpush
@endsection