@extends('layouts.app')

@section('title', 'My Found Items - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<style>
/* ── MODERN DESIGN SYSTEM (matches dashboard) ───────────────── */
:root {
    --bg-white: #ffffff;
    --bg-soft: #faf9fe;
    --bg-card: #ffffff;
    --border-light: #edeef5;
    --border-soft: #e6e8f0;
    --accent: #7c3aed;
    --accent-light: #8b5cf6;
    --accent-soft: #ede9fe;
    --text-dark: #1e1b2f;
    --text-muted: #5b5b7a;
    --text-soft: #7e7b9a;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
    --radius-card: 20px;
    --radius-sm: 12px;
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    --success: #10b981;
    --success-soft: #d1fae5;
    --warning: #f59e0b;
    --warning-soft: #fef3c7;
    --error: #ef4444;
    --error-soft: #fee2e2;
    --info: #3b82f6;
    --info-soft: #dbeafe;
    --glass: rgba(0, 0, 0, 0.02);
    --glass-b: rgba(0, 0, 0, 0.04);
    --glass-hover: rgba(0, 0, 0, 0.06);
}

/* DARK MODE */
body.dark {
    --bg-white: #0f0c1a;
    --bg-soft: #12101c;
    --bg-card: #191624;
    --border-light: #2a2438;
    --border-soft: #2d2740;
    --accent: #a78bfa;
    --accent-light: #c4b5fd;
    --accent-soft: #2d2648;
    --text-dark: #f0edfc;
    --text-muted: #b4adcf;
    --text-soft: #938bb0;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.4), 0 4px 8px rgba(0, 0, 0, 0.2);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.5);
    --success-soft: rgba(16, 185, 129, 0.15);
    --warning-soft: rgba(245, 158, 11, 0.15);
    --error-soft: rgba(239, 68, 68, 0.15);
    --info-soft: rgba(59, 130, 246, 0.15);
    --glass: rgba(255, 255, 255, 0.03);
    --glass-b: rgba(255, 255, 255, 0.06);
    --glass-hover: rgba(255, 255, 255, 0.08);
}

/* Dashboard Container */
.dashboard-container {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 28px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-light);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.02em;
}

.page-title h1 i {
    color: var(--accent);
    font-size: 26px;
}

.page-title p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.page-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 40px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid transparent;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
}

.btn-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
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
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.stat-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    background: var(--accent-soft);
    color: var(--accent);
}

.stat-value {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1.2;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Card */
.card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-bottom: 32px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 18px 24px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.card-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h5 i {
    color: var(--accent);
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
    background: var(--bg-soft);
    padding: 16px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--border-light);
}

.data-table td {
    padding: 18px 20px;
    font-size: 13px;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
}

.data-table tr:hover td {
    background: var(--glass);
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
    border-radius: 12px;
    object-fit: cover;
    border: 1px solid var(--border-light);
}

.item-thumbnail-placeholder {
    width: 52px;
    height: 52px;
    background: var(--bg-soft);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    font-size: 22px;
}

.item-details h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.item-details span {
    font-size: 11px;
    color: var(--text-muted);
}

/* Badges */
.badge {
    font-size: 10px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.badge.category {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge.status-pending {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.status-approved {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-rejected {
    background: var(--error-soft);
    color: var(--error);
}

.badge.status-claimed {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-returned {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge.status-disposed {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

.match-badge {
    background: var(--accent-soft);
    color: var(--accent);
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Date & Location */
.date-text {
    font-size: 12px;
    color: var(--text-muted);
    white-space: nowrap;
}

.location-text, .coordinates-text {
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.location-text i, .coordinates-text i {
    color: var(--accent);
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
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid;
    background: var(--bg-card);
    cursor: pointer;
}

.action-btn.view {
    border-color: var(--accent-soft);
    color: var(--accent);
}

.action-btn.view:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.action-btn.edit {
    border-color: var(--info-soft);
    color: var(--info);
}

.action-btn.edit:hover {
    background: var(--info);
    color: white;
    border-color: var(--info);
    transform: translateY(-2px);
}

.action-btn.delete {
    border-color: var(--error-soft);
    color: var(--error);
}

.action-btn.delete:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
}

/* Pagination */
.pagination-wrapper {
    padding: 20px 24px;
    background: var(--bg-soft);
    border-top: 1px solid var(--border-light);
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
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition);
    font-size: 13px;
}

.page-link:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: var(--accent);
    border-color: var(--accent);
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
    background: var(--bg-soft);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 2px dashed var(--border-light);
    color: var(--accent);
    font-size: 32px;
}

.empty-state h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 14px;
    color: var(--text-muted);
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
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h5 i {
    color: var(--accent);
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
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 20px;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.match-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
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
    border-radius: 30px;
}

.score-high {
    background: var(--success-soft);
    color: var(--success);
}

.score-medium {
    background: var(--warning-soft);
    color: var(--warning);
}

.score-low {
    background: var(--info-soft);
    color: var(--info);
}

.match-time {
    font-size: 11px;
    color: var(--text-muted);
}

.match-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 18px;
}

.match-item {
    background: var(--bg-soft);
    padding: 12px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
}

.match-item small {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-muted);
    display: block;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.match-item strong {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-dark);
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
    border-radius: 40px;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid;
}

.btn-view-match {
    border-color: var(--accent-soft);
    color: var(--accent);
    background: transparent;
}

.btn-view-match:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.btn-view-item.lost {
    border-color: var(--error-soft);
    color: var(--error);
    background: transparent;
}

.btn-view-item.lost:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
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
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    margin-bottom: 12px;
    box-shadow: var(--shadow-md);
    animation: slideInRight 0.3s ease;
}

.toast-body {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    font-size: 13px;
    color: var(--text-dark);
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
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    font-size: 18px;
    transition: var(--transition);
}

.toast-close:hover {
    color: var(--error);
    transform: rotate(90deg);
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
        border: 1px solid var(--border-light);
        border-radius: var(--radius-card);
        background: var(--bg-card);
    }

    .data-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 18px;
        border-bottom: 1px solid var(--border-light);
    }

    .data-table tbody td:last-child {
        border-bottom: none;
    }

    .data-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-dark);
        margin-right: 15px;
        min-width: 100px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
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
        padding: 20px;
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

    {{-- Stats Cards --}}
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-boxes"></i></div>
            <div><div class="stat-value">{{ $foundItems->total() }}</div><div class="stat-label">Total Items</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div><div class="stat-value">{{ $pendingCount }}</div><div class="stat-label">Pending</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div><div class="stat-value">{{ $approvedCount ?? 0 }}</div><div class="stat-label">Approved</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div><div class="stat-value">{{ $rejectedCount ?? 0 }}</div><div class="stat-label">Rejected</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-handshake"></i></div>
            <div><div class="stat-value">{{ $claimedCount }}</div><div class="stat-label">Claimed</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-home"></i></div>
            <div><div class="stat-value">{{ $returnedCount ?? 0 }}</div><div class="stat-label">Returned</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-times"></i></div>
            <div><div class="stat-value">{{ $disposedCount }}</div><div class="stat-label">Disposed</div></div>
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
                <i class="fas fa-exchange-alt" style="color: var(--accent);"></i>
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
            
            // Simulate export
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
    const iconColor = type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--error)' : 'var(--accent)';
    
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