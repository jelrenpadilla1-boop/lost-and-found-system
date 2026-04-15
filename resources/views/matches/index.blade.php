@extends('layouts.app')

@section('title', 'All Matches - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<style>
/* ── NETFLIX-STYLE MATCHES PAGE ───────────────── */
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

/* Alert Card */
.alert-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.alert-card.info {
    border-left: 3px solid var(--netflix-info);
    background: rgba(33, 150, 243, 0.1);
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--netflix-info);
    color: white;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    font-weight: 700;
    color: var(--netflix-text);
    margin-bottom: 8px;
    font-size: 13px;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
}

.filter-tag {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

.alert-action {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-netflix);
    background: var(--netflix-info);
    color: white;
}

.alert-action:hover {
    background: #1976d2;
    transform: scale(1.02);
}

/* Filter Card */
.filter-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 32px;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1.5fr;
    gap: 16px;
    align-items: end;
}

@media (max-width: 992px) {
    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .filter-group.actions-group {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    .filter-group.actions-group {
        grid-column: span 1;
    }
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.filter-label i {
    color: var(--netflix-red);
    font-size: 11px;
}

.select-wrapper {
    position: relative;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 10px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text);
    font-size: 14px;
    transition: var(--transition-netflix);
}

body.light .filter-select,
body.light .filter-input {
    background: rgba(0, 0, 0, 0.02);
}

.filter-select {
    appearance: none;
    padding-right: 40px;
    cursor: pointer;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: var(--netflix-red);
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--netflix-red);
    font-size: 12px;
    pointer-events: none;
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.filter-actions .btn {
    flex: 1;
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

/* Matches Grid */
.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(560px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

@media (max-width: 640px) {
    .matches-grid {
        grid-template-columns: 1fr;
    }
}

/* Match Card */
.match-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition-netflix);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.match-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-4px);
}

.match-header {
    padding: 14px 20px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
}

.match-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.match-title h5 {
    font-size: 13px;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    margin: 0;
    letter-spacing: 1px;
}

.match-badges {
    display: flex;
    gap: 8px;
}

/* Badges */
.badge {
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
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

/* Items Comparison */
.items-comparison {
    display: flex;
    align-items: stretch;
    gap: 16px;
    padding: 20px;
    flex: 1;
}

@media (max-width: 640px) {
    .items-comparison {
        flex-direction: column;
    }
    .vs-divider {
        transform: rotate(90deg);
        margin: 10px auto;
    }
}

.item-side {
    flex: 1;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition-netflix);
}

.item-side.lost:hover {
    border-color: var(--netflix-red);
}

.item-side.found:hover {
    border-color: var(--netflix-success);
}

