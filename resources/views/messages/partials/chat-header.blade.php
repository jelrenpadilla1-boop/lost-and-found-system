<div class="chat-header">
    <div class="chat-user-info">
        <div class="chat-avatar">
            @if($activeConversation->otherUser->profile_photo)
                <img src="{{ asset('storage/' . $activeConversation->otherUser->profile_photo) }}" 
                     alt="{{ $activeConversation->otherUser->name }}">
            @else
                {{ substr($activeConversation->otherUser->name, 0, 1) }}
            @endif
        </div>
        <div class="chat-details">
            <h3>{{ $activeConversation->otherUser->name }}</h3>
            <div class="chat-status {{ $activeConversation->otherUser->isOnline() ? 'online' : '' }}">
                <i class="fas fa-circle"></i>
                {{ $activeConversation->otherUser->isOnline() ? 'Online' : 'Offline' }}
            </div>
        </div>
    </div>
    <div class="chat-actions">
        <button class="chat-action-btn" onclick="Messages.toggleSidebar()" title="Show conversations">
            <i class="fas fa-comments"></i>
        </button>
    </div>
</div>