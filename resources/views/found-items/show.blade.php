@extends('layouts.app')

@section('title', $foundItem->item_name)

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-check-circle text-success"></i> {{ $foundItem->item_name }}
        </h1>
        <p>Found Item Details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('found-items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        @can('update', $foundItem)
        <a href="{{ route('found-items.edit', $foundItem) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        @endcan
    </div>
</div>

<div class="row">
    <!-- Left Column: Item Details -->
    <div class="col-lg-8">
        <!-- Item Information Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Photo Column -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        <div class="text-center">
                            @if($foundItem->photo)
                                <img src="{{ asset('storage/' . $foundItem->photo) }}" 
                                     class="img-fluid rounded shadow" 
                                     style="max-height: 300px; object-fit: cover;"
                                     alt="{{ $foundItem->item_name }}">
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary" onclick="openImageModal('{{ asset('storage/' . $foundItem->photo) }}')">
                                        <i class="fas fa-expand"></i> View Full Size
                                    </button>
                                </div>
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-4x text-muted"></i>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">No photo available</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Details Column -->
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge @if($foundItem->status == 'pending') bg-warning @elseif($foundItem->status == 'claimed') bg-success @else bg-secondary @endif fs-6">
                                    {{ ucfirst($foundItem->status) }}
                                </span>
                                <span class="badge bg-info ms-2 fs-6">{{ $foundItem->category }}</span>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $foundItem->created_at->diffForHumans() }}
                            </small>
                        </div>
                        
                        <h4 class="mb-3">{{ $foundItem->item_name }}</h4>
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {{ $foundItem->description }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Date Found</h6>
                                <p class="mb-0">
                                    <i class="fas fa-calendar text-success me-2"></i>
                                    {{ $foundItem->date_found->format('F d, Y') }}
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Found By</h6>
                                <p class="mb-0">
                                    <i class="fas fa-user text-success me-2"></i>
                                    {{ $foundItem->user->name }}
                                </p>
                            </div>
                            
                            @if($foundItem->latitude && $foundItem->longitude)
                            <div class="col-12">
                                <h6 class="text-muted mb-2">Location Found</h6>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    {{ number_format($foundItem->latitude, 6) }}, {{ number_format($foundItem->longitude, 6) }}
                                </p>
                                <small class="text-muted">
                                    <a href="https://maps.google.com/?q={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                                       target="_blank" class="text-decoration-none">
                                        View on Google Maps
                                    </a>
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Matches Section -->
        @if($matches->count() > 0)
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt"></i> Potential Matches ({{ $matches->count() }})
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    <i class="fas fa-lightbulb"></i> These lost items might match with this found item based on our AI matching system.
                </p>
                
                @foreach($matches as $match)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-danger rounded-circle p-2 me-3">
                                        <i class="fas fa-exclamation-circle text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $match->lostItem->item_name }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> {{ $match->lostItem->user->name }} • 
                                            <i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                                <p class="mb-0 text-muted small">
                                    {{ \Illuminate\Support\Str::limit($match->lostItem->description, 100) }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="mb-3">
                                    <span class="badge @if($match->match_score >= 80) bg-success @elseif($match->match_score >= 60) bg-warning @else bg-info @endif fs-6">
                                        {{ $match->match_score }}% Match
                                    </span>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> View Match
                                    </a>
                                    <a href="{{ route('lost-items.show', $match->lostItem) }}" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-external-link-alt"></i> View Item
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="text-center mt-3">
                    <a href="{{ route('matches.index') }}" class="btn btn-info">
                        <i class="fas fa-list"></i> View All Matches
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5>No Matches Found Yet</h5>
                <p class="text-muted">Our system is checking for potential matches with lost items.</p>
                <p class="text-muted">New matches will appear here as they are found.</p>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Right Column: Actions & Map -->
    <div class="col-lg-4">
        <!-- Quick Actions Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($foundItem->status === 'pending')
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#claimModal">
                            <i class="fas fa-handshake"></i> Mark as Claimed
                        </button>
                    @endif
                    
                    <a href="{{ route('matches.index') }}?found_item={{ $foundItem->id }}" class="btn btn-info">
                        <i class="fas fa-search"></i> Find More Matches
                    </a>
                    
                    @if($foundItem->latitude && $foundItem->longitude)
                    <a href="https://maps.google.com/?q={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                       target="_blank" class="btn btn-primary">
                        <i class="fas fa-map-marked-alt"></i> View on Google Maps
                    </a>
                    @endif
                    
                    @can('delete', $foundItem)
                    <form action="{{ route('found-items.destroy', $foundItem) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Delete Item
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        
        <!-- Location Map -->
        @if($foundItem->latitude && $foundItem->longitude)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map"></i> Location Map
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 250px; border-radius: 0 0 10px 10px;"></div>
            </div>
            <div class="card-footer bg-transparent text-center py-2">
                <small class="text-muted">
                    <i class="fas fa-map-pin me-1"></i>
                    {{ number_format($foundItem->latitude, 6) }}, {{ number_format($foundItem->longitude, 6) }}
                </small>
            </div>
        </div>
        @endif
        
        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle"></i> Contact Information
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success rounded-circle p-3 me-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $foundItem->user->name }}</h6>
                        <small class="text-muted">
                            {{ $foundItem->user->isAdmin() ? 'Administrator' : 'User' }}
                        </small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-envelope"></i> Email
                    </h6>
                    <p class="mb-0">{{ $foundItem->user->email }}</p>
                </div>
                
                @if($foundItem->user->latitude && $foundItem->user->longitude)
                <div class="mb-0">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-map-pin"></i> User Location
                    </h6>
                    <p class="mb-0 text-muted small">
                        {{ number_format($foundItem->user->latitude, 6) }}, {{ number_format($foundItem->user->longitude, 6) }}
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Share Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="fas fa-share-alt"></i> Share This Item
                </h6>
                <div class="btn-group w-100">
                    <button class="btn btn-outline-primary" onclick="shareItem('facebook')">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button class="btn btn-outline-info" onclick="shareItem('twitter')">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="shareItem('whatsapp')">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button class="btn btn-outline-secondary" onclick="copyLink()">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Claim Modal -->
@if($foundItem->status === 'pending')
<div class="modal fade" id="claimModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-handshake text-success"></i> Mark as Claimed
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('found-items.update', $foundItem) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="claimed">
                    
                    <div class="mb-3">
                        <label for="claim_details" class="form-label">Claim Details (Optional)</label>
                        <textarea class="form-control" id="claim_details" name="claim_details" 
                                  rows="3" placeholder="Add any details about the claim..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Note:</strong> This action will mark the item as claimed and notify any potential matches.
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

<!-- Notifications Container -->
<div id="notificationsContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
@endsection

@push('styles')
@if($foundItem->latitude && $foundItem->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .found-marker {
        color: #28a745;
        font-size: 30px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .toast {
        min-width: 250px;
    }
    
    .card {
        transition: transform 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .btn-group .btn {
        border-radius: 8px !important;
    }
    
    #map {
        height: 250px;
        width: 100%;
    }
    
    .leaflet-container {
        font-family: inherit;
    }
</style>
@endif
@endpush

@push('scripts')
@if($foundItem->latitude && $foundItem->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if map element exists
        const mapElement = document.getElementById('map');
        if (!mapElement) return;
        
        // Initialize map with a small delay to ensure container is ready
        setTimeout(function() {
            try {
                // Create map
                const map = L.map('map').setView([{{ $foundItem->latitude }}, {{ $foundItem->longitude }}], 15);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);
                
                // Create custom icon
                const customIcon = L.divIcon({
                    className: 'found-marker',
                    html: '<i class="fas fa-check-circle" style="color: #28a745; font-size: 30px;"></i>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                });
                
                // Add marker
                const marker = L.marker([{{ $foundItem->latitude }}, {{ $foundItem->longitude }}], {
                    icon: customIcon
                }).addTo(map);
                
                // Add popup
                marker.bindPopup(`
                    <strong>{{ $foundItem->item_name }}</strong><br>
                    <small>Found on {{ $foundItem->date_found->format('M d, Y') }}</small>
                `).openPopup();
                
                // Force map to resize (helps with hidden containers)
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);
                
            } catch (error) {
                console.error('Map initialization failed:', error);
                // Fallback to Google Maps link
                const mapContainer = document.getElementById('map');
                if (mapContainer) {
                    mapContainer.innerHTML = `
                        <div class="text-center p-4">
                            <i class="fas fa-exclamation-circle text-warning mb-2"></i>
                            <p>Map could not be loaded.</p>
                            <a href="https://maps.google.com/?q={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                               target="_blank" class="btn btn-sm btn-primary">
                                View on Google Maps
                            </a>
                        </div>
                    `;
                }
            }
        }, 200);
    });
</script>
@endif

<script>
    // Open image modal
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
    
    // Share item
    function shareItem(platform) {
        const url = window.location.href;
        const title = 'Found Item: {{ $foundItem->item_name }}';
        const text = 'Check out this found item on Lost & Found System';
        
        let shareUrl = '';
        switch(platform) {
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                break;
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
                break;
            case 'whatsapp':
                shareUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
                break;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    }
    
    // Copy link to clipboard
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link copied to clipboard!', 'success');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            showToast('Failed to copy link', 'error');
        });
    }
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center text-white bg-${type} border-0 mb-2`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();
        
        // Remove toast after hiding
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
</script>
@endpush