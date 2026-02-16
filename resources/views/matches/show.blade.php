@extends('layouts.app')

@section('title', 'Match Details')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> Match Details
        </h1>
        <p>View detailed match information between lost and found items</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('matches.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Matches
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Main Match Card -->
        <div class="main-card mb-4">
            <div class="card-header">
                <div class="header-content">
                    <h4 class="mb-0">
                        <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> Match Details
                    </h4>
                    <div class="header-badges">
                        <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                            {{ $match->match_score }}% Match
                        </span>
                        <span class="status-badge status-{{ $match->status }}">
                            {{ ucfirst($match->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Match Score Breakdown -->
                <div class="score-card mb-4">
                    <div class="score-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie" style="color: var(--primary);"></i> Match Score Breakdown
                        </h5>
                    </div>
                    <div class="score-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="score-item">
                                    <div class="score-label">
                                        <span>Item Name Similarity</span>
                                        <span class="score-value">30%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-fill" style="width: 30%; background: linear-gradient(135deg, var(--primary), var(--primary-light));"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item">
                                    <div class="score-label">
                                        <span>Description Similarity</span>
                                        <span class="score-value">25%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-fill" style="width: 25%; background: linear-gradient(135deg, #00fa9a, #00ff7f);"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="score-item">
                                    <div class="score-label">
                                        <span>Category Match</span>
                                        <span class="score-value">20%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-fill" style="width: 20%; background: linear-gradient(135deg, #ffa500, #ffb52e);"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item">
                                    <div class="score-label">
                                        <span>Location Proximity</span>
                                        <span class="score-value">15%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-fill" style="width: 15%; background: linear-gradient(135deg, #ff4444, #ff6b6b);"></div>
                                    </div>
                                </div>
                                
                                <div class="score-item">
                                    <div class="score-label">
                                        <span>Date Proximity</span>
                                        <span class="score-value">10%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-fill" style="width: 10%; background: linear-gradient(135deg, #888, #aaa);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Comparison -->
                <div class="items-row">
                    <!-- Lost Item -->
                    <div class="item-col">
                        <div class="item-card lost">
                            <div class="item-header">
                                <i class="fas fa-exclamation-circle"></i> Lost Item
                            </div>
                            <div class="item-body">
                                <div class="item-image-container">
                                    @if($match->lostItem->photo)
                                        <img src="{{ asset('storage/' . $match->lostItem->photo) }}" 
                                             class="item-image" 
                                             alt="{{ $match->lostItem->item_name }}">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image fa-3x" style="color: #ff4444; opacity: 0.3;"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="item-details">
                                    <table class="details-table">
                                        <tr>
                                            <th>Item Name:</th>
                                            <td>{{ $match->lostItem->item_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category:</th>
                                            <td><span class="category-tag">{{ $match->lostItem->category }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="item-status status-{{ $match->lostItem->status }}">
                                                    {{ $match->lostItem->status === 'pending' ? 'Pending' : ucfirst($match->lostItem->status) }}
                                                </span>
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
                                                <i class="fas fa-map-marker-alt" style="color: #ff4444;"></i>
                                                {{ $match->lostItem->latitude }}, {{ $match->lostItem->longitude }}
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                
                                <div class="description-section">
                                    <h6>Description:</h6>
                                    <div class="description-text">
                                        {{ $match->lostItem->description }}
                                    </div>
                                </div>
                                
                                <a href="{{ route('lost-items.show', $match->lostItem) }}" class="view-item-btn lost">
                                    <i class="fas fa-external-link-alt"></i> View Lost Item
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Found Item -->
                    <div class="item-col">
                        <div class="item-card found">
                            <div class="item-header">
                                <i class="fas fa-check-circle"></i> Found Item
                            </div>
                            <div class="item-body">
                                <div class="item-image-container">
                                    @if($match->foundItem->photo)
                                        <img src="{{ asset('storage/' . $match->foundItem->photo) }}" 
                                             class="item-image" 
                                             alt="{{ $match->foundItem->item_name }}">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image fa-3x" style="color: #00fa9a; opacity: 0.3;"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="item-details">
                                    <table class="details-table">
                                        <tr>
                                            <th>Item Name:</th>
                                            <td>{{ $match->foundItem->item_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category:</th>
                                            <td><span class="category-tag">{{ $match->foundItem->category }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="item-status status-{{ $match->foundItem->status }}">
                                                    {{ $match->foundItem->status === 'pending' ? 'Pending' : ucfirst($match->foundItem->status) }}
                                                </span>
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
                                                <i class="fas fa-map-marker-alt" style="color: #00fa9a;"></i>
                                                {{ $match->foundItem->latitude }}, {{ $match->foundItem->longitude }}
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                
                                <div class="description-section">
                                    <h6>Description:</h6>
                                    <div class="description-text">
                                        {{ $match->foundItem->description }}
                                    </div>
                                </div>
                                
                                <a href="{{ route('found-items.show', $match->foundItem) }}" class="view-item-btn found">
                                    <i class="fas fa-external-link-alt"></i> View Found Item
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Match Actions -->
        <div class="sidebar-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt" style="color: var(--primary);"></i> Match Actions
                </h5>
            </div>
            <div class="card-body">
                @if($match->status === 'pending')
                    @can('confirm', $match)
                    <form action="{{ route('matches.confirm', $match) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="action-btn confirm w-100" 
                                onclick="return confirm('Confirm this match? This will:\n1. Mark the lost item as "Found"\n2. Mark the found item as "Claimed"\n3. Notify both users')">
                            <i class="fas fa-handshake"></i> Confirm Match
                        </button>
                    </form>
                    @endcan
                    
                    @can('reject', $match)
                    <form action="{{ route('matches.reject', $match) }}" method="POST">
                        @csrf
                        <button type="submit" class="action-btn reject w-100"
                                onclick="return confirm('Reject this match?')">
                            <i class="fas fa-times-circle"></i> Reject Match
                        </button>
                    </form>
                    @endcan
                @elseif($match->status === 'confirmed')
                    <div class="status-message success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Match Confirmed</strong>
                            <p class="mb-0">This match was confirmed on {{ $match->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="status-message error">
                        <i class="fas fa-times-circle"></i>
                        <div>
                            <strong>Match Rejected</strong>
                            <p class="mb-0">This match was rejected on {{ $match->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                @endif
                
                <hr class="divider">
                
                <div class="quick-actions">
                    <a href="{{ route('lost-items.show', $match->lostItem) }}" class="quick-action-btn lost">
                        <i class="fas fa-exclamation-circle"></i> View Lost Item
                    </a>
                    <a href="{{ route('found-items.show', $match->foundItem) }}" class="quick-action-btn found">
                        <i class="fas fa-check-circle"></i> View Found Item
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="sidebar-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle" style="color: var(--primary);"></i> Contact Information
                </h5>
            </div>
            <div class="card-body">
                <div class="contact-section">
                    <h6 class="contact-title lost">
                        <i class="fas fa-user"></i> Lost Item Owner
                    </h6>
                    <div class="contact-info">
                        <p class="contact-name">{{ $match->lostItem->user->name }}</p>
                        <p class="contact-email">{{ $match->lostItem->user->email }}</p>
                    </div>
                </div>
                
                <hr class="divider">
                
                <div class="contact-section">
                    <h6 class="contact-title found">
                        <i class="fas fa-user"></i> Found Item Owner
                    </h6>
                    <div class="contact-info">
                        <p class="contact-name">{{ $match->foundItem->user->name }}</p>
                        <p class="contact-email">{{ $match->foundItem->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Match Timeline -->
        <div class="sidebar-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history" style="color: var(--primary);"></i> Match Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Match Created</h6>
                            <span class="timeline-date">{{ $match->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                    
                    @if($match->status !== 'pending')
                    <div class="timeline-item">
                        <div class="timeline-marker" style="background: {{ $match->status === 'confirmed' ? 'linear-gradient(135deg, #00fa9a, #00ff7f)' : 'linear-gradient(135deg, #ff4444, #ff6b6b)' }};"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Match {{ ucfirst($match->status) }}</h6>
                            <span class="timeline-date">{{ $match->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Main Card */
    .main-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .main-card:hover {
        border-color: var(--primary);
        box-shadow: 0 10px 30px var(--primary-glow);
    }

    .card-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1.25rem 1.5rem;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-content h4 {
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-badges {
        display: flex;
        gap: 0.5rem;
    }

    .score-badge {
        padding: 0.5rem 1rem;
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
        padding: 1.5rem;
    }

    /* Score Card */
    .score-card {
        background: #222;
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
    }

    .score-header {
        background: #2a2a2a;
        border-bottom: 1px solid #333;
        padding: 1rem 1.25rem;
    }

    .score-header h5 {
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .score-body {
        padding: 1.25rem;
    }

    .score-item {
        margin-bottom: 1.25rem;
    }

    .score-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        color: #a0a0a0;
        font-size: 0.875rem;
    }

    .score-value {
        color: var(--primary);
        font-weight: 600;
    }

    .progress-bar-container {
        width: 100%;
        height: 8px;
        background: #333;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    /* Items Row */
    .items-row {
        display: flex;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .item-col {
        flex: 1;
        min-width: 0;
    }

    .item-card {
        background: #222;
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
        height: 100%;
        transition: all 0.3s ease;
    }

    .item-card.lost:hover {
        border-color: #ff4444;
        box-shadow: 0 10px 30px rgba(255, 68, 68, 0.2);
        transform: translateY(-3px);
    }

    .item-card.found:hover {
        border-color: #00fa9a;
        box-shadow: 0 10px 30px rgba(0, 250, 154, 0.2);
        transform: translateY(-3px);
    }

    .item-header {
        padding: 1rem;
        font-size: 1rem;
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

    .item-body {
        padding: 1.25rem;
    }

    .item-image-container {
        text-align: center;
        margin-bottom: 1.25rem;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .item-image {
        max-height: 150px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .no-image {
        width: 100%;
        height: 150px;
        background: #2a2a2a;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #444;
    }

    /* Details Table */
    .details-table {
        width: 100%;
        margin-bottom: 1rem;
    }

    .details-table tr {
        border-bottom: 1px solid #333;
    }

    .details-table tr:last-child {
        border-bottom: none;
    }

    .details-table th {
        color: #a0a0a0;
        font-weight: 500;
        font-size: 0.8125rem;
        padding: 0.5rem 0;
        width: 100px;
    }

    .details-table td {
        color: white;
        font-size: 0.875rem;
        padding: 0.5rem 0;
    }

    .category-tag {
        background: #333;
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.75rem;
        border: 1px solid var(--primary);
    }

    .item-status {
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-pending {
        background: rgba(255, 165, 0, 0.2);
        color: #ffa500;
        border: 1px solid #ffa500;
    }

    .status-found, .status-claimed {
        background: rgba(0, 250, 154, 0.2);
        color: #00fa9a;
        border: 1px solid #00fa9a;
    }

    .status-returned {
        background: rgba(255, 20, 147, 0.2);
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .status-disposed {
        background: rgba(102, 102, 102, 0.2);
        color: #888;
        border: 1px solid #888;
    }

    /* Description Section */
    .description-section {
        margin: 1rem 0;
    }

    .description-section h6 {
        color: white;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .description-text {
        background: #2a2a2a;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 0.75rem;
        color: #a0a0a0;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    /* View Item Button */
    .view-item-btn {
        display: block;
        text-align: center;
        padding: 0.75rem;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .view-item-btn.lost {
        background: transparent;
        border-color: #ff4444;
        color: #ff4444;
    }

    .view-item-btn.lost:hover {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
    }

    .view-item-btn.found {
        background: transparent;
        border-color: #00fa9a;
        color: #00fa9a;
    }

    .view-item-btn.found:hover {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 250, 154, 0.3);
    }

    .view-item-btn i {
        margin-right: 0.375rem;
    }

    /* Sidebar Card */
    .sidebar-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .sidebar-card:hover {
        border-color: var(--primary);
        box-shadow: 0 10px 30px var(--primary-glow);
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.875rem;
        border: 2px solid transparent;
        border-radius: 30px;
        font-size: 0.9375rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: transparent;
    }

    .action-btn.confirm {
        border-color: #00fa9a;
        color: #00fa9a;
    }

    .action-btn.confirm:hover {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 250, 154, 0.3);
    }

    .action-btn.reject {
        border-color: #ff4444;
        color: #ff4444;
    }

    .action-btn.reject:hover {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
    }

    /* Status Message */
    .status-message {
        padding: 1rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .status-message.success {
        background: rgba(0, 250, 154, 0.1);
        border: 1px solid #00fa9a;
        color: #00fa9a;
    }

    .status-message.error {
        background: rgba(255, 68, 68, 0.1);
        border: 1px solid #ff4444;
        color: #ff4444;
    }

    .status-message i {
        font-size: 1.5rem;
    }

    .status-message strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .status-message p {
        color: #a0a0a0;
    }

    /* Divider */
    .divider {
        border: none;
        border-top: 1px solid #333;
        margin: 1rem 0;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .quick-action-btn {
        padding: 0.75rem;
        border-radius: 30px;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border: 2px solid transparent;
    }

    .quick-action-btn.lost {
        border-color: #ff4444;
        color: #ff4444;
    }

    .quick-action-btn.lost:hover {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
    }

    .quick-action-btn.found {
        border-color: #00fa9a;
        color: #00fa9a;
    }

    .quick-action-btn.found:hover {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 250, 154, 0.3);
    }

    /* Contact Section */
    .contact-section {
        margin-bottom: 1rem;
    }

    .contact-section:last-child {
        margin-bottom: 0;
    }

    .contact-title {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .contact-title.lost {
        color: #ff4444;
    }

    .contact-title.found {
        color: #00fa9a;
    }

    .contact-info {
        background: #222;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 0.75rem;
    }

    .contact-name {
        color: white;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .contact-email {
        color: #a0a0a0;
        font-size: 0.8125rem;
        margin: 0;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 25px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), #ff4444, #00fa9a);
        opacity: 0.3;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -25px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 15px currentColor;
    }

    .timeline-content {
        padding: 0.25rem 0;
    }

    .timeline-title {
        color: white;
        font-size: 0.9375rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .timeline-date {
        color: #a0a0a0;
        font-size: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .items-row {
            flex-direction: column;
        }

        .item-col {
            width: 100%;
        }

        .quick-actions {
            flex-direction: row;
        }

        .quick-action-btn {
            flex: 1;
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

    .main-card,
    .sidebar-card {
        animation: fadeIn 0.5s ease forwards;
    }
</style>
@endsection