@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="profile-wrapper">
    <!-- Cover Photo -->
    <div class="profile-cover">
        <div class="cover-gradient"></div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-header-content">
                <div class="profile-avatar-section">
                    <div class="profile-avatar-wrapper">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="profile-avatar-image">
                        @else
                            <div class="profile-avatar-initial" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="avatar-ring"></div>
                    </div>
                    
                    <div class="profile-title-info">
                        <h1 class="profile-name">{{ Auth::user()->name }}</h1>
                        <div class="profile-badges">
                            <span class="profile-badge">
                                <i class="fas fa-calendar-alt"></i>
                                Joined {{ Auth::user()->created_at->format('M Y') }}
                            </span>
                            <span class="profile-badge">
                                <i class="fas fa-envelope"></i>
                                {{ Auth::user()->email }}
                            </span>
                            @if(Auth::user()->isAdmin())
                                <span class="profile-badge admin">
                                    <i class="fas fa-crown"></i>
                                    Administrator
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="profile-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-edit">
                            <i class="fas fa-edit"></i>
                            <span>Edit Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon lost">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ Auth::user()->lostItems()->count() }}</div>
                    <div class="stat-label">Lost Items</div>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i> Total reported
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon found">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ Auth::user()->foundItems()->count() }}</div>
                    <div class="stat-label">Found Items</div>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i> Total reported
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon matches">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stat-content">
                    @php
                        $matchCount = \App\Models\ItemMatch::whereHas('lostItem', function($q) {
                            $q->where('user_id', Auth::id());
                        })->orWhereHas('foundItem', function($q) {
                            $q->where('user_id', Auth::id());
                        })->count();
                    @endphp
                    <div class="stat-value">{{ $matchCount }}</div>
                    <div class="stat-label">Matches</div>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-exchange-alt"></i> Potential matches
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="info-card">
            <div class="info-header">
                <div class="info-title">
                    <i class="fas fa-user-circle" style="color: var(--primary);"></i>
                    <h3>Profile Information</h3>
                </div>
                <span class="info-badge">Personal Details</span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="info-item-content">
                        <span class="info-item-label">Full Name</span>
                        <span class="info-item-value">{{ Auth::user()->name }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-item-content">
                        <span class="info-item-label">Email Address</span>
                        <span class="info-item-value">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                @if(Auth::user()->phone)
                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="info-item-content">
                        <span class="info-item-label">Phone Number</span>
                        <span class="info-item-value">{{ Auth::user()->phone }}</span>
                    </div>
                </div>
                @endif

                @if(Auth::user()->location)
                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-item-content">
                        <span class="info-item-label">Location</span>
                        <span class="info-item-value">{{ Auth::user()->location }}</span>
                    </div>
                </div>
                @endif

                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="info-item-content">
                        <span class="info-item-label">Member Since</span>
                        <span class="info-item-value">{{ Auth::user()->created_at->format('F d, Y') }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-item-content">
                        <span class="info-item-label">Last Updated</span>
                        <span class="info-item-value">{{ Auth::user()->updated_at->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        

        <!-- Activity Timeline -->
        <div class="timeline-card">
            <div class="timeline-header">
                <div class="timeline-title">
                    <i class="fas fa-history" style="color: var(--primary);"></i>
                    <h3>Recent Activity</h3>
                </div>
                <a href="{{ route('lost-items.my-items') }}" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="timeline">
                @php
                    $recentLost = Auth::user()->lostItems()->latest()->take(3)->get();
                    $recentFound = Auth::user()->foundItems()->latest()->take(3)->get();
                    $activities = collect()
                        ->concat($recentLost->map(fn($item) => [
                            'type' => 'lost',
                            'item' => $item,
                            'date' => $item->created_at,
                            'icon' => 'fa-exclamation-circle',
                            'color' => '#ff4444'
                        ]))
                        ->concat($recentFound->map(fn($item) => [
                            'type' => 'found',
                            'item' => $item,
                            'date' => $item->created_at,
                            'icon' => 'fa-check-circle',
                            'color' => '#00fa9a'
                        ]))
                        ->sortByDesc('date')
                        ->take(5);
                @endphp

                @forelse($activities as $activity)
                    <div class="timeline-item">
                        <div class="timeline-icon" style="background: {{ $activity['color'] }}20; color: {{ $activity['color'] }};">
                            <i class="fas {{ $activity['icon'] }}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-content-header">
                                <h6>
                                    @if($activity['type'] === 'lost')
                                        Lost Item: {{ $activity['item']->item_name }}
                                    @else
                                        Found Item: {{ $activity['item']->item_name }}
                                    @endif
                                </h6>
                                <span class="timeline-date">{{ $activity['date']->diffForHumans() }}</span>
                            </div>
                            <p class="timeline-description">
                                {{ Str::limit($activity['item']->description, 100) }}
                            </p>
                            <div class="timeline-footer">
                                <span class="timeline-status status-{{ $activity['item']->status }}">
                                    {{ ucfirst($activity['item']->status) }}
                                </span>
                                <a href="{{ $activity['type'] === 'lost' ? route('lost-items.show', $activity['item']) : route('found-items.show', $activity['item']) }}" 
                                   class="timeline-view">
                                    View Details <i class="fas fa-arrow-right"></i>
                                </a>
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

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-glow: rgba(255, 20, 147, 0.3);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-header: #222;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #e0e0e0;
    --text-muted: #a0a0a0;
    --success: #00fa9a;
    --error: #ff4444;
    --warning: #ffa500;
    --info: #ff69b4;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Profile Wrapper */
.profile-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Cover Photo */
.profile-cover {
    height: 200px;
    background: linear-gradient(135deg, #000000, #1a1a1a);
    border-radius: 30px 30px 0 0;
    position: relative;
    overflow: hidden;
    margin-bottom: -50px;
}

.cover-gradient {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, var(--primary-glow) 0%, transparent 70%);
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

/* Profile Header Card */
.profile-header-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    padding: 30px;
    margin-bottom: 30px;
    backdrop-filter: blur(10px);
    transition: var(--transition);
}

.profile-header-card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 40px var(--primary-glow);
    transform: translateY(-5px);
}

.profile-header-content {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 30px;
    flex-wrap: wrap;
}

.profile-avatar-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
}

.profile-avatar-image {
    width: 120px;
    height: 120px;
    border-radius: 40px;
    object-fit: cover;
    position: relative;
    z-index: 2;
    transition: var(--transition);
}

.profile-avatar-initial {
    width: 120px;
    height: 120px;
    border-radius: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 48px;
    position: relative;
    z-index: 2;
    transition: var(--transition);
}

.avatar-ring {
    position: absolute;
    inset: -5px;
    border-radius: 45px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    opacity: 0;
    transition: var(--transition);
    z-index: 1;
}

.profile-avatar-wrapper:hover .avatar-ring {
    opacity: 1;
    transform: scale(1.05);
}

.profile-avatar-wrapper:hover .profile-avatar-image,
.profile-avatar-wrapper:hover .profile-avatar-initial {
    transform: scale(0.95);
}

.profile-title-info {
    flex: 1;
}

.profile-name {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 10px;
    background: linear-gradient(135deg, white, var(--primary-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.profile-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.profile-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    font-size: 12px;
    color: var(--text-secondary);
    transition: var(--transition);
}

.profile-badge:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
}

.profile-badge.admin {
    background: rgba(255, 20, 147, 0.1);
    border-color: var(--primary);
    color: var(--primary);
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 5px 20px var(--primary-glow);
}

.btn-edit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px var(--primary-glow);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
}

.stat-card:hover {
    border-color: var(--primary);
    transform: translateY(-5px);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.stat-card:hover::before {
    opacity: 0.1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    transition: var(--transition);
}

.stat-icon.lost {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

.stat-icon.found {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 5px 15px rgba(0, 250, 154, 0.3);
}

.stat-icon.matches {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 5px 15px var(--primary-glow);
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    color: var(--text-muted);
    font-size: 13px;
}

.stat-trend {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 11px;
    color: var(--text-muted);
    opacity: 0;
    transition: var(--transition);
}

.stat-card:hover .stat-trend {
    opacity: 1;
}

/* Info Card */
.info-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 30px;
    transition: var(--transition);
}

.info-card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.info-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
}

.info-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-title i {
    font-size: 20px;
}

.info-title h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.info-badge {
    padding: 4px 12px;
    background: rgba(255, 20, 147, 0.1);
    border: 1px solid var(--primary);
    border-radius: 30px;
    font-size: 11px;
    color: var(--primary);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    transition: var(--transition);
}

.info-item:hover {
    border-color: var(--primary);
    transform: translateX(5px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.info-item-icon {
    width: 36px;
    height: 36px;
    background: rgba(255, 20, 147, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 16px;
    transition: var(--transition);
}

.info-item:hover .info-item-icon {
    background: var(--primary);
    color: white;
}

.info-item-content {
    flex: 1;
}

.info-item-label {
    display: block;
    font-size: 11px;
    color: var(--text-muted);
    margin-bottom: 2px;
}

.info-item-value {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-primary);
}

/* Quick Actions */
.quick-actions {
    margin-bottom: 30px;
}

.quick-actions-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.quick-action-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    text-decoration: none;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.quick-action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s ease;
}

.quick-action-card:hover {
    border-color: var(--primary);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px var(--primary-glow);
}

.quick-action-card:hover::before {
    left: 100%;
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    transition: var(--transition);
}

.quick-action-icon.lost {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
}

.quick-action-icon.found {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
}

.quick-action-icon.messages {
    background: linear-gradient(135deg, #8b5cf6, #a78bfa);
}

.quick-action-icon.matches {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
}

.quick-action-info {
    flex: 1;
}

.quick-action-info h5 {
    font-size: 15px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.quick-action-info p {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0;
}

.quick-action-arrow {
    color: var(--primary);
    font-size: 14px;
    opacity: 0;
    transform: translateX(-10px);
    transition: var(--transition);
}

.quick-action-card:hover .quick-action-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* Timeline Card */
.timeline-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 25px;
    transition: var(--transition);
}

.timeline-card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.timeline-title {
    display: flex;
    align-items: center;
    gap: 8px;
}

.timeline-title i {
    font-size: 20px;
}

.timeline-title h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.view-all-link {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--primary);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: var(--transition);
}

.view-all-link:hover {
    gap: 10px;
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.timeline-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    transition: var(--transition);
    animation: slideIn 0.3s ease;
}

.timeline-item:hover {
    border-color: var(--primary);
    transform: translateX(5px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    transition: var(--transition);
}

.timeline-item:hover .timeline-icon {
    transform: scale(1.1) rotate(360deg);
}

.timeline-content {
    flex: 1;
}

.timeline-content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.timeline-content-header h6 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.timeline-date {
    font-size: 11px;
    color: var(--text-muted);
}

.timeline-description {
    font-size: 12px;
    color: var(--text-secondary);
    margin-bottom: 10px;
    line-height: 1.5;
}

.timeline-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.timeline-status {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
}

.timeline-status.status-pending {
    background: rgba(255, 165, 0, 0.1);
    color: #ffa500;
    border: 1px solid #ffa500;
}

.timeline-status.status-found,
.timeline-status.status-claimed {
    background: rgba(0, 250, 154, 0.1);
    color: #00fa9a;
    border: 1px solid #00fa9a;
}

.timeline-status.status-returned {
    background: rgba(255, 20, 147, 0.1);
    color: var(--primary);
    border: 1px solid var(--primary);
}

.timeline-view {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--primary);
    text-decoration: none;
    font-size: 11px;
    font-weight: 500;
    transition: var(--transition);
}

.timeline-view:hover {
    gap: 8px;
}

.empty-timeline {
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}

.empty-timeline i {
    font-size: 48px;
    margin-bottom: 10px;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 992px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-avatar-section {
        flex-direction: column;
        text-align: center;
    }

    .profile-badges {
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .timeline-content-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>
@endsection