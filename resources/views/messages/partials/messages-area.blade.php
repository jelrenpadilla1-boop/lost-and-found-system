<div class="messages-area" id="messagesArea">
    @foreach($messages as $message)
    <div class="message-date-divider">
        <span>{{ $message->created_at->format('F j, Y') }}</span>
    </div>

    <div class="message-wrapper {{ $message->sender_id == Auth::id() ? 'sent' : 'received' }}">
        @if($message->sender_id != Auth::id())
        <div class="message-avatar">
            @if($activeConversation->otherUser->profile_photo)
                <img src="{{ asset('storage/' . $activeConversation->otherUser->profile_photo) }}" 
                     alt="{{ $activeConversation->otherUser->name }}">
            @else
                {{ substr($activeConversation->otherUser->name, 0, 1) }}
            @endif
        </div>
        @endif
        <div class="message-content">
            <div class="message-bubble">{{ $message->content }}</div>
            <div class="message-time">{{ $message->created_at->format('g:i A') }}</div>
        </div>
    </div>
    @endforeach
</div>