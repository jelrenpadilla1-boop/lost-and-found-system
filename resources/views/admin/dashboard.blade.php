@extends('layouts.app')

@section('title', 'Admin Dashboard - Foundify')

@push('styles')
<style>
/* Admin Dashboard — Foundify Theme */
:root {
    --bg-soft: var(--bg-secondary);
    --border-light: var(--border-color);
    --text-dark: var(--text-primary);
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    --shadow-sm: 0 4px 12px rgba(0,0,0,0.3);
    --shadow-md: 0 12px 30px rgba(0,0,0,0.5);
}

.admin-container {
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
    border-bottom: 1px solid var(--border-color);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.02em;
}

.page-title h1 i { color: var(--accent); }

.page-title p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.page-actions { display: flex; gap: 12px; flex-wrap: wrap; }

/* Buttons */
.btn-admin {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid transparent;
}

.btn-admin-primary {
    background: var(--accent);
    color: white;
}

.btn-admin-primary:hover {
    background: var(--accent-light);
    transform: scale(1.04);
    color: white;
}

.btn-admin-outline {
    background: transparent;
    border-color: var(--border-color);
    color: var(--text-muted);
}

.btn-admin-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 24px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--accent);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent);
    transform: scaleX(0);
    transition: transform 0.3s ease;
    transform-origin: left;
}

.stat-card:hover::before { transform: scaleX(1); }

.stat-card.success::before { background: var(--success); }
.stat-card.warning::before { background: var(--warning); }

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 16px;
}

