// Messages Module
const Messages = {
    // Initialize
    init: function() {
        this.bindEvents();
        this.initializeElements();
    },

    // Bind events
    bindEvents: function() {
        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', this.handleFilterClick.bind(this));
        });

        // Search input
        const searchInput = document.getElementById('searchConversations');
        if (searchInput) {
            searchInput.addEventListener('input', this.handleSearch.bind(this));
        }

        // Message form
        const messageForm = document.getElementById('messageForm');
        if (messageForm) {
            messageForm.addEventListener('submit', this.handleSendMessage.bind(this));
        }

        // Message input
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.addEventListener('input', this.handleMessageInput.bind(this));
        }
    },

    // Initialize elements
    initializeElements: function() {
        this.scrollToBottom();
        
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            this.autoResize(messageInput);
        }

        // Add fade-in animations
        document.querySelectorAll('.message-wrapper').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.05}s`;
        });
    },

    // Handle filter click
    handleFilterClick: function(e) {
        const btn = e.currentTarget;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        this.filterConversations(btn.dataset.filter);
    },

    // Filter conversations
    filterConversations: function(filter) {
        const conversations = document.querySelectorAll('.conversation-item');
        
        conversations.forEach(conv => {
            switch(filter) {
                case 'all':
                    conv.style.display = 'flex';
                    break;
                case 'unread':
                    conv.style.display = conv.classList.contains('unread') ? 'flex' : 'none';
                    break;
            }
        });
    },

    // Handle search
    handleSearch: function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const conversations = document.querySelectorAll('.conversation-item');
        
        conversations.forEach(conv => {
            const name = conv.querySelector('.conversation-name').textContent.toLowerCase();
            const preview = conv.querySelector('.conversation-preview').textContent.toLowerCase();
            
            conv.style.display = (name.includes(searchTerm) || preview.includes(searchTerm)) ? 'flex' : 'none';
        });
    },

    // Handle send message
    handleSendMessage: function(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        this.addMessageToChat(message, 'sent');
        input.value = '';
        this.autoResize(input);
        this.scrollToBottom();
    },

    // Handle message input
    handleMessageInput: function(e) {
        this.autoResize(e.target);
        const sendBtn = document.getElementById('sendBtn');
        if (sendBtn) {
            sendBtn.disabled = !e.target.value.trim();
        }
    },

    // Auto resize textarea
    autoResize: function(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    },

    // Add message to chat
    addMessageToChat: function(content, type) {
        const messagesArea = document.getElementById('messagesArea');
        if (!messagesArea) return;

        const now = new Date();
        const timeString = now.getHours() + ':' + now.getMinutes().toString().padStart(2, '0') + ' ' + 
                          (now.getHours() >= 12 ? 'PM' : 'AM');
        
        const avatarHtml = type === 'received' ? 
            `<div class="message-avatar">${document.querySelector('.chat-avatar')?.innerHTML || 'U'}</div>` : '';
        
        const messageHtml = `
            <div class="message-wrapper ${type} fade-in">
                ${avatarHtml}
                <div class="message-content">
                    <div class="message-bubble">${this.escapeHtml(content)}</div>
                    <div class="message-time">${timeString}</div>
                </div>
            </div>
        `;
        
        messagesArea.insertAdjacentHTML('beforeend', messageHtml);
    },

    // Escape HTML to prevent XSS
    escapeHtml: function(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    // Scroll to bottom
    scrollToBottom: function() {
        const messagesArea = document.getElementById('messagesArea');
        if (messagesArea) {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }
    },

    // Load conversation
    loadConversation: function(conversationId) {
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('active');
        });
        const selected = document.querySelector(`[data-conversation-id="${conversationId}"]`);
        if (selected) {
            selected.classList.add('active');
        }
        window.location.href = `/messages/${conversationId}`;
    },

    // Toggle sidebar on mobile
    toggleSidebar: function() {
        const sidebar = document.getElementById('messagesSidebar');
        if (sidebar) {
            sidebar.classList.toggle('show');
        }
    },

    // Open new message modal
    openNewMessageModal: function() {
        const modal = document.getElementById('newMessageModal');
        if (modal) {
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
    },

    // Send new message
    sendNewMessage: function() {
        const recipient = document.getElementById('recipientSelect');
        const content = document.getElementById('newMessageContent');
        const attachment = document.getElementById('newMessageAttachment');
        
        if (!recipient?.value || !content?.value) {
            this.showToast('Please select a recipient and enter a message', 'warning');
            return;
        }
        
        this.showToast('Message sent successfully!', 'success');
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('newMessageModal'));
        if (modal) modal.hide();
        
        setTimeout(() => {
            window.location.href = `/messages/${recipient.value}`;
        }, 1000);
    },

    // Attach file
    attachFile: function() {
        const input = document.createElement('input');
        input.type = 'file';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                this.showToast(`Selected: ${file.name}`, 'info');
            }
        };
        input.click();
    },

    // Add emoji
    addEmoji: function() {
        const input = document.getElementById('messageInput');
        if (input) {
            input.value += ' 😊';
            input.focus();
            this.autoResize(input);
        }
    },

    // Show toast notification
    showToast: function(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} fade-in`;
        toast.style.cssText = `
            margin-bottom: 8px;
            min-width: 280px;
            box-shadow: var(--shadow-lg);
        `;
        
        const icon = type === 'success' ? 'check-circle' : 
                    type === 'warning' ? 'exclamation-triangle' : 'info-circle';
        
        toast.innerHTML = `<i class="fas fa-${icon}"></i><span>${message}</span>`;
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => Messages.init());