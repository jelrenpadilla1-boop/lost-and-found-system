@extends('layouts.app')

@section('title', $lostItem->item_name)

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $lostItem->item_name }}</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-{{ $lostItem->status === 'pending' ? 'warning' : ($lostItem->status === 'found' ? 'success' : 'primary') }}">
                    {{ ucfirst($lostItem->status) }}
                </span>
                <small class="text-muted">Lost {{ $lostItem->created_at->diffForHumans() }}</small>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('lost-items.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            @can('update', $lostItem)
                <a href="{{ route('lost-items.edit', $lostItem) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
            @endcan
        </div>
    </div>

    <div class="row">
        {{-- Main Content Column --}}
        <div class="col-lg-8">
            {{-- Item Details Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        {{-- Image Column --}}
                        <div class="col-md-5 mb-3 mb-md-0">
                            @if($lostItem->photo)
                                <img src="{{ asset('storage/' . $lostItem->photo) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $lostItem->item_name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-secondary"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Details Column --}}
                        <div class="col-md-7">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="mb-3">{{ $lostItem->description }}</p>

                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Category</small>
                                    <span>{{ $lostItem->category }}</span>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Date Lost</small>
                                    <span>{{ $lostItem->date_lost->format('M d, Y') }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Reported By</small>
                                    <span>{{ $lostItem->user->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Actions</h6>
                    <div class="d-flex gap-2">
                        @if($lostItem->status === 'pending')
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#foundModal">
                                <i class="fas fa-check me-2"></i>Mark as Found
                            </button>
                        @endif

                        @can('delete', $lostItem)
                            <form action="{{ route('lost-items.destroy', $lostItem) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Matches Card --}}
            @if($matches->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Potential Matches</h6>
                            <span class="badge bg-primary">{{ $matches->count() }}</span>
                        </div>

                        @foreach($matches as $match)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-{{ $match->match_score >= 80 ? 'success' : ($match->match_score >= 60 ? 'warning' : 'info') }}">
                                                {{ $match->match_score }}% Match
                                            </span>
                                            <strong>{{ $match->foundItem->item_name }}</strong>
                                        </div>
                                        <p class="text-muted small mb-2">
                                            {{ Str::limit($match->foundItem->description, 80) }}
                                        </p>
                                        <small class="text-muted">
                                            Found by {{ $match->foundItem->user->name }} 
                                            on {{ $match->foundItem->date_found->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar Column --}}
        <div class="col-lg-4">
            {{-- Contact Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Contact Information</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle p-2 me-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">{{ $lostItem->user->name }}</p>
                            <small class="text-muted">{{ $lostItem->user->isAdmin() ? 'Administrator' : 'User' }}</small>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block">Email</small>
                        <p class="mb-0">{{ $lostItem->user->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Location Map Card --}}
            @if($lostItem->latitude && $lostItem->longitude)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Location</h6>
                        
                        {{-- Google Maps Iframe (Simple & Reliable) --}}
                        <div class="ratio ratio-4x3 mb-2">
                            <iframe
                                src="https://www.google.com/maps?q={{ $lostItem->latitude }},{{ $lostItem->longitude }}&hl=en&z=15&output=embed"
                                style="border:0; border-radius: 0.375rem;"
                                allowfullscreen=""
                                loading="lazy">
                            </iframe>
                        </div>
                        
                        {{-- Coordinates and Link --}}
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ number_format($lostItem->latitude, 6) }}, {{ number_format($lostItem->longitude, 6) }}
                            </small>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $lostItem->latitude }},{{ $lostItem->longitude }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-directions me-1"></i>Directions
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Found Modal --}}
@if($lostItem->status === 'pending')
    <div class="modal fade" id="foundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark as Found</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('lost-items.update', $lostItem) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="found">
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="found_details" class="form-label">Details (Optional)</label>
                            <textarea class="form-control" id="found_details" name="found_details" 
                                      rows="3" placeholder="How was the item found?"></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection