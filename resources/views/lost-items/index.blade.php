@extends('layouts.app')

@section('title', 'Lost Items')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-exclamation-circle text-danger"></i> Lost Items
        </h1>
        <p>Browse all items that have been reported as lost</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
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
                <div class="stats-icon danger">
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
                <div class="stats-icon warning">
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
                <div class="stats-icon success">
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
                <div class="stats-icon primary">
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
        <i class="fas fa-filter me-2"></i>
        <strong>Active Filters:</strong>
        <div class="d-flex flex-wrap gap-2">
            @if(request('status'))
                <span class="badge bg-primary">
                    Status: {{ request('status') == 'pending' ? 'Missing' : (request('status') == 'found' ? 'Found' : 'Returned') }}
                </span>
            @endif
            @if(request('category'))
                <span class="badge bg-primary">Category: {{ request('category') }}</span>
            @endif
            @if(request('search'))
                <span class="badge bg-primary">Search: "{{ request('search') }}"</span>
            @endif
        </div>
    </div>
    <a href="{{ route('lost-items.index') }}" class="btn-close"></a>
</div>
@endif

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('lost-items.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                        <option value="Documents" {{ request('category') == 'Documents' ? 'selected' : '' }}>Documents</option>
                        <option value="Jewelry" {{ request('category') == 'Jewelry' ? 'selected' : '' }}>Jewelry</option>
                        <option value="Clothing" {{ request('category') == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                        <option value="Bags" {{ request('category') == 'Bags' ? 'selected' : '' }}>Bags</option>
                        <option value="Keys" {{ request('category') == 'Keys' ? 'selected' : '' }}>Keys</option>
                        <option value="Wallet" {{ request('category') == 'Wallet' ? 'selected' : '' }}>Wallet</option>
                        <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Missing</option>
                        <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by item name or description...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
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
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge @if($item->status == 'pending') bg-warning @elseif($item->status == 'found') bg-success @else bg-primary @endif">
                        {{ $item->status == 'pending' ? 'Missing' : ucfirst($item->status) }}
                    </span>
                    <span class="badge bg-info ms-1">{{ $item->category }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('lost-items.show', $item) }}">
                                <i class="fas fa-eye me-2"></i> View Details
                            </a>
                        </li>
                        @can('update', $item)
                        <li>
                            <a class="dropdown-item" href="{{ route('lost-items.edit', $item) }}">
                                <i class="fas fa-edit me-2"></i> Edit
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
                                    <i class="fas fa-trash me-2"></i> Delete
                                </button>
                            </form>
                        </li>
                        @endcan
                    </ul>
                </div>
            </div>
            
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" 
                             class="img-fluid rounded" 
                             style="max-height: 150px; object-fit: cover;"
                             alt="{{ $item->item_name }}">
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                             style="height: 150px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
                
                <h5 class="card-title mb-2">{{ $item->item_name }}</h5>
                <p class="card-text text-muted small mb-3">
                    {{ \Illuminate\Support\Str::limit($item->description, 100) }}
                </p>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> {{ $item->date_lost->format('M d, Y') }}
                        </small>
                    </div>
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-user"></i> {{ $item->user->name }}
                        </small>
                    </div>
                </div>
                
                @if($item->latitude && $item->longitude)
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                    </small>
                </div>
                @endif
                
                <div class="d-grid">
                    <a href="{{ route('lost-items.show', $item) }}" class="btn btn-outline-danger">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            </div>
            
            <div class="card-footer bg-transparent">
                <small class="text-muted">
                    <i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}
                </small>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-box fa-4x text-muted mb-3"></i>
                <h4>No Lost Items Yet</h4>
                <p class="text-muted">Be the first to report a lost item!</p>
                <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
                    <i class="fas fa-plus-circle"></i> Report Lost Item
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($lostItems->hasPages())
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    {{ $lostItems->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie"></i> Quick Stats
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('lost-items.index', ['status' => 'pending']) }}" 
                           class="text-decoration-none stats-link">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning rounded-circle p-3 me-3">
                                    <i class="fas fa-search text-white"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $pendingCount }}</h5>
                                    <p class="text-muted mb-0">Still Searching</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('lost-items.index', ['status' => 'found']) }}" 
                           class="text-decoration-none stats-link">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success rounded-circle p-3 me-3">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $foundCount }}</h5>
                                    <p class="text-muted mb-0">Successfully Found</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle p-3 me-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $activeReporters }}</h5>
                                <p class="text-muted mb-0">People Seeking Help</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #3b82f6;
        --primary-light: #eff6ff;
        --primary-dark: #1d4ed8;
        --secondary-color: #64748b;
        --light-color: #f8fafc;
        --dark-color: #1e293b;
        --border-color: #e2e8f0;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #0ea5e9;
    }

    .stats-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
    }
    
    .stats-link {
        display: block;
        text-decoration: none;
    }
    
    .stats-link:hover .stats-card {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
        cursor: pointer;
    }
    
    .stats-link.active .stats-card {
        border: 2px solid var(--primary-color);
        background-color: #f0f7ff;
    }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.2s ease;
    }
    
    .stats-link:hover .stats-icon {
        transform: scale(1.1);
    }
    
    .stats-icon.primary {
        background-color: var(--primary-light);
        color: var(--primary-color);
    }
    
    .stats-icon.success {
        background-color: #d1fae5;
        color: var(--success-color);
    }
    
    .stats-icon.warning {
        background-color: #fef3c7;
        color: var(--warning-color);
    }
    
    .stats-icon.danger {
        background-color: #fee2e2;
        color: var(--danger-color);
    }
    
    .stats-content {
        flex: 1;
    }
    
    .stats-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
        line-height: 1;
    }
    
    .stats-label {
        font-size: 0.875rem;
        color: var(--secondary-color);
        margin-top: 0.25rem;
    }
    
    .card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem 1.5rem;
    }
    
    .card-header h5 {
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
        font-size: 1.125rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .badge {
        padding: 0.375rem 0.75rem;
        font-weight: 500;
        font-size: 0.75rem;
        border-radius: 6px;
    }
    
    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .badge-info {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .badge-primary {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .badge-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }
    
    /* Button Styles - Fixed with direct colors */
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        background: white;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }

    .btn:hover {
        background: #f8fafc;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
        border: 1px solid #3b82f6;
    }

    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        border: 1px solid #ef4444;
    }

    .btn-danger:hover {
        background: #dc2626;
        border-color: #dc2626;
    }

    .btn-success {
        background: #10b981;
        color: white;
        border: 1px solid #10b981;
    }

    .btn-success:hover {
        background: #059669;
        border-color: #059669;
    }

    .btn-info {
        background: #0ea5e9;
        color: white;
        border: 1px solid #0ea5e9;
    }

    .btn-info:hover {
        background: #0284c7;
        border-color: #0284c7;
    }

    .btn-outline-primary {
        background: transparent;
        color: #3b82f6;
        border: 1px solid #3b82f6;
    }

    .btn-outline-primary:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-outline-danger {
        background: transparent;
        color: #ef4444;
        border: 1px solid #ef4444;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
    }

    .btn-outline-success {
        background: transparent;
        color: #10b981;
        border: 1px solid #10b981;
    }

    .btn-outline-success:hover {
        background: #10b981;
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-outline-secondary:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    /* Loading animation */
    .loading {
        display: inline-block;
        width: 30px;
        height: 30px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .form-control, .form-select {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-label {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--dark-color);
        margin: 0;
    }
    
    .page-title p {
        color: var(--secondary-color);
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
    }
    
    .page-actions {
        display: flex;
        gap: 12px;
    }
    
    .alert-info {
        background-color: #e0f2fe;
        border-color: #bae6fd;
        color: #0369a1;
    }
    
    .alert-info .btn-close {
        filter: invert(0.5);
    }
    
    .btn-group {
        display: flex;
        gap: 4px;
    }
    
    .btn-group .btn {
        border-radius: 6px;
    }
    
    .bg-warning {
        background-color: #f59e0b !important;
    }
    
    .bg-success {
        background-color: #10b981 !important;
    }
    
    .bg-primary {
        background-color: #3b82f6 !important;
    }
    
    .bg-info {
        background-color: #0ea5e9 !important;
    }
    
    .text-danger {
        color: #ef4444 !important;
    }
    
    .text-success {
        color: #10b981 !important;
    }
    
    .text-muted {
        color: #64748b !important;
    }
</style>
@endsection

@push('scripts')
<script>
    // Auto-submit filter form on select change
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
        
        // Debounced search
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterForm.submit();
                }, 500);
            });
        }
        
        // Loading animation for filter
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
        
        // Highlight active stat
        const currentStatus = '{{ request('status') }}';
        if (currentStatus) {
            document.querySelectorAll('.stats-link').forEach(link => {
                if (link.href.includes('status=' + currentStatus)) {
                    link.classList.add('active');
                }
            });
        }
    });
</script>
@endpush