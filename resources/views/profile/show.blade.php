@extends('layouts.app')

@section('title', 'My Profile - Foundify')

@section('content')
@php
    $user = Auth::user();
    $isAdmin = $user->isAdmin();
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
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 32px;
}

/* Profile Cover */
.profile-cover {
    height: 200px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    border-radius: var(--radius-card) var(--radius-card) 0 0;
    position: relative;
    overflow: hidden;
    margin-bottom: -60px;
}

.cover-gradient {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.2) 0%, transparent 70%);
    animation: pulse 8s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
}

/* Profile Content */
.profile-content {
    position: relative;
    z-index: 2;
}

/* Cards */
.card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 18px 24px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.card-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h5 i {
    color: var(--accent);
    font-size: 18px;
}

.card-body {
    padding: 24px;
}

/* Badges */
.badge {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.badge.time {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

.badge.admin {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge.info {
    background: var(--info-soft);
    color: var(--info);
}

.badge.status-pending {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.status-approved {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-found {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-returned {
    background: var(--accent-soft);
    color: var(--accent);
}

/* Profile Header Card */
.profile-header-card {
    margin-bottom: 28px;
}

.profile-header-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 32px;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
    }
}

.profile-avatar-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
}

.profile-avatar-image {
    width: 120px;
    height: 120px;
    border-radius: 30px;
    object-fit: cover;
    transition: var(--transition);
    border: 3px solid var(--accent);
    box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
}

.profile-avatar-initial {
    width: 120px;
    height: 120px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent);
    color: white;
    font-weight: 800;
    font-size: 48px;
    border: 3px solid var(--accent);
    box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
}

.avatar-ring {
    position: absolute;
    inset: -5px;
    border-radius: 35px;
    background: var(--accent);
    opacity: 0;
    transition: var(--transition);
    z-index: 1;
    filter: blur(8px);
}

.profile-avatar-wrapper:hover .avatar-ring {
    opacity: 0.4;
    transform: scale(1.05);
}

.profile-avatar-wrapper:hover .profile-avatar-image,
.profile-avatar-wrapper:hover .profile-avatar-initial {
    transform: scale(0.98);
}

.profile-title-info {
    flex: 1;
}

.profile-name {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 12px;
    letter-spacing: -0.02em;
}

.profile-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.profile-actions {
    flex-shrink: 0;
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--accent);
    opacity: 0;
    transition: var(--transition);
}

.stat-card:hover::after {
    opacity: 1;
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
    background: var(--accent-soft);
    color: var(--accent);
}

.stat-value {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-trend {
    position: absolute;
    top: 16px;
    right: 16px;
    font-size: 10px;
    color: var(--text-muted);
    opacity: 0;
    transition: var(--transition);
}

.stat-card:hover .stat-trend {
    opacity: 1;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}

.info-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px;
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    transition: var(--transition);
}

.info-item:hover {
    border-color: var(--accent);
    transform: translateX(4px);
}

.info-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: var(--accent-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: 18px;
    transition: var(--transition);
}

.info-item:hover .info-icon {
    background: var(--accent);
    color: white;
}

.info-content {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 10px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-value {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-dark);
    word-break: break-word;
}

/* Quick Actions Section */
.quick-actions-section {
    margin-bottom: 28px;
}

.section-header {
    margin-bottom: 20px;
}

.section-header h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h5 i {
    color: var(--accent);
    font-size: 18px;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 992px) {
    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
}

.quick-action-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.quick-action-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--accent);
    opacity: 0;
    transition: var(--transition);
}

.quick-action-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.quick-action-card:hover::after {
    opacity: 1;
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    background: var(--accent-soft);
    color: var(--accent);
}

.quick-action-content {
    flex: 1;
}

.quick-action-content h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.quick-action-content p {
    font-size: 11px;
    color: var(--text-muted);
    margin: 0;
}

.quick-action-arrow {
    color: var(--accent);
    font-size: 14px;
    opacity: 0;
    transform: translateX(-5px);
    transition: var(--transition);
}

