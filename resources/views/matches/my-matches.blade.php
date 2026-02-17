@extends('layouts.app')

@section('title', 'My Matches')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-handshake" style="color: var(--primary);"></i> My Matches
        </h1>
        <p>View all matches related to your lost and found items</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <a href="{{ route('matches.my-matches', array_merge(request()->query(), ['status' => '', 'type' => '', 'recovered' => ''])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                <div class="stat-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Matches</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('matches.my-matches', array_merge(request()->query(), ['status' => 'pending', 'type' => '', 'recovered' => ''])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('matches.my-matches', array_merge(request()->query(), ['status' => 'confirmed', 'type' => '', 'recovered' => ''])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['confirmed'] }}</div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('matches.my-matches', array_merge(request()->query(), ['status' => 'confirmed', 'recovered' => 'true', 'type' => ''])) }}" class="stats-link">
            <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $stats['recovered'] }}</div>
                    <div class="stat-label">Recovered</div>
                </div>
                <div class="stat-hover-indicator">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('matches.my-matches') }}" id="filterForm">
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
                    <label for="type" class="form-label">
                        <i class="fas fa-tag" style="color: var(--primary);"></i> Item Type
                    </label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>My Lost Items</option>
                        <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>My Found Items</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="min_score" class="form-label">
                        <i class="fas fa-chart-line" style="color: var(--primary);"></i> Min Score
                    </label>
                    <input type="number" class="form-control" id="min_score" name="min_score" 
                           value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('matches.my-matches') }}" class="btn btn-outline-primary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Active Filter Indicators -->
@if(request('status') || request('type') || request('min_score') || request('recovered'))
<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center flex-wrap gap-2">
        <i class="fas fa-filter me-2" style="color: var(--primary);"></i>
        <strong style="color: var(--primary);">Active Filters:</strong>
        <div class="d-flex flex-wrap gap-2">
            @if(request('status'))
                <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    Status: {{ ucfirst(request('status')) }}
                </span>
            @endif
            @if(request('type'))
                <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    Type: {{ request('type') == 'lost' ? 'My Lost Items' : 'My Found Items' }}
                </span>
            @endif
            @if(request('min_score'))
                <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    Min Score: {{ request('min_score') }}%
                </span>
            @endif
            @if(request('recovered'))
                <span class="filter-badge" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    Recovered Items
                </span>
            @endif
        </div>
    </div>
    <a href="{{ route('matches.my-matches') }}" class="btn-close" style="filter: invert(1);"></a>
</div>
@endif

