@extends('layouts.app')

@section('title', 'Lost Items')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-exclamation-circle" style="color: var(--primary);"></i> Lost Items
        </h1>
        <p>Browse all items that have been reported as lost</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Report Lost Item
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => '', 'category' => ''])) }}" 
           class="text-decoration-none stats-link">
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
    <div class="col-md-3">
        <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => 'pending', 'category' => ''])) }}" 
           class="text-decoration-none stats-link">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $pendingCount }}</div>
                    <div class="stats-label">Still Missing</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => 'found', 'category' => ''])) }}" 
           class="text-decoration-none stats-link">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $foundCount }}</div>
                    <div class="stats-label">Found</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('lost-items.index', array_merge(request()->query(), ['status' => 'returned', 'category' => ''])) }}" 
           class="text-decoration-none stats-link">
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

<!-- Active Filter Indicator -->
@if(request('status') || request('category') || request('search'))
<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center flex-wrap gap-2">
        <i class="fas fa-filter me-2" style="color: var(--primary);"></i>
        <strong style="color: var(--primary);">Active Filters:</strong>
        <div class="d-flex flex-wrap gap-2">
            @if(request('status'))
                <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    Status: {{ request('status') == 'pending' ? 'Missing' : (request('status') == 'found' ? 'Found' : 'Returned') }}
                </span>
            @endif
            @if(request('category'))
                <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    Category: {{ request('category') }}
                </span>
            @endif
            @if(request('search'))
                <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                    Search: "{{ request('search') }}"
                </span>
            @endif
        </div>
    </div>
    <a href="{{ route('lost-items.index') }}" class="btn-close" style="filter: invert(1);"></a>
</div>
@endif

<!-- Filter Section -->
<div class="filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('lost-items.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="category" class="form-label">
                        <i class="fas fa-tag" style="color: var(--primary);"></i> Category
                    </label>
                    <select class="form-select" id="category" name="category">
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
                <div class="col-md-3">
                    <label for="status" class="form-label">
                        <i class="fas fa-circle" style="color: var(--primary);"></i> Status
                    </label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Missing</option>
                        <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">
                        <i class="fas fa-search" style="color: var(--primary);"></i> Search
                    </label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by item name, description, or location...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Items Grid -->
<div class="row" id="lostItemsGrid">
    @forelse($lostItems as $item)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="item-card">
            <div class="card-header">
                <div class="header-badges">
                    <span class="status-badge status-{{ $item->status }}">
                        {{ $item->status == 'pending' ? 'Missing' : ucfirst($item->status) }}
                    </span>
                    <span class="category-badge">{{ $item->category }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li>
                            <a class="dropdown-item" href="{{ route('lost-items.show', $item) }}">
                                <i class="fas fa-eye me-2" style="color: var(--primary);"></i> View Details
                            </a>
                        </li>
                        @can('update', $item)
                        <li>
                            <a class="dropdown-item" href="{{ route('lost-items.edit', $item) }}">
                                <i class="fas fa-edit me-2" style="color: var(--primary);"></i> Edit
                            </a>
                        </li>
                        @endcan
                        @can('delete', $item)
                        <li><hr class="dropdown-divider" style="border-color: #333;"></li>
                        <li>
                            <form action="{{ route('lost-items.destroy', $item) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-trash me-2"></i> Delete
                                </button>
                            </form>
                        </li>
                        @endcan
                    </ul>
                </div>
            </div>
            
            <div class="card-body">
                <div class="image-container">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" 
                             class="item-image" 
                             alt="{{ $item->item_name }}">
                    @else
                        <div class="placeholder-image">
                            <i class="fas fa-image fa-3x" style="color: var(--primary); opacity: 0.5;"></i>
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
                
                {{-- Location Display --}}
                @if($item->lost_location)
                <div class="location-badge" title="{{ $item->lost_location }}">
                    <i class="fas fa-map-marked-alt"></i>
                    {{ \Illuminate\Support\Str::limit($item->lost_location, 25) }}
                </div>
                @elseif($item->latitude && $item->longitude)
                <div class="location-badge">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                </div>
                @endif
                
                <a href="{{ route('lost-items.show', $item) }}" class="view-btn">
                    <i class="fas fa-eye"></i> View Details
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="card-footer">
                <i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box"></i>
            </div>
            <h4>No Lost Items Yet</h4>
            <p>Be the first to report a lost item!</p>
            <div class="empty-actions">
                <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Report Lost Item
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>



<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-12">
        <div class="stats-wrapper">
            <div class="stats-header">
                <i class="fas fa-chart-pie" style="color: var(--primary);"></i>
                <h5>Quick Stats</h5>
            </div>
            <div class="stats-grid">
                <a href="{{ route('lost-items.index', ['status' => 'pending']) }}" 
                   class="stat-item">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $pendingCount }}</div>
                        <div class="stat-label">Still Searching</div>
                    </div>
                </a>
                
                <a href="{{ route('lost-items.index', ['status' => 'found']) }}" 
                   class="stat-item">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $foundCount }}</div>
                        <div class="stat-label">Successfully Found</div>
                    </div>
                </a>
                
                <div class="stat-item">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $activeReporters }}</div>
                        <div class="stat-label">People Seeking Help</div>
                    </div>
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

/* Stats Cards */
.stats-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
}

.stats-link:hover .stats-card {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.stats-link:hover .stats-card::before {
    opacity: 0.1;
}

.stats-icon {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    transition: var(--transition);
    position: relative;
    z-index: 1;
}

.stats-link:hover .stats-icon {
    transform: scale(1.1) rotate(360deg);
}

.stats-content {
    flex: 1;
    position: relative;
    z-index: 1;
}

.stats-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
}

.stats-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

/* Filter Card */
.filter-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 1.5rem;
    transition: var(--transition);
}

.filter-card:hover {
    border-color: var(--primary);
    box-shadow: 0 5px 20px var(--primary-glow);
}

.filter-card .card-body {
    padding: 1.5rem;
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

/* Form Controls */
.form-label {
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label i {
    color: var(--primary);
    font-size: 0.875rem;
}

.form-select, .form-control {
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    transition: var(--transition);
}

.form-select:focus, .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    outline: none;
    background: var(--bg-card);
}

.form-select option {
    background: var(--bg-header);
    color: var(--text-primary);
}

.btn-group {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.75rem 1.25rem;
    border-radius: 30px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
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

/* Item Cards */
.item-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    position: relative;
}

.item-card::before {
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

.item-card:hover {
    transform: translateY(-8px);
    border-color: var(--primary);
    box-shadow: 0 15px 35px var(--primary-glow);
}

.item-card:hover::before {
    opacity: 0.1;
}

.card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.status-badge {
    padding: 0.375rem 0.875rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    display: inline-block;
}

.status-pending {
    background: linear-gradient(135deg, #ffa500, #ffb52e);
    box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
}

.status-found {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    box-shadow: 0 0 15px rgba(0, 250, 154, 0.3);
    color: black;
}

.status-returned {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    box-shadow: 0 0 15px var(--primary-glow);
}

.category-badge {
    padding: 0.375rem 0.875rem;
    background: var(--bg-header);
    border-radius: 30px;
    font-size: 0.75rem;
    color: var(--primary);
    border: 1px solid var(--primary);
}

/* Dropdown Menu */
.dropdown-menu-dark {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.dropdown-item {
    color: var(--text-muted);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: var(--transition);
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateX(5px);
}

.dropdown-item.text-danger:hover {
    background: linear-gradient(135deg, var(--error), #ff6b6b);
    color: white;
}

.dropdown-divider {
    border-top: 1px solid var(--border-color);
}

/* Image Container */
.image-container {
    text-align: center;
    margin-bottom: 1.25rem;
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

.placeholder-image {
    width: 100%;
    height: 160px;
    background: var(--bg-header);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--border-color);
}

/* Item Content */
.item-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.item-description {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.item-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    color: var(--text-muted);
    font-size: 0.75rem;
}

.meta-item i {
    color: var(--primary);
    font-size: 0.75rem;
}

.location-badge {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    padding: 0.375rem 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    color: var(--text-muted);
    font-size: 0.75rem;
    margin-bottom: 1rem;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.location-badge i {
    color: var(--primary);
    flex-shrink: 0;
}

/* View Button */
.view-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.75rem;
    background: transparent;
    border: 2px solid var(--primary);
    border-radius: 12px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.view-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: var(--primary-glow);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.view-btn:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--primary-glow);
}

.view-btn:hover::before {
    width: 300px;
    height: 300px;
}

.view-btn i:last-child {
    transition: transform 0.3s ease;
}

.view-btn:hover i:last-child {
    transform: translateX(5px);
}

/* Card Footer */
.card-footer {
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
    padding: 0.875rem 1.25rem;
    color: var(--text-muted);
    font-size: 0.75rem;
}

.card-footer i {
    color: var(--primary);
    margin-right: 0.375rem;
}

/* Empty State */
.empty-state {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
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
    box-shadow: 0 0 30px var(--primary-glow);
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
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.empty-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

/* Pagination */
.pagination-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 0.25rem;
    list-style: none;
    padding: 0;
    margin: 0;
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
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 8px !important;
    text-decoration: none;
    transition: var(--transition);
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
.stats-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
}

.stats-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.stats-header h5 {
    color: var(--text-primary);
    margin: 0;
    font-size: 1.125rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-header);
    border-radius: 12px;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid transparent;
}

.stat-item:hover {
    border-color: var(--primary);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px var(--primary-glow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    transition: var(--transition);
}

.stat-item:hover .stat-icon {
    transform: scale(1.1) rotate(360deg);
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Alert */
.alert-info {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    color: var(--text-primary);
    border-radius: 12px;
    padding: 1rem 1.25rem;
}

.btn-close {
    filter: invert(1);
    opacity: 0.5;
    transition: var(--transition);
    background: transparent;
    border: none;
    cursor: pointer;
}

.btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
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

.item-card {
    animation: fadeIn 0.5s ease forwards;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .stat-item {
        width: 100%;
    }

    .item-meta {
        flex-direction: column;
        gap: 0.5rem;
    }

    .header-badges {
        flex-wrap: wrap;
    }
    
    .empty-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
    
    .location-badge {
        max-width: 100%;
    }
}
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
                    submitBtn.disabled = true;
                    
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 2000);
                }
            });
        }
        
        const currentStatus = '{{ request('status') }}';
        if (currentStatus) {
            document.querySelectorAll('.stats-link').forEach(link => {
                if (link.href.includes('status=' + currentStatus)) {
                    link.classList.add('active');
                }
            });
        }

        // Add animation to cards
        const cards = document.querySelectorAll('.item-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endpush