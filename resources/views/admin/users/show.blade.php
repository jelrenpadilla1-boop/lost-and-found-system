@extends('layouts.app')

@section('title', 'User Details - Admin')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    $isOwner = Auth::id() === $user->id;
@endphp

<style>
/* ── MODERN DESIGN SYSTEM (matches dashboard) ───────────────── */
:root {
    --bg-white: #ffffff;
    --bg-soft: #faf9fe;
    --bg-card: #ffffff;
    --border-light: #edeef5;
    --border-soft: #e6e8f0;
    --accent: #7c3aed;
    --accent-light: #8b5cf6;
    --accent-soft: #ede9fe;
    --text-dark: #1e1b2f;
    --text-muted: #5b5b7a;
    --text-soft: #7e7b9a;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
    --radius-card: 20px;
    --radius-sm: 12px;
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    --success: #10b981;
    --success-soft: #d1fae5;
    --warning: #f59e0b;
    --warning-soft: #fef3c7;
    --error: #ef4444;
    --error-soft: #fee2e2;
    --info: #3b82f6;
    --info-soft: #dbeafe;
    --glass: rgba(0, 0, 0, 0.02);
    --glass-b: rgba(0, 0, 0, 0.04);
    --glass-hover: rgba(0, 0, 0, 0.06);
}

/* DARK MODE */
body.dark {
    --bg-white: #0f0c1a;
    --bg-soft: #12101c;
    --bg-card: #191624;
    --border-light: #2a2438;
    --border-soft: #2d2740;
    --accent: #a78bfa;
    --accent-light: #c4b5fd;
    --accent-soft: #2d2648;
    --text-dark: #f0edfc;
    --text-muted: #b4adcf;
    --text-soft: #938bb0;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.4), 0 4px 8px rgba(0, 0, 0, 0.2);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.5);
    --success-soft: rgba(16, 185, 129, 0.15);
    --warning-soft: rgba(245, 158, 11, 0.15);
    --error-soft: rgba(239, 68, 68, 0.15);
    --info-soft: rgba(59, 130, 246, 0.15);
    --glass: rgba(255, 255, 255, 0.03);
    --glass-b: rgba(255, 255, 255, 0.06);
    --glass-hover: rgba(255, 255, 255, 0.08);
}

/* Dashboard Container */
.dashboard-container {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 28px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-light);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.02em;
}

.page-title h1 i {
    color: var(--accent);
    font-size: 26px;
}

.page-title p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.page-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 40px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid transparent;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
}

.btn-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    color: var(--text-muted);
}

.btn-secondary:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 28px;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* Profile Card */
.profile-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.profile-card:hover {
    box-shadow: var(--shadow-md);
}

.profile-header {
    background: var(--bg-soft);
    padding: 32px 24px;
    text-align: center;
    border-bottom: 1px solid var(--border-light);
}

.profile-avatar-wrapper {
    position: relative;
    width: fit-content;
    margin: 0 auto 20px;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent);
    color: white;
    font-weight: 700;
    font-size: 40px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
    transition: var(--transition);
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
    background: var(--accent);
}

.verified-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: var(--success);
    color: white;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 2px solid var(--bg-card);
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
}

