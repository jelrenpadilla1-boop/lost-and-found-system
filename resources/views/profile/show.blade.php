@extends('layouts.app')

@section('title', 'My Profile - Foundify')

@section('content')
@php
    $user = Auth::user();
    $isAdmin = $user->isAdmin();
@endphp

<style>
/* ── NETFLIX-STYLE PROFILE PAGE ───────────────── */
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
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Profile Cover */
.profile-cover {
    height: 200px;
    background: linear-gradient(135deg, var(--netflix-red), var(--netflix-red-dark));
    border-radius: 8px 8px 0 0;
    position: relative;
    overflow: hidden;
    margin-bottom: -60px;
}

.cover-gradient {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.15) 0%, transparent 70%);
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
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 28px;
    transition: var(--transition-netflix);
}

.card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.card-header {
    padding: 16px 24px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.card-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h5 i {
    color: var(--netflix-red);
    font-size: 18px;
}

.card-body {
    padding: 24px;
}

/* Badges */
.badge {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge.time {
    background: rgba(255, 255, 255, 0.05);
    color: var(--netflix-text-secondary);
    border: 1px solid var(--netflix-border);
}

.badge.admin {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.info {
    background: rgba(33, 150, 243, 0.15);
    color: var(--netflix-info);
}

.badge.status-pending {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.badge.status-approved,
.badge.status-found,
.badge.status-returned,
.badge.status-recovered {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
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
    border-radius: 8px;
    object-fit: cover;
    transition: var(--transition-netflix);
    border: 2px solid var(--netflix-red);
}

.profile-avatar-initial {
    width: 120px;
    height: 120px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--netflix-red);
    color: white;
    font-weight: 800;
    font-size: 48px;
    border: 2px solid var(--netflix-red);
}

.avatar-ring {
    position: absolute;
    inset: -5px;
    border-radius: 13px;
    background: var(--netflix-red);
    opacity: 0;
    transition: var(--transition-netflix);
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
    color: var(--netflix-text);
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
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
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition-netflix);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-3px);
}

.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--netflix-red);
    opacity: 0;
    transition: var(--transition-netflix);
}

.stat-card:hover::after {
    opacity: 1;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.stat-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--netflix-text);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    color: var(--netflix-text-secondary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-trend {
    position: absolute;
    top: 16px;
    right: 16px;
    font-size: 10px;
    color: var(--netflix-text-secondary);
    opacity: 0;
    transition: var(--transition-netflix);
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
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    transition: var(--transition-netflix);
}

.info-item:hover {
    border-color: var(--netflix-red);
    transform: translateX(4px);
}

.info-icon {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: rgba(229, 9, 20, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--netflix-red);
    font-size: 18px;
    transition: var(--transition-netflix);
}

.info-item:hover .info-icon {
    background: var(--netflix-red);
    color: white;
}

.info-content {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 10px;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-value {
    font-size: 14px;
    font-weight: 500;
    color: var(--netflix-text);
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
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h5 i {
    color: var(--netflix-red);
    font-size: 18px;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
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
    padding: 16px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition-netflix);
    position: relative;
    overflow: hidden;
}

.quick-action-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--netflix-red);
    opacity: 0;
    transition: var(--transition-netflix);
}

.quick-action-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-3px);
}

.quick-action-card:hover::after {
    opacity: 1;
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.quick-action-content {
    flex: 1;
}

.quick-action-content h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0 0 4px 0;
}

.quick-action-content p {
    font-size: 11px;
    color: var(--netflix-text-secondary);
    margin: 0;
}

.quick-action-arrow {
    color: var(--netflix-red);
    font-size: 14px;
    opacity: 0;
    transform: translateX(-5px);
    transition: var(--transition-netflix);
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
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    transition: var(--transition-netflix);
}

.timeline-item:hover {
    border-color: var(--netflix-red);
    transform: translateX(4px);
}

.timeline-icon {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
    transition: var(--transition-netflix);
}

.timeline-item:hover .timeline-icon {
    background: var(--netflix-red);
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
    color: var(--netflix-text);
    margin: 0;
}

.timeline-date {
    font-size: 10px;
    color: var(--netflix-text-secondary);
}

.timeline-description {
    font-size: 12px;
    color: var(--netflix-text-secondary);
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
    color: var(--netflix-red);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition-netflix);
}

.view-link:hover {
    gap: 10px;
}

.timeline-view {
    font-size: 11px;
    font-weight: 600;
    color: var(--netflix-red);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: var(--transition-netflix);
}

.timeline-view:hover {
    gap: 8px;
}

.empty-timeline {
    text-align: center;
    padding: 50px 20px;
    color: var(--netflix-text-secondary);
}

.empty-timeline i {
    font-size: 48px;
    color: var(--netflix-border);
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
        padding: 16px;
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