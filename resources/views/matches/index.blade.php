@extends('layouts.app')

@section('title', 'All Matches')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> All Matches
        </h1>
        <p>Potential matches between lost and found items</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => ''])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                <div class="stat-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $matches->total() }}</div>
                    <div class="stat-label">Total Matches</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['pending'] ?? $matches->where('status', 'pending')->count() }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => 'confirmed'])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['confirmed'] ?? $matches->where('status', 'confirmed')->count() }}</div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('matches.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff4444, #ff6b6b);">
                <div class="stat-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['rejected'] ?? $matches->where('status', 'rejected')->count() }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Active Filter Indicators -->
@if(request('status'))
<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center flex-wrap gap-2">
        <i class="fas fa-filter me-2" style="color: var(--primary);"></i>
        <strong style="color: var(--primary);">Active Filter:</strong>
        <div class="d-flex flex-wrap gap-2">
            <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                Status: {{ ucfirst(request('status')) }}
            </span>
        </div>
    </div>
    <a href="{{ route('matches.index') }}" class="btn-close" style="filter: invert(1);"></a>
</div>
@endif

<!-- Filter Section -->
<div class="filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('matches.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">
                        <i class="fas fa-circle" style="color: var(--primary);"></i> Status
                    </label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="min_score" class="form-label">
                        <i class="fas fa-chart-line" style="color: var(--primary);"></i> Min Score
                    </label>
                    <input type="number" class="form-control" id="min_score" name="min_score" 
                           value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="max_score" class="form-label">
                        <i class="fas fa-chart-line" style="color: var(--primary);"></i> Max Score
                    </label>
                    <input type="number" class="form-control" id="max_score" name="max_score" 
                           value="{{ request('max_score') }}" min="0" max="100" placeholder="100">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('matches.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Matches List -->
<div class="row">
    @forelse($matches as $match)
    <div class="col-md-6 mb-4">
        <div class="match-card">
            <div class="card-header">
                <h5 class="mb-0">Match #{{ $match->id }}</h5>
                <div class="header-badges">
                    <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                        {{ $match->match_score }}% Match
                    </span>
                    <span class="status-badge status-{{ $match->status }}">
                        {{ ucfirst($match->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="items-row mb-3">
                    <!-- Lost Item -->
                    <div class="item-col">
                        <div class="item-card lost">
                            <div class="item-header">
                                <i class="fas fa-exclamation-circle"></i> Lost Item
                            </div>
                            <div class="item-content">
                                <p class="item-name">{{ $match->lostItem->item_name }}</p>
                                <p class="item-description">{{ \Illuminate\Support\Str::limit($match->lostItem->description, 50) }}</p>
                                <div class="item-meta">
                                    <small>
                                        <i class="fas fa-user"></i> {{ $match->lostItem->user->name }}
                                    </small>
                                    <small>
                                        <i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}
                                    </small>
                                    
                                    {{-- Lost Location --}}
                                    @if($match->lostItem->lost_location)
                                    <small class="location-info">
                                        <i class="fas fa-map-marked-alt" style="color: #ff4444;"></i> 
                                        {{ \Illuminate\Support\Str::limit($match->lostItem->lost_location, 25) }}
                                    </small>
                                    @elseif($match->lostItem->latitude && $match->lostItem->longitude)
                                    <small class="location-info">
                                        <i class="fas fa-map-marker-alt" style="color: #ff4444;"></i>
                                        {{ number_format($match->lostItem->latitude, 4) }}, {{ number_format($match->lostItem->longitude, 4) }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Found Item -->
                    <div class="item-col">
                        <div class="item-card found">
                            <div class="item-header">
                                <i class="fas fa-check-circle"></i> Found Item
                            </div>
                            <div class="item-content">
                                <p class="item-name">{{ $match->foundItem->item_name }}</p>
                                <p class="item-description">{{ \Illuminate\Support\Str::limit($match->foundItem->description, 50) }}</p>
                                <div class="item-meta">
                                    <small>
                                        <i class="fas fa-user"></i> {{ $match->foundItem->user->name }}
                                    </small>
                                    <small>
                                        <i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}
                                    </small>
                                    
                                    {{-- Found Location --}}
                                    @if($match->foundItem->found_location)
                                    <small class="location-info">
                                        <i class="fas fa-map-marked-alt" style="color: #00fa9a;"></i> 
                                        {{ \Illuminate\Support\Str::limit($match->foundItem->found_location, 25) }}
                                    </small>
                                    @elseif($match->foundItem->latitude && $match->foundItem->longitude)
                                    <small class="location-info">
                                        <i class="fas fa-map-marker-alt" style="color: #00fa9a;"></i>
                                        {{ number_format($match->foundItem->latitude, 4) }}, {{ number_format($match->foundItem->longitude, 4) }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Match Footer -->
                <div class="match-footer">
                    <div class="match-time">
                        <i class="fas fa-clock" style="color: var(--primary);"></i>
                        <small>{{ $match->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="match-actions">
                        <a href="{{ route('matches.show', $match) }}" class="btn-view">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        
                        @if($match->status === 'pending')
                            @can('confirm', $match)
                            <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-confirm" 
                                        onclick="return confirm('Confirm this match? This will mark both items as completed.')">
                                    <i class="fas fa-check"></i> Confirm
                                </button>
                            </form>
                            @endcan
                            
                            @can('reject', $match)
                            <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-reject"
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
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <h4>No matches found</h4>
            <p>No potential matches have been identified yet.</p>
            <p class="text-muted">Report more items to increase matching possibilities.</p>
            <div class="empty-actions">
                <a href="{{ route('lost-items.create') }}" class="btn-map primary">
                    <i class="fas fa-exclamation-circle"></i> Report Lost Item
                </a>
                <a href="{{ route('found-items.create') }}" class="btn-map primary">
                    <i class="fas fa-check-circle"></i> Report Found Item
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($matches->hasPages())
<div class="row mt-4">
    <div class="col-12">
        <div class="pagination-wrapper">
            <div class="d-flex justify-content-center">
                {{ $matches->links() }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- Match Statistics -->
<div class="statistics-card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-chart-pie" style="color: var(--primary);"></i> Match Statistics
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="chart-container">
                    <canvas id="matchStatusChart" width="200" height="200"></canvas>
                </div>
                <h6 class="chart-title">Status Distribution</h6>
            </div>
            <div class="col-md-9">
                <div class="stats-grid">
                    <div class="stat-item-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div class="stat-info">
                            @php
                                $highMatches = $matches->where('match_score', '>=', 80)->count();
                            @endphp
                            <div class="stat-number">{{ $highMatches }}</div>
                            <div class="stat-description">High Confidence Matches (80%+)</div>
                        </div>
                    </div>
                    
                    <div class="stat-item-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            @php
                                $avgScore = $matches->count() > 0 ? $matches->avg('match_score') : 0;
                            @endphp
                            <div class="stat-number">{{ number_format($avgScore, 1) }}%</div>
                            <div class="stat-description">Average Match Score</div>
                        </div>
                    </div>
                    
                    <div class="stat-item-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $matches->where('status', 'confirmed')->count() }}</div>
                            <div class="stat-description">Successful Recoveries</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Container -->
<div id="notificationsContainer"></div>

<style>
    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title p {
        color: #a0a0a0;
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
    }

    /* Stats Cards */
    .stats-link {
        text-decoration: none;
        display: block;
        position: relative;
    }

    .stat-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        color: white;
        cursor: pointer;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border-color: white;
    }

    .stat-card:hover::before {
        opacity: 0.1;
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
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-hover-indicator {
        right: 15px;
        opacity: 1;
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        background: rgba(255,255,255,0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(360deg);
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.8);
        margin-top: 0.25rem;
    }

    /* Filter Badge */
    .filter-badge {
        padding: 0.375rem 1rem;
        border-radius: 30px;
        color: white;
        font-size: 0.75rem;
        font-weight: 500;
        box-shadow: 0 0 15px var(--primary-glow);
    }

    /* Alert */
    .alert-info {
        background: #1a1a1a;
        border: 1px solid var(--primary);
        color: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
    }

    .btn-close {
        filter: invert(1);
        opacity: 0.5;
        transition: all 0.3s ease;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    /* Filter Card */
    .filter-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    .filter-card .card-body {
        padding: 1.5rem;
    }

    .form-label {
        color: white;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: var(--primary);
    }

    .form-select, .form-control {
        background: #222;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 0.75rem;
        color: white;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
        outline: none;
        background: #2a2a2a;
    }

    .form-select option {
        background: #222;
        color: white;
    }

    .btn-group {
        gap: 0.5rem;
    }

    .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: 2px solid transparent;
        text-decoration: none;
        cursor: pointer;
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
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--primary-glow);
    }

    /* Match Card */
    .match-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
    }

    .match-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }

    .match-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
        box-shadow: 0 10px 30px var(--primary-glow);
    }

    .match-card:hover::before {
        opacity: 0.1;
    }

    .card-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h5 {
        color: white;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
    }

    .header-badges {
        display: flex;
        gap: 0.5rem;
    }

    .score-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }

    .score-high {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
        color: black;
    }

    .score-medium {
        background: linear-gradient(135deg, #ffa500, #ffb52e);
        box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
    }

    .score-low {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        box-shadow: 0 0 15px var(--primary-glow);
    }

    .status-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }

    .status-pending {
        background: linear-gradient(135deg, #ffa500, #ffb52e);
        box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
    }

    .status-confirmed {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
        color: black;
    }

    .status-rejected {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        box-shadow: 0 0 15px rgba(255, 68, 68, 0.3);
    }

    .card-body {
        padding: 1.25rem;
    }

    /* Items Row */
    .items-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .item-col {
        flex: 1;
        min-width: 0;
    }

    .item-card {
        background: #222;
        border: 1px solid #333;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }

    .item-card.lost:hover {
        border-color: #ff4444;
        box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
        transform: translateY(-2px);
    }

    .item-card.found:hover {
        border-color: #00fa9a;
        box-shadow: 0 5px 20px rgba(0, 250, 154, 0.3);
        transform: translateY(-2px);
    }

    .item-header {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-bottom: 1px solid #333;
    }

    .item-card.lost .item-header {
        background: rgba(255, 68, 68, 0.1);
        color: #ff4444;
    }

    .item-card.found .item-header {
        background: rgba(0, 250, 154, 0.1);
        color: #00fa9a;
    }

    .item-header i {
        margin-right: 0.5rem;
    }

    .item-content {
        padding: 1rem;
    }

    .item-name {
        color: white;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }

    .item-description {
        color: #a0a0a0;
        font-size: 0.8125rem;
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }

    .item-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .item-meta small {
        color: #a0a0a0;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .item-meta i {
        width: 14px;
    }

    .location-info {
        margin-top: 0.25rem;
        padding: 0.25rem 0;
        border-top: 1px dashed #333;
    }

    .location-info i {
        margin-right: 0.25rem;
    }

    /* Match Footer */
    .match-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #333;
    }

    .match-time {
        color: #a0a0a0;
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .match-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-view {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    .btn-confirm, .btn-reject {
        border: none;
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        background: transparent;
    }

    .btn-confirm {
        border: 2px solid #00fa9a;
        color: #00fa9a;
    }

    .btn-confirm:hover {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 250, 154, 0.3);
    }

    .btn-reject {
        border: 2px solid #ff4444;
        color: #ff4444;
    }

    .btn-reject:hover {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
    }

    /* Empty State */
    .empty-state {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        background: #222;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 2px solid var(--primary);
    }

    .empty-icon i {
        font-size: 3rem;
        color: var(--primary);
    }

    .empty-state h4 {
        color: white;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #a0a0a0;
        margin-bottom: 0.5rem;
    }

    .empty-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .btn-map.primary {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-map.primary:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--primary-glow);
    }

    /* Pagination */
    .pagination-wrapper {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        padding: 1rem;
    }

    .pagination {
        margin: 0;
        display: flex;
        gap: 0.25rem;
    }

    .page-link {
        background: #222;
        border: 1px solid #333;
        color: #a0a0a0;
        border-radius: 8px !important;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border-color: var(--primary);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    .page-item.disabled .page-link {
        background: #1a1a1a;
        border-color: #333;
        color: #666;
        pointer-events: none;
    }

    /* Statistics Card */
    .statistics-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
    }

    .card-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1.25rem;
    }

    .card-header h5 {
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .chart-container {
        width: 200px;
        height: 200px;
        margin: 0 auto 1rem;
    }

    .chart-title {
        text-align: center;
        color: white;
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        height: 100%;
    }

    .stat-item-card {
        background: #222;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-item-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary);
        box-shadow: 0 10px 25px var(--primary-glow);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .stat-item-card:hover .stat-icon {
        transform: scale(1.1) rotate(360deg);
    }

    .stat-info {
        text-align: center;
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-description {
        color: #a0a0a0;
        font-size: 0.875rem;
    }

    /* Toast Notifications */
    #notificationsContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        background: #1a1a1a;
        border: 1px solid var(--primary);
        border-radius: 12px;
        min-width: 300px;
    }

    .toast-body {
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-close-white {
        filter: invert(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .items-row {
            flex-direction: column;
        }

        .item-col {
            width: 100%;
        }

        .match-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .match-actions {
            width: 100%;
            flex-wrap: wrap;
        }

        .btn-view, .btn-confirm, .btn-reject {
            flex: 1;
            justify-content: center;
        }

        .empty-actions {
            flex-direction: column;
        }

        .btn-map.primary {
            width: 100%;
            justify-content: center;
        }

        .chart-container {
            width: 150px;
            height: 150px;
        }
    }

    /* Animation */
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

    .match-card, .stat-card, .stat-item-card {
        animation: fadeIn 0.5s ease forwards;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize match status chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('matchStatusChart').getContext('2d');
        const matchStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $matches->where('status', 'pending')->count() }},
                        {{ $matches->where('status', 'confirmed')->count() }},
                        {{ $matches->where('status', 'rejected')->count() }}
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
    });
    
    // Auto-submit filter form on select change
    document.getElementById('status').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Loading animation for filter
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    });
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        
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
        
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
</script>
@endpush