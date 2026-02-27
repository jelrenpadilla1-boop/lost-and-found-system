@extends('layouts.app')

@section('title', 'All Matches')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<div class="dashboard-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <h1>
                <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> All Matches
            </h1>
            <p>Potential matches between lost and found items</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => ''])) }}" class="stats-link">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Matches</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="stats-link">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                @if($isAdmin && $stats['pending'] > 0)
                <span class="pending-badge">{{ $stats['pending'] }}</span>
                @endif
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => 'confirmed'])) }}" class="stats-link">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['confirmed'] }}</div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" class="stats-link">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ff4444, #ff6b6b);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['rejected'] }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Active Filter Indicators -->
    @if(request('status') || request('min_score') || request('max_score'))
    <div class="custom-alert info-alert mb-4">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-filter"></i>
            </div>
            <div class="alert-text">
                <strong>Active Filters:</strong>
                <div class="filter-tags">
                    @if(request('status'))
                        <span class="filter-tag">
                            Status: {{ ucfirst(request('status')) }}
                        </span>
                    @endif
                    @if(request('min_score'))
                        <span class="filter-tag">
                            Min Score: {{ request('min_score') }}%
                        </span>
                    @endif
                    @if(request('max_score'))
                        <span class="filter-tag">
                            Max Score: {{ request('max_score') }}%
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('matches.index') }}" class="alert-action-btn">
                <i class="fas fa-times"></i> Clear Filters
            </a>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="filter-card mb-4">
        <div class="filter-card-body">
            <form method="GET" action="{{ route('matches.index') }}" id="filterForm">
                <div class="filter-form-row">
                    <div class="filter-form-group">
                        <label for="status" class="filter-label">
                            <i class="fas fa-circle"></i> Status
                        </label>
                        <select class="filter-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="filter-form-group">
                        <label for="min_score" class="filter-label">
                            <i class="fas fa-chart-line"></i> Min Score
                        </label>
                        <input type="number" class="filter-input" id="min_score" name="min_score" 
                               value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                    </div>
                    
                    <div class="filter-form-group">
                        <label for="max_score" class="filter-label">
                            <i class="fas fa-chart-line"></i> Max Score
                        </label>
                        <input type="number" class="filter-input" id="max_score" name="max_score" 
                               value="{{ request('max_score') }}" min="0" max="100" placeholder="100">
                    </div>
                    
                    <div class="filter-form-group filter-actions-group">
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('matches.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-redo-alt"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Matches List -->
    <div class="matches-grid">
        @forelse($matches as $match)
        <div class="match-card-wrapper">
            <div class="match-card">
                <div class="match-card-header">
                    <div class="match-title">
                        <h5>Match #{{ $match->id }}</h5>
                        <div class="match-badges">
                            <span class="score-badge 
                                @if($match->match_score >= 80) score-high
                                @elseif($match->match_score >= 60) score-medium
                                @else score-low
                                @endif">
                                {{ $match->match_score }}%
                            </span>
                            <span class="status-badge status-{{ $match->status }}">
                                {{ ucfirst($match->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="match-card-body">
                    <div class="items-comparison">
                        <!-- Lost Item -->
                        <div class="item-side lost">
                            <div class="item-header">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Lost Item</span>
                            </div>
                            <div class="item-details">
                                <h6>{{ $match->lostItem->item_name }}</h6>
                                <p class="item-desc">{{ Str::limit($match->lostItem->description, 50) }}</p>
                                
                                <div class="item-meta-list">
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $match->lostItem->user->name }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $match->lostItem->date_lost->format('M d, Y') }}</span>
                                    </div>
                                    
                                    @if($match->lostItem->lost_location)
                                    <div class="meta-item location" title="{{ $match->lostItem->lost_location }}">
                                        <i class="fas fa-map-marked-alt"></i>
                                        <span>{{ Str::limit($match->lostItem->lost_location, 20) }}</span>
                                    </div>
                                    @elseif($match->lostItem->latitude && $match->lostItem->longitude)
                                    <div class="meta-item location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ round($match->lostItem->latitude, 4) }}, {{ round($match->lostItem->longitude, 4) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- VS Divider -->
                        <div class="vs-divider">
                            <span>VS</span>
                        </div>
                        
                        <!-- Found Item -->
                        <div class="item-side found">
                            <div class="item-header">
                                <i class="fas fa-check-circle"></i>
                                <span>Found Item</span>
                            </div>
                            <div class="item-details">
                                <h6>{{ $match->foundItem->item_name }}</h6>
                                <p class="item-desc">{{ Str::limit($match->foundItem->description, 50) }}</p>
                                
                                <div class="item-meta-list">
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $match->foundItem->user->name }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $match->foundItem->date_found->format('M d, Y') }}</span>
                                    </div>
                                    
                                    @if($match->foundItem->found_location)
                                    <div class="meta-item location" title="{{ $match->foundItem->found_location }}">
                                        <i class="fas fa-map-marked-alt"></i>
                                        <span>{{ Str::limit($match->foundItem->found_location, 20) }}</span>
                                    </div>
                                    @elseif($match->foundItem->latitude && $match->foundItem->longitude)
                                    <div class="meta-item location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ round($match->foundItem->latitude, 4) }}, {{ round($match->foundItem->longitude, 4) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="match-card-footer">
                    <div class="match-time">
                        <i class="fas fa-clock"></i>
                        <span>{{ $match->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="match-actions">
                        <a href="{{ route('matches.show', $match) }}" class="action-btn view">
                            <i class="fas fa-eye"></i> Details
                        </a>
                        
                        @if($match->status === 'pending')
                            @can('confirm', $match)
                            <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="action-btn confirm" 
                                        onclick="return confirm('Confirm this match? This will mark both items as completed.')">
                                    <i class="fas fa-check"></i> Confirm
                                </button>
                            </form>
                            @endcan
                            
                            @can('reject', $match)
                            <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="action-btn reject"
                                        onclick="return confirm('Reject this match?')">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state-wrapper">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h4>No matches found</h4>
                <p>No potential matches have been identified yet.</p>
                <p class="text-muted">Report more items to increase matching possibilities.</p>
                @if(!$isAdmin)
                <div class="empty-actions">
                    <a href="{{ route('lost-items.create') }}" class="btn btn-outline">
                        <i class="fas fa-exclamation-circle"></i> Report Lost
                    </a>
                    <a href="{{ route('found-items.create') }}" class="btn btn-outline">
                        <i class="fas fa-check-circle"></i> Report Found
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($matches->hasPages())
    <div class="pagination-wrapper">
        {{ $matches->withQueryString()->links() }}
    </div>
    @endif

    <!-- Match Statistics -->
    <div class="statistics-card">
        <div class="statistics-header">
            <i class="fas fa-chart-pie"></i>
            <h5>Match Statistics</h5>
        </div>
        <div class="statistics-body">
            <div class="statistics-row">
                <div class="chart-col">
                    <div class="chart-container">
                        <canvas id="matchStatusChart"></canvas>
                    </div>
                    <h6>Status Distribution</h6>
                </div>
                
                <div class="stats-col">
                    <div class="stats-list">
                        <div class="stat-item-row">
                            <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="stat-info">
                                @php
                                    $highMatches = $matches->where('match_score', '>=', 80)->count();
                                @endphp
                                <div class="stat-number">{{ $highMatches }}</div>
                                <div class="stat-description">High Confidence (80%+)</div>
                            </div>
                        </div>
                        
                        <div class="stat-item-row">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-info">
                                @php
                                    $avgScore = $matches->count() > 0 ? round($matches->avg('match_score'), 1) : 0;
                                @endphp
                                <div class="stat-number">{{ $avgScore }}%</div>
                                <div class="stat-description">Average Score</div>
                            </div>
                        </div>
                        
                        <div class="stat-item-row">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-number">{{ $stats['confirmed'] }}</div>
                                <div class="stat-description">Recovered Items</div>
                            </div>
                        </div>
                    </div>
                </div>
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
    --info: #8b5cf6;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Dashboard Wrapper */
.dashboard-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.header-left h1 {
    font-size: clamp(24px, 5vw, 28px);
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-left p {
    color: var(--text-muted);
    margin: 0;
    font-size: clamp(13px, 4vw, 15px);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stats-link {
    text-decoration: none;
    display: block;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    color: white;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.stat-icon {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    transition: var(--transition);
    flex-shrink: 0;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(360deg);
}

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-value {
    font-size: clamp(20px, 4vw, 24px);
    font-weight: 700;
    color: white;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.9);
}

.stat-hover-indicator {
    position: absolute;
    top: 50%;
    right: -20px;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.stat-card:hover .stat-hover-indicator {
    right: 15px;
    opacity: 1;
}

.pending-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--warning);
    color: black;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 20px;
    min-width: 24px;
    text-align: center;
    box-shadow: 0 0 15px rgba(255, 165, 0, 0.5);
    animation: pulse 2s infinite;
}

/* Custom Alert */
.custom-alert {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 16px 20px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    animation: slideIn 0.3s ease;
    margin-bottom: 24px;
}

.info-alert {
    border-left: 4px solid var(--primary);
    background: rgba(255, 20, 147, 0.1);
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
    flex-wrap: wrap;
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(255, 20, 147, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 20px;
    flex-shrink: 0;
}

.alert-text {
    flex: 1;
    min-width: 200px;
}

.alert-text strong {
    display: block;
    color: var(--text-primary);
    margin-bottom: 4px;
    font-size: 16px;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.filter-tag {
    background: rgba(255, 20, 147, 0.15);
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
}

.alert-action-btn {
    background: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    white-space: nowrap;
}

.alert-action-btn:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
}

/* Filter Card */
.filter-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    margin-bottom: 24px;
}

.filter-card-body {
    padding: 24px;
}

.filter-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 20px;
    align-items: end;
}

@media (max-width: 992px) {
    .filter-form-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-form-group.filter-actions-group {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .filter-form-row {
        grid-template-columns: 1fr;
    }
    
    .filter-form-group.filter-actions-group {
        grid-column: span 1;
    }
}

.filter-form-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
}

.filter-label i {
    color: var(--primary);
    font-size: 14px;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 14px;
    transition: var(--transition);
}

.filter-select:focus,
.filter-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    outline: none;
    background: var(--bg-card);
}

.filter-select option {
    background: var(--bg-header);
    color: var(--text-primary);
}

.filter-actions {
    display: flex;
    gap: 8px;
    width: 100%;
}

@media (max-width: 400px) {
    .filter-actions {
        flex-direction: column;
    }
}

/* Matches Grid */
.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
    gap: 24px;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .matches-grid {
        grid-template-columns: 1fr;
    }
}

