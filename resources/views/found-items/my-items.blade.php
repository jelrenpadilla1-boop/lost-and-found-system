@extends('layouts.app')

@section('title', 'My Found Items')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<div class="dashboard-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <h1>
                <i class="fas fa-box-open" style="color: var(--primary);"></i> My Found Items
            </h1>
            <p>Items you have reported as found</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Report New Item
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $foundItems->total() }}</div>
                <div class="stat-label">Total Items</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingCount }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $approvedCount ?? 0 }}</div>
                <div class="stat-label">Approved</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #ff4444, #ff6b6b);">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $rejectedCount ?? 0 }}</div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
            <div class="stat-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $claimedCount }}</div>
                <div class="stat-label">Claimed</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
            <div class="stat-icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $returnedCount ?? 0 }}</div>
                <div class="stat-label">Returned</div>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #666666, #888888);">
            <div class="stat-icon">
                <i class="fas fa-times"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $disposedCount }}</div>
                <div class="stat-label">Disposed</div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="table-card">
        <div class="card-header">
            <div class="header-content">
                <h5>
                    <i class="fas fa-list" style="color: var(--primary);"></i>
                    My Found Items List
                </h5>
                <div class="header-actions">
                    <button type="button" class="btn-export" id="exportBtn">
                        <i class="fas fa-download"></i>
                        <span>Export</span>
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
                                        <span>{{ Str::limit($item->description, 30) }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <td data-label="Category">
                                <span class="category-badge">{{ $item->category }}</span>
                            </td>
                            
                            <td data-label="Date Found">
                                <span class="date-text">{{ $item->date_found->format('M d, Y') }}</span>
                            </td>
                            
                            <td data-label="Location">
                                @if($item->found_location)
                                    <span class="location-text" title="{{ $item->found_location }}">
                                        <i class="fas fa-map-marked-alt"></i>
                                        {{ Str::limit($item->found_location, 20) }}
                                    </span>
                                @elseif($item->latitude && $item->longitude)
                                    <span class="coordinates-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            
                            <td data-label="Status">
                                @if($item->status === 'pending')
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @elseif($item->status === 'approved')
                                    <span class="status-badge status-approved">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                @elseif($item->status === 'rejected')
                                    <span class="status-badge status-rejected">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                @elseif($item->status === 'claimed')
                                    <span class="status-badge status-claimed">
                                        <i class="fas fa-handshake"></i> Claimed
                                    </span>
                                @elseif($item->status === 'returned')
                                    <span class="status-badge status-returned">
                                        <i class="fas fa-home"></i> Returned
                                    </span>
                                @elseif($item->status === 'disposed')
                                    <span class="status-badge status-disposed">
                                        <i class="fas fa-times"></i> Disposed
                                    </span>
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
                                    <span class="no-matches">—</span>
                                @endif
                            </td>
                            
                            <td data-label="Actions">
                                <div class="action-buttons">
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
                                          onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete Item">
                                            <i class="fas fa-trash"></i>
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
                <div class="empty-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h4>No Found Items Yet</h4>
                <p>You haven't reported any found items. Start by reporting your first found item.</p>
                <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Report Found Item
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
    <div class="matches-section">
        <div class="section-header">
            <h5>
                <i class="fas fa-exchange-alt" style="color: var(--primary);"></i>
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
                        <span class="status-badge status-{{ $match->status }}">
                            {{ ucfirst($match->status) }}
                        </span>
                    </div>
                    <span class="match-time">{{ $match->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="match-items">
                    <div class="match-item">
                        <small>Found Item:</small>
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

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.header-left h1 {
    font-size: clamp(24px, 5vw, 28px);
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-left p {
    color: var(--text-muted);
    margin: 0;
    font-size: clamp(13px, 4vw, 15px);
}

.header-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    color: white;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.stat-icon {
    width: 54px;
    height: 54px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    transition: var(--transition);
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(360deg);
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: clamp(20px, 4vw, 24px);
    font-weight: 700;
    color: white;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.9);
}

/* Table Card */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 30px;
}

.card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 16px 20px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.header-content h5 {
    color: var(--text-primary);
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-export {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-export:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
    border-color: transparent;
}

.card-body {
    padding: 20px;
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
    border-radius: 12px;
}

.dark-table {
    width: 100%;
    border-collapse: collapse;
}

.dark-table thead tr {
    background: var(--bg-header);
}

.dark-table thead th {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 13px;
    padding: 15px;
    text-align: left;
    border-bottom: 2px solid var(--primary);
    white-space: nowrap;
}

.dark-table tbody tr {
    background: var(--bg-dark);
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
}

.dark-table tbody tr:hover {
    background: var(--bg-header);
}

.dark-table tbody td {
    padding: 15px;
    color: var(--text-secondary);
    font-size: 13px;
}

/* Item Info */
.item-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-thumbnail {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    object-fit: cover;
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
    color: var(--primary);
    font-size: 20px;
}

.item-details h6 {
    color: var(--text-primary);
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.item-details span {
    color: var(--text-muted);
    font-size: 11px;
}

/* Category Badge */
.category-badge {
    background: var(--bg-header);
    color: var(--primary);
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 500;
    border: 1px solid var(--primary);
    display: inline-block;
    white-space: nowrap;
}

/* Location Text */
.location-text, .coordinates-text {
    color: var(--text-secondary);
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.location-text i, .coordinates-text i {
    color: var(--primary);
    font-size: 12px;
}

/* Date Text */
.date-text {
    color: var(--text-secondary);
    font-size: 12px;
    white-space: nowrap;
}

/* Status Badges */
.status-badge {
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

.status-pending {
    background: rgba(255, 165, 0, 0.15);
    color: #ffa500;
    border: 1px solid #ffa500;
}

.status-approved {
    background: rgba(0, 250, 154, 0.15);
    color: #00fa9a;
    border: 1px solid #00fa9a;
}

.status-rejected {
    background: rgba(255, 68, 68, 0.15);
    color: #ff4444;
    border: 1px solid #ff4444;
}

.status-claimed {
    background: rgba(0, 250, 154, 0.15);
    color: #00fa9a;
    border: 1px solid #00fa9a;
}

.status-returned {
    background: rgba(139, 92, 246, 0.15);
    color: #8b5cf6;
    border: 1px solid #8b5cf6;
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
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 0 10px var(--primary-glow);
}

.no-matches {
    color: var(--text-muted);
    font-size: 12px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
    border: 2px solid transparent;
    background: transparent;
    cursor: pointer;
}

.action-btn.view {
    border-color: var(--primary);
    color: var(--primary);
}

.action-btn.view:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.action-btn.edit {
    border-color: #3498db;
    color: #3498db;
}

.action-btn.edit:hover {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

.action-btn.delete {
    border-color: #ff4444;
    color: #ff4444;
}

.action-btn.delete:hover {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

/* Pagination */
.pagination-wrapper {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 5px;
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
    min-width: 36px;
    height: 36px;
    padding: 0 8px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition);
    font-size: 13px;
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
    opacity: 0.5;
    pointer-events: none;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: var(--bg-header);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 2px solid var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
}

.empty-icon i {
    font-size: 48px;
    color: var(--primary);
}

.empty-state h4 {
    color: var(--text-primary);
    margin-bottom: 10px;
    font-size: 20px;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 20px;
    font-size: 14px;
}

.empty-state .btn-primary {
    display: inline-flex;
}

/* Matches Section */
.matches-section {
    margin-top: 40px;
}

.section-header {
    margin-bottom: 20px;
}

.section-header h5 {
    color: var(--text-primary);
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.match-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 16px;
    transition: var(--transition);
}

.match-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px var(--primary-glow);
}

.match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 10px;
}

.match-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.score-badge {
    padding: 4px 10px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    color: white;
}

.score-badge.high {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 0 10px rgba(0, 250, 154, 0.3);
    color: black;
}

.score-badge.medium {
    background: linear-gradient(135deg, #ffa500, #ffb52e);
    box-shadow: 0 0 10px rgba(255, 165, 0, 0.3);
}

.score-badge.low {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 0 10px var(--primary-glow);
}

.match-time {
    color: var(--text-muted);
    font-size: 11px;
}

.match-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
}

.match-item {
    background: var(--bg-header);
    padding: 10px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
}

.match-item small {
    color: var(--text-muted);
    font-size: 10px;
    display: block;
    margin-bottom: 2px;
}

.match-item strong {
    color: var(--text-primary);
    font-size: 13px;
}

.match-actions {
    display: flex;
    gap: 10px;
}

.btn-view-match,
.btn-view-item {
    flex: 1;
    padding: 8px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
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

/* Responsive Table */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .dark-table thead {
        display: none;
    }
    
    .dark-table tbody tr {
        display: block;
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-card);
    }
    
    .dark-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .dark-table tbody td:last-child {
        border-bottom: none;
    }
    
    .dark-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-primary);
        margin-right: 15px;
        min-width: 80px;
        font-size: 12px;
    }
    
    .item-info {
        flex: 1;
    }
    
    .action-buttons {
        justify-content: flex-end;
    }
    
    .matches-grid {
        grid-template-columns: 1fr;
    }
    
    .match-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .header-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .btn-export {
        width: 100%;
        justify-content: center;
    }
    
    .item-info {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .item-thumbnail,
    .item-thumbnail-placeholder {
        margin-bottom: 8px;
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
.match-card {
    animation: fadeIn 0.5s ease forwards;
}

/* Utility Classes */
.text-muted {
    color: var(--text-muted) !important;
}

.d-inline {
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
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
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--primary-glow);
}
</style>

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
            
            // Simulate export (replace with actual export logic)
            setTimeout(() => {
                showNotification('Export started. Your file will download shortly.', 'info');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 1500);
        });
    }
    
    // Animation for cards
    const cards = document.querySelectorAll('.stat-card, .match-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Add data-label attributes for responsive table
    document.querySelectorAll('#foundItemsTable tbody td').forEach((td, index) => {
        const headers = ['Item', 'Category', 'Date Found', 'Location', 'Status', 'Matches', 'Actions'];
        const columnIndex = index % 7;
        td.setAttribute('data-label', headers[columnIndex]);
    });
});

// Notification function
function showNotification(message, type = 'info') {
    const container = document.getElementById('notificationsContainer');
    if (!container) {
        // Create container if it doesn't exist
        const newContainer = document.createElement('div');
        newContainer.id = 'notificationsContainer';
        newContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        `;
        document.body.appendChild(newContainer);
    }
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        background: var(--bg-card);
        border: 1px solid ${type === 'success' ? '#00fa9a' : type === 'error' ? '#ff4444' : 'var(--primary)'};
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 10px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease;
    `;
    
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    const iconColor = type === 'success' ? '#00fa9a' : type === 'error' ? '#ff4444' : 'var(--primary)';
    
    notification.innerHTML = `
        <i class="fas fa-${icon}" style="color: ${iconColor};"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="
            background: transparent;
            border: none;
            color: var(--text-muted);
            margin-left: auto;
            cursor: pointer;
            padding: 0 5px;
        ">×</button>
    `;
    
    document.getElementById('notificationsContainer').appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(20px);
    }
}

#notificationsContainer {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    max-width: 350px;
}

.notification {
    width: 100%;
}
</style>
@endpush
@endsection