.profile-name {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
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
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge.admin {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge.user {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

.badge.status-active {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-inactive {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

.badge.status-suspended {
    background: var(--error-soft);
    color: var(--error);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
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
    border-bottom: 1px solid var(--border-light);
    transition: var(--transition);
}

.detail-item:hover {
    background: var(--glass);
    transform: translateX(4px);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-icon {
    width: 42px;
    height: 42px;
    background: var(--bg-soft);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: 18px;
    flex-shrink: 0;
    border: 1px solid var(--border-light);
}

.detail-content {
    flex: 1;
}

.detail-label {
    display: block;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    color: var(--text-dark);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
}

.detail-value:hover {
    color: var(--accent);
}

/* Profile Actions */
.profile-actions {
    padding: 20px;
    display: flex;
    gap: 12px;
    border-top: 1px solid var(--border-light);
}

.action-btn {
    flex: 1;
    padding: 12px;
    border-radius: 40px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid;
    background: transparent;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.action-btn.reset-password {
    border-color: var(--accent-soft);
    color: var(--accent);
}

.action-btn.reset-password:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.action-btn.delete {
    border-color: var(--error-soft);
    color: var(--error);
}

.action-btn.delete:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.action-form {
    flex: 1;
}

/* Stats Grid Small */
.stats-grid-small {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

@media (max-width: 768px) {
    .stats-grid-small {
        grid-template-columns: 1fr;
    }
}

.stat-card-small {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.stat-card-small:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card-small .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-card-small .stat-content {
    flex: 1;
}

.stat-card-small .stat-value {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-card-small .stat-label {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Activity Card */
.activity-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.tabs-header {
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
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
    color: var(--text-muted);
    border: none;
    padding: 14px 20px;
    font-size: 13px;
    font-weight: 600;
    transition: var(--transition);
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    border-radius: 0;
    margin: 0;
    position: relative;
}

.nav-tabs .nav-link i {
    font-size: 14px;
}

.nav-tabs .nav-link:hover {
    color: var(--accent);
}

.nav-tabs .nav-link.active {
    color: var(--accent);
}

.nav-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--accent);
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
    background: var(--bg-soft);
}

.items-list::-webkit-scrollbar-thumb {
    background: var(--border-light);
    border-radius: 3px;
}

.items-list::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
}

.item-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    border-radius: var(--radius-sm);
    text-decoration: none;
    transition: var(--transition);
    margin-bottom: 4px;
}

.item-row:hover {
    background: var(--glass);
    transform: translateX(4px);
}

.item-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.item-icon.lost {
    background: var(--accent-soft);
    color: var(--accent);
}

.item-icon.found {
    background: var(--success-soft);
    color: var(--success);
}

.item-icon.match {
    background: var(--warning-soft);
    color: var(--warning);
}

.item-info {
    flex: 1;
    min-width: 0;
}

.item-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
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
    color: var(--text-muted);
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.arrow {
    color: var(--text-muted);
    font-size: 14px;
    transition: var(--transition);
}

.item-row:hover .arrow {
    color: var(--accent);
    transform: translateX(4px);
}

/* Score Badge */
.score-badge {
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
}

.score-high {
    background: var(--success-soft);
    color: var(--success);
}

.score-medium {
    background: var(--warning-soft);
    color: var(--warning);
}

.score-low {
    background: var(--accent-soft);
    color: var(--accent);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 48px;
    color: var(--border-light);
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 14px;
    margin: 0;
}

/* Modals */
.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
}

.modal-header {
    padding: 18px 24px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-title i {
    color: var(--accent);
}

.modal-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
}

.modal-close:hover {
    border-color: var(--error);
    color: var(--error);
    transform: rotate(90deg);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    background: var(--bg-soft);
    border-top: 1px solid var(--border-light);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

/* Form Elements */
.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

@media (max-width: 576px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-label i {
    color: var(--accent);
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    color: var(--text-dark);
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.select-wrapper {
    position: relative;
}

.form-select {
    width: 100%;
    padding: 12px 40px 12px 16px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    color: var(--text-dark);
    font-size: 14px;
    appearance: none;
    cursor: pointer;
}

.form-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--accent);
    font-size: 12px;
    pointer-events: none;
}

.info-message {
    background: var(--info-soft);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: var(--text-muted);
}

.info-message i {
    color: var(--info);
    font-size: 16px;
}

/* Photo Section */
.photo-section {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-light);
    flex-wrap: wrap;
}

.current-photo {
    flex-shrink: 0;
}

.photo-preview {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    object-fit: cover;
    border: 3px solid var(--accent);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.photo-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 32px;
    border: 3px solid var(--accent);
}

.photo-actions {
    flex: 1;
}

.photo-input {
    display: none;
}

.btn-upload {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-soft);
    border-radius: 40px;
    color: var(--accent);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.btn-upload:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.checkbox-remove {
    margin-top: 12px;
}

.checkbox-remove input {
    margin-right: 6px;
    accent-color: var(--error);
}

.checkbox-remove label {
    color: var(--error);
    font-size: 12px;
    cursor: pointer;
}

.upload-hint {
    margin-top: 8px;
    color: var(--text-muted);
    font-size: 11px;
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
        padding: 20px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-actions {
        width: 100%;
    }
    
    .page-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    .profile-actions {
        flex-direction: column;
    }
    
    .photo-section {
        flex-direction: column;
        text-align: center;
    }
    
    .current-photo {
        margin: 0 auto;
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">
                <i class="fas fa-edit"></i>
                Edit User
            </button>
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
                        @if($user->is_verified ?? false)
                            <div class="verified-badge"><i class="fas fa-check-circle"></i></div>
                        @endif
                    </div>
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <div class="profile-badges">
                        @if($user->isAdmin())
                            <span class="badge admin"><i class="fas fa-shield-alt"></i> Administrator</span>
                        @else
                            <span class="badge user"><i class="fas fa-user"></i> Regular User</span>
                        @endif
                        <span class="badge status-{{ $user->status ?? 'active' }}">
                            <span class="status-dot"></span> {{ ucfirst($user->status ?? 'active') }}
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
                        @csrf @method('DELETE')
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
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--accent), var(--accent-light));"><i class="fas fa-search"></i></div>
                    <div class="stat-content"><div class="stat-value">{{ $stats['lost_items'] ?? 0 }}</div><div class="stat-label">Lost Items</div></div>
                </div>
                <div class="stat-card-small">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--success), #0d9668);"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-content"><div class="stat-value">{{ $stats['found_items'] ?? 0 }}</div><div class="stat-label">Found Items</div></div>
                </div>
                <div class="stat-card-small">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning), #d97706);"><i class="fas fa-exchange-alt"></i></div>
                    <div class="stat-content"><div class="stat-value">{{ $stats['matches'] ?? 0 }}</div><div class="stat-label">Matches</div></div>
                </div>
            </div>

            <div class="activity-card fade-in">
                <div class="tabs-header">
                    <ul class="nav-tabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active" id="lost-tab" data-bs-toggle="tab" data-bs-target="#lost" type="button" role="tab"><i class="fas fa-search"></i> Lost Items</button></li>
                        <li class="nav-item"><button class="nav-link" id="found-tab" data-bs-toggle="tab" data-bs-target="#found" type="button" role="tab"><i class="fas fa-check-circle"></i> Found Items</button></li>
                        <li class="nav-item"><button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#matches" type="button" role="tab"><i class="fas fa-exchange-alt"></i> Matches</button></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="lost" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['lostItems'] ?? [] as $item)
                            <a href="{{ route('lost-items.show', $item) }}" class="item-row">
                                <div class="item-icon lost"><i class="fas fa-search"></i></div>
                                <div class="item-info"><h6 class="item-title">{{ $item->item_name }}</h6><div class="item-meta"><span><i class="fas fa-calendar"></i> {{ $item->created_at->format('M d, Y') }}</span><span class="badge status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></div></div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="empty-state"><i class="fas fa-search"></i><p>No lost items found</p></div>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="found" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['foundItems'] ?? [] as $item)
                            <a href="{{ route('found-items.show', $item) }}" class="item-row">
                                <div class="item-icon found"><i class="fas fa-check-circle"></i></div>
                                <div class="item-info"><h6 class="item-title">{{ $item->item_name }}</h6><div class="item-meta"><span><i class="fas fa-calendar"></i> {{ $item->created_at->format('M d, Y') }}</span><span class="badge status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></div></div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="empty-state"><i class="fas fa-check-circle"></i><p>No found items</p></div>
                            @endforelse
                        </div>
                    </div>
                    <div class="tab-pane fade" id="matches" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['matches'] ?? [] as $match)
                            <a href="{{ route('matches.show', $match) }}" class="item-row">
                                <div class="item-icon match"><i class="fas fa-exchange-alt"></i></div>
                                <div class="item-info"><h6 class="item-title">Match #{{ $match->id }}</h6><div class="item-meta"><span><i class="fas fa-calendar"></i> {{ $match->created_at->format('M d, Y') }}</span><span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">{{ $match->match_score }}%</span></div></div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="empty-state"><i class="fas fa-exchange-alt"></i><p>No matches found</p></div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit User</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="photo-section">
                        <div class="current-photo">
                            @if($user->profile_photo)<img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="photo-preview">
                            @else<div class="photo-placeholder">{{ substr($user->name, 0, 1) }}</div>@endif
                        </div>
                        <div class="photo-actions">
                            <input type="file" class="photo-input" id="profile_photo" name="profile_photo" accept="image/*">
                            <label for="profile_photo" class="btn-upload"><i class="fas fa-cloud-upload-alt"></i> Change Photo</label>
                            @if($user->profile_photo)<div class="checkbox-remove"><input type="checkbox" name="remove_photo" id="remove_photo"> <label for="remove_photo">Remove current photo</label></div>@endif
                            <p class="upload-hint">Max 2MB. JPG, PNG, GIF</p>
                        </div>
                    </div>
                    <div class="form-group"><label class="form-label"><i class="fas fa-user"></i> Full Name</label><input type="text" class="form-control" name="name" value="{{ $user->name }}" required></div>
                    <div class="form-group"><label class="form-label"><i class="fas fa-envelope"></i> Email Address</label><input type="email" class="form-control" name="email" value="{{ $user->email }}" required></div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label"><i class="fas fa-phone"></i> Phone</label><input type="text" class="form-control" name="phone" value="{{ $user->phone ?? '' }}" placeholder="Optional"></div>
                        <div class="form-group"><label class="form-label"><i class="fas fa-map-marker-alt"></i> Location</label><input type="text" class="form-control" name="location" value="{{ $user->location ?? '' }}" placeholder="Optional"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label"><i class="fas fa-tag"></i> Role</label><div class="select-wrapper"><select class="form-select" name="role"><option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Regular User</option><option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option></select><i class="fas fa-chevron-down select-arrow"></i></div></div>
                        <div class="form-group"><label class="form-label"><i class="fas fa-check-circle"></i> Status</label><div class="select-wrapper"><select class="form-select" name="status"><option value="active" {{ ($user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ ($user->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option><option value="suspended" {{ ($user->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option></select><i class="fas fa-chevron-down select-arrow"></i></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button></div>
            </form>
        </div>
    </div>
</div>

{{-- Reset Password Modal --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-key"></i> Reset Password</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group"><label class="form-label"><i class="fas fa-lock"></i> New Password</label><input type="password" class="form-control" name="password" required minlength="8"></div>
                    <div class="form-group"><label class="form-label"><i class="fas fa-lock"></i> Confirm Password</label><input type="password" class="form-control" name="password_confirmation" required minlength="8"></div>
                    <div class="info-message"><i class="fas fa-info-circle"></i><span>Password must be at least 8 characters long.</span></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Reset Password</button></div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('profile_photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) { alert('File size must be less than 2MB'); this.value = ''; return; }
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) { alert('Please upload a valid image file'); this.value = ''; return; }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.photo-preview, .photo-placeholder');
                    if (preview) {
                        if (preview.tagName === 'IMG') preview.src = e.target.result;
                        else { const newImg = document.createElement('img'); newImg.src = e.target.result; newImg.className = 'photo-preview'; preview.parentNode.replaceChild(newImg, preview); }
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    const resetForm = document.querySelector('#resetPasswordModal form');
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="password"]').value;
            const confirm = this.querySelector('input[name="password_confirmation"]').value;
            if (password !== confirm) { e.preventDefault(); alert('Passwords do not match!'); }
            if (password.length < 8) { e.preventDefault(); alert('Password must be at least 8 characters long!'); }
        });
    }
    const activeTab = localStorage.getItem('activeUserTab');
    if (activeTab) { const tab = document.querySelector(`[data-bs-target="${activeTab}"]`); if (tab) tab.click(); }
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('click', function() { localStorage.setItem('activeUserTab', this.getAttribute('data-bs-target')); });
    });
});
</script>
@endpush
@endsection