@extends('layouts.app')

@section('title', 'My Found Items - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<style>
/* ── NETFLIX-STYLE MY FOUND ITEMS PAGE ───────────────── */
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 16px;
    margin-bottom: 32px;
}

@media (max-width: 1100px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition-netflix);
}

.stat-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-3px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.stat-value {
    font-size: 22px;
    font-weight: 800;
    color: var(--netflix-text);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 10px;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Card */
.card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 32px;
}

.card-header {
    padding: 18px 24px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.card-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h5 i {
    color: var(--netflix-red);
    font-size: 18px;
}

.card-body {
    padding: 0;
}

/* Table */
.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: var(--netflix-dark);
    padding: 14px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--netflix-border);
}

.data-table td {
    padding: 16px 20px;
    font-size: 13px;
    color: var(--netflix-text-secondary);
    border-bottom: 1px solid var(--netflix-border);
    vertical-align: middle;
}

.data-table tr:hover td {
    background: rgba(229, 9, 20, 0.05);
}

.data-table tr:last-child td {
    border-bottom: none;
}

/* Item Info */
.item-info {
    display: flex;
    align-items: center;
    gap: 14px;
}

.item-thumbnail {
    width: 52px;
    height: 52px;
    border-radius: 4px;
    object-fit: cover;
    border: 1px solid var(--netflix-border);
}

.item-thumbnail-placeholder {
    width: 52px;
    height: 52px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    font-size: 22px;
}

.item-details h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0 0 4px 0;
}

.item-details span {
    font-size: 11px;
    color: var(--netflix-text-secondary);
}

/* Badges */
.badge {
    font-size: 10px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge.category {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.status-pending {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.badge.status-approved {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.badge.status-rejected {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.status-claimed {
    background: rgba(33, 150, 243, 0.2);
    color: var(--netflix-info);
}

.badge.status-returned {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.badge.status-disposed {
    background: rgba(255, 255, 255, 0.05);
    color: var(--netflix-text-secondary);
    border: 1px solid var(--netflix-border);
}

.match-badge {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Location & Date */
.date-text {
    font-size: 12px;
    color: var(--netflix-text-secondary);
    white-space: nowrap;
}

.location-text, .coordinates-text {
    font-size: 12px;
    color: var(--netflix-text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
}

.location-text i, .coordinates-text i {
    color: var(--netflix-red);
    font-size: 11px;
}

/* Action Buttons */
.action-group {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 34px;
    height: 34px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition-netflix);
    border: 1px solid;
    background: var(--netflix-card);
    cursor: pointer;
}

.action-btn.view {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.action-btn.view:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.05);
}

.action-btn.edit {
    border-color: rgba(33, 150, 243, 0.3);
    color: var(--netflix-info);
}

.action-btn.edit:hover {
    background: var(--netflix-info);
    color: white;
    border-color: var(--netflix-info);
    transform: scale(1.05);
}

.action-btn.delete {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.action-btn.delete:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.05);
}

/* Pagination */
.pagination-wrapper {
    padding: 20px 24px;
    background: var(--netflix-dark);
    border-top: 1px solid var(--netflix-border);
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

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 30px;
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
    margin-bottom: 24px;
}

/* Matches Section */
.matches-section {
    margin-top: 40px;
}

.section-header {
    margin-bottom: 24px;
}

.section-header h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h5 i {
    color: var(--netflix-red);
    font-size: 18px;
}

.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
}

@media (max-width: 768px) {
    .matches-grid {
        grid-template-columns: 1fr;
    }
}

.match-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 20px;
    transition: var(--transition-netflix);
}

.match-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-3px);
}

.match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 10px;
}

.match-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.score-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 4px;
}

