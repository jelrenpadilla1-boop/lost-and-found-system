@extends('layouts.app')

@section('title', 'Match Details - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    $isLostOwner = Auth::id() === $match->lostItem->user_id;
    $isFoundOwner = Auth::id() === $match->foundItem->user_id;
    $canAct = $isAdmin || ($match->status === 'pending' && ($isLostOwner || $isFoundOwner));
@endphp

<style>
/* ── NETFLIX-STYLE MATCH DETAILS PAGE ───────────────── */
:root {
    --netflix-red: #e50914;
    --netflix-red-dark: #b20710;
    --netflix-black: #141414;
    --netflix-dark: #0a0a0a;
    --netflix-card: #1a1a1a;
    --netflix-card-hover: #2a2a2a;
    --netflix-text: #ffffff;
    --netflix-text-secondary: #b3b3b3;
    --netflix-border: #333333;
    --netflix-success: #2e7d32;
    --netflix-warning: #f5c518;
    --netflix-info: #2196f3;
    --netflix-error: #e50914;
    --transition-netflix: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

/* Light Mode Overrides */
body.light {
    --netflix-black: #f5f5f5;
    --netflix-dark: #ffffff;
    --netflix-card: #ffffff;
    --netflix-card-hover: #f8f8f8;
    --netflix-text: #1a1a1a;
    --netflix-text-secondary: #666666;
    --netflix-border: #e0e0e0;
}

.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--netflix-text);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title h1 i {
    color: var(--netflix-red);
    font-size: 28px;
}

.page-title p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin: 0;
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition-netflix);
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: var(--netflix-red);
    color: white;
}

.btn-primary:hover {
    background: var(--netflix-red-dark);
    transform: scale(1.02);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
}

.btn-outline:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
    transform: scale(1.02);
}

.btn-success {
    background: var(--netflix-success);
    color: white;
}

.btn-success:hover {
    background: #1b5e20;
    transform: scale(1.02);
}

.btn-danger {
    background: var(--netflix-red);
    color: white;
}

.btn-danger:hover {
    background: var(--netflix-red-dark);
    transform: scale(1.02);
}

.w-100 {
    width: 100%;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 28px;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* Cards */
.card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 28px;
    transition: var(--transition-netflix);
}

.card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.card-header {
    padding: 16px 24px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
}

.card-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h5 i {
    color: var(--netflix-red);
    font-size: 16px;
}

.card-body {
    padding: 24px;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

.header-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* Badges */
.badge {
    font-size: 11px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge.score-high {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.badge.score-medium {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.badge.score-low {
    background: rgba(33, 150, 243, 0.2);
    color: var(--netflix-info);
}

.badge.status-pending {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.badge.status-confirmed {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.badge.status-rejected {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.category {
    background: rgba(255, 255, 255, 0.05);
    color: var(--netflix-text-secondary);
    border: 1px solid var(--netflix-border);
}

/* Score Card */
.score-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    margin-bottom: 24px;
}

.score-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--netflix-border);
}

.score-header h6 {
    font-size: 12px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.score-header h6 i {
    color: var(--netflix-red);
}

.score-body {
    padding: 20px;
}

.score-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

@media (max-width: 768px) {
    .score-grid {
        grid-template-columns: 1fr;
    }
}

.score-item {
    margin-bottom: 8px;
}

.score-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 11px;
    color: var(--netflix-text-secondary);
    letter-spacing: 1px;
}

.score-value {
    color: var(--netflix-red);
    font-weight: 700;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 0.3s ease;
}

/* Items Comparison */
.items-comparison {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 24px;
}

@media (max-width: 768px) {
    .items-comparison {
        grid-template-columns: 1fr;
    }
}

.item-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition-netflix);
}

.item-card.lost:hover {
    border-color: var(--netflix-red);
}

.item-card.found:hover {
    border-color: var(--netflix-success);
}

.item-header {
    padding: 12px 16px;
    font-size: 12px;
    font-weight: 700;
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.item-card.lost .item-header {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.item-card.found .item-header {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
}

.item-header i {
    font-size: 14px;
}

.item-body {
    padding: 18px;
}

.item-image {
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 8px;
    border: 1px solid var(--netflix-border);
    overflow: hidden;
}

.item-image img {
    max-height: 120px;
    max-width: 100%;
    object-fit: contain;
}

.image-placeholder {
    color: var(--netflix-text-secondary);
    font-size: 36px;
}

.item-details {
    margin-bottom: 16px;
}

.detail-row {
    display: flex;
    align-items: baseline;
    padding: 8px 0;
    border-bottom: 1px dashed var(--netflix-border);
    font-size: 13px;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    width: 90px;
    color: var(--netflix-text-secondary);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    flex-shrink: 0;
}

.detail-value {
    color: var(--netflix-text);
    font-weight: 500;
}

.location-text {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--netflix-text-secondary);
}

.description-section {
    margin: 16px 0;
    padding: 14px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 8px;
    border: 1px solid var(--netflix-border);
}

.description-section h6 {
    font-size: 11px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: var(--netflix-red);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.description-section p {
    margin: 0;
    color: var(--netflix-text-secondary);
    font-size: 13px;
    line-height: 1.6;
}

.view-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
    transition: var(--transition-netflix);
    border: 1px solid;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.view-link.lost {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.view-link.lost:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

.view-link.found {
    border-color: rgba(46, 125, 50, 0.3);
    color: var(--netflix-success);
}

.view-link.found:hover {
    background: var(--netflix-success);
    color: white;
    border-color: var(--netflix-success);
    transform: scale(1.02);
}

/* Sidebar Cards */
.sidebar-card {
    margin-bottom: 24px;
}

.info-message {
    background: rgba(33, 150, 243, 0.1);
    border: 1px solid rgba(33, 150, 243, 0.2);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    color: var(--netflix-text-secondary);
}

.info-message i {
    color: var(--netflix-info);
    font-size: 24px;
}

.info-message strong {
    color: var(--netflix-info);
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
    letter-spacing: 1px;
}

.status-message {
    padding: 16px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 14px;
}

.status-message.success {
    background: rgba(46, 125, 50, 0.1);
    border: 1px solid rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.status-message.error {
    background: rgba(229, 9, 20, 0.1);
    border: 1px solid rgba(229, 9, 20, 0.2);
    color: var(--netflix-red);
}

.status-message i {
    font-size: 24px;
}

.status-message strong {
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
    letter-spacing: 1px;
}

.status-message p {
    color: var(--netflix-text-secondary);
    margin: 0;
    font-size: 12px;
}

.divider {
    border: none;
    border-top: 1px solid var(--netflix-border);
    margin: 20px 0;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-action-btn {
    padding: 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
    transition: var(--transition-netflix);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1px solid;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.quick-action-btn.lost {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.quick-action-btn.lost:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

.quick-action-btn.found {
    border-color: rgba(46, 125, 50, 0.3);
    color: var(--netflix-success);
}

.quick-action-btn.found:hover {
    background: var(--netflix-success);
    color: white;
    border-color: var(--netflix-success);
    transform: scale(1.02);
}

/* Contact Section */
.contact-section {
    margin-bottom: 20px;
}

.contact-section:last-child {
    margin-bottom: 0;
}

.contact-title {
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.contact-title.lost {
    color: var(--netflix-red);
}

.contact-title.found {
    color: var(--netflix-success);
}

.contact-info {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 14px;
}

.contact-name {
    color: var(--netflix-text);
    font-weight: 700;
    margin: 0 0 6px 0;
    font-size: 14px;
}

.contact-email {
    color: var(--netflix-text-secondary);
    margin: 0;
    font-size: 13px;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 28px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--netflix-red), var(--netflix-warning), var(--netflix-success));
    opacity: 0.3;
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -28px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid var(--netflix-card);
    box-shadow: 0 0 10px rgba(229, 9, 20, 0.3);
}

.timeline-content {
    padding-bottom: 4px;
}

.timeline-title {
    color: var(--netflix-text);
    font-size: 13px;
    font-weight: 700;
    margin: 0 0 4px 0;
    letter-spacing: 1px;
}

.timeline-date {
    color: var(--netflix-text-secondary);
    font-size: 10px;
}

/* Animations */
.fade-in {
    animation: fadeIn 0.4s ease forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-actions {
        width: 100%;
    }
    
    .page-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 4px;
    }
    
    .detail-label {
        width: 100%;
    }
    
    .quick-actions {
        flex-direction: row;
    }
    
    .quick-action-btn {
        flex: 1;
    }
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-exchange-alt"></i>
                Match Details
            </h1>
            <p>View detailed match information between lost and found items</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('matches.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Matches
            </a>
        </div>
    </div>

    <div class="content-grid">
        {{-- Main Content (Left Column) --}}
        <div class="main-content-col">
            <div class="card main-card fade-in">
                <div class="card-header">
                    <div class="header-content">
                        <h5><i class="fas fa-exchange-alt"></i> Match Details</h5>
                        <div class="header-badges">
                            <span class="badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                {{ $match->match_score }}% Match
                            </span>
                            <span class="badge status-{{ $match->status }}">
                                {{ strtoupper($match->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Score Breakdown --}}
                    <div class="score-card">
                        <div class="score-header">
                            <h6><i class="fas fa-chart-pie"></i> Score Breakdown</h6>
                        </div>
                        <div class="score-body">
                            <div class="score-grid">
                                <div class="score-item">
                                    <div class="score-label"><span>Item Name</span><span class="score-value">30%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 30%; background: var(--netflix-red);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Description</span><span class="score-value">25%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 25%; background: var(--netflix-success);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Category</span><span class="score-value">20%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 20%; background: var(--netflix-warning);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Location</span><span class="score-value">15%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 15%; background: var(--netflix-info);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Date</span><span class="score-value">10%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 10%; background: var(--netflix-red);"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Items Comparison --}}
                    <div class="items-comparison">
                        {{-- Lost Item --}}
                        <div class="item-card lost">
                            <div class="item-header"><i class="fas fa-search"></i> Lost Item</div>
                            <div class="item-body">
                                <div class="item-image">
                                    @if($match->lostItem->photo)
                                        <img src="{{ asset('storage/' . $match->lostItem->photo) }}" alt="{{ $match->lostItem->item_name }}">
                                    @else
                                        <div class="image-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <div class="detail-row"><span class="detail-label">Item:</span><span class="detail-value">{{ $match->lostItem->item_name }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Category:</span><span class="badge category">{{ strtoupper($match->lostItem->category) }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Status:</span><span class="badge status-{{ $match->lostItem->status }}">{{ strtoupper($match->lostItem->status) }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Date Lost:</span><span class="detail-value">{{ $match->lostItem->date_lost->format('M d, Y') }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Owner:</span><span class="detail-value">{{ $match->lostItem->user->name }} @if($isLostOwner)<span style="color: var(--netflix-red);"> (You)</span>@endif</span></div>
                                    @if($match->lostItem->lost_location)
                                    <div class="detail-row"><span class="detail-label">Location:</span><span class="location-text"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->lostItem->lost_location, 35) }}</span></div>
                                    @endif
                                </div>
                                <div class="description-section"><h6>Description</h6><p>{{ Str::limit($match->lostItem->description, 120) }}</p></div>
                                <a href="{{ route('lost-items.show', $match->lostItem) }}" class="view-link lost"><i class="fas fa-external-link-alt"></i> View Lost Item</a>
                            </div>
                        </div>

                        {{-- Found Item --}}
                        <div class="item-card found">
                            <div class="item-header"><i class="fas fa-check-circle"></i> Found Item</div>
                            <div class="item-body">
                                <div class="item-image">
                                    @if($match->foundItem->photo)
                                        <img src="{{ asset('storage/' . $match->foundItem->photo) }}" alt="{{ $match->foundItem->item_name }}">
                                    @else
                                        <div class="image-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <div class="detail-row"><span class="detail-label">Item:</span><span class="detail-value">{{ $match->foundItem->item_name }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Category:</span><span class="badge category">{{ strtoupper($match->foundItem->category) }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Status:</span><span class="badge status-{{ $match->foundItem->status }}">{{ strtoupper($match->foundItem->status) }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Date Found:</span><span class="detail-value">{{ $match->foundItem->date_found->format('M d, Y') }}</span></div>
                                    <div class="detail-row"><span class="detail-label">Finder:</span><span class="detail-value">{{ $match->foundItem->user->name }} @if($isFoundOwner)<span style="color: var(--netflix-success);"> (You)</span>@endif</span></div>
                                    @if($match->foundItem->found_location)
                                    <div class="detail-row"><span class="detail-label">Location:</span><span class="location-text"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->foundItem->found_location, 35) }}</span></div>
                                    @endif
                                </div>
                                <div class="description-section"><h6>Description</h6><p>{{ Str::limit($match->foundItem->description, 120) }}</p></div>
                                <a href="{{ route('found-items.show', $match->foundItem) }}" class="view-link found"><i class="fas fa-external-link-alt"></i> View Found Item</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar (Right Column) --}}
        <div class="sidebar-col">
            {{-- Actions Card --}}
            <div class="card sidebar-card fade-in">
                <div class="card-header"><h5><i class="fas fa-bolt"></i> Actions</h5></div>
                <div class="card-body">
                    @if($match->status === 'pending')
                        @if($isAdmin)
                            <form action="{{ route('matches.confirm', $match) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Confirm this match?')"><i class="fas fa-handshake"></i> Confirm Match</button>
                            </form>
                            <form action="{{ route('matches.reject', $match) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this match?')"><i class="fas fa-times-circle"></i> Reject Match</button>
                            </form>
                        @else
                            <div class="info-message">
                                <i class="fas fa-lock"></i>
                                <div><strong>Admin Only</strong><p class="mb-0">Only administrators can confirm or reject matches.</p></div>
                            </div>
                        @endif
                    @elseif($match->status === 'confirmed')
                        <div class="status-message success">
                            <i class="fas fa-check-circle"></i>
                            <div><strong>Match Confirmed</strong><p>Confirmed on {{ $match->updated_at->format('F d, Y') }}</p></div>
                        </div>
                    @else
                        <div class="status-message error">
                            <i class="fas fa-times-circle"></i>
                            <div><strong>Match Rejected</strong><p>Rejected on {{ $match->updated_at->format('F d, Y') }}</p></div>
                        </div>
                    @endif

                    <hr class="divider">
                    <div class="quick-actions">
                        <a href="{{ route('lost-items.show', $match->lostItem) }}" class="quick-action-btn lost"><i class="fas fa-search"></i> View Lost Item</a>
                        <a href="{{ route('found-items.show', $match->foundItem) }}" class="quick-action-btn found"><i class="fas fa-check-circle"></i> View Found Item</a>
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="card sidebar-card fade-in">
                <div class="card-header"><h5><i class="fas fa-user-circle"></i> Contacts</h5></div>
                <div class="card-body">
                    <div class="contact-section">
                        <h6 class="contact-title lost"><i class="fas fa-user"></i> Lost Owner</h6>
                        <div class="contact-info">
                            <p class="contact-name">{{ $match->lostItem->user->name }}</p>
                            <p class="contact-email">{{ $match->lostItem->user->email }}</p>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="contact-section">
                        <h6 class="contact-title found"><i class="fas fa-user"></i> Finder</h6>
                        <div class="contact-info">
                            <p class="contact-name">{{ $match->foundItem->user->name }}</p>
                            <p class="contact-email">{{ $match->foundItem->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card sidebar-card fade-in">
                <div class="card-header"><h5><i class="fas fa-history"></i> Timeline</h5></div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker" style="background: var(--netflix-red);"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Match Created</h6>
                                <span class="timeline-date">{{ $match->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                        @if($match->status !== 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker" style="background: {{ $match->status === 'confirmed' ? 'var(--netflix-success)' : 'var(--netflix-red)' }};"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Match {{ strtoupper($match->status) }}</h6>
                                <span class="timeline-date">{{ $match->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection