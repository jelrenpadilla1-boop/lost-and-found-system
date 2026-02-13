@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0">Analytics Dashboard</h1>
                <p class="text-muted mb-0">System insights and statistics</p>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        Last 30 Days
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ number_format($stats['total_users']) }}</div>
                    <div class="stats-label">Total Users</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-search"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ number_format($stats['total_lost_items']) }}</div>
                    <div class="stats-label">Lost Items</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ number_format($stats['total_found_items']) }}</div>
                    <div class="stats-label">Found Items</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ number_format($stats['successful_matches']) }}</div>
                    <div class="stats-label">Successful Matches</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- User Registration Chart -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">User Registrations (Last 30 Days)</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <!-- This would be replaced with a real chart library like Chart.js -->
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <canvas id="registrationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recovery Rate -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Recovery Rate</h5>
                </div>
                <div class="card-body text-center">
                    <div class="display-1 fw-bold text-primary mb-3">
                        {{ number_format($stats['recovery_rate'], 1) }}%
                    </div>
                    <p class="text-muted">Of lost items are successfully recovered</p>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['recovery_rate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Distribution -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Lost Item Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($lostCategories as $category)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $category->category }}</div>
                                    <small class="text-muted">Most common lost item</small>
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $category->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Found Item Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($foundCategories as $category)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $category->category }}</div>
                                    <small class="text-muted">Most common found item</small>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $category->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Users and Match Success -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daily Active Users</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 200px;">
                        <!-- Active users chart -->
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <canvas id="activeUsersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Match Success Rate</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 200px;">
                        <!-- Match success chart -->
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <canvas id="matchSuccessChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Registration Chart
    const regCtx = document.getElementById('registrationChart').getContext('2d');
    const registrationChart = new Chart(regCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyRegistrations->keys()->toArray()) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($dailyRegistrations->values()->toArray()) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Active Users Chart
    const activeCtx = document.getElementById('activeUsersChart').getContext('2d');
    const activeUsersChart = new Chart(activeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($activeUsers->keys()->toArray()) !!},
            datasets: [{
                label: 'Active Users',
                data: {!! json_encode($activeUsers->values()->toArray()) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                borderColor: '#10b981',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Match Success Chart
    const matchCtx = document.getElementById('matchSuccessChart').getContext('2d');
    const matchSuccessChart = new Chart(matchCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($matchSuccess->pluck('date')->toArray()) !!},
            datasets: [
                {
                    label: 'Total Matches',
                    data: {!! json_encode($matchSuccess->pluck('total')->toArray()) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: 'Successful',
                    data: {!! json_encode($matchSuccess->pluck('successful')->toArray()) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush

<style>
    .chart-container {
        position: relative;
    }
    
    .display-1 {
        font-size: 4rem;
    }
</style>
@endsection