.match-card-wrapper {
    width: 100%;
}

.match-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.match-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.match-card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 16px 20px;
}

.match-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.match-title h5 {
    color: var(--text-primary);
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.match-badges {
    display: flex;
    gap: 8px;
}

.score-badge {
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    color: white;
}

.score-badge.high {
    background: linear-gradient(135deg, var(--success), #00ff7f);
    box-shadow: 0 0 10px rgba(0, 250, 154, 0.3);
    color: black;
}

.score-badge.medium {
    background: linear-gradient(135deg, var(--warning), #ffb52e);
    box-shadow: 0 0 10px rgba(255, 165, 0, 0.3);
}

.score-badge.low {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 0 10px var(--primary-glow);
}

.status-badge {
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    color: white;
}

.status-badge.pending {
    background: linear-gradient(135deg, #ffa500, #ffb52e);
    box-shadow: 0 0 10px rgba(255, 165, 0, 0.3);
}

.status-badge.confirmed {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 0 10px rgba(0, 250, 154, 0.3);
    color: black;
}

.status-badge.rejected {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
    box-shadow: 0 0 10px rgba(255, 68, 68, 0.3);
}

.match-card-body {
    padding: 20px;
    flex: 1;
}

/* Items Comparison */
.items-comparison {
    display: flex;
    align-items: stretch;
    gap: 15px;
    position: relative;
}

@media (max-width: 640px) {
    .items-comparison {
        flex-direction: column;
    }
    
    .vs-divider {
        transform: rotate(90deg);
        margin: 10px auto;
    }
}

.item-side {
    flex: 1;
    background: var(--bg-header);
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.item-side.lost:hover {
    border-color: #ff4444;
    box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
}

.item-side.found:hover {
    border-color: var(--success);
    box-shadow: 0 5px 20px rgba(0, 250, 154, 0.3);
}

.item-header {
    padding: 12px 15px;
    font-size: 13px;
    font-weight: 600;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 8px;
}

.item-side.lost .item-header {
    background: rgba(255, 68, 68, 0.1);
    color: #ff4444;
}

.item-side.found .item-header {
    background: rgba(0, 250, 154, 0.1);
    color: var(--success);
}

.item-details {
    padding: 15px;
}

.item-details h6 {
    color: var(--text-primary);
    font-size: 15px;
    font-weight: 600;
    margin: 0 0 8px 0;
}

.item-desc {
    color: var(--text-muted);
    font-size: 12px;
    margin-bottom: 12px;
    line-height: 1.5;
}

.item-meta-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 11px;
}

.meta-item i {
    width: 14px;
    font-size: 12px;
}

.meta-item.location i {
    color: inherit;
}

.item-side.lost .meta-item i {
    color: #ff4444;
}

.item-side.found .meta-item i {
    color: var(--success);
}

/* VS Divider */
.vs-divider {
    display: flex;
    align-items: center;
    justify-content: center;
}

.vs-divider span {
    background: var(--bg-header);
    border: 2px solid var(--primary);
    color: var(--primary);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 0 20px var(--primary-glow);
}

/* Match Card Footer */
.match-card-footer {
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.match-time {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: 12px;
}

.match-time i {
    color: var(--primary);
}

.match-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 2px solid transparent;
    cursor: pointer;
    background: transparent;
}

.action-btn.view {
    border-color: var(--primary);
    color: var(--primary);
}

.action-btn.view:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.action-btn.confirm {
    border-color: var(--success);
    color: var(--success);
}

.action-btn.confirm:hover {
    background: linear-gradient(135deg, var(--success), #00ff7f);
    color: black;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 250, 154, 0.3);
}

.action-btn.reject {
    border-color: var(--error);
    color: var(--error);
}

.action-btn.reject:hover {
    background: linear-gradient(135deg, var(--error), #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

/* Empty State */
.empty-state-wrapper {
    grid-column: 1 / -1;
    width: 100%;
}

.empty-state {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: var(--bg-header);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 2px solid var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
}

.empty-icon i {
    font-size: 48px;
    color: var(--primary);
}

.empty-state h4 {
    color: var(--text-primary);
    margin-bottom: 10px;
    font-size: 20px;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 5px;
    font-size: 14px;
}

.empty-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 20px;
    flex-wrap: wrap;
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-outline:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
    border-color: transparent;
}

/* Pagination */
.pagination-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    justify-content: center;
    margin-top: 30px;
    overflow-x: auto;
}

.pagination {
    display: flex;
    gap: 5px;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
    justify-content: center;
}

.page-item {
    display: inline-block;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 8px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition);
    font-size: 13px;
}

.page-link:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: transparent;
    box-shadow: 0 5px 15px var(--primary-glow);
}

.page-item.disabled .page-link {
    background: var(--bg-card);
    border-color: var(--border-color);
    color: var(--text-muted);
    opacity: 0.5;
    pointer-events: none;
}

/* Statistics Card */
.statistics-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    margin-top: 40px;
}

.statistics-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.statistics-header i {
    color: var(--primary);
    font-size: 20px;
}

.statistics-header h5 {
    color: var(--text-primary);
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.statistics-body {
    padding: 20px;
}

.statistics-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    align-items: center;
}

@media (max-width: 768px) {
    .statistics-row {
        grid-template-columns: 1fr;
    }
}

.chart-col {
    text-align: center;
}

.chart-col h6 {
    color: var(--text-primary);
    margin-top: 15px;
    font-size: 14px;
}

.chart-container {
    width: 200px;
    height: 200px;
    margin: 0 auto;
}

.stats-col {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.stats-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.stat-item-row {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
}

.stat-item-row:hover {
    border-color: var(--primary);
    transform: translateX(5px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    transition: var(--transition);
    flex-shrink: 0;
}

.stat-item-row:hover .stat-icon {
    transform: scale(1.1) rotate(360deg);
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-description {
    color: var(--text-muted);
    font-size: 12px;
}

/* Buttons */
.btn {
    padding: 12px 20px;
    border-radius: 30px;
    font-size: clamp(12px, 3vw, 13px);
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    white-space: nowrap;
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

.btn-outline-primary {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px var(--primary-glow);
}

.w-100 {
    width: 100%;
}

.d-inline {
    display: inline-block;
}

.text-muted {
    color: var(--text-muted) !important;
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

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.match-card,
.stat-card,
.stat-item-row {
    animation: fadeIn 0.5s ease forwards;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize match status chart
    const ctx = document.getElementById('matchStatusChart');
    if (ctx) {
        const matchStatusChart = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $stats['pending'] }},
                        {{ $stats['confirmed'] }},
                        {{ $stats['rejected'] }}
                    ],
                    backgroundColor: [
                        'rgba(255, 165, 0, 0.8)',
                        'rgba(0, 250, 154, 0.8)',
                        'rgba(255, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        '#ffa500',
                        '#00fa9a',
                        '#ff4444'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleColor: 'white',
                        bodyColor: '#a0a0a0',
                        borderColor: 'var(--primary)',
                        borderWidth: 1
                    }
                },
                cutout: '60%'
            }
        });
    }
    
    // Auto-submit filter form on select change
    const statusSelect = document.getElementById('status');
    const filterForm = document.getElementById('filterForm');
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    // Loading animation for filter
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
                submitBtn.disabled = true;
                
                // Re-enable after 2 seconds (prevents double submission)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            }
        });
    }
    
    // Add animation delay to cards
    const cards = document.querySelectorAll('.match-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Auto-hide alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.custom-alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 8000);
    
    // Show toast notification function
    window.showToast = function(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) {
            // Create container if it doesn't exist
            const newContainer = document.createElement('div');
            newContainer.id = 'notificationsContainer';
            newContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            `;
            document.body.appendChild(newContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center border-0 mb-2`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
        const bgColor = type === 'success' ? '#00fa9a' : type === 'error' ? '#ff4444' : 'var(--primary)';
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${icon}" style="color: ${bgColor};"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.getElementById('notificationsContainer').appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    };
});
</script>
@endpush
@endsection