@extends('layouts.app')

@section('title', 'All Matches')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-exchange-alt text-info"></i> All Matches
        </h1>
        <p>Potential matches between lost and found items</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="count">{{ $matches->total() }}</div>
            <div class="label">Total Matches</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="count">{{ $matches->where('status', 'pending')->count() }}</div>
            <div class="label">Pending</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="count">{{ $matches->where('status', 'confirmed')->count() }}</div>
            <div class="label">Confirmed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <div class="icon">
                <i class="fas fa-times"></i>
            </div>
            <div class="count">{{ $matches->where('status', 'rejected')->count() }}</div>
            <div class="label">Rejected</div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('matches.index') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="min_score" class="form-label">Min Score</label>
                    <input type="number" class="form-control" id="min_score" name="min_score" 
                           value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="max_score" class="form-label">Max Score</label>
                    <input type="number" class="form-control" id="max_score" name="max_score" 
                           value="{{ request('max_score') }}" min="0" max="100" placeholder="100">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Matches List -->
<div class="row">
    @forelse($matches as $match)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Match #{{ $match->id }}</h5>
                <div>
                    <span class="badge @if($match->match_score >= 80) bg-success @elseif($match->match_score >= 60) bg-warning @else bg-info @endif">
                        {{ $match->match_score }}% Match
                    </span>
                    <span class="badge @if($match->status == 'confirmed') bg-success @elseif($match->status == 'rejected') bg-danger @else bg-warning @endif ms-1">
                        {{ ucfirst($match->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <!-- Lost Item -->
                    <div class="col-6">
                        <div class="card item-lost h-100">
                            <div class="card-body">
                                <h6 class="card-title text-danger">
                                    <i class="fas fa-exclamation-circle"></i> Lost Item
                                </h6>
                                <p class="mb-1"><strong>{{ $match->lostItem->item_name }}</strong></p>
                                <p class="mb-1 small text-muted">{{ \Illuminate\Support\Str::limit($match->lostItem->description, 50) }}</p>
                                <p class="mb-0 small">
                                    <i class="fas fa-user"></i> {{ $match->lostItem->user->name }}<br>
                                    <i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Found Item -->
                    <div class="col-6">
                        <div class="card item-found h-100">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="fas fa-check-circle"></i> Found Item
                                </h6>
                                <p class="mb-1"><strong>{{ $match->foundItem->item_name }}</strong></p>
                                <p class="mb-1 small text-muted">{{ \Illuminate\Support\Str::limit($match->foundItem->description, 50) }}</p>
                                <p class="mb-0 small">
                                    <i class="fas fa-user"></i> {{ $match->foundItem->user->name }}<br>
                                    <i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Match Details -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $match->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                
                                @if($match->status === 'pending')
                                    @can('confirm', $match)
                                    <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" 
                                                onclick="return confirm('Confirm this match? This will mark both items as completed.')">
                                            <i class="fas fa-check"></i> Confirm
                                        </button>
                                    </form>
                                    @endcan
                                    
                                    @can('reject', $match)
                                    <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Reject this match?')">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                <h4>No matches found</h4>
                <p class="text-muted">No potential matches have been identified yet.</p>
                <p class="text-muted">Report more items to increase matching possibilities.</p>
                <div class="mt-3">
                    <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
                        <i class="fas fa-exclamation-circle"></i> Report Lost Item
                    </a>
                    <a href="{{ route('found-items.create') }}" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Report Found Item
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($matches->hasPages())
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    {{ $matches->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Match Statistics -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-chart-pie"></i> Match Statistics
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="mb-2">
                        <canvas id="matchStatusChart" width="100" height="100"></canvas>
                    </div>
                    <h5>Status Distribution</h5>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info rounded-circle p-3 me-3">
                                <i class="fas fa-bullseye text-white"></i>
                            </div>
                            <div>
                                @php
                                    $highMatches = $matches->where('match_score', '>=', 80)->count();
                                @endphp
                                <h5 class="mb-0">{{ $highMatches }}</h5>
                                <p class="text-muted mb-0">High Confidence Matches (80%+)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning rounded-circle p-3 me-3">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <div>
                                @php
                                    $avgScore = $matches->count() > 0 ? $matches->avg('match_score') : 0;
                                @endphp
                                <h5 class="mb-0">{{ number_format($avgScore, 1) }}%</h5>
                                <p class="text-muted mb-0">Average Match Score</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle p-3 me-3">
                                <i class="fas fa-trophy text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $matches->where('status', 'confirmed')->count() }}</h5>
                                <p class="text-muted mb-0">Successful Recoveries</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize match status chart
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('matchStatusChart').getContext('2d');
        const matchStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $matches->where('status', 'pending')->count() }},
                        {{ $matches->where('status', 'confirmed')->count() }},
                        {{ $matches->where('status', 'rejected')->count() }}
                    ],
                    backgroundColor: [
                        '#f39c12',
                        '#2ecc71',
                        '#e74c3c'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
    
    // Auto-submit filter form on select change
    document.getElementById('status').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Loading animation for filter
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
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