<!-- Matches List -->
<div class="row">
    @forelse($matches as $match)
    @php
        $isMyLostItem = $match->lostItem && $match->lostItem->user_id == Auth::id();
        $isMyFoundItem = $match->foundItem && $match->foundItem->user_id == Auth::id();
        $otherPartyName = $isMyLostItem ? ($match->foundItem->user->name ?? 'User') : ($match->lostItem->user->name ?? 'User');
    @endphp
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
                    <!-- Your Item -->
                    <div class="item-col">
                        @if($isMyLostItem)
                            <div class="item-card lost">
                                <div class="item-header">
                                    <i class="fas fa-exclamation-circle"></i> Your Lost Item
                                </div>
                                <div class="item-content">
                                    <p class="item-name">{{ $match->lostItem->item_name }}</p>
                                    <p class="item-description">{{ \Illuminate\Support\Str::limit($match->lostItem->description, 50) }}</p>
                                    <div class="item-meta">
                                        <small>
                                            <i class="fas fa-calendar"></i> Lost: {{ $match->lostItem->date_lost->format('M d, Y') }}
                                        </small>
                                        
                                        @if($match->lostItem->lost_location)
                                        <small class="location-info">
                                            <i class="fas fa-map-marked-alt" style="color: #ff4444;"></i> 
                                            {{ \Illuminate\Support\Str::limit($match->lostItem->lost_location, 25) }}
                                        </small>
                                        @elseif($match->lostItem->latitude && $match->lostItem->longitude)
                                        <small class="location-info">
                                            <i class="fas fa-map-marker-alt" style="color: #ff4444;"></i>
                                            {{ number_format($match->lostItem->latitude, 4) }}, {{ number_format($match->lostItem->longitude, 4) }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($isMyFoundItem)
                            <div class="item-card found">
                                <div class="item-header">
                                    <i class="fas fa-check-circle"></i> Your Found Item
                                </div>
                                <div class="item-content">
                                    <p class="item-name">{{ $match->foundItem->item_name }}</p>
                                    <p class="item-description">{{ \Illuminate\Support\Str::limit($match->foundItem->description, 50) }}</p>
                                    <div class="item-meta">
                                        <small>
                                            <i class="fas fa-calendar"></i> Found: {{ $match->foundItem->date_found->format('M d, Y') }}
                                        </small>
                                        
                                        @if($match->foundItem->found_location)
                                        <small class="location-info">
                                            <i class="fas fa-map-marked-alt" style="color: #00fa9a;"></i> 
                                            {{ \Illuminate\Support\Str::limit($match->foundItem->found_location, 25) }}
                                        </small>
                                        @elseif($match->foundItem->latitude && $match->foundItem->longitude)
                                        <small class="location-info">
                                            <i class="fas fa-map-marker-alt" style="color: #00fa9a;"></i>
                                            {{ number_format($match->foundItem->latitude, 4) }}, {{ number_format($match->foundItem->longitude, 4) }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Other Person's Item -->
                    <div class="item-col">
                        @if($isMyLostItem && $match->foundItem)
                            <div class="item-card found">
                                <div class="item-header">
                                    <i class="fas fa-check-circle"></i> Found Item
                                </div>
                                <div class="item-content">
                                    <p class="item-name">{{ $match->foundItem->item_name }}</p>
                                    <p class="item-description">{{ \Illuminate\Support\Str::limit($match->foundItem->description, 50) }}</p>
                                    <div class="item-meta">
                                        <small>
                                            <i class="fas fa-user"></i> {{ $match->foundItem->user->name }}
                                        </small>
                                        <small>
                                            <i class="fas fa-calendar"></i> Found: {{ $match->foundItem->date_found->format('M d, Y') }}
                                        </small>
                                        
                                        @if($match->foundItem->found_location)
                                        <small class="location-info">
                                            <i class="fas fa-map-marked-alt" style="color: #00fa9a;"></i> 
                                            {{ \Illuminate\Support\Str::limit($match->foundItem->found_location, 25) }}
                                        </small>
                                        @elseif($match->foundItem->latitude && $match->foundItem->longitude)
                                        <small class="location-info">
                                            <i class="fas fa-map-marker-alt" style="color: #00fa9a;"></i>
                                            {{ number_format($match->foundItem->latitude, 4) }}, {{ number_format($match->foundItem->longitude, 4) }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($isMyFoundItem && $match->lostItem)
                            <div class="item-card lost">
                                <div class="item-header">
                                    <i class="fas fa-exclamation-circle"></i> Lost Item
                                </div>
                                <div class="item-content">
                                    <p class="item-name">{{ $match->lostItem->item_name }}</p>
                                    <p class="item-description">{{ \Illuminate\Support\Str::limit($match->lostItem->description, 50) }}</p>
                                    <div class="item-meta">
                                        <small>
                                            <i class="fas fa-user"></i> {{ $match->lostItem->user->name }}
                                        </small>
                                        <small>
                                            <i class="fas fa-calendar"></i> Lost: {{ $match->lostItem->date_lost->format('M d, Y') }}
                                        </small>
                                        
                                        @if($match->lostItem->lost_location)
                                        <small class="location-info">
                                            <i class="fas fa-map-marked-alt" style="color: #ff4444;"></i> 
                                            {{ \Illuminate\Support\Str::limit($match->lostItem->lost_location, 25) }}
                                        </small>
                                        @elseif($match->lostItem->latitude && $match->lostItem->longitude)
                                        <small class="location-info">
                                            <i class="fas fa-map-marker-alt" style="color: #ff4444;"></i>
                                            {{ number_format($match->lostItem->latitude, 4) }}, {{ number_format($match->lostItem->longitude, 4) }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Contact Information for Pending Matches -->
                @if($match->status === 'pending')
                <div class="contact-preview mb-3">
                    <div class="contact-preview-header">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Information</span>
                    </div>
                    <div class="contact-preview-body">
                        @if($isMyLostItem && $match->foundItem)
                            <div class="contact-item">
                                <i class="fas fa-user" style="color: #00fa9a;"></i>
                                <span>{{ $match->foundItem->user->name }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope" style="color: #00fa9a;"></i>
                                <span>{{ $match->foundItem->user->email }}</span>
                            </div>
                        @elseif($isMyFoundItem && $match->lostItem)
                            <div class="contact-item">
                                <i class="fas fa-user" style="color: #ff4444;"></i>
                                <span>{{ $match->lostItem->user->name }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope" style="color: #ff4444;"></i>
                                <span>{{ $match->lostItem->user->email }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Match Footer -->
                <div class="match-footer">
                    <div class="match-time">
                        <i class="fas fa-clock" style="color: var(--primary);"></i>
                        <small>Matched {{ $match->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="match-actions">
                        <a href="{{ route('matches.show', $match) }}" class="btn-view">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        
                        @if($match->status === 'pending')
                            @if($isMyLostItem || $isMyFoundItem)
                                <button class="btn-contact" onclick="openContactModal({{ $match->id }})">
                                    <i class="fas fa-envelope"></i> Contact
                                </button>
                            @endif
                        @endif
                        
                        @if($match->status === 'confirmed')
                            <span class="success-badge">
                                <i class="fas fa-check-circle"></i> Match Successful
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Modal for each match -->
    <div class="modal fade" id="contactModal{{ $match->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-envelope" style="color: var(--primary);"></i> Contact {{ $otherPartyName }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body">
                    @if($isMyLostItem && $match->foundItem)
                        <div class="contact-details">
                            <div class="contact-detail-item">
                                <i class="fas fa-user" style="color: #00fa9a;"></i>
                                <div>
                                    <strong>Name:</strong>
                                    <p>{{ $match->foundItem->user->name }}</p>
                                </div>
                            </div>
                            <div class="contact-detail-item">
                                <i class="fas fa-envelope" style="color: #00fa9a;"></i>
                                <div>
                                    <strong>Email:</strong>
                                    <p>{{ $match->foundItem->user->email }}</p>
                                </div>
                            </div>
                            @if($match->foundItem->user->phone)
                            <div class="contact-detail-item">
                                <i class="fas fa-phone" style="color: #00fa9a;"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <p>{{ $match->foundItem->user->phone }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @elseif($isMyFoundItem && $match->lostItem)
                        <div class="contact-details">
                            <div class="contact-detail-item">
                                <i class="fas fa-user" style="color: #ff4444;"></i>
                                <div>
                                    <strong>Name:</strong>
                                    <p>{{ $match->lostItem->user->name }}</p>
                                </div>
                            </div>
                            <div class="contact-detail-item">
                                <i class="fas fa-envelope" style="color: #ff4444;"></i>
                                <div>
                                    <strong>Email:</strong>
                                    <p>{{ $match->lostItem->user->email }}</p>
                                </div>
                            </div>
                            @if($match->lostItem->user->phone)
                            <div class="contact-detail-item">
                                <i class="fas fa-phone" style="color: #ff4444;"></i>
                                <div>
                                    <strong>Phone:</strong>
                                    <p>{{ $match->lostItem->user->phone }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                    
                    <hr class="divider">
                    
                    <div class="message-suggestion">
                        <h6><i class="fas fa-lightbulb" style="color: var(--primary);"></i> Suggested Message</h6>
                        <div class="suggestion-box">
                            @if($isMyLostItem)
                                <p>Hi {{ $match->foundItem->user->name }},</p>
                                <p>I saw that you found a {{ $match->foundItem->item_name }} that matches my lost {{ $match->lostItem->item_name }}. Could we arrange a time to verify and potentially claim it?</p>
                            @else
                                <p>Hi {{ $match->lostItem->user->name }},</p>
                                <p>I found a {{ $match->foundItem->item_name }} that matches your lost {{ $match->lostItem->item_name }}. Please contact me to verify and arrange pickup.</p>
                            @endif
                        </div>
                        <button class="btn-copy-suggestion" onclick="copySuggestion(this)">
                            <i class="fas fa-copy"></i> Copy Message
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
                    <a href="mailto:{{ $isMyLostItem ? $match->foundItem->user->email : $match->lostItem->user->email }}" class="btn-send-email">
                        <i class="fas fa-paper-plane"></i> Send Email
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <h4>No matches found</h4>
            <p>You don't have any matches for your items yet.</p>
            <p class="text-muted">Report more items to increase matching possibilities.</p>
            <div class="empty-actions">
                <a href="{{ route('lost-items.create') }}" class="btn-map primary">
                    <i class="fas fa-exclamation-circle"></i> Report Lost Item
                </a>
                <a href="{{ route('found-items.create') }}" class="btn-map primary">
                    <i class="fas fa-check-circle"></i> Report Found Item
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($matches->hasPages())
<div class="row mt-4">
    <div class="col-12">
        <div class="pagination-wrapper">
            <div class="d-flex justify-content-center">
                {{ $matches->links() }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- Notifications Container -->
<div id="notificationsContainer"></div>

<style>
    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title p {
        color: #a0a0a0;
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
    }

    /* Stats Cards */
    .stats-link {
        text-decoration: none;
        display: block;
        position: relative;
    }

    .stat-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        color: white;
        cursor: pointer;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border-color: white;
    }

    .stat-card:hover::before {
        opacity: 0.1;
    }

    .stat-hover-indicator {
        position: absolute;
        top: 50%;
        right: -20px;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-hover-indicator {
        right: 15px;
        opacity: 1;
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        background: rgba(255,255,255,0.2);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(360deg);
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.8);
        margin-top: 0.25rem;
    }

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

    .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: 2px solid transparent;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        box-shadow: 0 0 20px var(--primary-glow);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px var(--primary-glow);
    }

    .btn-outline-primary {
        background: transparent;
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--primary-glow);
    }

    /* Filter Badge */
    .filter-badge {
        padding: 0.375rem 1rem;
        border-radius: 30px;
        color: white;
        font-size: 0.75rem;
        font-weight: 500;
        box-shadow: 0 0 15px var(--primary-glow);
    }

    /* Alert */
    .alert-info {
        background: #1a1a1a;
        border: 1px solid var(--primary);
        color: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
    }

    .btn-close {
        filter: invert(1);
        opacity: 0.5;
        transition: all 0.3s ease;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
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
        gap: 0.5rem;
    }

    .item-meta small {
        color: #a0a0a0;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .item-meta i {
        width: 14px;
    }

    .location-info {
        margin-top: 0.25rem;
        padding: 0.25rem 0;
        border-top: 1px dashed #333;
    }

    /* Contact Preview */
    .contact-preview {
        background: #222;
        border: 1px solid #333;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .contact-preview-header {
        background: #2a2a2a;
        padding: 0.75rem 1rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid #333;
    }

    .contact-preview-body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #a0a0a0;
        font-size: 0.875rem;
    }

    .contact-item i {
        width: 16px;
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

    .btn-contact {
        background: transparent;
        border: 2px solid #3498db;
        color: #3498db;
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-contact:hover {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .success-badge {
        background: rgba(0, 250, 154, 0.1);
        border: 1px solid #00fa9a;
        color: #00fa9a;
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
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
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        padding: 1rem;
    }

    .pagination {
        margin: 0;
        display: flex;
        gap: 0.25rem;
    }

    .page-link {
        background: #222;
        border: 1px solid #333;
        color: #a0a0a0;
        border-radius: 8px !important;
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

    .contact-details {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .contact-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem;
        background: #222;
        border: 1px solid #333;
        border-radius: 12px;
    }

    .contact-detail-item i {
        font-size: 1.25rem;
        margin-top: 0.25rem;
    }

    .contact-detail-item strong {
        display: block;
        color: white;
        margin-bottom: 0.25rem;
        font-size: 0.8125rem;
    }

    .contact-detail-item p {
        color: #a0a0a0;
        margin: 0;
        font-size: 0.9375rem;
    }

    .divider {
        border: none;
        border-top: 1px solid #333;
        margin: 1.5rem 0;
    }

    .message-suggestion h6 {
        color: white;
        font-size: 0.9375rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .suggestion-box {
        background: #222;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        color: #a0a0a0;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .suggestion-box p {
        margin-bottom: 0.5rem;
    }

    .suggestion-box p:last-child {
        margin-bottom: 0;
    }

    .btn-copy-suggestion {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.8125rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
    }

    .btn-copy-suggestion:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px var(--primary-glow);
    }

    .btn-cancel {
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: 2px solid #666;
        color: #a0a0a0;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        border-color: #ff4444;
        color: #ff4444;
    }

    .btn-send-email {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border: none;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 0 20px var(--primary-glow);
    }

    .btn-send-email:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px var(--primary-glow);
    }

    /* Toast Notifications */
    #notificationsContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        background: #1a1a1a;
        border: 1px solid var(--primary);
        border-radius: 12px;
        min-width: 300px;
    }

    .toast-body {
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-close-white {
        filter: invert(1);
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

        .btn-view, .btn-contact {
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

        .contact-detail-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
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

    .match-card, .stat-card {
        animation: fadeIn 0.5s ease forwards;
    }
</style>
@endsection

@push('scripts')
<script>
    // Auto-submit filter form on select change
    document.getElementById('status')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    document.getElementById('type')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Loading animation for filter
    document.getElementById('filterForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    });
    
    // Open contact modal
    function openContactModal(matchId) {
        const modal = new bootstrap.Modal(document.getElementById('contactModal' + matchId));
        modal.show();
    }
    
    // Copy suggestion text
    function copySuggestion(button) {
        const suggestionBox = button.closest('.message-suggestion').querySelector('.suggestion-box');
        const text = suggestionBox.innerText;
        
        navigator.clipboard.writeText(text).then(() => {
            showToast('Message copied to clipboard!', 'success');
            
            // Change button text temporarily
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            setTimeout(() => {
                button.innerHTML = originalText;
            }, 2000);
        }).catch(err => {
            showToast('Failed to copy message', 'error');
        });
    }
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center border-0 mb-2`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
        const bgColor = type === 'success' ? '#00fa9a' : type === 'error' ? '#ff4444' : 'var(--primary)';
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${icon}" style="color: ${bgColor};"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
    
    // Add animation to cards
    document.querySelectorAll('.stat-card, .match-card').forEach((card, index) => {
        card.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1}s`;
    });
</script>
@endpush