.quick-action-card:hover .quick-action-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* Timeline */
.timeline {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.timeline-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    transition: var(--transition);
}

.timeline-item:hover {
    border-color: var(--accent);
    transform: translateX(4px);
}

.timeline-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    background: var(--accent-soft);
    color: var(--accent);
    transition: var(--transition);
}

.timeline-item:hover .timeline-icon {
    background: var(--accent);
    color: white;
    transform: scale(1.05);
}

.timeline-content {
    flex: 1;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    flex-wrap: wrap;
    gap: 8px;
}

.timeline-header h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.timeline-date {
    font-size: 10px;
    color: var(--text-muted);
}

.timeline-description {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 10px;
    line-height: 1.5;
}

.timeline-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.view-link {
    font-size: 11px;
    font-weight: 600;
    color: var(--accent);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
}

.view-link:hover {
    gap: 10px;
}

.timeline-view {
    font-size: 11px;
    font-weight: 600;
    color: var(--accent);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: var(--transition);
}

.timeline-view:hover {
    gap: 8px;
}

.empty-timeline {
    text-align: center;
    padding: 50px 20px;
    color: var(--text-muted);
}

.empty-timeline i {
    font-size: 48px;
    color: var(--border-light);
    margin-bottom: 12px;
}

.empty-timeline p {
    font-size: 14px;
    margin: 0;
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
    
    .profile-cover {
        height: 150px;
        margin-bottom: -45px;
    }
    
    .profile-name {
        font-size: 24px;
    }
    
    .profile-badges {
        justify-content: center;
    }
    
    .profile-actions {
        width: 100%;
    }
    
    .profile-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .timeline-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<div class="dashboard-container">
    {{-- Cover Photo --}}
    <div class="profile-cover fade-in">
        <div class="cover-gradient"></div>
    </div>

    {{-- Profile Content --}}
    <div class="profile-content">
        {{-- Profile Header Card --}}
        <div class="card profile-header-card fade-in">
            <div class="card-body">
                <div class="profile-header-content">
                    <div class="profile-avatar-section">
                        <div class="profile-avatar-wrapper">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="{{ $user->name }}" 
                                     class="profile-avatar-image">
                            @else
                                <div class="profile-avatar-initial">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="avatar-ring"></div>
                        </div>
                        
                        <div class="profile-title-info">
                            <h1 class="profile-name">{{ $user->name }}</h1>
                            <div class="profile-badges">
                                <span class="badge time">
                                    <i class="fas fa-calendar-alt"></i>
                                    Joined {{ $user->created_at->format('M Y') }}
                                </span>
                                <span class="badge time">
                                    <i class="fas fa-envelope"></i>
                                    {{ $user->email }}
                                </span>
                                @if($isAdmin)
                                    <span class="badge admin">
                                        <i class="fas fa-crown"></i>
                                        Administrator
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="profile-actions">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-search"></i></div>
                <div><div class="stat-value">{{ $user->lostItems()->count() }}</div><div class="stat-label">Lost Items</div></div>
                <div class="stat-trend"><i class="fas fa-chart-line"></i> Total</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div><div class="stat-value">{{ $user->foundItems()->count() }}</div><div class="stat-label">Found Items</div></div>
                <div class="stat-trend"><i class="fas fa-chart-line"></i> Total</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-handshake"></i></div>
                <div>
                    @php
                        $matchCount = $user->lostItems()->with('matches')->get()->sum(fn($i) => $i->matches->count()) + 
                                      $user->foundItems()->with('matches')->get()->sum(fn($i) => $i->matches->count());
                    @endphp
                    <div class="stat-value">{{ $matchCount }}</div>
                    <div class="stat-label">Matches</div>
                </div>
                <div class="stat-trend"><i class="fas fa-exchange-alt"></i> Potential</div>
            </div>
        </div>

        {{-- Profile Information --}}
        <div class="card info-card fade-in">
            <div class="card-header">
                <h5><i class="fas fa-user-circle"></i> Profile Information</h5>
                <span class="badge info">Personal</span>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-user"></i></div>
                        <div class="info-content"><span class="info-label">Full Name</span><span class="info-value">{{ $user->name }}</span></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div class="info-content"><span class="info-label">Email Address</span><span class="info-value">{{ $user->email }}</span></div>
                    </div>
                    @if($user->phone)
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="info-content"><span class="info-label">Phone Number</span><span class="info-value">{{ $user->phone }}</span></div>
                    </div>
                    @endif
                    @if($user->location)
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info-content"><span class="info-label">Location</span><span class="info-value">{{ $user->location }}</span></div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="info-content"><span class="info-label">Member Since</span><span class="info-value">{{ $user->created_at->format('F d, Y') }}</span></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-clock"></i></div>
                        <div class="info-content"><span class="info-label">Last Updated</span><span class="info-value">{{ $user->updated_at->format('F d, Y') }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="quick-actions-section fade-in">
            <div class="section-header">
                <h5><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="quick-actions-grid">
                <a href="{{ route('lost-items.create') }}" class="quick-action-card">
                    <div class="quick-action-icon"><i class="fas fa-search"></i></div>
                    <div class="quick-action-content"><h6>Report Lost</h6><p>Help find your belongings</p></div>
                    <i class="fas fa-arrow-right quick-action-arrow"></i>
                </a>
                <a href="{{ route('found-items.create') }}" class="quick-action-card">
                    <div class="quick-action-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="quick-action-content"><h6>Report Found</h6><p>Help reunite items</p></div>
                    <i class="fas fa-arrow-right quick-action-arrow"></i>
                </a>
                <a href="{{ route('messages.index') }}" class="quick-action-card">
                    <div class="quick-action-icon"><i class="fas fa-comments"></i></div>
                    <div class="quick-action-content"><h6>Messages</h6><p>Check conversations</p></div>
                    <i class="fas fa-arrow-right quick-action-arrow"></i>
                </a>
                <a href="{{ route('matches.my-matches') }}" class="quick-action-card">
                    <div class="quick-action-icon"><i class="fas fa-handshake"></i></div>
                    <div class="quick-action-content"><h6>My Matches</h6><p>View potential matches</p></div>
                    <i class="fas fa-arrow-right quick-action-arrow"></i>
                </a>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="card timeline-card fade-in">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Recent Activity</h5>
                <a href="{{ route('lost-items.my-items') }}" class="view-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @php
                        $recentLost = $user->lostItems()->latest()->take(3)->get();
                        $recentFound = $user->foundItems()->latest()->take(3)->get();
                        $activities = collect()
                            ->concat($recentLost->map(fn($item) => ['type' => 'lost', 'item' => $item, 'date' => $item->created_at, 'icon' => 'fa-search']))
                            ->concat($recentFound->map(fn($item) => ['type' => 'found', 'item' => $item, 'date' => $item->created_at, 'icon' => 'fa-check-circle']))
                            ->sortByDesc('date')
                            ->take(5);
                    @endphp

                    @forelse($activities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-icon"><i class="fas {{ $activity['icon'] }}"></i></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h6>{{ $activity['type'] === 'lost' ? 'Lost Item' : 'Found Item' }}: {{ $activity['item']->item_name }}</h6>
                                    <span class="timeline-date">{{ $activity['date']->diffForHumans() }}</span>
                                </div>
                                <p class="timeline-description">{{ Str::limit($activity['item']->description, 80) }}</p>
                                <div class="timeline-footer">
                                    <span class="badge status-{{ $activity['item']->status }}">{{ strtoupper($activity['item']->status) }}</span>
                                    <a href="{{ $activity['type'] === 'lost' ? route('lost-items.show', $activity['item']) : route('found-items.show', $activity['item']) }}" 
                                       class="timeline-view">View <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-timeline">
                            <i class="fas fa-history"></i>
                            <p>No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection