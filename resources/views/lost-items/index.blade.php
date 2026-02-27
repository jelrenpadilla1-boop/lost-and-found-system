@extends('layouts.app')

@section('title', 'Lost Items')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<div class="dashboard-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="fas fa-exclamation-circle" style="color: var(--primary);"></i> Lost Items
            </h1>
            <p>{{ $isAdmin ? 'Manage and verify reported lost items' : 'Browse all items that have been reported as lost' }}</p>
        </div>
        <div class="page-actions">
            @if(!$isAdmin)
            <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Report Lost Item
            </a>
            @endif
        </div>
    </div>

    <!-- Admin Pending Items Alert -->
    @if($isAdmin && $pendingCount > 0)
    <div class="custom-alert warning-alert mb-4" id="pendingAlert">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-text">
                <strong>Pending Verification Required</strong>
                <p>There {{ $pendingCount > 1 ? 'are' : 'is' }} <strong>{{ $pendingCount }}</strong> lost item{{ $pendingCount > 1 ? 's' : '' }} waiting for your approval.</p>
            </div>
            <a href="#pending-items" class="alert-action-btn">
                <i class="fas fa-arrow-down"></i> Review Now
            </a>
        </div>
        <button type="button" class="alert-close" onclick="this.closest('.custom-alert').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid-container mb-4">
        <div class="stats-grid-row">
            <div class="stats-grid-col">
                <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => '', 'category' => ''])) }}" 
                   class="stats-link">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-value">{{ $totalItems }}</div>
                            <div class="stats-label">Total Items</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="stats-grid-col">
                <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => 'pending', 'category' => ''])) }}" 
                   class="stats-link">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-value">{{ $pendingCount }}</div>
                            <div class="stats-label">{{ $isAdmin ? 'Pending Approval' : 'Still Missing' }}</div>
                        </div>
                        @if($isAdmin && $pendingCount > 0)
                        <span class="pending-badge">{{ $pendingCount }}</span>
                        @endif
                    </div>
                </a>
            </div>
            <div class="stats-grid-col">
                <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => 'approved', 'category' => ''])) }}" 
                   class="stats-link">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-value">{{ $approvedCount ?? 0 }}</div>
                            <div class="stats-label">Approved</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="stats-grid-col">
                <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => 'returned', 'category' => ''])) }}" 
                   class="stats-link">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-value">{{ $returnedCount }}</div>
                            <div class="stats-label">Returned</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Filter Indicator -->
    @if(request('status') || request('category') || request('search'))
    <div class="custom-alert info-alert mb-4">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-filter"></i>
            </div>
            <div class="alert-text">
                <strong>Active Filters:</strong>
                <div class="filter-tags">
                    @if(request('status'))
                        <span class="filter-tag">
                            Status: {{ request('status') == 'pending' ? ($isAdmin ? 'Pending Approval' : 'Missing') : (request('status') == 'approved' ? 'Approved' : (request('status') == 'found' ? 'Found' : 'Returned')) }}
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="filter-tag">
                            Category: {{ request('category') }}
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="filter-tag">
                            Search: "{{ request('search') }}"
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('lost-items.index') }}" class="alert-action-btn">
                <i class="fas fa-times"></i> Clear Filters
            </a>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="filter-card mb-4">
        <div class="filter-card-body">
            <form method="GET" action="{{ route('lost-items.index') }}" id="filterForm">
                <div class="filter-form-row">
                    <div class="filter-form-group">
                        <label for="category" class="filter-label">
                            <i class="fas fa-tag"></i> Category
                        </label>
                        <select class="filter-select" id="category" name="category">
                            <option value="">All Categories</option>
                            <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>📱 Electronics</option>
                            <option value="Documents" {{ request('category') == 'Documents' ? 'selected' : '' }}>📄 Documents</option>
                            <option value="Jewelry" {{ request('category') == 'Jewelry' ? 'selected' : '' }}>💎 Jewelry</option>
                            <option value="Clothing" {{ request('category') == 'Clothing' ? 'selected' : '' }}>👕 Clothing</option>
                            <option value="Bags" {{ request('category') == 'Bags' ? 'selected' : '' }}>🎒 Bags</option>
                            <option value="Keys" {{ request('category') == 'Keys' ? 'selected' : '' }}>🔑 Keys</option>
                            <option value="Wallet" {{ request('category') == 'Wallet' ? 'selected' : '' }}>👛 Wallet</option>
                            <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>📦 Other</option>
                        </select>
                    </div>
                    <div class="filter-form-group">
                        <label for="status" class="filter-label">
                            <i class="fas fa-circle"></i> Status
                        </label>
                        <select class="filter-select" id="status" name="status">
                            <option value="">All Status</option>
                            @if($isAdmin)
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            @else
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Missing</option>
                            @endif
                            <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                    </div>
                    <div class="filter-form-group search-group">
                        <label for="search" class="filter-label">
                            <i class="fas fa-search"></i> Search
                        </label>
                        <input type="text" class="filter-input" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by item name, description, or location...">
                    </div>
                    <div class="filter-form-group filter-actions-group">
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('lost-items.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-redo-alt"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Pending Items Section for Admin -->
    @if($isAdmin && !in_array(request('status'), ['approved', 'found', 'returned']))
        @if(isset($pendingItems) && $pendingItems->count() > 0)
        <div id="pending-items" class="mb-5">
            <div class="section-header">
                <h3>
                    <i class="fas fa-exclamation-triangle" style="color: #ffa500;"></i> 
                    Items Pending Approval
                </h3>
                <p>These items need your review and approval before they appear in the main list.</p>
            </div>
            
            <div class="items-grid">
                @foreach($pendingItems as $item)
                <div class="items-grid-col">
                    <div class="item-card pending-card">
                        <div class="item-card-header">
                            <div class="item-badges">
                                <span class="status-badge pending">
                                    <i class="fas fa-clock me-1"></i> Pending Approval
                                </span>
                                <span class="category-badge">{{ $item->category }}</span>
                            </div>
                            <div class="item-actions-dropdown">
                                <button class="dropdown-toggle-btn" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('lost-items.show', $item) }}">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('lost-items.approve', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success" onclick="return confirm('Approve this lost item? It will be visible to all users.')">
                                                <i class="fas fa-check-circle"></i> Approve
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('lost-items.reject', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Reject this lost item? The user will be notified.')">
                                                <i class="fas fa-times-circle"></i> Reject
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="item-card-body">
                            <div class="item-image-container">
                                @if($item->photo)
                                    <img src="{{ asset('storage/' . $item->photo) }}" 
                                         class="item-image" 
                                         alt="{{ $item->item_name }}">
                                @else
                                    <div class="item-image-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <h5 class="item-title">{{ $item->item_name }}</h5>
                            <p class="item-description">
                                {{ \Illuminate\Support\Str::limit($item->description, 80) }}
                            </p>
                            
                            <div class="item-meta">
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $item->date_lost->format('M d, Y') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $item->user->name }}</span>
                                </div>
                            </div>
                            
                            @if($item->lost_location)
                            <div class="item-location" title="{{ $item->lost_location }}">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ \Illuminate\Support\Str::limit($item->lost_location, 25) }}
                            </div>
                            @endif
                            
                            <div class="admin-actions">
                                <form action="{{ route('lost-items.approve', $item) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('lost-items.reject', $item) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="item-card-footer">
                            <i class="fas fa-clock"></i> Reported {{ $item->created_at->diffForHumans() }}
                            <span class="pending-badge-small">Awaiting Review</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif

    <!-- Approved Items Grid -->
    <div class="approved-items-section">
        <div class="section-header">
            <h3>
                <i class="fas fa-check-circle" style="color: var(--success);"></i> 
                {{ $isAdmin ? 'Approved Items' : 'Lost Items' }}
            </h3>
        </div>
        
        <div class="items-grid" id="lostItemsGrid">
            @forelse($lostItems as $item)
            <div class="items-grid-col">
                <div class="item-card">
                    <div class="item-card-header">
                        <div class="item-badges">
                            <span class="status-badge {{ $item->status }}">
                                @if($item->status == 'pending' && $isAdmin)
                                    Pending Approval
                                @elseif($item->status == 'pending')
                                    Missing
                                @elseif($item->status == 'approved')
                                    <i class="fas fa-check-circle me-1"></i> Approved
                                @elseif($item->status == 'found')
                                    Found
                                @elseif($item->status == 'returned')
                                    Returned
                                @else
                                    {{ ucfirst($item->status) }}
                                @endif
                            </span>
                            <span class="category-badge">{{ $item->category }}</span>
                        </div>
                        <div class="item-actions-dropdown">
                            <button class="dropdown-toggle-btn" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('lost-items.show', $item) }}">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </li>
                                
                                @if($isAdmin && $item->status == 'pending')
                                <li>
                                    <form action="{{ route('lost-items.approve', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-success">
                                            <i class="fas fa-check-circle"></i> Approve
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('lost-items.reject', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-times-circle"></i> Reject
                                        </button>
                                    </form>
                                </li>
                                @endif
                                
                                @can('update', $item)
                                <li>
                                    <a class="dropdown-item" href="{{ route('lost-items.edit', $item) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </li>
                                @endcan
                                
                                @can('delete', $item)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('lost-items.destroy', $item) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                    
                    <div class="item-card-body">
                        <div class="item-image-container">
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" 
                                     class="item-image" 
                                     alt="{{ $item->item_name }}">
                            @else
                                <div class="item-image-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h5 class="item-title">{{ $item->item_name }}</h5>
                        <p class="item-description">
                            {{ \Illuminate\Support\Str::limit($item->description, 100) }}
                        </p>
                        
                        <div class="item-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $item->date_lost->format('M d, Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>{{ $item->user->name }}</span>
                            </div>
                        </div>
                        
                        @if($item->lost_location)
                        <div class="item-location" title="{{ $item->lost_location }}">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ \Illuminate\Support\Str::limit($item->lost_location, 25) }}
                        </div>
                        @endif
                        
                        <a href="{{ route('lost-items.show', $item) }}" class="view-details-btn">
                            <i class="fas fa-eye"></i> View Details
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="item-card-footer">
                        <i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}
                        @if($item->approved_at)
                        <span class="approved-badge">Approved {{ $item->approved_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state-wrapper">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h4>No Lost Items Found</h4>
                    <p>
                        @if($isAdmin)
                            No items match your current filters.
                        @else
                            No lost items have been reported yet. Be the first to report one!
                        @endif
                    </p>
                    @if(!$isAdmin)
                    <div class="empty-state-actions">
                        <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Report Lost Item
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($lostItems->hasPages())
        <div class="pagination-wrapper mt-4">
            {{ $lostItems->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats-wrapper mt-5">
        <div class="quick-stats-header">
            <i class="fas fa-chart-pie"></i>
            <h5>Quick Stats</h5>
        </div>
        <div class="quick-stats-grid">
            <a href="{{ route('lost-items.index', ['status' => 'pending']) }}" class="quick-stat-item">
                <div class="quick-stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="quick-stat-info">
                    <div class="quick-stat-number">{{ $pendingCount }}</div>
                    <div class="quick-stat-label">{{ $isAdmin ? 'Pending Approval' : 'Still Searching' }}</div>
                </div>
            </a>
            
            <a href="{{ route('lost-items.index', ['status' => 'approved']) }}" class="quick-stat-item">
                <div class="quick-stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="quick-stat-info">
                    <div class="quick-stat-number">{{ $approvedCount ?? 0 }}</div>
                    <div class="quick-stat-label">Approved Items</div>
                </div>
            </a>
            
            <div class="quick-stat-item">
                <div class="quick-stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    <i class="fas fa-users"></i>
                </div>
                <div class="quick-stat-info">
                    <div class="quick-stat-number">{{ $activeReporters }}</div>
                    <div class="quick-stat-label">Active Reporters</div>
                </div>
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

.page-title h1 {
    font-size: clamp(24px, 5vw, 28px);
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title p {
    color: var(--text-muted);
    margin: 0;
    font-size: clamp(13px, 4vw, 15px);
}

.page-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Custom Alerts */
.custom-alert {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 16px 20px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    animation: slideIn 0.3s ease;
}

.warning-alert {
    border-left: 4px solid #ffa500;
    background: rgba(255, 165, 0, 0.1);
}

.info-alert {
    border-left: 4px solid var(--primary);
    background: rgba(255, 20, 147, 0.1);
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
    flex-wrap: wrap;
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(255, 165, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffa500;
    font-size: 20px;
    flex-shrink: 0;
}

.info-alert .alert-icon {
    background: rgba(255, 20, 147, 0.2);
    color: var(--primary);
}

.alert-text {
    flex: 1;
    min-width: 200px;
}

.alert-text strong {
    display: block;
    color: var(--text-primary);
    margin-bottom: 4px;
    font-size: 16px;
}

.alert-text p {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.filter-tag {
    background: rgba(255, 20, 147, 0.15);
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
}

.alert-action-btn {
    background: transparent;
    border: 1px solid #ffa500;
    color: #ffa500;
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    white-space: nowrap;
}

.info-alert .alert-action-btn {
    border-color: var(--primary);
    color: var(--primary);
}

.alert-action-btn:hover {
    background: #ffa500;
    color: black;
    transform: translateY(-2px);
}

.info-alert .alert-action-btn:hover {
    background: var(--primary);
    color: white;
}

.alert-close {
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    flex-shrink: 0;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--error);
    transform: rotate(90deg);
}

/* Stats Grid */
.stats-grid-container {
    width: 100%;
    margin-bottom: 24px;
}

.stats-grid-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 992px) {
    .stats-grid-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .stats-grid-row {
        grid-template-columns: 1fr;
    }
}

.stats-grid-col {
    width: 100%;
}

/* Stats Cards */
.stats-link {
    text-decoration: none;
    display: block;
    height: 100%;
}

.stats-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    transition: var(--transition);
    flex-shrink: 0;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1) rotate(360deg);
}

.stats-content {
    flex: 1;
    min-width: 0;
}

.stats-value {
    font-size: clamp(20px, 4vw, 28px);
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stats-label {
    font-size: clamp(11px, 3vw, 13px);
    color: var(--text-muted);
    font-weight: 500;
    word-wrap: break-word;
}

.pending-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--warning);
    color: black;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 20px;
    min-width: 24px;
    text-align: center;
    box-shadow: 0 0 15px rgba(255, 165, 0, 0.5);
    animation: pulse 2s infinite;
}

/* Filter Card */
.filter-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    margin-bottom: 24px;
}

.filter-card-body {
    padding: 24px;
}

.filter-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1.5fr 1fr;
    gap: 20px;
    align-items: end;
}

@media (max-width: 992px) {
    .filter-form-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-form-group.search-group {
        grid-column: span 2;
    }
    
    .filter-form-group.filter-actions-group {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .filter-form-row {
        grid-template-columns: 1fr;
    }
    
    .filter-form-group.search-group,
    .filter-form-group.filter-actions-group {
        grid-column: span 1;
    }
}

.filter-form-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
}

.filter-label i {
    color: var(--primary);
    font-size: 14px;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 14px;
    transition: var(--transition);
}

.filter-select:focus,
.filter-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    outline: none;
    background: var(--bg-card);
}

.filter-select option {
    background: var(--bg-header);
    color: var(--text-primary);
}

.filter-actions {
    display: flex;
    gap: 8px;
    width: 100%;
}

@media (max-width: 400px) {
    .filter-actions {
        flex-direction: column;
    }
}

/* Section Header */
.section-header {
    margin-bottom: 20px;
}

.section-header h3 {
    font-size: clamp(18px, 4vw, 20px);
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.section-header p {
    color: var(--text-muted);
    margin: 0;
    font-size: clamp(12px, 3vw, 14px);
}

/* Items Grid */
.items-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

@media (max-width: 992px) {
    .items-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .items-grid {
        grid-template-columns: 1fr;
    }
}

.items-grid-col {
    width: 100%;
}

/* Item Cards */
.item-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    position: relative;
    display: flex;
    flex-direction: column;
}

.item-card:hover {
    transform: translateY(-8px);
    border-color: var(--primary);
    box-shadow: 0 15px 35px var(--primary-glow);
}

.pending-card {
    border: 2px solid #ffa500;
    animation: glowPulse 2s infinite;
}

@keyframes glowPulse {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 165, 0, 0.3); }
    50% { box-shadow: 0 0 30px rgba(255, 165, 0, 0.6); }
}

.item-card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.item-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-badge.pending {
    background: linear-gradient(135deg, #ffa500, #ffb52e);
    box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
}

.status-badge.approved {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
    color: black;
}

.status-badge.found {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 0 15px var(--primary-glow);
}

.status-badge.returned {
    background: linear-gradient(135deg, #8b5cf6, #a78bfa);
    box-shadow: 0 0 15px rgba(139, 92, 246, 0.3);
}

.category-badge {
    padding: 6px 12px;
    background: var(--bg-header);
    border: 1px solid var(--primary);
    border-radius: 30px;
    font-size: 11px;
    color: var(--primary);
}

.item-actions-dropdown {
    position: relative;
    flex-shrink: 0;
}

.dropdown-toggle-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropdown-toggle-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: rotate(90deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 8px;
    min-width: 180px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: none;
}

.dropdown-menu.show {
    display: block;
    animation: slideDown 0.2s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    color: var(--text-muted);
    text-decoration: none;
    border-radius: 8px;
    transition: var(--transition);
    font-size: 13px;
    width: 100%;
    background: transparent;
    border: none;
    cursor: pointer;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateX(5px);
}

.dropdown-item i {
    width: 16px;
    color: var(--primary);
    font-size: 14px;
}

.dropdown-item:hover i {
    color: white;
}

.dropdown-item.text-success {
    color: var(--success) !important;
}

.dropdown-item.text-success i {
    color: var(--success);
}

.dropdown-item.text-success:hover {
    background: linear-gradient(135deg, var(--success), #00ff7f);
    color: black !important;
}

.dropdown-item.text-success:hover i {
    color: black;
}

.dropdown-item.text-danger {
    color: var(--error) !important;
}

.dropdown-item.text-danger i {
    color: var(--error);
}

.dropdown-item.text-danger:hover {
    background: linear-gradient(135deg, var(--error), #ff6b6b);
    color: white !important;
}

.dropdown-item.text-danger:hover i {
    color: white;
}

.dropdown-divider {
    height: 1px;
    background: var(--border-color);
    margin: 8px 0;
}

/* Item Card Body */
.item-card-body {
    padding: 20px;
    flex: 1;
}

.item-image-container {
    text-align: center;
    margin-bottom: 16px;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-image {
    max-height: 160px;
    max-width: 100%;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.item-image-placeholder {
    width: 100%;
    height: 160px;
    background: var(--bg-header);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--border-color);
    color: var(--text-muted);
    font-size: 48px;
}

.item-title {
    font-size: clamp(16px, 4vw, 18px);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
    word-wrap: break-word;
}

.item-description {
    color: var(--text-muted);
    font-size: clamp(12px, 3vw, 13px);
    margin-bottom: 16px;
    line-height: 1.6;
    word-wrap: break-word;
}

.item-meta {
    display: flex;
    gap: 16px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: clamp(11px, 2.5vw, 12px);
}

.meta-item i {
    color: var(--primary);
    font-size: 12px;
    flex-shrink: 0;
}

.item-location {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    padding: 8px 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: clamp(11px, 2.5vw, 12px);
    margin-bottom: 16px;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-location i {
    color: var(--primary);
    flex-shrink: 0;
}

/* Admin Actions */
.admin-actions {
    display: flex;
    gap: 10px;
    margin-top: 16px;
    flex-wrap: wrap;
}

.flex-1 {
    flex: 1;
    min-width: 120px;
}

/* Buttons */
.btn {
    padding: 12px 20px;
    border-radius: 30px;
    font-size: clamp(12px, 3vw, 13px);
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    white-space: nowrap;
}

@media (max-width: 400px) {
    .btn {
        white-space: normal;
        padding: 10px 16px;
    }
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
    border: 2px solid var(--primary);
    color: var(--primary);
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
}

.btn-success {
    background: linear-gradient(135deg, var(--success), #00ff7f);
    color: black;
    box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 250, 154, 0.5);
}

.btn-danger {
    background: linear-gradient(135deg, var(--error), #ff6b6b);
    color: white;
    box-shadow: 0 0 15px rgba(255, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 68, 68, 0.5);
}

.w-100 {
    width: 100%;
}

/* View Details Button */
.view-details-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: transparent;
    border: 2px solid var(--primary);
    border-radius: 12px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    margin-top: 16px;
    font-size: clamp(12px, 3vw, 13px);
}

.view-details-btn:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--primary-glow);
}

.view-details-btn:hover i:last-child {
    transform: translateX(5px);
}

.view-details-btn i:last-child {
    transition: transform 0.3s ease;
}

/* Card Footer */
.item-card-footer {
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
    padding: 14px 20px;
    color: var(--text-muted);
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}

.item-card-footer i {
    color: var(--primary);
    margin-right: 6px;
}

.pending-badge-small {
    background: rgba(255, 165, 0, 0.15);
    border: 1px solid #ffa500;
    color: #ffa500;
    padding: 4px 8px;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.approved-badge {
    background: rgba(0, 250, 154, 0.15);
    border: 1px solid var(--success);
    color: var(--success);
    padding: 4px 8px;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

/* Empty State */
.empty-state-wrapper {
    grid-column: 1 / -1;
    width: 100%;
}

.empty-state {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
}

.empty-state-icon {
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

.empty-state-icon i {
    font-size: 48px;
    color: var(--primary);
}

.empty-state h4 {
    color: var(--text-primary);
    margin-bottom: 10px;
    font-size: clamp(18px, 4vw, 20px);
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 20px;
    font-size: clamp(13px, 3vw, 14px);
}

.empty-state-actions {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}

/* Pagination */
.pagination-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    justify-content: center;
    overflow-x: auto;
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
    background: var(--bg-card);
    border-color: var(--border-color);
    color: var(--text-muted);
    opacity: 0.5;
    pointer-events: none;
}

/* Quick Stats */
.quick-stats-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 24px;
}

.quick-stats-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.quick-stats-header i {
    color: var(--primary);
    font-size: 20px;
}

.quick-stats-header h5 {
    color: var(--text-primary);
    margin: 0;
    font-size: clamp(16px, 4vw, 18px);
    font-weight: 600;
}

.quick-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 768px) {
    .quick-stats-grid {
        grid-template-columns: 1fr;
    }
}

.quick-stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px;
    background: var(--bg-header);
    border-radius: 16px;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid transparent;
    flex-wrap: wrap;
}

@media (max-width: 400px) {
    .quick-stat-item {
        flex-direction: column;
        text-align: center;
    }
}

.quick-stat-item:hover {
    border-color: var(--primary);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px var(--primary-glow);
}

.quick-stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    transition: var(--transition);
    flex-shrink: 0;
}

.quick-stat-item:hover .quick-stat-icon {
    transform: scale(1.1) rotate(360deg);
}

.quick-stat-info {
    flex: 1;
    min-width: 0;
}

.quick-stat-number {
    font-size: clamp(18px, 4vw, 22px);
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.quick-stat-label {
    font-size: clamp(11px, 2.5vw, 12px);
    color: var(--text-muted);
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

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.fade-in {
    animation: fadeIn 0.5s ease forwards;
}

/* Utility Classes */
.mb-4 {
    margin-bottom: 24px;
}

.mb-5 {
    margin-bottom: 40px;
}

.mt-4 {
    margin-top: 24px;
}

.mt-5 {
    margin-top: 40px;
}

.w-100 {
    width: 100%;
}

.text-center {
    text-align: center;
}

/* Responsive Breakpoints */
@media (max-width: 1200px) {
    .dashboard-wrapper {
        padding: 15px;
    }
}

@media (max-width: 768px) {
    .dashboard-wrapper {
        padding: 10px;
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
    }
    
    .alert-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .alert-action-btn {
        width: 100%;
        text-align: center;
    }
    
    .item-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .admin-actions {
        flex-direction: column;
    }
    
    .flex-1 {
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown functionality
    document.querySelectorAll('.dropdown-toggle-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('show');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.item-actions-dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // Filter form auto-submit
    const categorySelect = document.getElementById('category');
    const statusSelect = document.getElementById('status');
    const searchInput = document.getElementById('search');
    const filterForm = document.getElementById('filterForm');
    
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    }
    
    // Form submission loading state
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
                submitBtn.disabled = true;
            }
        });
    }
    
    // Add animation to cards
    const cards = document.querySelectorAll('.item-card');
    cards.forEach((card, index) => {
        card.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1}s`;
        card.style.opacity = '0';
    });

    // Auto-hide alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.custom-alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 8000);
});
</script>
@endpush
@endsection