.stat-icon.red   { background: var(--accent-soft); color: var(--accent); }
.stat-icon.green { background: var(--success-soft); color: var(--success); }
.stat-icon.yellow { background: var(--warning-soft); color: var(--warning); }
.stat-icon.blue  { background: rgba(59,130,246,0.15); color: #60a5fa; }

.stat-number {
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.stat-week {
    margin-top: 12px;
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-week i { color: var(--success); font-size: 10px; }

/* Section Cards */
.section-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
}

.section-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.section-card:hover { box-shadow: var(--shadow-md); }

.section-header {
    padding: 16px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-header h5 i { color: var(--accent); }

.section-link {
    font-size: 12px;
    color: var(--text-muted);
    text-decoration: none;
    transition: color 0.2s;
    display: flex;
    align-items: center;
    gap: 4px;
}

.section-link:hover { color: var(--accent); }

/* User List */
.user-list { padding: 8px 0; }

.user-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    transition: var(--transition);
    border-bottom: 1px solid var(--border-color);
}

.user-row:last-child { border-bottom: none; }

.user-row:hover { background: var(--bg-secondary); }

.user-avatar {
    width: 38px; height: 38px;
    border-radius: 8px;
    background: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
    text-decoration: none;
}

.user-info { flex: 1; min-width: 0; }

.user-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-email {
    font-size: 11px;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-meta {
    font-size: 11px;
    color: var(--text-muted);
    text-align: right;
    flex-shrink: 0;
}

/* Role Badge */
.role-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin { background: var(--accent-soft); color: var(--accent); }
.role-user  { background: var(--success-soft); color: var(--success); }

/* Match List */
.match-list { padding: 8px 0; }

.match-row {
    padding: 12px 20px;
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
}

.match-row:last-child { border-bottom: none; }
.match-row:hover { background: var(--bg-secondary); }

.match-items {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
    font-size: 13px;
}

.match-item-name {
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px;
}

.match-arrow { color: var(--text-muted); font-size: 11px; }

.match-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.match-score {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.match-score i { color: var(--warning); }

/* Status Badges */
.status-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending  { background: var(--warning-soft); color: var(--warning); }
.status-confirmed { background: var(--success-soft); color: var(--success); }
.status-rejected { background: var(--error-soft); color: var(--error); }

/* Quick Stats Row */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.quick-stat {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
}

.quick-stat:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-sm);
}

.quick-stat-icon {
    width: 44px; height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.quick-stat-info { flex: 1; }
.quick-stat-number { font-size: 22px; font-weight: 800; color: var(--text-primary); line-height: 1; }
.quick-stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }

/* Empty state */
.empty-row {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-muted);
    font-size: 13px;
}

.empty-row i { display: block; font-size: 28px; margin-bottom: 8px; color: var(--border-color); }

/* Admin Quick Links */
.quick-links {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.quick-link-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 10px;
    transition: var(--transition);
}

.quick-link-card:hover {
    border-color: var(--accent);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.quick-link-card:hover .quick-link-icon { background: var(--accent); color: white; }

.quick-link-icon {
    width: 48px; height: 48px;
    border-radius: 8px;
    background: var(--accent-soft);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: var(--transition);
}

.quick-link-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Fade-in */
.fade-in { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

.fade-in:nth-child(1) { animation-delay: 0.05s; }
.fade-in:nth-child(2) { animation-delay: 0.10s; }
.fade-in:nth-child(3) { animation-delay: 0.15s; }
.fade-in:nth-child(4) { animation-delay: 0.20s; }

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .quick-links { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .admin-container { padding: 16px; }
    .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .section-grid { grid-template-columns: 1fr; }
    .quick-stats { grid-template-columns: 1fr; }
    .quick-links { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
}

@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr; }
    .quick-links { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush

@section('content')
<div class="admin-container">

    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <p>Platform overview and recent activity</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-outline">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="{{ route('admin.items.index') }}" class="btn-admin btn-admin-primary">
                <i class="fas fa-search"></i> View Items
            </a>
        </div>
    </div>

    {{-- Main Stats --}}
    <div class="stats-grid">
        <div class="stat-card fade-in">
            <div class="stat-icon red"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-week">
                <i class="fas fa-arrow-up"></i>
                +{{ $stats['users_this_week'] }} this week
            </div>
        </div>

        <div class="stat-card fade-in">
            <div class="stat-icon red"><i class="fas fa-search"></i></div>
            <div class="stat-number">{{ number_format($stats['total_lost_items']) }}</div>
            <div class="stat-label">Lost Items</div>
            <div class="stat-week">
                <i class="fas fa-clock"></i>
                +{{ $stats['items_this_week'] }} items this week
            </div>
        </div>

        <div class="stat-card success fade-in">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ number_format($stats['total_found_items']) }}</div>
            <div class="stat-label">Found Items</div>
            <div class="stat-week">
                <i class="fas fa-arrow-up"></i>
                Actively reported
            </div>
        </div>

        <div class="stat-card warning fade-in">
            <div class="stat-icon yellow"><i class="fas fa-link"></i></div>
            <div class="stat-number">{{ number_format($stats['total_matches']) }}</div>
            <div class="stat-label">Total Matches</div>
            <div class="stat-week">
                <i class="fas fa-check"></i>
                {{ $stats['confirmed_matches'] }} confirmed
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="quick-stats">
        <div class="quick-stat fade-in">
            <div class="quick-stat-icon" style="background: var(--warning-soft); color: var(--warning);">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-number">{{ $stats['pending_matches'] }}</div>
                <div class="quick-stat-label">Pending Matches</div>
            </div>
        </div>

        <div class="quick-stat fade-in">
            <div class="quick-stat-icon" style="background: var(--success-soft); color: var(--success);">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-number">{{ $stats['confirmed_matches'] }}</div>
                <div class="quick-stat-label">Confirmed Matches</div>
            </div>
        </div>

        <div class="quick-stat fade-in">
            <div class="quick-stat-icon" style="background: var(--accent-soft); color: var(--accent);">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-number">{{ $stats['users_this_week'] }}</div>
                <div class="quick-stat-label">New Users (7 days)</div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="quick-links">
        <a href="{{ route('admin.users.index') }}" class="quick-link-card fade-in">
            <div class="quick-link-icon"><i class="fas fa-users-cog"></i></div>
            <div class="quick-link-label">Users</div>
        </a>
        <a href="{{ route('admin.items.lost') }}" class="quick-link-card fade-in">
            <div class="quick-link-icon"><i class="fas fa-search-location"></i></div>
            <div class="quick-link-label">Lost Items</div>
        </a>
        <a href="{{ route('admin.items.found') }}" class="quick-link-card fade-in">
            <div class="quick-link-icon"><i class="fas fa-box-open"></i></div>
            <div class="quick-link-label">Found Items</div>
        </a>
        <a href="{{ route('admin.matches.index') }}" class="quick-link-card fade-in">
            <div class="quick-link-icon"><i class="fas fa-link"></i></div>
            <div class="quick-link-label">Matches</div>
        </a>
    </div>

    {{-- Recent Users & Recent Matches --}}
    <div class="section-grid">

        {{-- Recent Users --}}
        <div class="section-card fade-in">
            <div class="section-header">
                <h5><i class="fas fa-user-clock"></i> Recent Users</h5>
                <a href="{{ route('admin.users.index') }}" class="section-link">
                    View all <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="user-list">
                @forelse($recentUsers as $user)
                <div class="user-row">
                    <a href="{{ route('admin.users.show', $user) }}" class="user-avatar">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </a>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>
                    <div class="user-meta">
                        <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                            {{ $user->role ?? 'user' }}
                        </span>
                        <div style="margin-top: 4px; font-size: 10px;">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-row">
                    <i class="fas fa-users"></i>
                    No users yet
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Matches --}}
        <div class="section-card fade-in">
            <div class="section-header">
                <h5><i class="fas fa-link"></i> Recent Matches</h5>
                <a href="{{ route('admin.matches.index') }}" class="section-link">
                    View all <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="match-list">
                @forelse($recentMatches as $match)
                <div class="match-row">
                    <div class="match-items">
                        <span class="match-item-name">{{ $match->lostItem?->item_name ?? 'Deleted item' }}</span>
                        <span class="match-arrow"><i class="fas fa-arrows-alt-h"></i></span>
                        <span class="match-item-name">{{ $match->foundItem?->item_name ?? 'Deleted item' }}</span>
                    </div>
                    <div class="match-footer">
                        <span class="match-score">
                            <i class="fas fa-star"></i>
                            {{ number_format($match->match_score * 100, 0) }}% match
                        </span>
                        <span class="status-badge status-{{ $match->status }}">{{ $match->status }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-row">
                    <i class="fas fa-link"></i>
                    No matches yet
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
