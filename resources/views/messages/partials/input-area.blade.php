<div class="chat-input-area">
    <form id="messageForm">
        <div class="input-wrapper">
            <textarea class="message-input" 
                      placeholder="Type your message..." 
                      rows="1"
                      id="messageInput"></textarea>
            <div class="input-actions">
                <button type="button" class="input-action-btn" onclick="Messages.attachFile()">
                    <i class="fas fa-paperclip"></i>
                </button>
                <button type="button" class="input-action-btn" onclick="Messages.addEmoji()">
                    <i class="fas fa-smile"></i>
                </button>
            </div>
            <button type="submit" class="send-btn" id="sendBtn" disabled>
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</div>