@extends('layouts.app')

@section('title', 'My Found Items')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-box-open" style="color: var(--primary);"></i> My Found Items
        </h1>
        <p>Items you have reported as found</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('found-items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Report New Item
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $foundItems->total() }}</div>
                <div class="stat-label">Total Items</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $foundItems->where('status', 'pending')->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
            <div class="stat-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $foundItems->where('status', 'claimed')->count() }}</div>
                <div class="stat-label">Claimed</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #ff4444, #ff6b6b);">
            <div class="stat-icon">
                <i class="fas fa-times"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $foundItems->where('status', 'disposed')->count() }}</div>
                <div class="stat-label">Disposed</div>
            </div>
        </div>
    </div>
</div>

<!-- Items Table -->
<div class="table-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Found Items List</h5>
            <div class="btn-group">
                <button type="button" class="btn-export" id="exportBtn">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($foundItems->count() > 0)
        <div class="table-responsive">
            <table class="dark-table" id="foundItemsTable">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Date Found</th>
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
                                        <i class="fas fa-image" style="color: var(--primary); opacity: 0.5;"></i>
                                    </div>
                                @endif
                                <div class="item-details">
                                    <h6 class="item-name">{{ $item->item_name }}</h6>
                                    <span class="item-description">{{ \Illuminate\Support\Str::limit($item->description, 30) }}</span>
                                </div>
                            </div>
                        </td>
                        <td data-label="Category">
                            <span class="category-badge">{{ $item->category }}</span>
                        </td>
                        <td data-label="Date Found">
                            <span class="date-text">{{ $item->date_found->format('M d, Y') }}</span>
                        </td>
                        <td data-label="Status">
                            @if($item->status === 'pending')
                                <span class="status-badge status-pending">Pending</span>
                            @elseif($item->status === 'claimed')
                                <span class="status-badge status-claimed">Claimed</span>
                            @else
                                <span class="status-badge status-disposed">Disposed</span>
                            @endif
                        </td>
                        <td data-label="Matches">
                            @php
                                $matchCount = $item->matches()->count();
                            @endphp
                            @if($matchCount > 0)
                                <span class="match-badge">
                                    <i class="fas fa-exchange-alt"></i> {{ $matchCount }}
                                </span>
                            @else
                                <span class="no-matches">-</span>
                            @endif
                        </td>
                        <td data-label="Actions">
                            <div class="action-buttons">
                                <a href="{{ route('found-items.show', $item) }}" class="btn-icon view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('found-items.edit', $item) }}" class="btn-icon edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('found-items.destroy', $item) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination-container">
            {{ $foundItems->links() }}
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h4>No Found Items Yet</h4>
            <p>You haven't reported any found items yet.</p>
            <a href="{{ route('found-items.create') }}" class="btn-primary">
                <i class="fas fa-plus-circle"></i> Report Your First Found Item
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Recent Matches -->
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
<div class="matches-card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-exchange-alt" style="color: var(--primary);"></i> Recent Matches
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($recentMatches as $match)
            <div class="col-md-6 mb-3">
                <div class="match-item-card">
                    <div class="match-header">
                        <div class="match-badges">
                            <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                {{ $match->match_score }}% Match
                            </span>
                            <span class="status-badge status-{{ $match->status }}">
                                {{ ucfirst($match->status) }}
                            </span>
                        </div>
                        <span class="match-time">{{ $match->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="match-items">
                        <div class="match-item">
                            <small class="item-label">Found Item:</small>
                            <strong class="item-name">{{ $match->foundItem->item_name }}</strong>
                        </div>
                        <div class="match-item">
                            <small class="item-label">Lost Item:</small>
                            <strong class="item-name">{{ $match->lostItem->item_name }}</strong>
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
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Notifications Container -->
<div id="notificationsContainer"></div>

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-dark: #c71585;
    --primary-glow: rgba(255, 20, 147, 0.3);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-header: #222;
    --bg-table-header: #1a1a1a;
    --bg-table-row: #0a0a0a;
    --bg-table-row-hover: #1a1a1a;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #e0e0e0;
    --text-muted: #888;
    --success: #00fa9a;
    --danger: #ff4444;
    --warning: #ffa500;
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-title p {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
    font-size: 1rem;
}

/* Stats Cards */
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    color: white;
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
}

.stat-card:hover::before {
    opacity: 0.1;
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

/* Table Card */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 1.25rem 1.5rem;
}

.card-header h5 {
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-export {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-export:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
    border-color: transparent;
}

.card-body {
    padding: 1.5rem;
}

/* Dark Table Styles */
.table-responsive {
    overflow-x: auto;
    border-radius: 12px;
}

.dark-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--bg-table-row);
    border-radius: 12px;
    overflow: hidden;
}

.dark-table thead tr {
    background: var(--bg-table-header);
}

.dark-table thead th {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.875rem;
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 2px solid var(--primary);
    white-space: nowrap;
    background: var(--bg-table-header);
}

.dark-table tbody tr {
    background: var(--bg-table-row);
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--border-color);
}

.dark-table tbody tr:hover {
    background: var(--bg-table-row-hover);
}

.dark-table tbody td {
    padding: 1rem 1.5rem;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.dark-table tbody tr:last-child td {
    border-bottom: none;
}

/* Item Info */
.item-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.item-thumbnail {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border-color);
}

.item-thumbnail-placeholder {
    width: 48px;
    height: 48px;
    background: var(--bg-header);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
}

.item-details {
    flex: 1;
}

.item-name {
    color: var(--text-primary);
    font-size: 0.9375rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.item-description {
    color: var(--text-muted);
    font-size: 0.75rem;
}

/* Category Badge */
.category-badge {
    background: var(--bg-header);
    color: var(--primary);
    padding: 0.375rem 0.875rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid var(--primary);
    display: inline-block;
}

/* Date Text */
.date-text {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Status Badges */
.status-badge {
    padding: 0.375rem 0.875rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
}

.status-pending {
    background: rgba(255, 165, 0, 0.15);
    color: #ffa500;
    border: 1px solid #ffa500;
}

.status-claimed {
    background: rgba(0, 250, 154, 0.15);
    color: #00fa9a;
    border: 1px solid #00fa9a;
}

.status-disposed {
    background: rgba(255, 68, 68, 0.15);
    color: #ff4444;
    border: 1px solid #ff4444;
}

/* Match Badge */
.match-badge {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    padding: 0.375rem 0.875rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    box-shadow: 0 0 10px var(--primary-glow);
}

.no-matches {
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    background: transparent;
}

.btn-icon.view {
    border-color: var(--primary);
    color: var(--primary);
}

.btn-icon.view:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.btn-icon.edit {
    border-color: #3498db;
    color: #3498db;
}

.btn-icon.edit:hover {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

.btn-icon.delete {
    border-color: #ff4444;
    color: #ff4444;
}

.btn-icon.delete:hover {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

/* Pagination */
.pagination-container {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 0.25rem;
    list-style: none;
    padding: 0;
}

.page-link {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: transparent;
    box-shadow: 0 5px 15px var(--primary-glow);
}

.page-item.disabled .page-link {
    background: var(--bg-header);
    border-color: var(--border-color);
    color: var(--text-muted);
    pointer-events: none;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: var(--bg-header);
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
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
}

.btn-primary {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    padding: 0.75rem 1.5rem;
    border-radius: 30px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
    border-color: transparent;
}

/* Recent Matches Card */
.matches-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.match-item-card {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    height: 100%;
    transition: all 0.3s ease;
}

.match-item-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px var(--primary-glow);
}

.match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.match-badges {
    display: flex;
    gap: 0.5rem;
}

.score-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.score-high {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 0 10px rgba(0, 250, 154, 0.3);
    color: black;
}

.score-medium {
    background: linear-gradient(135deg, #ffa500, #ffb52e);
    box-shadow: 0 0 10px rgba(255, 165, 0, 0.3);
}

.score-low {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 0 10px var(--primary-glow);
}

.match-time {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.match-items {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.match-item {
    flex: 1;
}

.item-label {
    color: var(--text-muted);
    font-size: 0.75rem;
    display: block;
    margin-bottom: 0.25rem;
}

.item-name {
    color: var(--text-primary);
    font-size: 0.875rem;
}

.match-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-view-match,
.btn-view-item {
    padding: 0.375rem 0.875rem;
    border-radius: 30px;
    font-size: 0.75rem;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    border: 2px solid transparent;
}

.btn-view-match {
    border-color: var(--primary);
    color: var(--primary);
}

.btn-view-match:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.btn-view-item.lost {
    border-color: #ff4444;
    color: #ff4444;
}

.btn-view-item.lost:hover {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

/* Toast Notifications */
#notificationsContainer {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 12px;
    min-width: 300px;
}

.toast-body {
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-close-white {
    filter: invert(1);
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }

    .item-info {
        flex-direction: column;
        align-items: flex-start;
    }

    .action-buttons {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .btn-icon {
        width: 40px;
        height: 40px;
    }

    .match-items {
        flex-direction: column;
        gap: 0.5rem;
    }

    .match-actions {
        flex-direction: column;
    }

    .btn-view-match,
    .btn-view-item {
        width: 100%;
        justify-content: center;
    }

    /* Responsive Table */
    .dark-table thead {
        display: none;
    }

    .dark-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-table-row);
    }

    .dark-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border: none;
        border-bottom: 1px solid var(--border-color);
    }

    .dark-table tbody td:last-child {
        border-bottom: none;
    }

    .dark-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-primary);
        margin-right: 1rem;
        min-width: 80px;
    }

    .item-info {
        flex-direction: row;
        align-items: center;
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

.stat-card,
.table-card,
.matches-card {
    animation: fadeIn 0.5s ease forwards;
}
</style>
@endsection

@push('scripts')
<script>
    // Export functionality
    document.getElementById('exportBtn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
        btn.disabled = true;
        
        // Simulate export process
        setTimeout(() => {
            showToast('Export started. Your file will download shortly.', 'info');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1500);
    });
    
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
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1}s`;
    });

    // Add data-label attributes for responsive table
    document.querySelectorAll('#foundItemsTable tbody td').forEach((td, index) => {
        const headers = ['Item', 'Category', 'Date Found', 'Status', 'Matches', 'Actions'];
        const columnIndex = index % 6;
        td.setAttribute('data-label', headers[columnIndex]);
    });
</script>
@endpush