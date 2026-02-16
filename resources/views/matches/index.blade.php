@extends('layouts.app')

@section('title', 'Matches')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> Matches
        </h1>
        <p>Potential matches between lost and found items</p>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('matches.index') }}">
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
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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
                                <p class="item-description">{{ Str::limit($match->lostItem->description, 50) }}</p>
                                <div class="item-meta">
                                    <small>
                                        <i class="fas fa-user"></i> {{ $match->lostItem->user->name }}
                                    </small>
                                    <small>
                                        <i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}
                                    </small>
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
                                <p class="item-description">{{ Str::limit($match->foundItem->description, 50) }}</p>
                                <div class="item-meta">
                                    <small>
                                        <i class="fas fa-user"></i> {{ $match->foundItem->user->name }}
                                    </small>
                                    <small>
                                        <i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Match Details -->
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
                    <i class="fas fa-exclamation-circle"></i> Report Lost
                </a>
                <a href="{{ route('found-items.create') }}" class="btn-map primary">
                    <i class="fas fa-check-circle"></i> Report Found
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($matches->hasPages())
<div class="pagination-wrapper">
    {{ $matches->links() }}
</div>
@endif

<style>
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
        gap: 0.25rem;
    }

    .item-meta small {
        color: #a0a0a0;
        font-size: 0.75rem;
    }

    .item-meta i {
        color: var(--primary);
        width: 14px;
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
    }

    .btn-confirm {
        background: transparent;
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
        background: transparent;
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
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.25rem;
        list-style: none;
        padding: 0;
    }

    .page-item {
        margin: 0;
    }

    .page-link {
        background: #1a1a1a;
        border: 1px solid #333;
        color: #a0a0a0;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        text-decoration: none;
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

    /* Responsive */
    @media (max-width: 768px) {
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

    .match-card {
        animation: fadeIn 0.5s ease forwards;
    }
</style>
@endsection