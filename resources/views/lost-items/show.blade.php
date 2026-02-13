@extends('layouts.app')

@section('title', $lostItem->item_name)

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <h1 class="h4 mb-0">{{ $lostItem->item_name }}</h1>
                <span class="badge @if($lostItem->status == 'pending') bg-warning @elseif($lostItem->status == 'found') bg-success @else bg-primary @endif">
                    {{ ucfirst($lostItem->status) }}
                </span>
            </div>
            <p class="text-muted mb-0">Lost {{ $lostItem->created_at->diffForHumans() }}</p>
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

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Item Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Image -->
                        <div class="col-md-5">
                            @if($lostItem->photo)
                                <div class="bg-light rounded p-2 text-center">
                                    <img src="{{ asset('storage/' . $lostItem->photo) }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 240px; object-fit: contain;"
                                         alt="{{ $lostItem->item_name }}">
                                </div>
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-2x text-secondary"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="col-md-7">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Description</h6>
                                <p class="mb-0">{{ $lostItem->description }}</p>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <h6 class="text-muted mb-1">Category</h6>
                                    <p class="mb-0">{{ $lostItem->category }}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <h6 class="text-muted mb-1">Date Lost</h6>
                                    <p class="mb-0">{{ $lostItem->date_lost->format('M d, Y') }}</p>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted mb-1">Reported By</h6>
                                    <p class="mb-0">{{ $lostItem->user->name }}</p>
                                </div>
                                @if($lostItem->latitude && $lostItem->longitude)
                                <div class="col-6">
                                    <h6 class="text-muted mb-1">Location</h6>
                                    <a href="https://maps.google.com/?q={{ $lostItem->latitude }},{{ $lostItem->longitude }}" 
                                       target="_blank" class="text-primary">
                                        View on Map
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="mb-3">Actions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if($lostItem->status === 'pending')
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#foundModal">
                                <i class="fas fa-check me-2"></i>Mark as Found
                            </button>
                        @endif

                        @can('delete', $lostItem)
                        <form action="{{ route('lost-items.destroy', $lostItem) }}" method="POST" 
                              onsubmit="return confirm('Delete this item?');">
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

            <!-- Matches -->
            @if($matches->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Potential Matches</h6>
                        <span class="badge bg-primary">{{ $matches->count() }}</span>
                    </div>

                    @foreach($matches as $match)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge @if($match->match_score >= 80) bg-success @elseif($match->match_score >= 60) bg-warning @else bg-info @endif">
                                        {{ $match->match_score }}% Match
                                    </span>
                                    <strong>{{ $match->foundItem->item_name }}</strong>
                                </div>
                                <p class="text-muted small mb-2">
                                    {{ \Illuminate\Support\Str::limit($match->foundItem->description, 80) }}
                                </p>
                                <p class="text-muted small mb-0">
                                    Found by {{ $match->foundItem->user->name }} on {{ $match->foundItem->date_found->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Contact -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="mb-3">Contact</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary rounded-circle p-2 me-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-medium">{{ $lostItem->user->name }}</p>
                            <small class="text-muted">
                                {{ $lostItem->user->isAdmin() ? 'Administrator' : 'User' }}
                            </small>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Email</h6>
                        <p class="mb-0">{{ $lostItem->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Location Map -->
            @if($lostItem->latitude && $lostItem->longitude)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="mb-3">Location</h6>
                    <div id="map" style="height: 200px; border-radius: 0.375rem;"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Found Modal -->
@if($lostItem->status === 'pending')
<div class="modal fade" id="foundModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Mark as Found</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lost-items.update', $lostItem) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="found">
                    
                    <div class="mb-4">
                        <label for="found_details" class="form-label">Details (Optional)</label>
                        <textarea class="form-control" id="found_details" name="found_details" 
                                  rows="3" placeholder="How was the item found?"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if($lostItem->latitude && $lostItem->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([{{ $lostItem->latitude }}, {{ $lostItem->longitude }}], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        L.marker([{{ $lostItem->latitude }}, {{ $lostItem->longitude }}])
            .addTo(map)
            .bindPopup('{{ $lostItem->item_name }}');
    });
</script>
@endif
@endpush