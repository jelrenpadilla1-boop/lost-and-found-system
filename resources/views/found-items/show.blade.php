@extends('layouts.app')

@section('title', $foundItem->item_name)

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    $isOwner = Auth::id() === $foundItem->user_id;
@endphp

<div class="dashboard-wrapper">
    {{-- Authentication Check --}}
    @if(!$isAdmin && !$isOwner && $foundItem->status === 'pending')
        <div class="access-denied">
            <i class="fas fa-lock"></i>
            <h4>Access Denied</h4>
            <p>This item is pending approval and not visible to the public.</p>
            <a href="{{ route('found-items.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Found Items
            </a>
        </div>
    @else
        {{-- Header Section --}}
        <div class="page-header">
            <div class="header-left">
                <h1>{{ $foundItem->item_name }}</h1>
                <div class="header-meta">
                    <span class="status-badge status-{{ $foundItem->status }}">
                        @if($foundItem->status == 'pending' && $isAdmin)
                            <i class="fas fa-clock"></i> Pending Approval
                        @elseif($foundItem->status == 'pending')
                            <i class="fas fa-clock"></i> Pending
                        @elseif($foundItem->status == 'approved')
                            <i class="fas fa-check-circle"></i> Approved
                        @elseif($foundItem->status == 'claimed')
                            <i class="fas fa-handshake"></i> Claimed
                        @elseif($foundItem->status == 'returned')
                            <i class="fas fa-home"></i> Returned
                        @elseif($foundItem->status == 'disposed')
                            <i class="fas fa-times"></i> Disposed
                        @elseif($foundItem->status == 'rejected')
                            <i class="fas fa-times-circle"></i> Rejected
                        @endif
                    </span>
                    
                    <span class="time-badge">
                        <i class="fas fa-clock"></i>
                        Found {{ $foundItem->created_at->diffForHumans() }}
                    </span>

                    @if($isOwner)
                        <span class="owner-badge">
                            <i class="fas fa-star"></i> Your Item
                        </span>
                    @endif
                    
                    @if($isAdmin)
                        <span class="admin-badge">
                            <i class="fas fa-crown"></i> Admin View
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="header-actions">
                <a href="{{ route('found-items.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
                
                @if($isAdmin && $foundItem->status === 'pending')
                    <form action="{{ route('found-items.approve', $foundItem) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Approve this found item?')">
                            <i class="fas fa-check-circle"></i>
                            <span>Approve</span>
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times-circle"></i>
                        <span>Reject</span>
                    </button>
                @endif
                
                @can('update', $foundItem)
                    <a href="{{ route('found-items.edit', $foundItem) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>
                @endcan
            </div>
        </div>

        {{-- Alerts Section --}}
        <div class="alerts-container">
            @if($foundItem->status === 'rejected' && $foundItem->rejection_reason && ($isAdmin || $isOwner))
                <div class="alert-card alert-danger">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Item Rejected</strong>
                        <p>{{ $foundItem->rejection_reason }}</p>
                    </div>
                </div>
            @endif

            @if($foundItem->status === 'pending' && $isOwner && !$isAdmin)
                <div class="alert-card alert-warning">
                    <div class="alert-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Pending Approval</strong>
                        <p>Your item is awaiting admin review. It will be visible to others once approved.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Main Content Grid --}}
        <div class="content-grid">
            {{-- Left Column --}}
            <div class="left-column">
                {{-- Item Details Card --}}
                <div class="card details-card">
                    <div class="card-body">
                        <div class="details-grid">
                            {{-- Image Section --}}
                            <div class="image-section">
                                @if($foundItem->photo)
                                    <div class="image-wrapper">
                                        <img src="{{ asset('storage/' . $foundItem->photo) }}" 
                                             class="item-image" 
                                             alt="{{ $foundItem->item_name }}">
                                        <button class="expand-btn" onclick="openImageModal('{{ asset('storage/' . $foundItem->photo) }}')">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                        <span>No photo available</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info Section --}}
                            <div class="info-section">
                                <div class="info-group">
                                    <label class="info-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
                                    <p class="description">{{ $foundItem->description }}</p>
                                </div>

                                <div class="info-grid">
                                    <div class="info-item">
                                        <label>Category</label>
                                        <span>{{ $foundItem->category }}</span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <label>Date Found</label>
                                        <span>{{ $foundItem->date_found->format('M d, Y') }}</span>
                                    </div>
                                    
                                    @if($foundItem->found_location)
                                    <div class="info-item full-width">
                                        <label>Found Location</label>
                                        <span>{{ $foundItem->found_location }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0)
                                    <div class="info-item full-width">
                                        <label>Coordinates</label>
                                        <span>{{ number_format($foundItem->latitude, 6) }}, {{ number_format($foundItem->longitude, 6) }}</span>
                                    </div>
                                    @endif
                                    
                                    <div class="info-item full-width">
                                        <label>Found By</label>
                                        <span>
                                            {{ $foundItem->user->name }}
                                            @if($foundItem->user_id === Auth::id())
                                                <span class="you-badge">(You)</span>
                                            @endif
                                        </span>
                                    </div>

                                    @if($isAdmin && $foundItem->approved_at)
                                    <div class="info-item full-width">
                                        <label>Approval Info</label>
                                        <span>Approved {{ $foundItem->approved_at->diffForHumans() }} by {{ $foundItem->approver->name ?? 'Admin' }}</span>
                                    </div>
                                    @endif

                                    @if($isAdmin && $foundItem->rejected_at)
                                    <div class="info-item full-width">
                                        <label>Rejection Info</label>
                                        <span>Rejected {{ $foundItem->rejected_at->diffForHumans() }} by {{ $foundItem->rejecter->name ?? 'Admin' }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Card --}}
                @if(($foundItem->status === 'pending' && ($isAdmin || $isOwner)) || 
                    ($foundItem->status === 'approved' && $isOwner) ||
                    ($isAdmin))
                    <div class="card actions-card">
                        <div class="card-header">
                            <h6><i class="fas fa-bolt"></i> Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="actions-grid">
                                @if($foundItem->status === 'approved' && $isOwner)
                                    <button class="action-btn success" data-bs-toggle="modal" data-bs-target="#claimModal">
                                        <i class="fas fa-handshake"></i> Mark as Claimed
                                    </button>
                                @endif

                                @can('delete', $foundItem)
                                    <form action="{{ route('found-items.destroy', $foundItem) }}" method="POST" 
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
                @endif

                {{-- Matches Card --}}
                @if($foundItem->status === 'approved' || $isAdmin || $isOwner)
                    <div class="card matches-card">
                        <div class="card-header">
                            <div class="header-content">
                                <h6><i class="fas fa-exchange-alt"></i> Potential Matches</h6>
                                @if($matches->count() > 0)
                                    <span class="matches-badge">{{ $matches->count() }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if($matches->count() > 0)
                                @foreach($matches as $match)
                                    @if($match->lostItem)
                                    <div class="match-item">
                                        <div class="match-header">
                                            <div class="match-score 
                                                @if($match->match_score >= 80) score-high
                                                @elseif($match->match_score >= 60) score-medium
                                                @else score-low
                                                @endif">
                                                {{ $match->match_score }}%
                                            </div>
                                            <div class="match-info">
                                                <strong>{{ $match->lostItem->item_name }}</strong>
                                                @if($match->lostItem->user_id === Auth::id())
                                                    <span class="your-item">Your item</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('matches.show', $match) }}" class="view-link">
                                                View <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        
                                        <p class="match-description">{{ Str::limit($match->lostItem->description, 60) }}</p>
                                        
                                        <div class="match-footer">
                                            <span><i class="fas fa-user"></i> {{ $match->lostItem->user->name }}</span>
                                            <span><i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}</span>
                                        </div>
                                        
                                        @if($match->status !== 'pending')
                                        <div class="match-status-badge mt-2">
                                            <span class="status-badge status-{{ $match->status }}">
                                                {{ ucfirst($match->status) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-search fa-3x mb-3" style="color: var(--text-muted);"></i>
                                    <p class="text-muted">No potential matches found yet.</p>
                                    @if($isOwner || $isAdmin)
                                        <small class="text-muted d-block mt-2">
                                            Matches will appear here when similar lost items are reported.
                                        </small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Debug Info (Only visible to admin) --}}
                @if($isAdmin)
                <div class="card debug-card" style="margin-top: 20px; border-color: #ffa500;">
                    <div class="card-header" style="background: rgba(255, 165, 0, 0.1);">
                        <h6><i class="fas fa-bug" style="color: #ffa500;"></i> Debug Info</h6>
                    </div>
                    <div class="card-body">
                        <ul style="color: var(--text-muted); font-size: 12px;">
                            <li>Item Status: <strong>{{ $foundItem->status }}</strong></li>
                            <li>Matches Count: <strong>{{ $matches->count() }}</strong></li>
                            <li>Item ID: <strong>{{ $foundItem->id }}</strong></li>
                            <li>Category: <strong>{{ $foundItem->category }}</strong></li>
                            @if($matches->count() > 0)
                                <li>Match IDs: 
                                    @foreach($matches as $match)
                                        {{ $match->id }} ({{ $match->match_score }}%) 
                                    @endforeach
                                </li>
                            @endif
                        </ul>
                        <small class="text-muted">This section is only visible to admins</small>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right Column --}}
            <div class="right-column">
                {{-- Contact Card --}}
                @if($foundItem->status !== 'pending' || $isAdmin || $isOwner)
                <div class="card contact-card">
                    <div class="card-header">
                        <h6><i class="fas fa-user-circle"></i> Contact Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="contact-profile">
                            <div class="contact-avatar">
                                {{ substr($foundItem->user->name, 0, 1) }}
                            </div>
                            <div class="contact-details">
                                <p class="contact-name">{{ $foundItem->user->name }}</p>
                                <small class="contact-role">
                                    {{ $foundItem->user->isAdmin() ? 'Administrator' : 'User' }}
                                    @if($foundItem->user_id === Auth::id())
                                        <span class="you-indicator">(You)</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="contact-info">
                            <div class="info-row">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $foundItem->user->email }}</span>
                            </div>

                            @if($foundItem->user->latitude && $foundItem->user->longitude)
                                <div class="info-row">
                                    <i class="fas fa-map-pin"></i>
                                    <span>{{ number_format($foundItem->user->latitude, 4) }}, {{ number_format($foundItem->user->longitude, 4) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($isOwner || $isAdmin)
                        <a href="{{ route('messages.start', $foundItem->user) }}" class="message-btn">
                            <i class="fas fa-comment"></i> Send Message
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Location Map Card --}}
                @if(($foundItem->found_location || ($foundItem->latitude && $foundItem->longitude)) && 
                    ($foundItem->status !== 'pending' || $isAdmin || $isOwner))
                <div class="card map-card">
                    <div class="card-header">
                        <h6><i class="fas fa-map"></i> Location</h6>
                    </div>
                    <div class="map-container" id="mapContainer">
                        <div id="map" style="height: 200px; width: 100%;"></div>
                    </div>
                    
                    <div class="map-footer" id="mapFooter">
                        @if($foundItem->found_location)
                            <div class="location-name">
                                <i class="fas fa-map-marked-alt"></i>
                                <span>{{ Str::limit($foundItem->found_location, 35) }}</span>
                            </div>
                        @endif
                        
                        <div class="map-actions">
                            @if($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0)
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                                   target="_blank" class="directions-btn">
                                    <i class="fas fa-directions"></i> Get Directions
                                </a>
                            @elseif($foundItem->found_location)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($foundItem->found_location) }}" 
                                   target="_blank" class="directions-btn">
                                    <i class="fas fa-search"></i> Search on Maps
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- Claim Modal --}}
@if($foundItem->status === 'approved' && $isOwner)
<div class="modal fade" id="claimModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-handshake" style="color: var(--primary);"></i> Mark as Claimed
                </h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('found-items.update', $foundItem) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="claimed">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="claim_details">Claim Details (Optional)</label>
                        <textarea id="claim_details" name="claim_details" 
                                  rows="3" placeholder="Add any details about the claim..."></textarea>
                    </div>
                    
                    <div class="info-box">
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

