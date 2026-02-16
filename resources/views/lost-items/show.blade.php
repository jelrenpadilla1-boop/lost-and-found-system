@extends('layouts.app')

@section('title', $lostItem->item_name)

@section('content')
<div class="container py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="color: white;">{{ $lostItem->item_name }}</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="status-badge status-{{ $lostItem->status }}">
                    {{ ucfirst($lostItem->status) }}
                </span>
                <small style="color: #a0a0a0;">
                    <i class="fas fa-clock" style="color: var(--primary);"></i>
                    Lost {{ $lostItem->created_at->diffForHumans() }}
                </small>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('lost-items.index') }}" class="btn btn-outline-primary">
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
            <div class="details-card mb-4">
                <div class="card-body">
                    <div class="row">
                        {{-- Image Column --}}
                        <div class="col-md-5 mb-3 mb-md-0">
                            @if($lostItem->photo)
                                <div class="image-preview-container">
                                    <img src="{{ asset('storage/' . $lostItem->photo) }}" 
                                         class="item-preview-image" 
                                         alt="{{ $lostItem->item_name }}">
                                    <button class="btn-view-full mt-2" onclick="openImageModal('{{ asset('storage/' . $lostItem->photo) }}')">
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

                        {{-- Details Column --}}
                        <div class="col-md-7">
                            <h6 class="section-label">
                                <i class="fas fa-align-left" style="color: var(--primary);"></i> Description
                            </h6>
                            <p class="description-text mb-4">{{ $lostItem->description }}</p>

                            <div class="info-grid">
                                <div class="info-item">
                                    <small class="info-label">
                                        <i class="fas fa-tag" style="color: var(--primary);"></i> Category
                                    </small>
                                    <span class="info-value">{{ $lostItem->category }}</span>
                                </div>
                                <div class="info-item">
                                    <small class="info-label">
                                        <i class="fas fa-calendar" style="color: var(--primary);"></i> Date Lost
                                    </small>
                                    <span class="info-value">{{ $lostItem->date_lost->format('M d, Y') }}</span>
                                </div>
                                <div class="info-item full-width">
                                    <small class="info-label">
                                        <i class="fas fa-user" style="color: var(--primary);"></i> Reported By
                                    </small>
                                    <span class="info-value">{{ $lostItem->user->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions Card --}}
            <div class="actions-card mb-4">
                <div class="card-body">
                    <h6 class="section-label mb-3">
                        <i class="fas fa-bolt" style="color: var(--primary);"></i> Actions
                    </h6>
                    <div class="actions-grid">
                        @if($lostItem->status === 'pending')
                            <button class="action-btn success" data-bs-toggle="modal" data-bs-target="#foundModal">
                                <i class="fas fa-check"></i> Mark as Found
                            </button>
                        @endif

                        @can('delete', $lostItem)
                            <form action="{{ route('lost-items.destroy', $lostItem) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this item?');">
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

            {{-- Matches Card --}}
            @if($matches->count() > 0)
                <div class="matches-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> Potential Matches
                            </h6>
                            <span class="matches-count">{{ $matches->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($matches as $match)
                            <div class="match-card-item">
                                <div class="match-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="match-score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                            {{ $match->match_score }}% Match
                                        </span>
                                        <strong class="match-item-name">{{ $match->foundItem->item_name }}</strong>
                                    </div>
                                    <a href="{{ route('matches.show', $match) }}" class="btn-view-match">
                                        View <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                                
                                <p class="match-description">
                                    {{ Str::limit($match->foundItem->description, 80) }}
                                </p>
                                
                                <div class="match-meta">
                                    <small>
                                        <i class="fas fa-user"></i> {{ $match->foundItem->user->name }}
                                    </small>
                                    <small>
                                        <i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}
                                    </small>
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
            <div class="contact-card mb-4">
                <div class="card-body">
                    <h6 class="section-label mb-3">
                        <i class="fas fa-user-circle" style="color: var(--primary);"></i> Contact Information
                    </h6>
                    
                    <div class="contact-user">
                        <div class="contact-avatar">
                            {{ substr($lostItem->user->name, 0, 1) }}
                        </div>
                        <div class="contact-user-info">
                            <p class="contact-name">{{ $lostItem->user->name }}</p>
                            <small class="contact-role">
                                {{ $lostItem->user->isAdmin() ? 'Administrator' : 'User' }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="contact-detail">
                        <small class="detail-label">
                            <i class="fas fa-envelope" style="color: var(--primary);"></i> Email
                        </small>
                        <p class="detail-value">{{ $lostItem->user->email }}</p>
                    </div>

                    @if($lostItem->user->latitude && $lostItem->user->longitude)
                        <div class="contact-detail">
                            <small class="detail-label">
                                <i class="fas fa-map-pin" style="color: var(--primary);"></i> User Location
                            </small>
                            <p class="detail-value small">
                                {{ number_format($lostItem->user->latitude, 6) }}, {{ number_format($lostItem->user->longitude, 6) }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Location Map Card --}}
            @if($lostItem->latitude && $lostItem->longitude)
                <div class="map-card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-map" style="color: var(--primary);"></i> Location
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps?q={{ $lostItem->latitude }},{{ $lostItem->longitude }}&hl=en&z=15&output=embed"
                                style="border:0; width: 100%; height: 200px;"
                                allowfullscreen=""
                                loading="lazy">
                            </iframe>
                        </div>
                        
                        <div class="map-footer">
                            <small class="coordinates">
                                <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                                {{ number_format($lostItem->latitude, 6) }}, {{ number_format($lostItem->longitude, 6) }}
                            </small>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $lostItem->latitude }},{{ $lostItem->longitude }}" 
                               target="_blank" 
                               class="btn-directions">
                                <i class="fas fa-directions"></i> Directions
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle" style="color: var(--primary);"></i> Mark as Found
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
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
                        
                        <div class="alert-note">
                            <i class="fas fa-info-circle"></i>
                            <span>This will notify potential matches and update the item status.</span>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-confirm">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- Image Modal --}}
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

