@extends('layouts.app')

@section('title', 'Match Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-exchange-alt text-info"></i> Match Details
                    </h4>
                    <div>
                        <span class="badge @if($match->match_score >= 80) bg-success @elseif($match->match_score >= 60) bg-warning @else bg-info @endif fs-6">
                            {{ $match->match_score }}% Match
                        </span>
                        <span class="badge @if($match->status == 'confirmed') bg-success @elseif($match->status == 'rejected') bg-danger @else bg-warning @endif fs-6 ms-1">
                            {{ ucfirst($match->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Match Score Breakdown -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Match Score Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Item Name Similarity</label>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ min(100, $match->match_score * 0.3 / 0.3) }}%">
                                            30%
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description Similarity</label>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: {{ min(100, $match->match_score * 0.25 / 0.25) }}%">
                                            25%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category Match</label>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ min(100, $match->match_score * 0.2 / 0.2) }}%">
                                            20%
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Location Proximity</label>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ min(100, $match->match_score * 0.15 / 0.15) }}%">
                                            15%
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date Proximity</label>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-secondary" role="progressbar" 
                                             style="width: {{ min(100, $match->match_score * 0.1 / 0.1) }}%">
                                            10%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Comparison -->
                <div class="row">
                    <!-- Lost Item -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 item-lost">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-circle text-danger"></i> Lost Item
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    @if($match->lostItem->photo)
                                        <img src="{{ asset('storage/' . $match->lostItem->photo) }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 200px;"
                                             alt="{{ $match->lostItem->item_name }}">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 150px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">Item Name:</th>
                                        <td>{{ $match->lostItem->item_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>
                                            <span class="badge bg-info">{{ $match->lostItem->category }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($match->lostItem->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($match->lostItem->status === 'found')
                                                <span class="badge bg-success">Found</span>
                                            @else
                                                <span class="badge bg-primary">Returned</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date Lost:</th>
                                        <td>{{ $match->lostItem->date_lost->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reported By:</th>
                                        <td>{{ $match->lostItem->user->name }}</td>
                                    </tr>
                                    @if($match->lostItem->latitude && $match->lostItem->longitude)
                                    <tr>
                                        <th>Location:</th>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                            {{ $match->lostItem->latitude }}, {{ $match->lostItem->longitude }}
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                                
                                <div class="mt-3">
                                    <h6>Description:</h6>
                                    <div class="card bg-light">
                                        <div class="card-body p-2">
                                            {{ $match->lostItem->description }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="{{ route('lost-items.show', $match->lostItem) }}" 
                                       class="btn btn-outline-danger btn-sm w-100">
                                        <i class="fas fa-external-link-alt"></i> View Lost Item
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Found Item -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 item-found">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-check-circle text-success"></i> Found Item
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    @if($match->foundItem->photo)
                                        <img src="{{ asset('storage/' . $match->foundItem->photo) }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 200px;"
                                             alt="{{ $match->foundItem->item_name }}">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 150px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">Item Name:</th>
                                        <td>{{ $match->foundItem->item_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>
                                            <span class="badge bg-info">{{ $match->foundItem->category }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if($match->foundItem->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($match->foundItem->status === 'claimed')
                                                <span class="badge bg-success">Claimed</span>
                                            @else
                                                <span class="badge bg-secondary">Disposed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date Found:</th>
                                        <td>{{ $match->foundItem->date_found->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Found By:</th>
                                        <td>{{ $match->foundItem->user->name }}</td>
                                    </tr>
                                    @if($match->foundItem->latitude && $match->foundItem->longitude)
                                    <tr>
                                        <th>Location:</th>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-success"></i>
                                            {{ $match->foundItem->latitude }}, {{ $match->foundItem->longitude }}
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                                
                                <div class="mt-3">
                                    <h6>Description:</h6>
                                    <div class="card bg-light">
                                        <div class="card-body p-2">
                                            {{ $match->foundItem->description }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="{{ route('found-items.show', $match->foundItem) }}" 
                                       class="btn btn-outline-success btn-sm w-100">
                                        <i class="fas fa-external-link-alt"></i> View Found Item
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Match Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Match Actions</h5>
            </div>
            <div class="card-body">
                @if($match->status === 'pending')
                    @can('confirm', $match)
                    <form action="{{ route('matches.confirm', $match) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" 
                                onclick="return confirm('Confirm this match? This will:\n1. Mark the lost item as "Found"\n2. Mark the found item as "Claimed"\n3. Notify both users')">
                            <i class="fas fa-handshake"></i> Confirm Match
                        </button>
                    </form>
                    @endcan
                    
                    @can('reject', $match)
                    <form action="{{ route('matches.reject', $match) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Reject this match?')">
                            <i class="fas fa-times-circle"></i> Reject Match
                        </button>
                    </form>
                    @endcan
                @elseif($match->status === 'confirmed')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Match Confirmed</strong>
                        <p class="mb-0">This match was confirmed on {{ $match->updated_at->format('F d, Y') }}</p>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        <strong>Match Rejected</strong>
                        <p class="mb-0">This match was rejected on {{ $match->updated_at->format('F d, Y') }}</p>
                    </div>
                @endif
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('lost-items.show', $match->lostItem) }}" class="btn btn-outline-danger">
                        <i class="fas fa-exclamation-circle"></i> View Lost Item
                    </a>
                    <a href="{{ route('found-items.show', $match->foundItem) }}" class="btn btn-outline-success">
                        <i class="fas fa-check-circle"></i> View Found Item
                    </a>
                    <a href="{{ route('matches.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-list"></i> Back to Matches
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-danger">
                        <i class="fas fa-user"></i> Lost Item Owner
                    </h6>
                    <p class="mb-1">{{ $match->lostItem->user->name }}</p>
                    <p class="mb-0 text-muted small">{{ $match->lostItem->user->email }}</p>
                </div>
                
                <hr>
                
                <div class="mb-0">
                    <h6 class="text-success">
                        <i class="fas fa-user"></i> Found Item Owner
                    </h6>
                    <p class="mb-1">{{ $match->foundItem->user->name }}</p>
                    <p class="mb-0 text-muted small">{{ $match->foundItem->user->email }}</p>
                </div>
            </div>
        </div>
        
        <!-- Match Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Match Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Match Created</h6>
                            <small class="text-muted">{{ $match->created_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                    
                    @if($match->status !== 'pending')
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $match->status === 'confirmed' ? 'bg-success' : 'bg-danger' }}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Match {{ ucfirst($match->status) }}</h6>
                            <small class="text-muted">{{ $match->updated_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .timeline-content {
        padding: 5px 0;
    }
</style>
@endpush