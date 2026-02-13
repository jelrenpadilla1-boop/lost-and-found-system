@extends('layouts.app')

@section('title', 'Matches')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">
            <i class="fas fa-exchange-alt text-info"></i> Matches
        </h1>
        <p class="text-muted">Potential matches between lost and found items</p>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('matches.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="min_score" class="form-label">Min Score</label>
                    <input type="number" class="form-control" id="min_score" name="min_score" 
                           value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="max_score" class="form-label">Max Score</label>
                    <input type="number" class="form-control" id="max_score" name="max_score" 
                           value="{{ request('max_score') }}" min="0" max="100" placeholder="100">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset
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
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Match #{{ $match->id }}</h5>
                <div>
                    <span class="badge @if($match->match_score >= 80) bg-success @elseif($match->match_score >= 60) bg-warning @else bg-info @endif">
                        {{ $match->match_score }}% Match
                    </span>
                    <span class="badge @if($match->status == 'confirmed') bg-success @elseif($match->status == 'rejected') bg-danger @else bg-warning @endif ms-1">
                        {{ ucfirst($match->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <!-- Lost Item -->
                    <div class="col-6">
                        <div class="card item-lost h-100">
                            <div class="card-body">
                                <h6 class="card-title text-danger">
                                    <i class="fas fa-exclamation-circle"></i> Lost Item
                                </h6>
                                <p class="mb-1"><strong>{{ $match->lostItem->item_name }}</strong></p>
                                <p class="mb-1 small text-muted">{{ Str::limit($match->lostItem->description, 50) }}</p>
                                <p class="mb-0 small">
                                    <i class="fas fa-user"></i> {{ $match->lostItem->user->name }}<br>
                                    <i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Found Item -->
                    <div class="col-6">
                        <div class="card item-found h-100">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="fas fa-check-circle"></i> Found Item
                                </h6>
                                <p class="mb-1"><strong>{{ $match->foundItem->item_name }}</strong></p>
                                <p class="mb-1 small text-muted">{{ Str::limit($match->foundItem->description, 50) }}</p>
                                <p class="mb-0 small">
                                    <i class="fas fa-user"></i> {{ $match->foundItem->user->name }}<br>
                                    <i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Match Details -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $match->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                
                                @if($match->status === 'pending')
                                    @can('confirm', $match)
                                    <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" 
                                                onclick="return confirm('Confirm this match? This will mark both items as completed.')">
                                            <i class="fas fa-check"></i> Confirm
                                        </button>
                                    </form>
                                    @endcan
                                    
                                    @can('reject', $match)
                                    <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
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
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                <h4>No matches found</h4>
                <p class="text-muted">No potential matches have been identified yet.</p>
                <p class="text-muted">Report more items to increase matching possibilities.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $matches->links() }}
</div>
@endsection