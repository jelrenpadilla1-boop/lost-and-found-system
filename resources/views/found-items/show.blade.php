@extends('layouts.app')

@section('title', $foundItem->item_name)

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-check-circle" style="color: var(--primary);"></i> {{ $foundItem->item_name }}
        </h1>
        <p>Found Item Details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('found-items.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        @can('update', $foundItem)
        <a href="{{ route('found-items.edit', $foundItem) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        @endcan
    </div>
</div>

<div class="row">
    <!-- Left Column: Item Details -->
    <div class="col-lg-8">
        <!-- Item Information Card -->
        <div class="details-card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Photo Column -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        <div class="image-preview-container">
                            @if($foundItem->photo)
                                <img src="{{ asset('storage/' . $foundItem->photo) }}" 
                                     class="item-preview-image" 
                                     alt="{{ $foundItem->item_name }}">
                                <div class="mt-3">
                                    <button class="btn-view-full" onclick="openImageModal('{{ asset('storage/' . $foundItem->photo) }}')">
                                        <i class="fas fa-expand"></i> View Full Size
                                    </button>
                                </div>
                            @else
                                <div class="no-image-placeholder">
                                    <i class="fas fa-image fa-4x" style="color: var(--primary); opacity: 0.3;"></i>
                                    <span class="mt-2">No photo available</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Details Column -->
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="badge-group">
                                <span class="status-badge status-{{ $foundItem->status }}">
                                    {{ ucfirst($foundItem->status) }}
                                </span>
                                <span class="category-badge">{{ $foundItem->category }}</span>
                            </div>
                            <small class="time-badge">
                                <i class="fas fa-clock" style="color: var(--primary);"></i> 
                                {{ $foundItem->created_at->diffForHumans() }}
                            </small>
                        </div>
                        
                        <h4 class="item-title-detail">{{ $foundItem->item_name }}</h4>
                        
                        <div class="description-section mb-4">
                            <h6 class="section-label">
                                <i class="fas fa-align-left" style="color: var(--primary);"></i> Description
                            </h6>
                            <div class="description-content">
                                {{ $foundItem->description }}
                            </div>
                        </div>
                        
                        <div class="info-grid">
                            <div class="info-item">
                                <h6 class="info-label">
                                    <i class="fas fa-calendar" style="color: var(--primary);"></i> Date Found
                                </h6>
                                <p class="info-value">{{ $foundItem->date_found->format('F d, Y') }}</p>
                            </div>
                            
                            <div class="info-item">
                                <h6 class="info-label">
                                    <i class="fas fa-user" style="color: var(--primary);"></i> Found By
                                </h6>
                                <p class="info-value">{{ $foundItem->user->name }}</p>
                            </div>
                            
                            @if($foundItem->latitude && $foundItem->longitude)
                            <div class="info-item full-width">
                                <h6 class="info-label">
                                    <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Location Found
                                </h6>
                                <p class="info-value">
                                    {{ number_format($foundItem->latitude, 6) }}, {{ number_format($foundItem->longitude, 6) }}
                                </p>
                                <a href="https://maps.google.com/?q={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                                   target="_blank" class="map-link">
                                    <i class="fas fa-external-link-alt"></i> View on Google Maps
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Matches Section -->
        @if($matches->count() > 0)
        <div class="matches-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> 
                    Potential Matches ({{ $matches->count() }})
                </h5>
            </div>
            <div class="card-body">
                <p class="matches-intro">
                    <i class="fas fa-lightbulb" style="color: var(--primary);"></i> 
                    These lost items might match with this found item based on our AI matching system.
                </p>
                
                @foreach($matches as $match)
                <div class="match-card-item">
                    <div class="match-content">
                        <div class="match-header">
                            <div class="match-user">
                                <div class="match-avatar">
                                    {{ substr($match->lostItem->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="match-title">{{ $match->lostItem->item_name }}</h6>
                                    <small class="match-meta">
                                        <i class="fas fa-user"></i> {{ $match->lostItem->user->name }} • 
                                        <i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                            <div class="match-score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                {{ $match->match_score }}% Match
                            </div>
                        </div>
                        
                        <p class="match-description">
                            {{ \Illuminate\Support\Str::limit($match->lostItem->description, 100) }}
                        </p>
                        
                        <div class="match-actions">
                            <a href="{{ route('matches.show', $match) }}" class="btn-view-match">
                                <i class="fas fa-eye"></i> View Match
                            </a>
                            <a href="{{ route('lost-items.show', $match->lostItem) }}" class="btn-view-item">
                                <i class="fas fa-external-link-alt"></i> View Item
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="text-center mt-4">
                    <a href="{{ route('matches.index') }}" class="btn-view-all">
                        <i class="fas fa-list"></i> View All Matches
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="no-matches-card">
            <div class="no-matches-content">
                <i class="fas fa-search fa-3x" style="color: var(--primary); opacity: 0.5;"></i>
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
        <div class="actions-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt" style="color: var(--primary);"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="actions-grid">
                    @if($foundItem->status === 'pending')
                        <button class="action-btn success" data-bs-toggle="modal" data-bs-target="#claimModal">
                            <i class="fas fa-handshake"></i> Mark as Claimed
                        </button>
                    @endif
                    
                    <a href="{{ route('matches.index') }}?found_item={{ $foundItem->id }}" class="action-btn info">
                        <i class="fas fa-search"></i> Find More Matches
                    </a>
                    
                    @if($foundItem->latitude && $foundItem->longitude)
                    <a href="https://maps.google.com/?q={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                       target="_blank" class="action-btn primary">
                        <i class="fas fa-map-marked-alt"></i> View on Google Maps
                    </a>
                    @endif
                    
                    @can('delete', $foundItem)
                    <form action="{{ route('found-items.destroy', $foundItem) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn danger">
                            <i class="fas fa-trash"></i> Delete Item
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        
        <!-- Location Map -->
        @if($foundItem->latitude && $foundItem->longitude)
        <div class="map-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map" style="color: var(--primary);"></i> Location Map
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 250px; border-radius: 0 0 16px 16px;"></div>
            </div>
            <div class="card-footer">
                <small>
                    <i class="fas fa-map-pin me-1" style="color: var(--primary);"></i>
                    {{ number_format($foundItem->latitude, 6) }}, {{ number_format($foundItem->longitude, 6) }}
                </small>
            </div>
        </div>
        @endif
        
        <!-- Contact Information -->
        <div class="contact-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle" style="color: var(--primary);"></i> Contact Information
                </h5>
            </div>
            <div class="card-body">
                <div class="contact-user">
                    <div class="contact-avatar">
                        {{ substr($foundItem->user->name, 0, 1) }}
                    </div>
                    <div class="contact-user-info">
                        <h6 class="contact-name">{{ $foundItem->user->name }}</h6>
                        <small class="contact-role">
                            {{ $foundItem->user->isAdmin() ? 'Administrator' : 'User' }}
                        </small>
                    </div>
                </div>
                
                <div class="contact-detail">
                    <h6 class="detail-label">
                        <i class="fas fa-envelope" style="color: var(--primary);"></i> Email
                    </h6>
                    <p class="detail-value">{{ $foundItem->user->email }}</p>
                </div>
                
                @if($foundItem->user->latitude && $foundItem->user->longitude)
                <div class="contact-detail">
                    <h6 class="detail-label">
                        <i class="fas fa-map-pin" style="color: var(--primary);"></i> User Location
                    </h6>
                    <p class="detail-value small">
                        {{ number_format($foundItem->user->latitude, 6) }}, {{ number_format($foundItem->user->longitude, 6) }}
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Share Card -->
        <div class="share-card mt-4">
            <div class="card-body">
                <h6 class="share-title">
                    <i class="fas fa-share-alt" style="color: var(--primary);"></i> Share This Item
                </h6>
                <div class="share-buttons">
                    <button class="share-btn facebook" onclick="shareItem('facebook')">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button class="share-btn twitter" onclick="shareItem('twitter')">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button class="share-btn whatsapp" onclick="shareItem('whatsapp')">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button class="share-btn copy" onclick="copyLink()">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-handshake" style="color: var(--primary);"></i> Mark as Claimed
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
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
                    
                    <div class="alert-note">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Note:</strong> This action will mark the item as claimed and notify any potential matches.
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-confirm">Confirm Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="" style="max-height: 70vh;">
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
@endif
<style>
    /* Main Container Styles */
    .details-card,
    .matches-card,
    .no-matches-card,
    .actions-card,
    .map-card,
    .contact-card,
    .share-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .details-card:hover,
    .matches-card:hover,
    .actions-card:hover,
    .map-card:hover,
    .contact-card:hover,
    .share-card:hover {
        border-color: var(--primary);
        box-shadow: 0 10px 30px var(--primary-glow);
        transform: translateY(-2px);
    }

    .card-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-footer {
        background: #222;
        border-top: 1px solid #333;
        padding: 0.875rem 1.5rem;
        color: #a0a0a0;
        font-size: 0.875rem;
    }

    /* Image Preview */
    .image-preview-container {
        text-align: center;
    }

    .item-preview-image {
        max-width: 100%;
        max-height: 250px;
        border-radius: 16px;
        object-fit: cover;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .item-preview-image:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 35px var(--primary-glow);
    }

    .btn-view-full {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view-full:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    .no-image-placeholder {
        height: 200px;
        background: #222;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #333;
        color: #a0a0a0;
    }

    /* Badges */
    .badge-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 600;
        color: white;
    }

    .status-pending {
        background: linear-gradient(135deg, #ffa500, #ffb52e);
        box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
    }

    .status-claimed {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
        color: black;
    }

    .status-disposed {
        background: linear-gradient(135deg, #666, #888);
        box-shadow: 0 0 15px rgba(102, 102, 102, 0.3);
    }

    .category-badge {
        padding: 0.5rem 1rem;
        background: #333;
        border: 1px solid var(--primary);
        border-radius: 30px;
        font-size: 0.875rem;
        color: var(--primary);
    }

    .time-badge {
        color: #a0a0a0;
        font-size: 0.875rem;
    }

    /* Item Title */
    .item-title-detail {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin: 1rem 0;
        background: linear-gradient(135deg, white, var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Description Section */
    .section-label {
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .description-content {
        background: #222;
        border: 1px solid #333;
        border-radius: 16px;
        padding: 1.25rem;
        color: #a0a0a0;
        line-height: 1.6;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .info-item {
        &.full-width {
            grid-column: 1 / -1;
        }
    }

    .info-label {
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        color: #a0a0a0;
        font-size: 1rem;
        margin: 0;
    }

    .map-link {
        color: var(--primary);
        text-decoration: none;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
        transition: all 0.3s ease;
    }

    .map-link:hover {
        color: var(--primary-light);
        transform: translateX(5px);
    }

    /* Matches Section */
    .matches-intro {
        color: #a0a0a0;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #222;
        border-radius: 12px;
        border: 1px solid #333;
    }

    .match-card-item {
        background: #222;
        border: 1px solid #333;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .match-card-item:hover {
        border-color: var(--primary);
        transform: translateX(5px);
        box-shadow: 0 5px 20px var(--primary-glow);
    }

    .match-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .match-user {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .match-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
    }

    .match-title {
        color: white;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
    }

    .match-meta {
        color: #a0a0a0;
        font-size: 0.75rem;
    }

    .match-meta i {
        color: var(--primary);
        margin-right: 0.25rem;
    }

    .match-score-badge {
        padding: 0.375rem 1rem;
        border-radius: 30px;
        font-size: 0.875rem;
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

    .match-description {
        color: #a0a0a0;
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .match-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-view-match,
    .btn-view-item {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-view-match {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
    }

    .btn-view-match:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    .btn-view-item {
        background: transparent;
        border: 2px solid #666;
        color: #a0a0a0;
    }

    .btn-view-item:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        text-decoration: none;
        border-radius: 30px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 0 20px var(--primary-glow);
    }

    .btn-view-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px var(--primary-glow);
    }

    /* No Matches */
    .no-matches-card {
        padding: 3rem;
        text-align: center;
    }

    .no-matches-content {
        color: white;
    }

    .no-matches-content h5 {
        margin: 1rem 0 0.5rem;
        color: white;
    }

    /* Actions Grid */
    .actions-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .action-btn {
        padding: 1rem;
        border: 2px solid transparent;
        border-radius: 16px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        width: 100%;
    }

    .action-btn.success {
        background: transparent;
        border-color: #00fa9a;
        color: #00fa9a;
    }

    .action-btn.success:hover {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 250, 154, 0.3);
    }

    .action-btn.info {
        background: transparent;
        border-color: var(--primary);
        color: var(--primary);
    }

    .action-btn.info:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--primary-glow);
    }

    .action-btn.primary {
        background: transparent;
        border-color: var(--primary);
        color: var(--primary);
    }

    .action-btn.primary:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--primary-glow);
    }

    .action-btn.danger {
        background: transparent;
        border-color: #ff4444;
        color: #ff4444;
    }

    .action-btn.danger:hover {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
    }

    /* Contact Card */
    .contact-user {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #222;
        border-radius: 16px;
        border: 1px solid #333;
    }

    .contact-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .contact-user-info {
        flex: 1;
    }

    .contact-name {
        color: white;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
    }

    .contact-role {
        color: var(--primary);
    }

    .contact-detail {
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: #222;
        border-radius: 12px;
        border: 1px solid #333;
    }

    .detail-label {
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-value {
        color: #a0a0a0;
        margin: 0;
        word-break: break-all;
    }

    /* Share Card */
    .share-title {
        color: white;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .share-buttons {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }

    .share-btn {
        width: 100%;
        aspect-ratio: 1;
        border: none;
        border-radius: 12px;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .share-btn.facebook {
        background: #1877f2;
        color: white;
    }

    .share-btn.twitter {
        background: #1da1f2;
        color: white;
    }

    .share-btn.whatsapp {
        background: #25d366;
        color: white;
    }

    .share-btn.copy {
        background: #333;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    /* Modal Styles */
    .modal-content {
        background: #1a1a1a;
        border: 1px solid var(--primary);
        border-radius: 20px;
    }

    .modal-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .alert-note {
        background: rgba(255, 20, 147, 0.1);
        border: 1px solid var(--primary);
        border-radius: 12px;
        padding: 1rem;
        margin: 1rem 0;
        color: #a0a0a0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-note i {
        color: var(--primary);
    }

    .modal-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .btn-cancel,
    .btn-confirm {
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-cancel {
        background: transparent;
        border: 2px solid #666;
        color: #a0a0a0;
    }

    .btn-cancel:hover {
        border-color: #ff4444;
        color: #ff4444;
    }

    .btn-confirm {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        box-shadow: 0 0 20px var(--primary-glow);
    }

    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px var(--primary-glow);
    }

    /* Map Styles */
    #map {
        background: #222;
    }

    .found-marker {
        color: var(--primary);
        font-size: 30px;
        text-shadow: 0 0 20px var(--primary-glow);
    }

    /* Toast Notifications */
    .toast {
        background: #1a1a1a !important;
        border: 1px solid var(--primary) !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px var(--primary-glow) !important;
    }

    .toast-body {
        color: white !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-close-white {
        filter: invert(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .match-header {
            flex-direction: column;
            gap: 1rem;
        }

        .match-actions {
            flex-direction: column;
        }

        .share-buttons {
            grid-template-columns: repeat(2, 1fr);
        }

        .modal-actions {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-confirm {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
@if($foundItem->latitude && $foundItem->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapElement = document.getElementById('map');
        if (!mapElement) return;
        
        setTimeout(function() {
            try {
                const map = L.map('map').setView([{{ $foundItem->latitude }}, {{ $foundItem->longitude }}], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19
                }).addTo(map);
                
                const customIcon = L.divIcon({
                    className: 'found-marker',
                    html: '<i class="fas fa-check-circle" style="color: var(--primary); font-size: 30px;"></i>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    popupAnchor: [0, -30]
                });
                
                const marker = L.marker([{{ $foundItem->latitude }}, {{ $foundItem->longitude }}], {
                    icon: customIcon
                }).addTo(map);
                
                marker.bindPopup(`
                    <strong style="color: var(--primary);">{{ $foundItem->item_name }}</strong><br>
                    <small>Found on {{ $foundItem->date_found->format('M d, Y') }}</small>
                `).openPopup();
                
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);
                
            } catch (error) {
                console.error('Map initialization failed:', error);
                const mapContainer = document.getElementById('map');
                if (mapContainer) {
                    mapContainer.innerHTML = `
                        <div class="text-center p-4">
                            <i class="fas fa-exclamation-circle" style="color: var(--primary); margin-bottom: 0.5rem;"></i>
                            <p style="color: #a0a0a0;">Map could not be loaded.</p>
                            <a href="https://maps.google.com/?q={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                               target="_blank" class="btn-view-match" style="display: inline-flex;">
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
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
    
    function shareItem(platform) {
        const url = window.location.href;
        const title = 'Found Item: {{ $foundItem->item_name }}';
        const text = 'Check out this found item on Foundify';
        
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
    
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link copied to clipboard!', 'success');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            showToast('Failed to copy link', 'error');
        });
    }
    
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast align-items-center border-0 mb-2`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        const bgColor = type === 'success' ? '#00fa9a' : type === 'error' ? '#ff4444' : 'var(--primary)';
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}" 
                       style="color: ${bgColor};"></i>
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
        
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }

    // Add animation to cards
    document.querySelectorAll('.details-card, .matches-card, .actions-card, .map-card, .contact-card, .share-card').forEach((card, index) => {
        card.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1}s`;
    });
</script>
@endpush