.score-high {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.score-medium {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.score-low {
    background: rgba(33, 150, 243, 0.2);
    color: var(--netflix-info);
}

.match-time {
    font-size: 11px;
    color: var(--netflix-text-secondary);
}

.match-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 18px;
}

.match-item {
    background: rgba(255, 255, 255, 0.03);
    padding: 12px;
    border-radius: 4px;
    border: 1px solid var(--netflix-border);
}

.match-item small {
    font-size: 10px;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    display: block;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.match-item strong {
    font-size: 13px;
    font-weight: 700;
    color: var(--netflix-text);
}

.match-actions {
    display: flex;
    gap: 12px;
}

.btn-view-match,
.btn-view-item {
    flex: 1;
    font-size: 11px;
    font-weight: 600;
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    transition: var(--transition-netflix);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid;
}

.btn-view-match {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
    background: transparent;
}

.btn-view-match:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

.btn-view-item.lost {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
    background: transparent;
}

.btn-view-item.lost:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

/* Toast */
#notificationsContainer {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    max-width: 350px;
}

.toast {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    margin-bottom: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: slideInRight 0.3s ease;
}

.toast-body {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    font-size: 13px;
    color: var(--netflix-text);
}

.toast-body i {
    font-size: 16px;
}

.toast-body span {
    flex: 1;
}

.toast-close {
    background: transparent;
    border: none;
    color: var(--netflix-text-secondary);
    cursor: pointer;
    padding: 4px;
    font-size: 18px;
    transition: var(--transition-netflix);
}

.toast-close:hover {
    color: var(--netflix-red);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
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

/* Responsive Table */
@media (max-width: 768px) {
    .data-table thead {
        display: none;
    }

    .data-table tbody tr {
        display: block;
        margin-bottom: 16px;
        border: 1px solid var(--netflix-border);
        border-radius: 8px;
        background: var(--netflix-card);
    }

    .data-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid var(--netflix-border);
    }

    .data-table tbody td:last-child {
        border-bottom: none;
    }

    .data-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--netflix-text);
        margin-right: 15px;
        min-width: 100px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .item-info {
        flex: 1;
    }

    .action-group {
        justify-content: flex-end;
    }
}

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
    
    .match-actions {
        flex-direction: column;
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
                <i class="fas fa-box-open"></i>
                My Found Items
            </h1>
            <p>Items you have reported as found</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Report New Item
            </a>
        </div>
    </div>

   

    {{-- Items Table --}}
    <div class="card table-card fade-in">
        <div class="card-header">
            <h5>
                <i class="fas fa-list"></i>
                My Items List
            </h5>
            <button type="button" class="btn btn-outline" id="exportBtn">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
        
        @if($foundItems->count() > 0)
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Date Found</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Matches</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foundItems as $item)
                    <tr>
                        <td data-label="Item">
                            <div class="item-info">
                                @if($item->photo)
                                    <img src="{{ asset('storage/' . $item->photo) }}" 
                                         class="item-thumbnail" 
                                         alt="{{ $item->item_name }}">
                                @else
                                    <div class="item-thumbnail-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div class="item-details">
                                    <h6>{{ $item->item_name }}</h6>
                                    <span>{{ Str::limit($item->description, 35) }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td data-label="Category">
                            <span class="badge category">{{ strtoupper($item->category) }}</span>
                        </td>
                        
                        <td data-label="Date Found">
                            <span class="date-text">{{ $item->date_found->format('M d, Y') }}</span>
                        </td>
                        
                        <td data-label="Location">
                            @if($item->found_location)
                                <span class="location-text" title="{{ $item->found_location }}">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ Str::limit($item->found_location, 25) }}
                                </span>
                            @elseif($item->latitude && $item->longitude)
                                <span class="coordinates-text">
                                    <i class="fas fa-map-pin"></i>
                                    {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        
                        <td data-label="Status">
                            @if($item->status === 'pending')
                                <span class="badge status-pending"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($item->status === 'approved')
                                <span class="badge status-approved"><i class="fas fa-check-circle"></i> Active</span>
                            @elseif($item->status === 'rejected')
                                <span class="badge status-rejected"><i class="fas fa-times-circle"></i> Rejected</span>
                            @elseif($item->status === 'claimed')
                                <span class="badge status-claimed"><i class="fas fa-handshake"></i> Claimed</span>
                            @elseif($item->status === 'returned')
                                <span class="badge status-returned"><i class="fas fa-home"></i> Returned</span>
                            @elseif($item->status === 'disposed')
                                <span class="badge status-disposed"><i class="fas fa-times"></i> Disposed</span>
                            @endif
                        </td>
                        
                        <td data-label="Matches">
                            @php
                                $matchCount = $item->matches()->count();
                            @endphp
                            @if($matchCount > 0)
                                <span class="match-badge">
                                    <i class="fas fa-exchange-alt"></i>
                                    {{ $matchCount }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        
                        <td data-label="Actions">
                            <div class="action-group">
                                <a href="{{ route('found-items.show', $item) }}" 
                                   class="action-btn view" 
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @can('update', $item)
                                <a href="{{ route('found-items.edit', $item) }}" 
                                   class="action-btn edit" 
                                   title="Edit Item">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                
                                @can('delete', $item)
                                <form action="{{ route('found-items.destroy', $item) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this item? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Delete Item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination-wrapper">
            {{ $foundItems->links() }}
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h5>No Found Items Yet</h5>
            <p>You haven't reported any found items. Start by reporting your first found item.</p>
            <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Report Found Item
            </a>
        </div>
        @endif
    </div>

    {{-- Recent Matches --}}
    @php
        $recentMatches = Auth::user()->foundItems()
            ->with(['matches' => function($query) {
                $query->latest()->take(5);
            }])
            ->get()
            ->pluck('matches')
            ->flatten()
            ->take(5);
    @endphp

    @if($recentMatches->count() > 0)
    <div class="matches-section fade-in">
        <div class="section-header">
            <h5>
                <i class="fas fa-exchange-alt"></i>
                Recent Matches
            </h5>
        </div>
        
        <div class="matches-grid">
            @foreach($recentMatches as $match)
            <div class="match-card">
                <div class="match-header">
                    <div class="match-badges">
                        <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                            {{ $match->match_score }}% Match
                        </span>
                        <span class="badge status-{{ $match->status }}">
                            {{ strtoupper($match->status) }}
                        </span>
                    </div>
                    <span class="match-time">{{ $match->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="match-items">
                    <div class="match-item">
                        <small>Your Found Item:</small>
                        <strong>{{ $match->foundItem->item_name }}</strong>
                    </div>
                    <div class="match-item">
                        <small>Lost Item:</small>
                        <strong>{{ $match->lostItem->item_name }}</strong>
                    </div>
                </div>
                
                <div class="match-actions">
                    <a href="{{ route('matches.show', $match) }}" class="btn-view-match">
                        <i class="fas fa-eye"></i> View Match
                    </a>
                    <a href="{{ route('lost-items.show', $match->lostItem) }}" class="btn-view-item lost">
                        <i class="fas fa-external-link-alt"></i> View Lost Item
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Notifications Container --}}
<div id="notificationsContainer"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Export functionality
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            btn.disabled = true;
            
            setTimeout(() => {
                showToast('Export started - CSV file will be downloaded', 'info');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1500);
        });
    }
    
    // Add data-label attributes for responsive table
    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        const cells = row.querySelectorAll('td');
        const headers = ['Item', 'Category', 'Date Found', 'Location', 'Status', 'Matches', 'Actions'];
        cells.forEach((cell, index) => {
            if (headers[index]) {
                cell.setAttribute('data-label', headers[index]);
            }
        });
    });
    
    // Stagger animations
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.05}s`;
    });
});

// Toast notification
function showToast(message, type = 'info') {
    const container = document.getElementById('notificationsContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = 'toast';
    
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    const iconColor = type === 'success' ? 'var(--netflix-success)' : type === 'error' ? 'var(--netflix-red)' : 'var(--netflix-info)';
    
    toast.innerHTML = `
        <div class="toast-body">
            <i class="fas fa-${icon}" style="color: ${iconColor};"></i>
            <span>${message}</span>
            <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
        </div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        if (toast && toast.parentNode) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
}
</script>
@endpush
@endsection