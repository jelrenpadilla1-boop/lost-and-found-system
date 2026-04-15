@extends('layouts.app')

@section('title', 'User Details - Admin')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    $isOwner = Auth::id() === $user->id;
@endphp

<style>
/* ── NETFLIX-STYLE ADMIN USER DETAILS PAGE ───────────────── */
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

.dashboard-container {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--netflix-text);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title h1 i {
    color: var(--netflix-red);
    font-size: 28px;
}

.page-title p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin: 0;
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition-netflix);
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: var(--netflix-red);
    color: white;
}

.btn-primary:hover {
    background: var(--netflix-red-dark);
    transform: scale(1.02);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
}

.btn-outline:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
    transform: scale(1.02);
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 28px;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* Profile Card */
.profile-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition-netflix);
}

.profile-card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.profile-header {
    background: var(--netflix-dark);
    padding: 32px 24px;
    text-align: center;
    border-bottom: 1px solid var(--netflix-border);
}

.profile-avatar-wrapper {
    position: relative;
    width: fit-content;
    margin: 0 auto 20px;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--netflix-red);
    color: white;
    font-weight: 700;
    font-size: 40px;
    overflow: hidden;
    transition: var(--transition-netflix);
}

.profile-avatar:hover {
    transform: scale(1.02);
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

.profile-name {
    font-size: 24px;
    font-weight: 800;
    color: var(--netflix-text);
    margin-bottom: 12px;
}

.profile-badges {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.badge {
    padding: 6px 14px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge.admin {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.user {
    background: rgba(255, 255, 255, 0.05);
    color: var(--netflix-text-secondary);
    border: 1px solid var(--netflix-border);
}

.badge.status-active {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    background: var(--netflix-success);
    margin-right: 6px;
}

/* Profile Details */
.profile-details {
    padding: 20px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px;
    border-bottom: 1px solid var(--netflix-border);
    transition: var(--transition-netflix);
}

.detail-item:hover {
    background: rgba(229, 9, 20, 0.05);
    transform: translateX(4px);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-icon {
    width: 42px;
    height: 42px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--netflix-red);
    font-size: 18px;
    flex-shrink: 0;
    border: 1px solid var(--netflix-border);
}

.detail-content {
    flex: 1;
}

.detail-label {
    display: block;
    color: var(--netflix-text-secondary);
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.detail-value {
    color: var(--netflix-text);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
}

/* Profile Actions */
.profile-actions {
    padding: 20px;
    display: flex;
    gap: 12px;
    border-top: 1px solid var(--netflix-border);
}

.action-btn {
    flex: 1;
    padding: 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid;
    background: transparent;
    cursor: pointer;
    transition: var(--transition-netflix);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.action-btn.reset-password {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.action-btn.reset-password:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

.action-btn.delete {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.action-btn.delete:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

.action-form {
    flex: 1;
}

/* Stats Grid */
.stats-grid-small {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

@media (max-width: 768px) {
    .stats-grid-small {
        grid-template-columns: 1fr;
    }
}

.stat-card-small {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition-netflix);
}

.stat-card-small:hover {
    border-color: var(--netflix-red);
    transform: translateY(-2px);
}

.stat-card-small .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
    background: var(--netflix-red);
}

.stat-card-small .stat-content {
    flex: 1;
}

.stat-card-small .stat-value {
    font-size: 24px;
    font-weight: 800;
    color: var(--netflix-text);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-card-small .stat-label {
    font-size: 11px;
    color: var(--netflix-text-secondary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Activity Card */
.activity-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
}

.tabs-header {
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    padding: 0 20px;
}

.nav-tabs {
    display: flex;
    gap: 4px;
    border: none;
    margin: 0;
    list-style: none;
}

.nav-tabs .nav-item {
    margin: 0;
}

.nav-tabs .nav-link {
    color: var(--netflix-text-secondary);
    border: none;
    padding: 14px 20px;
    font-size: 13px;
    font-weight: 600;
    transition: var(--transition-netflix);
    background: transparent;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 0;
    margin: 0;
    position: relative;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.nav-tabs .nav-link i {
    font-size: 14px;
}

.nav-tabs .nav-link:hover {
    color: var(--netflix-red);
}

.nav-tabs .nav-link.active {
    color: var(--netflix-red);
}

.nav-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--netflix-red);
    border-radius: 2px;
}

.tab-content {
    padding: 20px;
}

/* Items List */
.items-list {
    min-height: 300px;
    max-height: 400px;
    overflow-y: auto;
}

.items-list::-webkit-scrollbar {
    width: 6px;
}

.items-list::-webkit-scrollbar-track {
    background: var(--netflix-dark);
}

.items-list::-webkit-scrollbar-thumb {
    background: var(--netflix-border);
    border-radius: 3px;
}

.items-list::-webkit-scrollbar-thumb:hover {
    background: var(--netflix-red);
}

.item-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition-netflix);
    margin-bottom: 4px;
}

.item-row:hover {
    background: rgba(229, 9, 20, 0.05);
    transform: translateX(4px);
}

.item-icon {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.item-icon.lost {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.item-icon.found {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
}

.item-icon.match {
    background: rgba(245, 197, 24, 0.15);
    color: var(--netflix-warning);
}

.item-info {
    flex: 1;
    min-width: 0;
}

.item-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0 0 4px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.item-meta span {
    color: var(--netflix-text-secondary);
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.arrow {
    color: var(--netflix-text-secondary);
    font-size: 14px;
    transition: var(--transition-netflix);
}

.item-row:hover .arrow {
    color: var(--netflix-red);
    transform: translateX(4px);
}

.score-badge {
    padding: 2px 10px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
}

.score-high {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.score-medium {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.score-low {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.status-pending {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.status-approved,
.status-confirmed {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.status-rejected {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: var(--netflix-text-secondary);
}

.empty-state i {
    font-size: 48px;
    color: var(--netflix-border);
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 14px;
    margin: 0;
}

/* Modal */
.modal-content {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
}

.modal-header {
    padding: 18px 24px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--netflix-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-title i {
    color: var(--netflix-red);
}

.modal-close {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    cursor: pointer;
    transition: var(--transition-netflix);
}

.modal-close:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    transform: rotate(90deg);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    background: var(--netflix-dark);
    border-top: 1px solid var(--netflix-border);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.form-label i {
    color: var(--netflix-red);
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text);
    font-size: 14px;
    transition: var(--transition-netflix);
}

body.light .form-control {
    background: rgba(0, 0, 0, 0.02);
}

.form-control:focus {
    outline: none;
    border-color: var(--netflix-red);
}

.info-message {
    background: rgba(33, 150, 243, 0.1);
    border: 1px solid rgba(33, 150, 243, 0.2);
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: var(--netflix-text-secondary);
}

.info-message i {
    color: var(--netflix-info);
    font-size: 16px;
}

/* Animations */
.fade-in {
    animation: fadeIn 0.4s ease forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-actions {
        width: 100%;
    }
    
    .profile-actions {
        flex-direction: column;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-footer .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-user-circle"></i>
                User Details
            </h1>
            <p>View and manage user information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Edit User
            </a>
        </div>
    </div>

    <div class="content-grid">
        {{-- Left Column - Profile Card --}}
        <div class="left-column">
            <div class="profile-card fade-in">
                <div class="profile-header">
                    <div class="profile-avatar-wrapper">
                        <div class="profile-avatar">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="avatar-image">
                            @else
                                <div class="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            @endif
                        </div>
                    </div>
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <div class="profile-badges">
                        @if($user->isAdmin())
                            <span class="badge admin"><i class="fas fa-shield-alt"></i> Administrator</span>
                        @else
                            <span class="badge user"><i class="fas fa-user"></i> Regular User</span>
                        @endif
                        <span class="badge status-active">
                            <span class="status-dot"></span> Active
                        </span>
                    </div>
                </div>

                <div class="profile-details">
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                        <div class="detail-content">
                            <span class="detail-label">Email Address</span>
                            <a href="mailto:{{ $user->email }}" class="detail-value">{{ $user->email }}</a>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="detail-content">
                            <span class="detail-label">Member Since</span>
                            <span class="detail-value">{{ $user->created_at->format('F d, Y') }}</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-clock"></i></div>
                        <div class="detail-content">
                            <span class="detail-label">Last Updated</span>
                            <span class="detail-value">{{ $user->updated_at->format('F d, Y') }}</span>
                        </div>
                    </div>
                    @if($user->phone)
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-phone"></i></div>
                        <div class="detail-content">
                            <span class="detail-label">Phone</span>
                            <a href="tel:{{ $user->phone }}" class="detail-value">{{ $user->phone }}</a>
                        </div>
                    </div>
                    @endif
                    @if($user->location)
                    <div class="detail-item">
                        <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="detail-content">
                            <span class="detail-label">Location</span>
                            <span class="detail-value">{{ $user->location }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="profile-actions">
                    <button type="button" class="action-btn reset-password" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="action-form" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="action-btn delete"><i class="fas fa-trash-alt"></i> Delete User</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column - Stats and Activity --}}
        <div class="right-column">
            <div class="stats-grid-small fade-in">
                <div class="stat-card-small">
                    <div class="stat-icon"><i class="fas fa-search"></i></div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['lost_items'] ?? 0 }}</div>
                        <div class="stat-label">Lost Items</div>
                    </div>
                </div>
                <div class="stat-card-small">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['found_items'] ?? 0 }}</div>
                        <div class="stat-label">Found Items</div>
                    </div>
                </div>
                <div class="stat-card-small">
                    <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['matches'] ?? 0 }}</div>
                        <div class="stat-label">Matches</div>
                    </div>
                </div>
            </div>

            <div class="activity-card fade-in">
                <div class="tabs-header">
                    <ul class="nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="lost-tab" data-bs-toggle="tab" data-bs-target="#lost" type="button" role="tab">
                                <i class="fas fa-search"></i> Lost Items
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="found-tab" data-bs-toggle="tab" data-bs-target="#found" type="button" role="tab">
                                <i class="fas fa-check-circle"></i> Found Items
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#matches" type="button" role="tab">
                                <i class="fas fa-exchange-alt"></i> Matches
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    {{-- LOST ITEMS TAB --}}
                    <div class="tab-pane fade show active" id="lost" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['lostItems'] ?? [] as $item)
                            <a href="{{ route('lost-items.show', $item->id) }}" class="item-row">
                                <div class="item-icon lost"><i class="fas fa-search"></i></div>
                                <div class="item-info">
                                    <h6 class="item-title">{{ $item->item_name }}</h6>
                                    <div class="item-meta">
                                        <span><i class="fas fa-calendar"></i> {{ $item->created_at->format('M d, Y') }}</span>
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $item->location ?? 'Unknown location' }}</span>
                                        <span class="badge status-{{ $item->status }}">{{ ucfirst($item->status ?? 'pending') }}</span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <p>No lost items found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- FOUND ITEMS TAB --}}
                    <div class="tab-pane fade" id="found" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['foundItems'] ?? [] as $item)
                            <a href="{{ route('found-items.show', $item->id) }}" class="item-row">
                                <div class="item-icon found"><i class="fas fa-check-circle"></i></div>
                                <div class="item-info">
                                    <h6 class="item-title">{{ $item->item_name }}</h6>
                                    <div class="item-meta">
                                        <span><i class="fas fa-calendar"></i> {{ $item->created_at->format('M d, Y') }}</span>
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $item->location ?? 'Unknown location' }}</span>
                                        <span class="badge status-{{ $item->status }}">{{ ucfirst($item->status ?? 'pending') }}</span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-check-circle"></i>
                                <p>No found items</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- MATCHES TAB --}}
                    <div class="tab-pane fade" id="matches" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['matches'] ?? [] as $match)
                            <a href="{{ route('matches.show', $match->id) }}" class="item-row">
                                <div class="item-icon match"><i class="fas fa-exchange-alt"></i></div>
                                <div class="item-info">
                                    <h6 class="item-title">
                                        @if($match->lostItem && $match->foundItem)
                                            {{ $match->lostItem->item_name }} ↔ {{ $match->foundItem->item_name }}
                                        @else
                                            Match #{{ $match->id }}
                                        @endif
                                    </h6>
                                    <div class="item-meta">
                                        <span><i class="fas fa-calendar"></i> {{ $match->created_at->format('M d, Y') }}</span>
                                        <span><i class="fas fa-chart-line"></i> Match Score</span>
                                        <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                            {{ number_format($match->match_score, 2) }}%
                                        </span>
                                        <span class="badge status-{{ $match->status }}">{{ ucfirst($match->status ?? 'pending') }}</span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-exchange-alt"></i>
                                <p>No matches found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.querySelector('#resetPasswordModal form');
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="password"]').value;
            const confirm = this.querySelector('input[name="password_confirmation"]').value;
            if (password !== confirm) { 
                e.preventDefault(); 
                alert('Passwords do not match!'); 
            }
            if (password.length < 8) { 
                e.preventDefault(); 
                alert('Password must be at least 8 characters long!'); 
            }
        });
    }
});
</script>
@endpush
@endsection