<style>
    /* Status Badge */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 600;
        color: white;
        display: inline-block;
    }

    .status-pending {
        background: linear-gradient(135deg, #ffa500, #ffb52e);
        box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
    }

    .status-found {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
        color: black;
    }

    .status-returned {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        box-shadow: 0 0 15px var(--primary-glow);
    }

    /* Cards */
    .details-card,
    .actions-card,
    .matches-card,
    .contact-card,
    .map-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .details-card:hover,
    .actions-card:hover,
    .matches-card:hover,
    .contact-card:hover,
    .map-card:hover {
        border-color: var(--primary);
        box-shadow: 0 10px 30px var(--primary-glow);
        transform: translateY(-2px);
    }

    .card-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1rem 1.25rem;
    }

    .card-header h6 {
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Section Label */
    .section-label {
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Image Preview */
    .image-preview-container {
        text-align: center;
    }

    .item-preview-image {
        max-width: 100%;
        max-height: 250px;
        border-radius: 12px;
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
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #333;
        color: #a0a0a0;
    }

    /* Description Text */
    .description-text {
        color: #a0a0a0;
        line-height: 1.6;
        background: #222;
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid #333;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .info-item {
        &.full-width {
            grid-column: 1 / -1;
        }
    }

    .info-label {
        color: #a0a0a0;
        display: block;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: white;
        font-weight: 500;
    }

    /* Actions Grid */
    .actions-grid {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 0.75rem 1.25rem;
        border: 2px solid transparent;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: transparent;
    }

    .action-btn.success {
        border-color: #00fa9a;
        color: #00fa9a;
    }

    .action-btn.success:hover {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 250, 154, 0.3);
    }

    .action-btn.danger {
        border-color: #ff4444;
        color: #ff4444;
    }

    .action-btn.danger:hover {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
    }

    /* Matches */
    .matches-count {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 0 15px var(--primary-glow);
    }

    .match-card-item {
        background: #222;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .match-card-item:hover {
        border-color: var(--primary);
        transform: translateX(5px);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    .match-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .match-score-badge {
        padding: 0.25rem 0.75rem;
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

    .match-item-name {
        color: white;
        font-size: 0.875rem;
    }

    .btn-view-match {
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
    }

    .btn-view-match:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateX(3px);
    }

    .match-description {
        color: #a0a0a0;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
    }

    .match-meta {
        display: flex;
        gap: 1rem;
        color: #a0a0a0;
        font-size: 0.75rem;
    }

    .match-meta i {
        color: var(--primary);
        margin-right: 0.25rem;
    }

    /* Contact Card */
    .contact-user {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #222;
        border-radius: 12px;
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
        box-shadow: 0 0 15px var(--primary-glow);
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
        border-radius: 10px;
        border: 1px solid #333;
    }

    .detail-label {
        color: #a0a0a0;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        color: white;
        margin: 0;
        word-break: break-all;
    }

    /* Map Card */
    .map-container {
        width: 100%;
        height: 200px;
        overflow: hidden;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .map-footer {
        padding: 1rem;
        background: #222;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .coordinates {
        color: #a0a0a0;
        font-size: 0.75rem;
    }

    .btn-directions {
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

    .btn-directions:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
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

    .modal-footer {
        background: #222;
        border-top: 1px solid #333;
        padding: 1.25rem 1.5rem;
    }

    .form-label {
        color: white;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control {
        background: #222;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: white;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
        outline: none;
        background: #2a2a2a;
    }

    .form-control::placeholder {
        color: #666;
    }

    .alert-note {
        background: rgba(255, 20, 147, 0.1);
        border: 1px solid var(--primary);
        border-radius: 12px;
        padding: 1rem;
        color: #a0a0a0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-note i {
        color: var(--primary);
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

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .match-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .map-footer {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .btn-directions {
            width: 100%;
            justify-content: center;
        }

        .actions-grid {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
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

    .details-card,
    .actions-card,
    .matches-card,
    .contact-card,
    .map-card {
        animation: fadeIn 0.5s ease forwards;
    }
</style>

<script>
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
</script>
@endsection