{{-- Reject Modal --}}
@if($isAdmin && $foundItem->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle" style="color: var(--error);"></i> Reject Item
                </h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('found-items.reject', $foundItem) }}" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Rejection Reason <span class="required">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" 
                                  rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <span>The user will be notified of this rejection.</span>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-danger">Reject Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="fullscreen-image" alt="">
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-glow: rgba(255, 20, 147, 0.3);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-header: #222;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #e0e0e0;
    --text-muted: #a0a0a0;
    --success: #00fa9a;
    --error: #ff4444;
    --warning: #ffa500;
    --info: #8b5cf6;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Dashboard Wrapper */
.dashboard-wrapper {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Access Denied */
.access-denied {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    max-width: 500px;
    margin: 40px auto;
}

.access-denied i {
    font-size: 60px;
    color: var(--error);
    margin-bottom: 20px;
}

.access-denied h4 {
    color: var(--text-primary);
    margin-bottom: 10px;
}

.access-denied p {
    color: var(--text-muted);
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    gap: 20px;
    flex-wrap: wrap;
}

.header-left h1 {
    color: var(--text-primary);
    margin: 0 0 10px 0;
    font-size: clamp(24px, 5vw, 28px);
}

.header-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Badges */
.status-badge {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-pending {
    background: linear-gradient(135deg, #ffa500, #ffb52e);
    box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
}

.status-approved {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
    color: black;
}

.status-claimed {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
    color: black;
}

.status-returned {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 0 15px var(--primary-glow);
}

.status-disposed {
    background: linear-gradient(135deg, #666, #888);
    box-shadow: 0 0 15px rgba(102, 102, 102, 0.3);
}

.status-rejected {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
    box-shadow: 0 0 15px rgba(255, 68, 68, 0.3);
}

.time-badge {
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
}

.time-badge i {
    color: var(--primary);
}

.owner-badge {
    background: rgba(255, 20, 147, 0.15);
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.admin-badge {
    background: rgba(255, 165, 0, 0.15);
    border: 1px solid #ffa500;
    color: #ffa500;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Header Actions */
.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 2px solid transparent;
    cursor: pointer;
}

.btn-outline {
    background: transparent;
    border-color: var(--primary);
    color: var(--primary);
}

.btn-outline:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    box-shadow: 0 0 20px var(--primary-glow);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--primary-glow);
}

.btn-success {
    background: linear-gradient(135deg, var(--success), #00ff7f);
    color: black;
    box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 250, 154, 0.5);
}

.btn-danger {
    background: linear-gradient(135deg, var(--error), #ff6b6b);
    color: white;
    box-shadow: 0 0 15px rgba(255, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(255, 68, 68, 0.5);
}

/* Alerts */
.alerts-container {
    margin-bottom: 30px;
}

.alert-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    border-left: 4px solid;
    margin-bottom: 15px;
}

.alert-danger {
    border-color: var(--error);
    background: rgba(255, 68, 68, 0.1);
}

.alert-warning {
    border-color: var(--warning);
    background: rgba(255, 165, 0, 0.1);
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-danger .alert-icon {
    background: rgba(255, 68, 68, 0.2);
    color: var(--error);
}

.alert-warning .alert-icon {
    background: rgba(255, 165, 0, 0.2);
    color: var(--warning);
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 5px;
    color: var(--text-primary);
}

.alert-content p {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* Cards */
.card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 25px;
    transition: var(--transition);
}

.card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
    transform: translateY(-2px);
}

.card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 16px 20px;
}

.card-header h6 {
    color: var(--text-primary);
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header h6 i {
    color: var(--primary);
}

.card-body {
    padding: 20px;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 25px;
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
}

/* Image Section */
.image-section {
    width: 100%;
}

.image-wrapper {
    position: relative;
    width: 100%;
    border-radius: 16px;
    overflow: hidden;
    aspect-ratio: 1;
    background: var(--bg-header);
}

.item-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-wrapper:hover .item-image {
    transform: scale(1.05);
}

.expand-btn {
    position: absolute;
    bottom: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.7);
    border: 2px solid var(--primary);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    opacity: 0;
}

.image-wrapper:hover .expand-btn {
    opacity: 1;
}

.expand-btn:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: scale(1.1);
}

.no-image {
    aspect-ratio: 1;
    background: var(--bg-header);
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--border-color);
    color: var(--text-muted);
}

.no-image i {
    font-size: 48px;
    margin-bottom: 10px;
    color: var(--primary);
    opacity: 0.5;
}

/* Info Section */
.info-group {
    margin-bottom: 20px;
}

.info-label {
    color: var(--text-muted);
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.info-label i {
    color: var(--primary);
}

.description {
    color: var(--text-secondary);
    line-height: 1.6;
    background: var(--bg-header);
    padding: 15px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    margin: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.info-item {
    background: var(--bg-header);
    padding: 12px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    margin-bottom: 5px;
}

.info-item span {
    color: var(--text-primary);
    font-weight: 500;
    word-break: break-word;
}

.you-badge {
    color: var(--primary);
    font-size: 12px;
    margin-left: 5px;
}

/* Actions Grid */
.actions-grid {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 12px 24px;
    border: 2px solid transparent;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
}

.action-btn.success {
    border-color: var(--success);
    color: var(--success);
}

.action-btn.success:hover {
    background: linear-gradient(135deg, var(--success), #00ff7f);
    color: black;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 250, 154, 0.3);
}

.action-btn.danger {
    border-color: var(--error);
    color: var(--error);
}

.action-btn.danger:hover {
    background: linear-gradient(135deg, var(--error), #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

/* Matches */
.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.matches-badge {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 0 15px var(--primary-glow);
}

.match-item {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 15px;
    margin-bottom: 15px;
    transition: var(--transition);
}

.match-item:hover {
    border-color: var(--primary);
    transform: translateX(5px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.match-item:last-child {
    margin-bottom: 0;
}

.match-header {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.match-score {
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    color: white;
    white-space: nowrap;
}

.match-score.high {
    background: linear-gradient(135deg, var(--success), #00ff7f);
    box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
    color: black;
}

.match-score.medium {
    background: linear-gradient(135deg, var(--warning), #ffb52e);
    box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
}

.match-score.low {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 0 15px var(--primary-glow);
}

.match-info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.match-info strong {
    color: var(--text-primary);
    font-size: 14px;
}

.your-item {
    background: rgba(255, 20, 147, 0.1);
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 2px 8px;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 500;
}

.view-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: var(--transition);
    white-space: nowrap;
}

.view-link:hover {
    color: var(--primary-light);
    transform: translateX(3px);
}

.match-description {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 10px;
}

.match-footer {
    display: flex;
    gap: 15px;
    color: var(--text-muted);
    font-size: 11px;
    flex-wrap: wrap;
}

.match-footer i {
    color: var(--primary);
    margin-right: 4px;
}

/* Contact Card */
.contact-profile {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
    flex-wrap: wrap;
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
    font-size: 20px;
    box-shadow: 0 0 15px var(--primary-glow);
    flex-shrink: 0;
}

.contact-details {
    flex: 1;
    min-width: 0;
}

.contact-name {
    color: var(--text-primary);
    font-weight: 600;
    margin: 0 0 5px 0;
    font-size: 16px;
    word-break: break-word;
}

.contact-role {
    color: var(--primary);
}

.you-indicator {
    color: var(--primary);
    margin-left: 5px;
}

.contact-info {
    margin-bottom: 20px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-color);
    word-break: break-word;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row i {
    color: var(--primary);
    width: 16px;
}

.message-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: transparent;
    border: 2px solid var(--primary);
    border-radius: 30px;
    color: var(--primary);
    text-decoration: none;
    transition: var(--transition);
}

.message-btn:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

/* Map Card */
.map-container {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: var(--bg-header);
}

#map {
    height: 200px;
    width: 100%;
}

.map-footer {
    padding: 15px;
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
}

.location-name {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 10px;
    word-break: break-word;
}

.location-name i {
    color: var(--primary);
    flex-shrink: 0;
}

.map-actions {
    display: flex;
    gap: 10px;
}

.directions-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    background: transparent;
    border: 2px solid var(--primary);
    border-radius: 30px;
    color: var(--primary);
    text-decoration: none;
    font-size: 12px;
    transition: var(--transition);
}

.directions-btn:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

/* Modal Styles */
.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 20px;
    overflow: hidden;
}

.modal-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.close-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--error);
    transform: rotate(90deg);
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
    padding: 15px 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* Form Elements */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    color: var(--text-primary);
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group textarea {
    width: 100%;
    padding: 12px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 14px;
    transition: var(--transition);
    resize: vertical;
}

.form-group textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    outline: none;
    background: var(--bg-card);
}

.required {
    color: var(--error);
}

.info-box {
    background: rgba(255, 20, 147, 0.1);
    border: 1px solid var(--primary);
    border-radius: 12px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-muted);
    font-size: 13px;
}

.info-box i {
    color: var(--primary);
}

.btn-cancel,
.btn-confirm,
.btn-danger {
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    border: 2px solid transparent;
}

.btn-cancel {
    background: transparent;
    border-color: var(--text-muted);
    color: var(--text-muted);
}

.btn-cancel:hover {
    border-color: var(--error);
    color: var(--error);
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

.btn-danger {
    background: linear-gradient(135deg, var(--error), #ff6b6b);
    color: white;
    box-shadow: 0 0 20px rgba(255, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 68, 68, 0.5);
}

/* Fullscreen Image */
.fullscreen-image {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
}

/* Leaflet Popup Customization */
.leaflet-popup-content-wrapper {
    background: var(--bg-card);
    color: var(--text-primary);
    border-radius: 12px;
    border: 1px solid var(--primary);
}

.leaflet-popup-tip {
    background: var(--bg-card);
}

.leaflet-popup-close-button {
    color: var(--text-muted) !important;
}

.leaflet-popup-close-button:hover {
    color: var(--primary) !important;
}

/* Debug Card */
.debug-card {
    margin-top: 20px;
    border-color: #ffa500;
}

.debug-card .card-header {
    background: rgba(255, 165, 0, 0.1);
}

.debug-card ul {
    list-style: none;
    padding: 0;
    margin: 0 0 10px 0;
}

.debug-card li {
    padding: 5px 0;
    border-bottom: 1px dashed var(--border-color);
}

.debug-card li:last-child {
    border-bottom: none;
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

.card {
    animation: fadeIn 0.5s ease forwards;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-wrapper {
        padding: 15px;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn {
        flex: 1;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .match-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .actions-grid {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
    
    .contact-profile {
        flex-direction: column;
        text-align: center;
    }
    
    .contact-avatar {
        margin: 0 auto;
    }
    
    .contact-details {
        text-align: center;
    }
    
    .map-actions {
        flex-direction: column;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-footer button {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .header-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .match-footer {
        flex-direction: column;
        gap: 8px;
    }
    
    .info-row {
        flex-wrap: wrap;
    }
}

/* Utility Classes */
.d-inline {
    display: inline-block;
}

.text-center {
    text-align: center;
}

.py-4 {
    padding-top: 20px;
    padding-bottom: 20px;
}

.mt-2 {
    margin-top: 8px;
}

.mb-3 {
    margin-bottom: 12px;
}
</style>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @php
        $canShowMap = ($foundItem->found_location || ($foundItem->latitude && $foundItem->longitude)) && 
                     ($foundItem->status !== 'pending' || $isAdmin || $isOwner);
    @endphp
    
    @if($canShowMap)
        initMap();
    @endif
});

function initMap() {
    const hasCoordinates = @json(($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0));
    const locationName = @json($foundItem->found_location);
    
    if (hasCoordinates) {
        const lat = @json($foundItem->latitude);
        const lng = @json($foundItem->longitude);
        displayMap(lat, lng, locationName);
    } else if (locationName) {
        geocodeLocation(locationName);
    }
}

function geocodeLocation(location) {
    const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}&limit=1`;
    
    fetch(geocodeUrl, {
        headers: { 'User-Agent': 'Foundify-App/1.0' }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            displayMap(lat, lng, location);
            
            const footer = document.getElementById('mapFooter');
            if (footer && !footer.querySelector('.coordinates-added')) {
                const coordHtml = `
                    <div class="location-name coordinates-added">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        <span>${lat.toFixed(6)}, ${lng.toFixed(6)} <small class="text-muted">(Approximate)</small></span>
                    </div>
                `;
                footer.insertAdjacentHTML('afterbegin', coordHtml);
            }
        } else {
            showGeocodingFallback();
        }
    })
    .catch(error => {
        console.error('Geocoding failed:', error);
        showGeocodingFallback();
    });
}

function displayMap(lat, lng, locationName) {
    const map = L.map('map').setView([lat, lng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    const markerIcon = L.divIcon({
        className: 'custom-marker',
        html: '<i class="fas fa-map-marker-alt" style="color: var(--primary); font-size: 30px; text-shadow: 0 0 10px var(--primary-glow);"></i>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });
    
    const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
    
    let popupContent = `<strong style="color: var(--primary);">${locationName || 'Found Item Location'}</strong>`;
    
    const hasExactCoordinates = @json(($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0));
    if (!hasExactCoordinates) {
        popupContent += '<br><small class="text-muted">Approximate location based on address</small>';
    }
    
    marker.bindPopup(popupContent).openPopup();
}

function showGeocodingFallback() {
    const mapContainer = document.getElementById('mapContainer');
    const footer = document.getElementById('mapFooter');
    
    if (mapContainer) {
        mapContainer.innerHTML = `
            <div style="height: 200px; display: flex; align-items: center; justify-content: center; background: var(--bg-header); flex-direction: column; padding: 20px;">
                <i class="fas fa-map-marked-alt fa-3x mb-3" style="color: var(--primary); opacity: 0.5;"></i>
                <p style="color: var(--text-primary); text-align: center; margin-bottom: 5px;">{{ $foundItem->found_location ?? 'Location provided' }}</p>
                <p class="text-muted small text-center">Could not pinpoint exact location</p>
            </div>
        `;
    }
    
    if (footer) {
        footer.innerHTML = `
            <div class="location-name">
                <i class="fas fa-map-marked-alt"></i>
                <span>{{ Str::limit($foundItem->found_location, 40) }}</span>
            </div>
            <div class="map-actions">
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($foundItem->found_location) }}" 
                   target="_blank" class="directions-btn">
                    <i class="fas fa-search"></i> Search on Google Maps
                </a>
            </div>
        `;
    }
}

function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}
</script>
@endpush
@endsection