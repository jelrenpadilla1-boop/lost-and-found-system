@extends('layouts.app')

@section('title', 'My Lost Items')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-box text-danger"></i> My Lost Items
        </h1>
        <p>Items you have reported as lost</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
            <i class="fas fa-plus-circle"></i> Report New Item
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="count">{{ $lostItems->total() }}</div>
            <div class="label">Total Items</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="count">{{ $lostItems->where('status', 'pending')->count() }}</div>
            <div class="label">Still Missing</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="count">{{ $lostItems->where('status', 'found')->count() }}</div>
            <div class="label">Found</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="icon">
                <i class="fas fa-home"></i>
            </div>
            <div class="count">{{ $lostItems->where('status', 'returned')->count() }}</div>
            <div class="label">Returned</div>
        </div>
    </div>
</div>

<!-- Items Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Lost Items List</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="exportBtn">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($lostItems->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover" id="lostItemsTable">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Date Lost</th>
                        <th>Status</th>
                        <th>Matches</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lostItems as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($item->photo)
                                    <img src="{{ asset('storage/' . $item->photo) }}" 
                                         class="rounded me-3" 
                                         width="40" 
                                         height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $item->item_name }}</h6>
                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 30) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $item->category }}</span>
                        </td>
                        <td>{{ $item->date_lost->format('M d, Y') }}</td>
                        <td>
                            @if($item->status === 'pending')
                                <span class="badge bg-warning">Missing</span>
                            @elseif($item->status === 'found')
                                <span class="badge bg-success">Found</span>
                            @else
                                <span class="badge bg-primary">Returned</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $matchCount = $item->matches()->count();
                            @endphp
                            @if($matchCount > 0)
                                <span class="badge bg-primary">
                                    <i class="fas fa-exchange-alt"></i> {{ $matchCount }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('lost-items.show', $item) }}" class="btn btn-outline-danger">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('lost-items.edit', $item) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('lost-items.destroy', $item) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
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
        
        <div class="d-flex justify-content-center mt-4">
            {{ $lostItems->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-box fa-4x text-muted mb-3"></i>
            <h4>No Lost Items Yet</h4>
            <p class="text-muted">You haven't reported any lost items yet.</p>
            <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
                <i class="fas fa-plus-circle"></i> Report Your First Lost Item
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Recent Matches -->
@php
    $recentMatches = Auth::user()->lostItems()
        ->with(['matches' => function($query) {
            $query->latest()->take(5);
        }])
        ->get()
        ->pluck('matches')
        ->flatten()
        ->take(5);
@endphp

@if($recentMatches->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-exchange-alt text-info"></i> Recent Matches
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($recentMatches as $match)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge @if($match->match_score >= 80) bg-success @elseif($match->match_score >= 60) bg-warning @else bg-info @endif">
                                    {{ $match->match_score }}% Match
                                </span>
                                <span class="badge @if($match->status == 'confirmed') bg-success @elseif($match->status == 'rejected') bg-danger @else bg-warning @endif ms-1">
                                    {{ ucfirst($match->status) }}
                                </span>
                            </div>
                            <small class="text-muted">{{ $match->created_at->diffForHumans() }}</small>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">Your Item:</small>
                                <strong>{{ $match->lostItem->item_name }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Found Item:</small>
                                <strong>{{ $match->foundItem->item_name }}</strong>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i> View Match
                            </a>
                            <a href="{{ route('found-items.show', $match->foundItem) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-external-link-alt"></i> View Found Item
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
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
            // In a real application, this would trigger a CSV/Excel download
            showToast('Export started. Your file will download shortly.', 'info');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1500);
    });
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const container = document.getElementById('notificationsContainer');
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after hiding
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
</script>
@endpush