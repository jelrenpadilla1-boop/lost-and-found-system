@extends('layouts.app')

@section('title', 'Foundify - Dashboard')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<style>
/* ── MODERN DESIGN SYSTEM (matches landing page) ───────────────── */
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
    --glass: rgba(255, 255, 255, 0.03);
    --glass-b: rgba(255, 255, 255, 0.06);
    --glass-hover: rgba(255, 255, 255, 0.08);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Dashboard Container */
.dashboard-container {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 28px 32px;
}

/* Welcome Header */
.welcome-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-light);
}

.welcome-content h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.02em;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.welcome-content h1 i {
    color: var(--accent);
    font-size: 26px;
}

.admin-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    color: var(--accent);
    background: var(--accent-soft);
    border-radius: 20px;
    padding: 4px 10px;
    letter-spacing: 0.03em;
}

.welcome-content p {
    font-size: 14px;
    color: var(--text-muted);
}

.welcome-content p span {
    color: var(--accent);
    font-weight: 600;
}

.header-actions {
    display: flex;
    gap: 12px;
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.stat-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.icon-teal {
    background: var(--accent-soft);
    color: var(--accent);
}

.icon-green {
    background: var(--success-soft);
    color: var(--success);
}

.icon-amber {
    background: var(--warning-soft);
    color: var(--warning);
}

.icon-purple {
    background: rgba(167, 139, 250, 0.15);
    color: #a78bfa;
}

.stat-value {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
    letter-spacing: -0.02em;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}

.stat-trend {
    font-size: 11px;
    margin-top: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 20px;
}

.trend-positive {
    background: var(--success-soft);
    color: var(--success);
}

.trend-warning {
    background: var(--warning-soft);
    color: var(--warning);
}

/* Section Titles */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    color: var(--accent);
    font-size: 18px;
}

/* Tables */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-bottom: 28px;
    transition: var(--transition);
}

.table-card:hover {
    border-color: var(--accent);
}

.table-header {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h5 {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-header h5 i {
    color: var(--accent);
}

.view-link {
    font-size: 11px;
    font-weight: 600;
    color: var(--accent);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: var(--transition);
}

.view-link:hover {
    gap: 8px;
    color: var(--accent-light);
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 14px 16px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.data-table td {
    padding: 14px 16px;
    font-size: 13px;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-light);
}

.data-table tr:hover td {
    background: var(--glass);
}

.item-link {
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.item-link:hover {
    color: var(--accent);
}

/* Badges */
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.badge-high {
    background: var(--success-soft);
    color: var(--success);
}

.badge-medium {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge-pending {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge-confirmed,
.badge-found,
.badge-claimed {
    background: var(--success-soft);
    color: var(--success);
}

.badge-lost {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge-found-type {
    background: var(--success-soft);
    color: var(--success);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-light);
    background: var(--bg-card);
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.action-btn.view:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
}

.action-btn.approve:hover {
    border-color: var(--success);
    color: var(--success);
    background: var(--success-soft);
}

.action-btn.reject:hover {
    border-color: var(--error);
    color: var(--error);
    background: var(--error-soft);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px;
    color: var(--text-muted);
    font-size: 13px;
}

/* Profile Card */
.profile-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 28px;
    text-align: center;
    transition: var(--transition);
}

.profile-card:hover {
    border-color: var(--accent);
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 40px;
    background: var(--accent-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 32px;
    font-weight: 700;
    color: var(--accent);
    border: 2px solid var(--accent);
}

.profile-card h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.member-badge {
    font-size: 11px;
    color: var(--text-muted);
    background: var(--glass);
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 20px;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    padding-top: 20px;
    border-top: 1px solid var(--border-light);
}

.profile-stat-item {
    text-align: center;
}

.stat-number {
    font-size: 22px;
    font-weight: 800;
    color: var(--accent);
    line-height: 1;
    margin-bottom: 4px;
}

.profile-stat-item .stat-label {
    font-size: 10px;
}

/* Items List */
.items-list {
    padding: 8px;
}

.item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    text-decoration: none;
    border-radius: 12px;
    transition: var(--transition);
}

.item-row:hover {
    background: var(--glass);
    transform: translateX(4px);
}

.item-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.item-icon.lost {
    background: var(--accent-soft);
    color: var(--accent);
}

.item-icon.found {
    background: var(--success-soft);
    color: var(--success);
}

.item-details h6 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.item-date {
    font-size: 11px;
    color: var(--text-muted);
}

.empty-state-item {
    text-align: center;
    padding: 32px;
    color: var(--text-muted);
    font-size: 12px;
}

/* Quick Actions */
.quick-actions {
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid var(--border-light);
}

.quick-actions-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}

.quick-actions-label i {
    color: var(--accent);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
}

.quick-action-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 20px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}

.quick-action-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.quick-action-card i {
    font-size: 28px;
    margin-bottom: 12px;
    display: block;
}

.quick-action-card span {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    margin-bottom: 28px;
}

/* Responsive */
@media (max-width: 1100px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 900px) {
    .dashboard-container {
        padding: 20px;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .welcome-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .data-table th,
    .data-table td {
        padding: 10px 12px;
        font-size: 12px;
    }
}

/* Animations */
.fade-in {
    opacity: 0;
    transform: translateY(16px);
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<div class="dashboard-container">
    {{-- Welcome Header --}}
    <div class="welcome-header fade-in">
        <div class="welcome-content">
            <h1>
                <i class="fas fa-{{ $isAdmin ? 'crown' : 'home' }}"></i>
                {{ $isAdmin ? 'Admin Dashboard' : 'Dashboard' }}
                @if($isAdmin)
                    <span class="admin-tag"><i class="fas fa-shield-alt"></i> ADMIN</span>
                @endif
            </h1>
            <p>Welcome back, <span>{{ Auth::user()->name }}</span> — here's what's happening with your items.</p>
        </div>

        @if(!$isAdmin)
        <div class="header-actions">
            <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Report Lost
            </a>
            <a href="{{ route('found-items.create') }}" class="btn btn-outline">
                <i class="fas fa-check"></i> Report Found
            </a>
        </div>
        @endif
    </div>

    @if($isAdmin)
    {{-- ADMIN VIEW --}}
    <div class="stats-grid fade-in" style="animation-delay: 0.05s">
        <a href="{{ route('admin.users.index') }}" class="stat-card">
            <div class="stat-icon icon-teal"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
                <span class="stat-trend trend-positive"><i class="fas fa-circle" style="font-size: 5px;"></i> Active</span>
            </div>
        </a>
        <a href="{{ route('matches.index') }}" class="stat-card">
            <div class="stat-icon icon-purple"><i class="fas fa-exchange-alt"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_matches'] ?? 0 }}</div>
                <div class="stat-label">Total Matches</div>
                <span class="stat-trend trend-warning"><i class="fas fa-circle" style="font-size: 5px;"></i> {{ $detailedStats['matches']['pending'] ?? 0 }} pending</span>
            </div>
        </a>
        <a href="{{ route('matches.index', ['status' => 'confirmed']) }}" class="stat-card">
            <div class="stat-icon icon-green"><i class="fas fa-handshake"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['confirmed_matches'] ?? 0 }}</div>
                <div class="stat-label">Successful</div>
                <span class="stat-trend trend-positive"><i class="fas fa-circle" style="font-size: 5px;"></i> Confirmed</span>
            </div>
        </a>
        <a href="{{ route('matches.index', ['status' => 'pending']) }}" class="stat-card">
            <div class="stat-icon icon-amber"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $detailedStats['matches']['pending'] ?? 0 }}</div>
                <div class="stat-label">Pending</div>
                <span class="stat-trend trend-warning"><i class="fas fa-circle" style="font-size: 5px;"></i> Awaiting</span>
            </div>
        </a>
    </div>

    {{-- Pending Lost Items --}}
    <div class="table-card fade-in" style="animation-delay: 0.1s">
        <div class="table-header">
            <h5><i class="fas fa-search"></i> Lost Items — Pending Review</h5>
            <a href="{{ route('lost-items.index', ['status' => 'pending']) }}" class="view-link">View All →</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Reported By</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLost ?? [] as $item)
                    <tr>
                        <td><a href="{{ route('lost-items.show', $item) }}" class="item-link">{{ $item->item_name }}</a></td>
                        <td>{{ $item->user->name ?? 'Unknown' }}</td>
                        <td>{{ $item->location ?? '—' }}</td>
                        <td>{{ $item->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('lost-items.show', $item) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('lost-items.approve', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn approve" onclick="return confirm('Approve this item?')"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="{{ route('lost-items.reject', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn reject" onclick="return confirm('Reject this item?')"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">No pending lost items</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Found Items --}}
    <div class="table-card fade-in" style="animation-delay: 0.15s">
        <div class="table-header">
            <h5><i class="fas fa-check-circle"></i> Found Items — Pending Review</h5>
            <a href="{{ route('found-items.index', ['status' => 'pending']) }}" class="view-link">View All →</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Reported By</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingFound ?? [] as $item)
                    <tr>
                        <td><a href="{{ route('found-items.show', $item) }}" class="item-link">{{ $item->item_name }}</a></td>
                        <td>{{ $item->user->name ?? 'Unknown' }}</td>
                        <td>{{ $item->location ?? '—' }}</td>
                        <td>{{ $item->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('found-items.show', $item) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('found-items.approve', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn approve" onclick="return confirm('Approve this item?')"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="{{ route('found-items.reject', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn reject" onclick="return confirm('Reject this item?')"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">No pending found items</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Matches --}}
    <div class="table-card fade-in" style="animation-delay: 0.2s">
        <div class="table-header">
            <h5><i class="fas fa-exchange-alt"></i> Matches — Pending Confirmation</h5>
            <a href="{{ route('matches.index', ['status' => 'pending']) }}" class="view-link">View All →</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Lost Item</th>
                        <th>Found Item</th>
                        <th>Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingMatches ?? [] as $match)
                    <tr>
                        <td><a href="{{ route('lost-items.show', $match->lostItem) }}" class="item-link">{{ $match->lostItem->item_name ?? '—' }}</a></td>
                        <td><a href="{{ route('found-items.show', $match->foundItem) }}" class="item-link">{{ $match->foundItem->item_name ?? '—' }}</a></td>
                        <td><span class="badge {{ $match->match_score >= 80 ? 'badge-high' : 'badge-medium' }}">{{ $match->match_score }}%</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('matches.show', $match) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn approve" onclick="return confirm('Confirm this match?')"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn reject" onclick="return confirm('Reject this match?')"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-state">No pending matches</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @else
    {{-- USER VIEW --}}
    
    {{-- Stats Grid --}}
    <div class="stats-grid fade-in" style="animation-delay: 0.05s">
        <a href="{{ route('lost-items.my-items') }}" class="stat-card">
            <div class="stat-icon icon-teal"><i class="fas fa-search"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ Auth::user()->lostItems()->count() }}</div>
                <div class="stat-label">Lost Items</div>
            </div>
        </a>
        <a href="{{ route('found-items.my-items') }}" class="stat-card">
            <div class="stat-icon icon-green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ Auth::user()->foundItems()->count() }}</div>
                <div class="stat-label">Found Items</div>
            </div>
        </a>
        <a href="{{ route('matches.my-matches') }}" class="stat-card">
            <div class="stat-icon icon-purple"><i class="fas fa-exchange-alt"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $highMatches->count() }}</div>
                <div class="stat-label">Potential Matches</div>
            </div>
        </a>
        <a href="{{ route('matches.my-matches', ['status' => 'confirmed']) }}" class="stat-card">
            <div class="stat-icon icon-amber"><i class="fas fa-trophy"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ Auth::user()->lostItems()->where('status', 'found')->count() + Auth::user()->foundItems()->where('status', 'claimed')->count() }}</div>
                <div class="stat-label">Recovered</div>
            </div>
        </a>
    </div>

    {{-- Profile & Matches --}}
    <div class="content-grid fade-in" style="animation-delay: 0.1s">
        <div class="profile-card">
            <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <h5>{{ Auth::user()->name }}</h5>
            <span class="member-badge">Member since {{ Auth::user()->created_at->format('M Y') }}</span>
            <div class="profile-stats">
                <div class="profile-stat-item">
                    <div class="stat-number">{{ Auth::user()->lostItems()->count() + Auth::user()->foundItems()->count() }}</div>
                    <div class="stat-label">Total Items</div>
                </div>
                <div class="profile-stat-item">
                    <div class="stat-number">{{ $highMatches->count() }}</div>
                    <div class="stat-label">Matches</div>
                </div>
                <div class="profile-stat-item">
                    <div class="stat-number">{{ Auth::user()->lostItems()->where('status', 'found')->count() }}</div>
                    <div class="stat-label">Recovered</div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h5><i class="fas fa-microchip"></i> Potential Matches</h5>
                <a href="{{ route('matches.my-matches') }}" class="view-link">View All →</a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($highMatches as $match)
                        <tr>
                            <td>
                                @if($match->lostItem && $match->lostItem->user_id == Auth::id())
                                    <a href="{{ route('lost-items.show', $match->lostItem) }}" class="item-link">{{ $match->lostItem->item_name }}</a>
                                @elseif($match->foundItem && $match->foundItem->user_id == Auth::id())
                                    <a href="{{ route('found-items.show', $match->foundItem) }}" class="item-link">{{ $match->foundItem->item_name }}</a>
                                @endif
                            </td>
                            <td>
                                @if($match->lostItem && $match->lostItem->user_id == Auth::id())
                                    <span class="badge badge-lost">LOST</span>
                                @else
                                    <span class="badge badge-found-type">FOUND</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $match->match_score >= 80 ? 'badge-high' : 'badge-medium' }}">{{ $match->match_score }}%</span></td>
                            <td><span class="badge badge-{{ $match->status }}">{{ strtoupper($match->status) }}</span></td>
                            <td><a href="{{ route('matches.show', $match) }}" class="action-btn view"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-state">No matches yet — keep reporting items!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Items --}}
    <div class="stats-grid fade-in" style="animation-delay: 0.15s; gap: 24px;">
        <div class="table-card" style="margin: 0;">
            <div class="table-header">
                <h5><i class="fas fa-search"></i> Recent Lost Items</h5>
                <a href="{{ route('lost-items.my-items') }}" class="view-link">View All →</a>
            </div>
            <div class="items-list">
                @forelse($recentLost as $item)
                <a href="{{ route('lost-items.show', $item) }}" class="item-row">
                    <div class="item-info">
                        <div class="item-icon lost"><i class="fas fa-search"></i></div>
                        <div class="item-details">
                            <h6>{{ $item->item_name }}</h6>
                            <span class="item-date">{{ $item->created_at->format('M d, Y') }} • {{ $item->location ?? 'No location' }}</span>
                        </div>
                    </div>
                    <span class="badge badge-{{ $item->status }}">{{ strtoupper($item->status == 'pending' ? 'ACTIVE' : $item->status) }}</span>
                </a>
                @empty
                <div class="empty-state-item">No lost items reported yet</div>
                @endforelse
            </div>
        </div>

        <div class="table-card" style="margin: 0;">
            <div class="table-header">
                <h5><i class="fas fa-check-circle"></i> Recent Found Items</h5>
                <a href="{{ route('found-items.my-items') }}" class="view-link">View All →</a>
            </div>
            <div class="items-list">
                @forelse($recentFound as $item)
                <a href="{{ route('found-items.show', $item) }}" class="item-row">
                    <div class="item-info">
                        <div class="item-icon found"><i class="fas fa-check-circle"></i></div>
                        <div class="item-details">
                            <h6>{{ $item->item_name }}</h6>
                            <span class="item-date">{{ $item->created_at->format('M d, Y') }} • {{ $item->location ?? 'No location' }}</span>
                        </div>
                    </div>
                    <span class="badge badge-{{ $item->status }}">{{ strtoupper($item->status) }}</span>
                </a>
                @empty
                <div class="empty-state-item">No found items reported yet</div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="quick-actions fade-in" style="animation-delay: 0.25s">
        <div class="quick-actions-label">
            <i class="fas fa-bolt"></i> Quick Actions
        </div>
        <div class="actions-grid">
            @if(!$isAdmin)
            <a href="{{ route('lost-items.create') }}" class="quick-action-card">
                <i class="fas fa-plus-circle" style="color: var(--accent);"></i>
                <span>Report Lost Item</span>
            </a>
            <a href="{{ route('found-items.create') }}" class="quick-action-card">
                <i class="fas fa-check-circle" style="color: var(--success);"></i>
                <span>Report Found Item</span>
            </a>
            @endif
            <a href="{{ route('map.index') }}" class="quick-action-card">
                <i class="fas fa-map-marked-alt" style="color: #a78bfa;"></i>
                <span>View Map</span>
            </a>
            <a href="{{ route('matches.index') }}" class="quick-action-card">
                <i class="fas fa-exchange-alt" style="color: var(--warning);"></i>
                <span>All Matches</span>
            </a>
            @if($isAdmin)
            <a href="{{ route('admin.users.index') }}" class="quick-action-card">
                <i class="fas fa-users-cog" style="color: var(--accent);"></i>
                <span>Manage Users</span>
            </a>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Smooth fade-in for cards
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach((el, index) => {
        if (!el.style.animationDelay) {
            el.style.animationDelay = `${index * 0.05}s`;
        }
    });
});
</script>
@endsection