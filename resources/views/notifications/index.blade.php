@extends('layouts.app')

@section('title', 'Notifications – Foundify')

@section('content')
<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-dim);
    }

    .page-title h1 {
        font-family: var(--ff-display);
        font-size: 28px;
        font-weight: 800;
        color: var(--white);
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.02em;
    }

    .page-title h1 i {
        color: var(--teal);
        font-size: 24px;
        filter: drop-shadow(0 0 8px var(--teal-glow));
    }

    .page-title p {
        font-family: var(--ff-body);
        font-size: 15px;
        color: var(--subtle);
        margin: 0;
        font-weight: 300;
    }

    .page-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        font-family: var(--ff-mono);
        font-size: 11px;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 6px;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--teal);
        color: var(--void);
        border-color: var(--teal);
    }

    .btn-primary:hover {
        background: var(--teal-dim);
        border-color: var(--teal-dim);
        box-shadow: 0 0 24px var(--teal-glow);
        transform: translateY(-2px);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border-dim);
        color: var(--subtle);
    }

    .btn-outline:hover {
        border-color: var(--teal);
        color: var(--teal);
        background: var(--teal-faint);
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 10px;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Notifications List */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notif-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 20px 24px;
        background: var(--surface);
        border: 1px solid var(--border-dim);
        border-radius: 12px;
        transition: var(--transition);
        backdrop-filter: blur(12px);
        position: relative;
        overflow: hidden;
    }

    .notif-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--teal);
        opacity: 0;
        transition: var(--transition);
    }

    .notif-item.unread::before {
        opacity: 1;
    }

    .notif-item:hover {
        border-color: var(--border);
        transform: translateX(6px);
        box-shadow: 0 0 25px var(--teal-glow);
    }

    .notif-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background: var(--glass);
        border: 1px solid var(--border-dim);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: var(--transition);
    }

    .notif-item:hover .notif-icon {
        transform: scale(1.1);
    }

    .notif-body {
        flex: 1;
        min-width: 0;
    }

    .notif-title {
        font-family: var(--ff-mono);
        font-size: 14px;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: var(--teal);
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 10px var(--teal-glow);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.2); }
    }

    .notif-message {
        font-family: var(--ff-body);
        font-size: 14px;
        color: var(--subtle);
        margin-bottom: 8px;
        line-height: 1.5;
        font-weight: 300;
    }

    .notif-time {
        font-family: var(--ff-mono);
        font-size: 10px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .notif-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-shrink: 0;
    }

    /* Empty State */
    .notif-empty {
        text-align: center;
        padding: 100px 20px;
    }

    .notif-empty i {
        font-size: 60px;
        color: var(--border);
        margin-bottom: 20px;
        display: block;
    }

    .notif-empty p {
        font-family: var(--ff-mono);
        font-size: 16px;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 8px;
    }

    .notif-empty span {
        font-size: 14px;
        color: var(--muted);
    }

    /* Toast Notifications */
    #notificationsContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        background: var(--surface);
        border: 1px solid var(--border-dim);
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: slideIn 0.3s ease forwards;
        max-width: 350px;
    }

    .toast-body {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toast-body i {
        font-size: 18px;
    }

    .toast-body span {
        flex: 1;
        font-family: var(--ff-mono);
        font-size: 12px;
        color: var(--white);
    }

    .toast-close {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .toast-close:hover {
        color: var(--white);
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to   { transform: translateX(0);    opacity: 1; }
    }

    @keyframes slideOut {
        from { transform: translateX(0);    opacity: 1; }
        to   { transform: translateX(100%); opacity: 0; }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0);   }
        to   { opacity: 0; transform: translateX(20px); }
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 40px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
        list-style: none;
        padding: 0;
    }

    .pagination .page-item {
        display: inline-block;
    }

    .pagination .page-item .page-link {
        background: var(--glass);
        border: 1px solid var(--border-dim);
        color: var(--subtle);
        font-family: var(--ff-mono);
        font-size: 12px;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination .page-item.active .page-link {
        background: var(--teal);
        border-color: var(--teal);
        color: var(--void);
        box-shadow: 0 0 15px var(--teal-glow);
    }

    .pagination .page-item .page-link:hover {
        border-color: var(--teal);
        color: var(--teal);
        background: var(--teal-faint);
        transform: translateY(-2px);
    }

    /* Loading State */
    .loading {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-actions {
            width: 100%;
        }

        .page-actions .btn {
            flex: 1;
        }

        .notif-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 16px;
        }

        .notif-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }

    @media (max-width: 576px) {
        .notif-actions {
            flex-direction: column;
        }

        .notif-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-bell"></i>
                NOTIFICATIONS
            </h1>
            <p>Your alerts and system updates</p>
        </div>
        <div class="page-actions">
            @if(isset($unreadCount) && $unreadCount > 0)
                <button class="btn btn-outline" onclick="window.markAllAsRead()">
                    <i class="fas fa-check-double"></i> MARK ALL READ
                </button>
                <button class="btn btn-outline" onclick="window.clearAllNotifications()">
                    <i class="fas fa-trash"></i> CLEAR ALL
                </button>
            @endif
        </div>
    </div>

    <!-- Debug info - Remove in production -->
    @if(app()->environment('local') && config('app.debug'))
        <div style="background: #1a1a1a; padding: 10px 15px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid var(--teal); color: #fff; font-family: monospace; font-size: 12px;">
            <strong>🔍 Debug:</strong> 
            Total notifications: {{ $notifications->total() ?? 0 }} | 
            Current page: {{ $notifications->currentPage() ?? 1 }} | 
            Unread count: {{ $unreadCount ?? 0 }}
        </div>
    @endif

    <!-- Notifications List -->
    <div class="notif-list fade-in">
        @forelse($notifications as $notification)
            @php
                // Get icon data - either from the accessor or use defaults
                $iconData = method_exists($notification, 'getIconDataAttribute') 
                    ? $notification->icon_data 
                    : (isset($notification->data['icon']) ? $notification->data['icon'] : []);
                
                $icon = $iconData['icon'] ?? 'bell';
                $color = $iconData['color'] ?? '#64ffda';
            @endphp

            <div class="notif-item {{ !$notification->is_read ? 'unread' : '' }}"
                 data-id="{{ $notification->id }}">

                <div class="notif-icon">
                    <i class="fas fa-{{ $icon }}" style="color: {{ $color }};"></i>
                </div>

                <div class="notif-body">
                    <div class="notif-title">
                        {{ $notification->title }}
                        @if(!$notification->is_read)
                            <span class="unread-dot"></span>
                        @endif
                    </div>
                    <div class="notif-message">{{ $notification->body }}</div>
                    <div class="notif-time">
                        <i class="fas fa-clock"></i>
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="notif-actions">
                    @if($notification->url)
                        <a href="{{ $notification->url }}"
                           class="btn btn-primary btn-sm"
                           onclick="window.markRead('{{ $notification->id }}', event)">
                            <i class="fas fa-eye"></i> VIEW
                        </a>
                    @endif

                    @if(!$notification->is_read)
                        <button class="btn btn-outline btn-sm mark-read-btn"
                                onclick="window.markRead('{{ $notification->id }}', event, this)">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif

                    <button class="btn btn-outline btn-sm"
                            onclick="window.deleteNotification('{{ $notification->id }}', event, this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="notif-empty">
                <i class="fas fa-bell-slash"></i>
                <p>NO NOTIFICATIONS</p>
                <span>You're all caught up!</span>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($notifications) && method_exists($notifications, 'links') && $notifications->hasPages())
        <div class="pagination-wrapper">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<!-- Toast Container -->
<div id="notificationsContainer"></div>

@push('scripts')
<script>
(function() {
    'use strict';

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Notifications page loaded');
        updateNotificationBadge();
    });

    /**
     * Mark single notification as read
     */
    window.markRead = async function(id, event, btn = null) {
        if (event) event.preventDefault();
        console.log('Marking notification as read:', id);

        if (btn) {
            btn.disabled = true;
            btn.classList.add('loading');
        }

        try {
            const response = await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: buildHeaders()
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to mark as read');
            }

            // Update UI
            const item = document.querySelector(`.notif-item[data-id="${id}"]`);
            if (item) {
                item.classList.remove('unread');
                
                // Remove unread dot
                const dot = item.querySelector('.unread-dot');
                if (dot) dot.remove();
                
                // Remove mark read button
                const markBtn = item.querySelector('.mark-read-btn');
                if (markBtn) markBtn.remove();
            }

            // Update notification badge
            await updateNotificationBadge();
            showToast('Notification marked as read', 'success');

        } catch (error) {
            console.error('Failed to mark as read:', error);
            showToast(error.message || 'Failed to mark as read', 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('loading');
            }
        }
    };

    /**
     * Mark all notifications as read
     */
    window.markAllAsRead = async function() {
        console.log('Marking all notifications as read');

        if (!confirm('Mark all notifications as read?')) {
            return;
        }

        // Disable all buttons
        const buttons = document.querySelectorAll('.page-actions .btn');
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('loading');
        });

        try {
            const response = await fetch('/notifications/read-all', {
                method: 'POST',
                headers: buildHeaders()
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to mark all as read');
            }

            // Update all items
            document.querySelectorAll('.notif-item.unread').forEach(item => {
                item.classList.remove('unread');
                
                // Remove unread dots
                const dot = item.querySelector('.unread-dot');
                if (dot) dot.remove();
                
                // Remove mark read buttons
                const markBtn = item.querySelector('.mark-read-btn');
                if (markBtn) markBtn.remove();
            });

            // Remove action buttons
            const pageActions = document.querySelector('.page-actions');
            if (pageActions) {
                pageActions.innerHTML = '';
            }

            // Update notification badge
            await updateNotificationBadge();
            showToast('All notifications marked as read', 'success');

        } catch (error) {
            console.error('Failed to mark all as read:', error);
            showToast(error.message || 'Failed to mark all as read', 'error');
        } finally {
            // Re-enable buttons
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('loading');
            });
        }
    };

    /**
     * Delete single notification
     */
    window.deleteNotification = async function(id, event, btn) {
        if (event) event.preventDefault();
        console.log('Deleting notification:', id);

        if (!confirm('Delete this notification?')) {
            return;
        }

        if (btn) {
            btn.disabled = true;
            btn.classList.add('loading');
        }

        try {
            const response = await fetch(`/notifications/${id}/delete`, {
                method: 'DELETE',
                headers: buildHeaders()
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to delete');
            }

            // Animate and remove item
            const item = document.querySelector(`.notif-item[data-id="${id}"]`);
            if (item) {
                item.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => {
                    item.remove();
                    checkEmptyState();
                }, 300);
            }

            // Update notification badge
            await updateNotificationBadge();
            showToast('Notification deleted', 'success');

        } catch (error) {
            console.error('Failed to delete:', error);
            showToast(error.message || 'Failed to delete', 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('loading');
            }
        }
    };

    /**
     * Clear all notifications
     */
    window.clearAllNotifications = async function() {
        console.log('Clearing all notifications');

        if (!confirm('Clear all notifications? This cannot be undone.')) {
            return;
        }

        // Disable all buttons
        const buttons = document.querySelectorAll('.page-actions .btn, .notif-actions .btn');
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('loading');
        });

        try {
            const response = await fetch('/notifications/clear-all', {
                method: 'DELETE',
                headers: buildHeaders()
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to clear all');
            }

            // Clear notifications list
            const container = document.querySelector('.notif-list');
            if (container) {
                container.innerHTML = emptyStateHtml();
            }

            // Remove action buttons
            const pageActions = document.querySelector('.page-actions');
            if (pageActions) {
                pageActions.innerHTML = '';
            }

            // Update notification badge
            await updateNotificationBadge();
            showToast('All notifications cleared', 'success');

        } catch (error) {
            console.error('Failed to clear all:', error);
            showToast(error.message || 'Failed to clear all', 'error');
        } finally {
            // Re-enable buttons
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('loading');
            });
        }
    };

    /**
     * Update notification badge in header
     */
    window.updateNotificationBadge = async function() {
        try {
            const response = await fetch('/notifications/unread-count', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to get count');
            }

            const badge = document.querySelector('.notification-badge');
            const count = data.count || 0;

            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                } else {
                    // Try to find notification button and add badge
                    const notifyBtn = document.getElementById('notificationBtn') || 
                                    document.querySelector('[data-notification-btn]');
                    if (notifyBtn) {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'notification-badge';
                        newBadge.textContent = count;
                        newBadge.style.cssText = 'position:absolute;top:-5px;right:-5px;background:#00f0c8;color:#050811;border-radius:50%;padding:2px 6px;font-size:10px;font-weight:bold;';
                        
                        // Make sure parent has position relative
                        if (getComputedStyle(notifyBtn).position === 'static') {
                            notifyBtn.style.position = 'relative';
                        }
                        
                        notifyBtn.appendChild(newBadge);
                    }
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        } catch (error) {
            console.error('Failed to update badge:', error);
        }
    };

    /**
     * Check if notifications list is empty
     */
    function checkEmptyState() {
        const container = document.querySelector('.notif-list');
        const items = container?.querySelectorAll('.notif-item');
        
        if (container && (!items || items.length === 0)) {
            container.innerHTML = emptyStateHtml();
        }
    }

    /**
     * Empty state HTML
     */
    function emptyStateHtml() {
        return `
            <div class="notif-empty">
                <i class="fas fa-bell-slash"></i>
                <p>NO NOTIFICATIONS</p>
                <span>You're all caught up!</span>
            </div>
        `;
    }

    /**
     * Show toast notification
     */
    window.showToast = function(message, type = 'info') {
        // Create container if it doesn't exist
        let container = document.getElementById('notificationsContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notificationsContainer';
            container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;';
            document.body.appendChild(container);
        }

        const icon = type === 'success' ? 'check-circle' : 
                    type === 'error' ? 'exclamation-circle' : 
                    'info-circle';
        
        const color = type === 'success' ? '#22d37a' : 
                     type === 'error' ? '#ff4d6a' : 
                     '#00f0c8';

        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <div class="toast-body">
                <i class="fas fa-${icon}" style="color: ${color};"></i>
                <span>${escapeHtml(message)}</span>
                <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
            </div>
        `;

        container.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 5000);
    };

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /**
     * Build headers for fetch requests
     */
    function buildHeaders() {
        return {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
    }

    /**
     * Get CSRF token from meta tag
     */
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * Initialize Echo for real-time notifications (if available)
     */
    function initializeEcho() {
        if (typeof window.Echo === 'undefined' || !window.Echo) {
            return;
        }

        const userId = {{ Auth::id() ?? 'null' }};
        
        if (!userId) return;

        console.log('Initializing Echo for user:', userId);

        // Listen for new notifications
        window.Echo.private(`notifications.${userId}`)
            .listen('.notification.sent', () => {
                console.log('New notification received');
                // Refresh the page to show new notification
                // Or you could update the UI dynamically
                location.reload();
            })
            .listen('.notification.read', (data) => {
                console.log('Notification marked as read:', data);
                const item = document.querySelector(`[data-id="${data.notification_id}"]`);
                if (item) {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.unread-dot');
                    if (dot) dot.remove();
                }
            })
            .listen('.notifications.all.read', () => {
                console.log('All notifications marked as read');
                document.querySelectorAll('.notif-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.unread-dot');
                    if (dot) dot.remove();
                });
            });
    }

    // Initialize Echo after DOM is loaded
    setTimeout(initializeEcho, 1000);

})();
</script>
@endpush
@endsection