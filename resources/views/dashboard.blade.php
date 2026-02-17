@extends('layouts.app')

@section('title', 'Foundify - Dashboard')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<div class="dashboard-wrapper">
    <!-- Welcome Header -->
    <div class="welcome-header fade-in">
        <div class="welcome-content">
            <h1>
                <i class="fas fa-{{ $isAdmin ? 'crown' : 'home' }}" style="color: var(--primary);"></i>
                {{ $isAdmin ? 'Admin Dashboard' : 'Dashboard' }}
            </h1>
            <p>Welcome back, <span style="color: var(--primary); font-weight: 600;">{{ Auth::user()->name }}</span>!</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                <i class="fas fa-exclamation-circle"></i> Report Lost
            </a>
            <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                <i class="fas fa-check-circle"></i> Report Found
            </a>
        </div>
    </div>

    @if($isAdmin)
        <!-- ADMIN DASHBOARD -->
        
        <!-- Admin Stats Cards -->
        <div class="row mb-4 fade-in">
            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.users.index') }}" class="stats-link">
                    <div class="admin-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                            <div class="stat-label">Total Users</div>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> Active
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 mb-3">
                <a href="{{ route('matches.index') }}" class="stats-link">
                    <div class="admin-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $stats['total_matches'] ?? 0 }}</div>
                            <div class="stat-label">Total Matches</div>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> {{ $detailedStats['matches']['pending'] ?? 0 }} pending
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 mb-3">
                <a href="{{ route('matches.index', ['status' => 'confirmed']) }}" class="stats-link">
                    <div class="admin-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $stats['confirmed_matches'] ?? 0 }}</div>
                            <div class="stat-label">Successful</div>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> Confirmed
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 mb-3">
                <a href="{{ route('matches.index', ['status' => 'pending']) }}" class="stats-link">
                    <div class="admin-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #ff4444, #ff6b6b);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $detailedStats['matches']['pending'] ?? 0 }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-trend negative">
                            <i class="fas fa-arrow-down"></i> Awaiting review
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- System Health Cards -->
        <div class="row fade-in">
            <div class="col-md-4 mb-4">
                <div class="health-card">
                    <div class="health-icon success">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="health-content">
                        <h6>Lost Items</h6>
                        <div class="health-status">
                            <span class="status-dot success"></span>
                            <span>{{ $detailedStats['lost_items']['total'] ?? 0 }} total</span>
                        </div>
                        <small class="text-muted">{{ $detailedStats['lost_items']['pending'] ?? 0 }} pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="health-card">
                    <div class="health-icon info">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="health-content">
                        <h6>Found Items</h6>
                        <div class="health-status">
                            <span class="status-dot success"></span>
                            <span>{{ $detailedStats['found_items']['total'] ?? 0 }} total</span>
                        </div>
                        <small class="text-muted">{{ $detailedStats['found_items']['pending'] ?? 0 }} pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="health-card">
                    <div class="health-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="health-content">
                        <h6>Matches</h6>
                        <div class="health-status">
                            <span class="status-dot success"></span>
                            <span>{{ $detailedStats['matches']['total'] ?? 0 }} total</span>
                        </div>
                        <small class="text-muted">{{ $detailedStats['matches']['confirmed'] ?? 0 }} confirmed</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Items Tables -->
        <div class="row fade-in">
            <div class="col-lg-6 mb-4">
                <div class="admin-table-card">
                    <div class="card-header">
                        <h5><i class="fas fa-exclamation-circle" style="color: var(--primary);"></i> Recent Lost Items</h5>
                        <a href="{{ route('lost-items.index') }}" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Reported By</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentLost as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('lost-items.show', $item) }}" class="item-link">
                                                {{ $item->item_name }}
                                            </a>
                                        </td>
                                        <td>{{ $item->user->name ?? 'Unknown' }}</td>
                                        <td>{{ $item->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $item->status }}">
                                                {{ $item->status == 'pending' ? 'Missing' : ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('lost-items.show', $item) }}" class="action-btn view" title="View Item">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No lost items found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="admin-table-card">
                    <div class="card-header">
                        <h5><i class="fas fa-check-circle" style="color: #00fa9a;"></i> Recent Found Items</h5>
                        <a href="{{ route('found-items.index') }}" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Reported By</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentFound as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('found-items.show', $item) }}" class="item-link">
                                                {{ $item->item_name }}
                                            </a>
                                        </td>
                                        <td>{{ $item->user->name ?? 'Unknown' }}</td>
                                        <td>{{ $item->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $item->status }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('found-items.show', $item) }}" class="action-btn view" title="View Item">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No found items found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- High Probability Matches -->
        <div class="row fade-in">
            <div class="col-12 mb-4">
                <div class="admin-table-card">
                    <div class="card-header">
                        <h5><i class="fas fa-exchange-alt" style="color: var(--primary);"></i> High Probability Matches (80%+)</h5>
                        <a href="{{ route('matches.index', ['min_score' => 80]) }}" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Lost Item</th>
                                        <th>Found Item</th>
                                        <th>Match Score</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($highMatches as $match)
                                    <tr>
                                        <td>
                                            <a href="{{ route('lost-items.show', $match->lostItem) }}" class="item-link">
                                                {{ $match->lostItem->item_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('found-items.show', $match->foundItem) }}" class="item-link">
                                                {{ $match->foundItem->item_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : 'medium' }}">
                                                {{ $match->match_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $match->status }}">
                                                {{ ucfirst($match->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('matches.show', $match) }}" class="action-btn view" title="View Match">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($match->status === 'pending')
                                                    <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="action-btn confirm" title="Confirm Match" onclick="return confirm('Confirm this match?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="action-btn reject" title="Reject Match" onclick="return confirm('Reject this match?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No high probability matches found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- USER DASHBOARD -->
        
        <!-- User Stats Cards -->
        <div class="row mb-4 fade-in">
            <div class="col-md-3 mb-3">
                <a href="{{ route('lost-items.my-items') }}" class="stats-link">
                    <div class="user-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ Auth::user()->lostItems()->count() }}</div>
                            <div class="stat-label">My Lost Items</div>
                        </div>
                        <div class="stat-action">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 mb-3">
                <a href="{{ route('found-items.my-items') }}" class="stats-link">
                    <div class="user-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ Auth::user()->foundItems()->count() }}</div>
                            <div class="stat-label">My Found Items</div>
                        </div>
                        <div class="stat-action">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 mb-3">
                <a href="{{ route('matches.my-matches') }}" class="stats-link">
                    <div class="user-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $highMatches->count() }}</div>
                            <div class="stat-label">My Matches</div>
                        </div>
                        <div class="stat-action">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 mb-3">
                <a href="{{ route('matches.my-matches', ['status' => 'confirmed']) }}" class="stats-link">
                    <div class="user-stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ Auth::user()->lostItems()->where('status', 'found')->count() + Auth::user()->foundItems()->where('status', 'claimed')->count() }}</div>
                            <div class="stat-label">Recovered</div>
                        </div>
                        <div class="stat-action">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- User Profile & Recent Items -->
        <div class="row fade-in">
            <div class="col-lg-4 mb-4">
                <!-- User Profile Card -->
                <div class="user-profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="profile-info">
                            <h5>{{ Auth::user()->name }}</h5>
                            <span class="badge" style="background: var(--primary);">Member since {{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <div class="stat-number">{{ Auth::user()->lostItems()->count() + Auth::user()->foundItems()->count() }}</div>
                            <div class="stat-label">Total Items</div>
                        </div>
                        <div class="profile-stat">
                            <div class="stat-number">{{ $highMatches->count() }}</div>
                            <div class="stat-label">Matches</div>
                        </div>
                        <div class="profile-stat">
                            <div class="stat-number">{{ Auth::user()->lostItems()->where('status', 'found')->count() }}</div>
                            <div class="stat-label">Recovered</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-4">
                <!-- User's High Probability Matches -->
                <div class="admin-table-card">
                    <div class="card-header">
                        <h5><i class="fas fa-exchange-alt" style="color: var(--primary);"></i> Your Potential Matches</h5>
                        <a href="{{ route('matches.my-matches') }}" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Type</th>
                                        <th>Match Score</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($highMatches as $match)
                                    <tr>
                                        <td>
                                            @if($match->lostItem && $match->lostItem->user_id == Auth::id())
                                                <a href="{{ route('lost-items.show', $match->lostItem) }}" class="item-link">
                                                    {{ $match->lostItem->item_name }}
                                                </a>
                                            @elseif($match->foundItem && $match->foundItem->user_id == Auth::id())
                                                <a href="{{ route('found-items.show', $match->foundItem) }}" class="item-link">
                                                    {{ $match->foundItem->item_name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($match->lostItem && $match->lostItem->user_id == Auth::id())
                                                <span class="badge" style="background: var(--primary);">Lost</span>
                                            @elseif($match->foundItem && $match->foundItem->user_id == Auth::id())
                                                <span class="badge" style="background: #00fa9a; color: black;">Found</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : 'medium' }}">
                                                {{ $match->match_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $match->status }}">
                                                {{ ucfirst($match->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('matches.show', $match) }}" class="action-btn view" title="View Match">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No matches found for your items</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Items Grid -->
        <div class="row fade-in">
            <div class="col-md-6 mb-4">
                <div class="items-card">
                    <div class="card-header">
                        <h5><i class="fas fa-exclamation-circle" style="color: var(--primary);"></i> My Recent Lost Items</h5>
                        <a href="{{ route('lost-items.my-items') }}" class="view-all-link">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recentLost as $item)
                        <a href="{{ route('lost-items.show', $item) }}" class="item-row">
                            <div class="item-row-content">
                                <div class="item-icon">
                                    <i class="fas fa-exclamation-circle" style="color: var(--primary);"></i>
                                </div>
                                <div class="item-details">
                                    <h6>{{ $item->item_name }}</h6>
                                    <span class="item-meta">{{ $item->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <span class="status-badge status-{{ $item->status }}">
                                {{ $item->status == 'pending' ? 'Missing' : ucfirst($item->status) }}
                            </span>
                        </a>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-muted">No lost items yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="items-card">
                    <div class="card-header">
                        <h5><i class="fas fa-check-circle" style="color: #00fa9a;"></i> My Recent Found Items</h5>
                        <a href="{{ route('found-items.my-items') }}" class="view-all-link">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse($recentFound as $item)
                        <a href="{{ route('found-items.show', $item) }}" class="item-row">
                            <div class="item-row-content">
                                <div class="item-icon">
                                    <i class="fas fa-check-circle" style="color: #00fa9a;"></i>
                                </div>
                                <div class="item-details">
                                    <h6>{{ $item->item_name }}</h6>
                                    <span class="item-meta">{{ $item->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <span class="status-badge status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </a>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-muted">No found items yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions (Common for both) -->
    <div class="quick-actions-section fade-in">
        <div class="section-header">
            <i class="fas fa-bolt" style="color: var(--primary);"></i>
            <h5>Quick Actions</h5>
        </div>
        <div class="actions-grid">
            <a href="{{ route('lost-items.create') }}" class="action-card">
                <i class="fas fa-exclamation-circle" style="color: var(--primary);"></i>
                <span>Report Lost</span>
            </a>
            <a href="{{ route('found-items.create') }}" class="action-card">
                <i class="fas fa-check-circle" style="color: #00fa9a;"></i>
                <span>Report Found</span>
            </a>
            <a href="{{ route('map.index') }}" class="action-card">
                <i class="fas fa-map-marked-alt" style="color: var(--primary);"></i>
                <span>View Map</span>
            </a>
            <a href="{{ route('matches.index') }}" class="action-card">
                <i class="fas fa-exchange-alt" style="color: #ffa500;"></i>
                <span>All Matches</span>
            </a>
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
    --info: #8b5cf6;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Dashboard Wrapper */
.dashboard-wrapper {
    max-width: 1400px;
    margin: 0 auto;
}

/* Welcome Header */
.welcome-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.welcome-content h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.welcome-content p {
    color: var(--text-muted);
    margin: 0;
    font-size: 15px;
}

.header-actions {
    display: flex;
    gap: 12px;
}

/* Button Styles */
.btn {
    padding: 12px 24px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 2px solid transparent;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    box-shadow: 0 0 20px var(--primary-glow);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--primary-glow);
}

/* ========== ADMIN STYLES ========== */

/* Admin Stat Cards */
.admin-stat-card {
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

.stats-link {
    text-decoration: none;
    display: block;
}

.admin-stat-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.admin-stat-card .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.admin-stat-card .stat-content {
    flex: 1;
}

.admin-stat-card .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.admin-stat-card .stat-label {
    color: var(--text-muted);
    font-size: 13px;
}

.admin-stat-card .stat-trend {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.admin-stat-card .stat-trend.positive {
    background: rgba(0, 250, 154, 0.1);
    color: var(--success);
    border: 1px solid var(--success);
}

.admin-stat-card .stat-trend.negative {
    background: rgba(255, 68, 68, 0.1);
    color: var(--error);
    border: 1px solid var(--error);
}

/* Health Cards */
.health-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
}

.health-card:hover {
    border-color: var(--primary);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px var(--primary-glow);
}

.health-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.health-icon.success {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
}

.health-icon.info {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
}

.health-icon.warning {
    background: linear-gradient(135deg, #ffa500, #ffb52e);
}

.health-content h6 {
    color: var(--text-primary);
    margin: 0 0 5px 0;
    font-size: 15px;
}

.health-status {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 5px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-dot.success {
    background: var(--success);
    box-shadow: 0 0 10px var(--success);
}

/* Admin Tables */
.admin-table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    height: 100%;
}

.view-all-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: var(--transition);
}

.view-all-link:hover {
    color: var(--primary-light);
    transform: translateX(3px);
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: var(--bg-header);
    color: var(--text-primary);
    font-weight: 600;
    font-size: 12px;
    padding: 15px;
    text-align: left;
    border-bottom: 2px solid var(--primary);
}

.admin-table td {
    padding: 12px 15px;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border-color);
    font-size: 13px;
}

.admin-table tr:hover td {
    background: var(--bg-header);
}

.item-link {
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
}

.item-link:hover {
    color: var(--primary);
}

.table-actions {
    display: flex;
    gap: 5px;
}

.action-btn {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    background: transparent;
}

.action-btn.view {
    color: var(--primary);
    border: 1px solid var(--primary);
}

.action-btn.view:hover {
    background: var(--primary);
    color: white;
}

.action-btn.confirm {
    color: var(--success);
    border: 1px solid var(--success);
}

.action-btn.confirm:hover {
    background: var(--success);
    color: black;
}

.action-btn.reject {
    color: var(--error);
    border: 1px solid var(--error);
}

.action-btn.reject:hover {
    background: var(--error);
    color: white;
}

/* ========== USER STYLES ========== */

/* User Stat Cards */
.user-stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
    position: relative;
    cursor: pointer;
}

.user-stat-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.user-stat-card .stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
}

.user-stat-card .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.user-stat-card .stat-label {
    color: var(--text-muted);
    font-size: 13px;
}

.user-stat-card .stat-action {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    opacity: 0;
    transition: var(--transition);
}

.user-stat-card:hover .stat-action {
    opacity: 1;
    transform: translateY(-50%) translateX(5px);
}

/* User Profile Card */
.user-profile-card {
    background: linear-gradient(135deg, var(--bg-card), var(--bg-header));
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 20px;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 24px;
    box-shadow: 0 0 20px var(--primary-glow);
}

.profile-info h5 {
    color: var(--text-primary);
    margin: 0 0 5px 0;
    font-size: 18px;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    text-align: center;
}

.profile-stat .stat-number {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.profile-stat .stat-label {
    color: var(--text-muted);
    font-size: 11px;
}

/* Items Cards */
.items-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
}

.item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border-color);
    text-decoration: none;
    transition: var(--transition);
}

.item-row:last-child {
    border-bottom: none;
}

.item-row:hover {
    background: var(--bg-header);
    transform: translateX(5px);
}

.item-row-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-icon {
    width: 36px;
    height: 36px;
    background: rgba(255, 20, 147, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.item-details h6 {
    color: var(--text-primary);
    margin: 0 0 4px 0;
    font-size: 14px;
}

.item-meta {
    color: var(--text-muted);
    font-size: 11px;
}

/* Status Badges */
.status-badge {
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 500;
    display: inline-block;
}

.status-badge.pending {
    background: rgba(255, 165, 0, 0.15);
    color: #ffa500;
    border: 1px solid #ffa500;
}

.status-badge.found,
.status-badge.confirmed {
    background: rgba(0, 250, 154, 0.15);
    color: var(--success);
    border: 1px solid var(--success);
}

.status-badge.returned,
.status-badge.claimed {
    background: rgba(255, 20, 147, 0.15);
    color: var(--primary);
    border: 1px solid var(--primary);
}

.status-badge.rejected,
.status-badge.disposed {
    background: rgba(255, 68, 68, 0.15);
    color: var(--error);
    border: 1px solid var(--error);
}

/* Score Badges */
.score-badge {
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.score-high {
    background: rgba(0, 250, 154, 0.15);
    color: var(--success);
    border: 1px solid var(--success);
}

.score-medium {
    background: rgba(255, 165, 0, 0.15);
    color: #ffa500;
    border: 1px solid #ffa500;
}

/* Quick Actions Section */
.quick-actions-section {
    margin-top: 30px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.section-header h5 {
    color: var(--text-primary);
    margin: 0;
    font-size: 18px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition);
}

.action-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.action-card i {
    font-size: 28px;
    margin-bottom: 10px;
}

.action-card span {
    display: block;
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 500;
}

/* Common Styles */
.card-header {
    padding: 15px 20px;
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h5 {
    color: var(--text-primary);
    margin: 0;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-body {
    padding: 20px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease forwards;
}

/* Responsive */
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
    
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-table th,
    .admin-table td {
        padding: 10px;
    }
    
    .table-actions {
        flex-wrap: wrap;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 5px;
    box-shadow: 0 0 10px var(--primary-glow);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
}
</style>
@endsection