.item-header {
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.item-side.lost .item-header {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.item-side.found .item-header {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
}

.item-header i {
    font-size: 12px;
}

.item-content {
    padding: 14px;
}

.item-content h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0 0 8px 0;
}

.item-desc {
    font-size: 12px;
    color: var(--netflix-text-secondary);
    margin-bottom: 12px;
    line-height: 1.5;
}

.item-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.item-meta span {
    font-size: 10px;
    color: var(--netflix-text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
}

.item-meta i {
    font-size: 10px;
    width: 14px;
}

.item-side.lost .item-meta i {
    color: var(--netflix-red);
}

.item-side.found .item-meta i {
    color: var(--netflix-success);
}

.location {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* VS Divider */
.vs-divider {
    display: flex;
    align-items: center;
    justify-content: center;
}

.vs-divider span {
    background: var(--netflix-card);
    border: 2px solid var(--netflix-red);
    color: var(--netflix-red);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
}

/* Match Footer */
.match-footer {
    padding: 14px 20px;
    background: var(--netflix-dark);
    border-top: 1px solid var(--netflix-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.match-time {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: var(--netflix-text-secondary);
}

.match-time i {
    color: var(--netflix-red);
    font-size: 11px;
}

.match-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-view,
.btn-confirm,
.btn-reject {
    font-size: 11px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    transition: var(--transition-netflix);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid;
    cursor: pointer;
    background: transparent;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-view {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.btn-view:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

.btn-confirm {
    border-color: rgba(46, 125, 50, 0.3);
    color: var(--netflix-success);
}

.btn-confirm:hover {
    background: var(--netflix-success);
    color: white;
    border-color: var(--netflix-success);
    transform: scale(1.02);
}

.btn-reject {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.btn-reject:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 30px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 2px dashed var(--netflix-border);
    color: var(--netflix-red);
    font-size: 32px;
}

.empty-state h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--netflix-text);
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin-bottom: 20px;
}

.empty-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 32px;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
    justify-content: center;
}

.page-item {
    display: inline-block;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    border-radius: 4px;
    text-decoration: none;
    transition: var(--transition-netflix);
    font-size: 13px;
}

.page-link:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
}

.page-item.active .page-link {
    background: var(--netflix-red);
    border-color: var(--netflix-red);
    color: white;
}

/* Animations */
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

.fade-in {
    animation: fadeIn 0.4s ease forwards;
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
    
    .filter-actions {
        flex-direction: column;
    }
    
    .match-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .btn-view,
    .btn-confirm,
    .btn-reject {
        width: 100%;
        justify-content: center;
    }
    
    .empty-actions {
        flex-direction: column;
    }
    
    .empty-actions .btn {
        width: 100%;
    }
}

.d-inline {
    display: inline;
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-exchange-alt"></i>
                All Matches
            </h1>
            <p>Potential matches between lost and found items</p>
        </div>
    </div>

    {{-- Active Filter Indicators --}}
    @if(request('status') || request('min_score') || request('max_score'))
    <div class="alert-card info fade-in">
        <div class="alert-icon"><i class="fas fa-filter"></i></div>
        <div class="alert-content">
            <strong>Active Filters</strong>
            <div class="filter-tags">
                @if(request('status'))<span class="filter-tag">Status: {{ strtoupper(request('status')) }}</span>@endif
                @if(request('min_score'))<span class="filter-tag">Min Score: {{ request('min_score') }}%</span>@endif
                @if(request('max_score'))<span class="filter-tag">Max Score: {{ request('max_score') }}%</span>@endif
            </div>
        </div>
        <a href="{{ route('matches.index') }}" class="alert-action"><i class="fas fa-times"></i> Clear All</a>
    </div>
    @endif

    {{-- Filter Section --}}
    <div class="filter-card fade-in">
        <form method="GET" action="{{ route('matches.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-circle"></i> Status</label>
                    <div class="select-wrapper">
                        <select class="filter-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-chart-line"></i> Min Score (%)</label>
                    <input type="number" class="filter-input" name="min_score" value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                </div>

                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-chart-line"></i> Max Score (%)</label>
                    <input type="number" class="filter-input" name="max_score" value="{{ request('max_score') }}" min="0" max="100" placeholder="100">
                </div>

                <div class="filter-group actions-group">
                    <label class="filter-label">&nbsp;</label>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
                        <a href="{{ route('matches.index') }}" class="btn btn-outline"><i class="fas fa-redo-alt"></i> Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Matches List --}}
    <div class="matches-grid fade-in">
        @forelse($matches as $match)
        <div class="match-card">
            <div class="match-header">
                <div class="match-title">
                    <h5>Match #{{ $match->id }}</h5>
                    <div class="match-badges">
                        <span class="badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">{{ $match->match_score }}%</span>
                        <span class="badge status-{{ $match->status }}">{{ strtoupper($match->status) }}</span>
                    </div>
                </div>
            </div>

            <div class="items-comparison">
                {{-- Lost Item --}}
                <div class="item-side lost">
                    <div class="item-header"><i class="fas fa-search"></i> LOST</div>
                    <div class="item-content">
                        <h6>{{ $match->lostItem->item_name }}</h6>
                        <p class="item-desc">{{ Str::limit($match->lostItem->description, 60) }}</p>
                        <div class="item-meta">
                            <span><i class="fas fa-user"></i> {{ $match->lostItem->user->name }}</span>
                            <span><i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}</span>
                            @if($match->lostItem->lost_location)
                            <span class="location" title="{{ $match->lostItem->lost_location }}"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->lostItem->lost_location, 20) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- VS Divider --}}
                <div class="vs-divider"><span>VS</span></div>

                {{-- Found Item --}}
                <div class="item-side found">
                    <div class="item-header"><i class="fas fa-check-circle"></i> FOUND</div>
                    <div class="item-content">
                        <h6>{{ $match->foundItem->item_name }}</h6>
                        <p class="item-desc">{{ Str::limit($match->foundItem->description, 60) }}</p>
                        <div class="item-meta">
                            <span><i class="fas fa-user"></i> {{ $match->foundItem->user->name }}</span>
                            <span><i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}</span>
                            @if($match->foundItem->found_location)
                            <span class="location" title="{{ $match->foundItem->found_location }}"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->foundItem->found_location, 20) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="match-footer">
                <div class="match-time"><i class="fas fa-clock"></i> {{ $match->created_at->diffForHumans() }}</div>
                <div class="match-actions">
                    <a href="{{ route('matches.show', $match) }}" class="btn-view"><i class="fas fa-eye"></i> Details</a>
                    @if($match->status === 'pending')
                        @can('confirm', $match)
                        <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-confirm" onclick="return confirm('Confirm this match?')"><i class="fas fa-check"></i> Confirm</button>
                        </form>
                        @endcan
                        @can('reject', $match)
                        <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-reject" onclick="return confirm('Reject this match?')"><i class="fas fa-times"></i> Reject</button>
                        </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-exchange-alt"></i></div>
            <h5>No Matches Found</h5>
            <p>No potential matches have been identified yet.</p>
            <p class="text-muted">Report more items to increase matching possibilities.</p>
            @if(!$isAdmin)
            <div class="empty-actions">
                <a href="{{ route('lost-items.create') }}" class="btn btn-outline"><i class="fas fa-search"></i> Report Lost</a>
                <a href="{{ route('found-items.create') }}" class="btn btn-outline"><i class="fas fa-check-circle"></i> Report Found</a>
            </div>
            @endif
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($matches->hasPages())
    <div class="pagination-wrapper fade-in">
        {{ $matches->withQueryString()->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filter form on select change
    const statusSelect = document.querySelector('select[name="status"]');
    const filterForm = document.getElementById('filterForm');
    if (statusSelect && filterForm) {
        statusSelect.addEventListener('change', () => filterForm.submit());
    }

    // Debounced input for score filters
    const minScoreInput = document.querySelector('input[name="min_score"]');
    const maxScoreInput = document.querySelector('input[name="max_score"]');
    let scoreTimeout;

    if (minScoreInput && filterForm) {
        minScoreInput.addEventListener('input', function() {
            clearTimeout(scoreTimeout);
            scoreTimeout = setTimeout(() => filterForm.submit(), 500);
        });
    }

    if (maxScoreInput && filterForm) {
        maxScoreInput.addEventListener('input', function() {
            clearTimeout(scoreTimeout);
            scoreTimeout = setTimeout(() => filterForm.submit(), 500);
        });
    }

    // Form loading state
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';
                submitBtn.disabled = true;
            }
        });
    }

    // Stagger animations
    document.querySelectorAll('.match-card').forEach((card, i) => {
        card.style.animation = `fadeIn 0.4s ease forwards ${i * 0.08}s`;
        card.style.opacity = '0';
    });

    // Auto-hide alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-card').forEach(alert => {
            alert.style.transition = 'opacity 0.3s, transform 0.3s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            setTimeout(() => alert.remove(), 300);
        });
    }, 8000);
});
</script>
@endpush
@endsection