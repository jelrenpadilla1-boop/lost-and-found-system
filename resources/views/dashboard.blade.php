@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header fade-in">
    <div class="page-title">
        <h1>
            <i class="fas fa-home"></i> Dashboard
        </h1>
        <p>Welcome back, {{ Auth::user()->name }}!</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
            <i class="fas fa-exclamation-circle"></i> Report Lost
        </a>
        <a href="{{ route('found-items.create') }}" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Report Found
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4 fade-in">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="icon" style="background: var(--danger);">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="count">{{ $stats['lost_items'] }}</div>
            <div class="label">Lost Items</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="icon" style="background: var(--success);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="count">{{ $stats['found_items'] }}</div>
            <div class="label">Found Items</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="icon" style="background: var(--info);">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="count">{{ $stats['total_matches'] }}</div>
            <div class="label">Total Matches</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="icon" style="background: var(--warning);">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="count">{{ $stats['confirmed_matches'] }}</div>
            <div class="label">Confirmed</div>
        </div>
    </div>
</div>

<div class="row fade-in">
    <!-- Recent Lost Items -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-exclamation-circle text-danger"></i> Recent Lost Items
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentLost as $item)
                <a href="{{ route('lost-items.show', $item) }}" class="recent-item">
                    <div class="recent-item-image">
                        @if($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}" 
                                 alt="{{ $item->item_name }}"
                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);">
                        @else
                            <i class="fas fa-image"></i>
                        @endif
                    </div>
                    <div class="recent-item-content">
                        <h6>{{ $item->item_name }}</h6>
                        <div class="recent-item-meta">
                            <span><i class="fas fa-calendar"></i> {{ $item->date_lost->format('M d, Y') }}</span>
                            <span><i class="fas fa-user"></i> {{ $item->user->name }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <p class="text-muted text-center mb-0">No lost items found</p>
                @endforelse
                <a href="{{ route('lost-items.index') }}" class="btn btn-outline btn-sm btn-block mt-3">
                    View All Lost Items
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Found Items -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-check-circle text-success"></i> Recent Found Items
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentFound as $item)
                <a href="{{ route('found-items.show', $item) }}" class="recent-item">
                    <div class="recent-item-image">
                        @if($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}" 
                                 alt="{{ $item->item_name }}"
                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-md);">
                        @else
                            <i class="fas fa-image"></i>
                        @endif
                    </div>
                    <div class="recent-item-content">
                        <h6>{{ $item->item_name }}</h6>
                        <div class="recent-item-meta">
                            <span><i class="fas fa-calendar"></i> {{ $item->date_found->format('M d, Y') }}</span>
                            <span><i class="fas fa-user"></i> {{ $item->user->name }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <p class="text-muted text-center mb-0">No found items found</p>
                @endforelse
                <a href="{{ route('found-items.index') }}" class="btn btn-outline btn-sm btn-block mt-3">
                    View All Found Items
                </a>
            </div>
        </div>
    </div>

    <!-- High Probability Matches -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-exchange-alt text-info"></i> High Probability Matches
                </h5>
            </div>
            <div class="card-body">
                @forelse($highMatches as $match)
                <div class="match-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="match-score">{{ $match->match_score }}% Match</span>
                        <small class="text-muted">{{ $match->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="match-items">
                        <div class="match-item">
                            <small>Lost Item</small>
                            <div class="text-truncate">{{ $match->lostItem->item_name }}</div>
                        </div>
                        <div class="match-item">
                            <small>Found Item</small>
                            <div class="text-truncate">{{ $match->foundItem->item_name }}</div>
                        </div>
                    </div>
                    <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-info w-100">
                        View Details
                    </a>
                </div>
                @empty
                <p class="text-muted text-center mb-0">No high probability matches</p>
                @endforelse
                <a href="{{ route('matches.index') }}" class="btn btn-outline btn-sm btn-block">
                    View All Matches
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card fade-in">
    <div class="card-header">
        <h5 class="card-title">Quick Actions</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <a href="{{ route('lost-items.create') }}" class="quick-action">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    <span>Report Lost</span>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('found-items.create') }}" class="quick-action">
                    <i class="fas fa-check-circle text-success"></i>
                    <span>Report Found</span>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('map.index') }}" class="quick-action">
                    <i class="fas fa-map text-primary"></i>
                    <span>View Map</span>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('matches.my-matches') }}" class="quick-action">
                    <i class="fas fa-exchange-alt text-info"></i>
                    <span>My Matches</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="card fade-in">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fas fa-chart-line"></i> System Status
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="status-item">
                    <div class="status-icon success">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="status-content">
                        <h5>Operational</h5>
                        <p>All systems normal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="status-item">
                    <div class="status-icon info">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="status-content">
                        <h5>AI Matching Active</h5>
                        <p>Real-time matching enabled</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="status-item">
                    <div class="status-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="status-content">
                        <h5>{{ \App\Models\User::count() }} Users</h5>
                        <p>Active in system</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection