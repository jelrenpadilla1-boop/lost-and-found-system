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
/* ── MODERN DESIGN SYSTEM (matches dashboard) ───────────────── */
:root {
    --bg-white: #ffffff;
    --bg-soft: #faf9fe;
    --bg-card: #ffffff;
    --border-light: #edeef5;
    --border-soft: #e6e8f0;
    --accent: #7c3aed;
    --accent-light: #8b5cf6;
    --accent-soft: #ede9fe;
    --text-dark: #1e1b2f;
    --text-muted: #5b5b7a;
    --text-soft: #7e7b9a;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
    --radius-card: 20px;
    --radius-sm: 12px;
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    --success: #10b981;
    --success-soft: #d1fae5;
    --warning: #f59e0b;
    --warning-soft: #fef3c7;
    --error: #ef4444;
    --error-soft: #fee2e2;
    --info: #3b82f6;
    --info-soft: #dbeafe;
    --glass: rgba(0, 0, 0, 0.02);
    --glass-b: rgba(0, 0, 0, 0.04);
    --glass-hover: rgba(0, 0, 0, 0.06);
}

/* DARK MODE */
body.dark {
    --bg-white: #0f0c1a;
    --bg-soft: #12101c;
    --bg-card: #191624;
    --border-light: #2a2438;
    --border-soft: #2d2740;
    --accent: #a78bfa;
    --accent-light: #c4b5fd;
    --accent-soft: #2d2648;
    --text-dark: #f0edfc;
    --text-muted: #b4adcf;
    --text-soft: #938bb0;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.4), 0 4px 8px rgba(0, 0, 0, 0.2);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.5);
    --success-soft: rgba(16, 185, 129, 0.15);
    --warning-soft: rgba(245, 158, 11, 0.15);
    --error-soft: rgba(239, 68, 68, 0.15);
    --info-soft: rgba(59, 130, 246, 0.15);
    --glass: rgba(255, 255, 255, 0.03);
    --glass-b: rgba(255, 255, 255, 0.06);
    --glass-hover: rgba(255, 255, 255, 0.08);
}

/* Dashboard Container */
.dashboard-container {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 28px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-light);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.02em;
}

.page-title h1 i {
    color: var(--accent);
    font-size: 26px;
}

.page-title p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.page-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 40px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid transparent;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
}

.btn-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

.btn-success {
    background: var(--success);
    color: white;
}

.btn-success:hover {
    background: #0d9668;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-danger {
    background: var(--error);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 18px 24px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.card-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h5 i {
    color: var(--accent);
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
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.badge.score-high {
    background: var(--success-soft);
    color: var(--success);
}

.badge.score-medium {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.score-low {
    background: var(--info-soft);
    color: var(--info);
}

.badge.status-pending {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.status-confirmed {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-rejected {
    background: var(--error-soft);
    color: var(--error);
}

.badge.category {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

/* Score Card */
.score-card {
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    margin-bottom: 24px;
}

.score-header {
    padding: 14px 18px;
    background: rgba(0, 0, 0, 0.02);
    border-bottom: 1px solid var(--border-light);
}

.score-header h6 {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.score-header h6 i {
    color: var(--accent);
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
    color: var(--text-muted);
    letter-spacing: 0.04em;
}

.score-value {
    color: var(--accent);
    font-weight: 700;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--glass);
    border: 1px solid var(--border-light);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 3px;
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
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    overflow: hidden;
    transition: var(--transition);
}

.item-card.lost:hover {
    border-color: var(--error);
    box-shadow: 0 0 15px var(--error-soft);
}

.item-card.found:hover {
    border-color: var(--success);
    box-shadow: 0 0 15px var(--success-soft);
}

.item-header {
    padding: 12px 16px;
    font-size: 12px;
    font-weight: 700;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.item-card.lost .item-header {
    background: var(--error-soft);
    color: var(--error);
}

.item-card.found .item-header {
    background: var(--success-soft);
    color: var(--success);
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
    background: var(--bg-soft);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
    overflow: hidden;
}

.item-image img {
    max-height: 120px;
    max-width: 100%;
    object-fit: contain;
}

.image-placeholder {
    color: var(--text-muted);
    font-size: 36px;
}

.item-details {
    margin-bottom: 16px;
}

.detail-row {
    display: flex;
    align-items: baseline;
    padding: 8px 0;
    border-bottom: 1px dashed var(--border-light);
    font-size: 13px;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    width: 90px;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.04em;
    flex-shrink: 0;
}

.detail-value {
    color: var(--text-dark);
    font-weight: 500;
}

.location-text {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
}

.description-section {
    margin: 16px 0;
    padding: 14px;
    background: var(--bg-soft);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
}

.description-section h6 {
    font-size: 11px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.description-section p {
    margin: 0;
    color: var(--text-muted);
    font-size: 13px;
    line-height: 1.6;
}

.view-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    border-radius: 40px;
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
    transition: var(--transition);
    border: 1px solid;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.view-link.lost {
    border-color: var(--error-soft);
    color: var(--error);
}

.view-link.lost:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
}

.view-link.found {
    border-color: var(--success-soft);
    color: var(--success);
}

.view-link.found:hover {
    background: var(--success);
    color: white;
    border-color: var(--success);
    transform: translateY(-2px);
}

/* Sidebar Cards */
.sidebar-card {
    margin-bottom: 24px;
}

.info-message {
    background: var(--info-soft);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--radius-sm);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    color: var(--text-muted);
}

.info-message i {
    color: var(--info);
    font-size: 24px;
}

.info-message strong {
    color: var(--info);
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
    letter-spacing: 0.05em;
}

.status-message {
    padding: 16px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    gap: 14px;
}

.status-message.success {
    background: var(--success-soft);
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: var(--success);
}

.status-message.error {
    background: var(--error-soft);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: var(--error);
}

.status-message i {
    font-size: 24px;
}

.status-message strong {
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
    letter-spacing: 0.05em;
}

.status-message p {
    color: var(--text-muted);
    margin: 0;
    font-size: 12px;
}

.divider {
    border: none;
    border-top: 1px solid var(--border-light);
    margin: 20px 0;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-action-btn {
    padding: 12px;
    border-radius: 40px;
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1px solid;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.quick-action-btn.lost {
    border-color: var(--error-soft);
    color: var(--error);
}

.quick-action-btn.lost:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
}

.quick-action-btn.found {
    border-color: var(--success-soft);
    color: var(--success);
}

.quick-action-btn.found:hover {
    background: var(--success);
    color: white;
    border-color: var(--success);
    transform: translateY(-2px);
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
    letter-spacing: 0.05em;
}

.contact-title.lost {
    color: var(--error);
}

.contact-title.found {
    color: var(--success);
}

.contact-info {
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 14px;
}

.contact-name {
    color: var(--text-dark);
    font-weight: 700;
    margin: 0 0 6px 0;
    font-size: 14px;
}

.contact-email {
    color: var(--text-muted);
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
    background: linear-gradient(to bottom, var(--accent), var(--warning), var(--success));
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
    border: 2px solid var(--bg-card);
    box-shadow: 0 0 10px rgba(124, 58, 237, 0.3);
}

.timeline-content {
    padding-bottom: 4px;
}

.timeline-title {
    color: var(--text-dark);
    font-size: 13px;
    font-weight: 700;
    margin: 0 0 4px 0;
    letter-spacing: 0.04em;
}

.timeline-date {
    color: var(--text-muted);
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
        padding: 20px;
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
                                    <div class="progress-bar"><div class="progress-fill" style="width: 30%; background: var(--accent);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Description</span><span class="score-value">25%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 25%; background: var(--success);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Category</span><span class="score-value">20%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 20%; background: var(--warning);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Location</span><span class="score-value">15%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 15%; background: var(--info);"></div></div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label"><span>Date</span><span class="score-value">10%</span></div>
                                    <div class="progress-bar"><div class="progress-fill" style="width: 10%; background: var(--error);"></div></div>
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
                                    <div class="detail-row"><span class="detail-label">Owner:</span><span class="detail-value">{{ $match->lostItem->user->name }} @if($isLostOwner)<span style="color: var(--accent);"> (You)</span>@endif</span></div>
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
                                    <div class="detail-row"><span class="detail-label">Finder:</span><span class="detail-value">{{ $match->foundItem->user->name }} @if($isFoundOwner)<span style="color: var(--accent);"> (You)</span>@endif</span></div>
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
                            <div class="timeline-marker" style="background: var(--accent);"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Match Created</h6>
                                <span class="timeline-date">{{ $match->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                        @if($match->status !== 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker" style="background: {{ $match->status === 'confirmed' ? 'var(--success)' : 'var(--error)' }};"></div>
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