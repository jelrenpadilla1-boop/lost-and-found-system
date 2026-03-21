@extends('layouts.app')

@section('title', 'Item Locations Map - Foundify')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
    --map-marker-lost: #ef4444;
    --map-marker-found: #10b981;
    --map-marker-user: #7c3aed;
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
    max-width: 1600px;
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

/* Map Card */
.map-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.map-card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.card-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header h5 i {
    color: var(--accent);
    font-size: 16px;
}

#map {
    height: 500px;
    width: 100%;
    background: var(--bg-soft);
    z-index: 1;
}

/* Sidebar Card */
.sidebar-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.sidebar-card:hover {
    box-shadow: var(--shadow-md);
}

.sidebar-header {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.sidebar-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sidebar-header h5 i {
    color: var(--accent);
    font-size: 16px;
}

.sidebar-body {
    padding: 20px;
}

/* Legend Section */
.legend-section {
    margin-bottom: 24px;
}

.legend-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.legend-title i {
    color: var(--accent);
    font-size: 12px;
}

.legend-item {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    margin-bottom: 10px;
    transition: var(--transition);
}

.legend-item:hover {
    border-color: var(--accent);
    transform: translateX(4px);
}

.legend-color {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.legend-color.lost {
    background: var(--error);
    box-shadow: 0 0 0 2px var(--error-soft);
}

.legend-color.found {
    background: var(--success);
    box-shadow: 0 0 0 2px var(--success-soft);
}

.legend-text {
    flex: 1;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-muted);
}

.legend-count {
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    color: var(--accent);
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

/* Filter Controls */
.filter-group {
    margin-bottom: 24px;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-label i {
    color: var(--accent);
    font-size: 12px;
}

.filter-select {
    width: 100%;
    padding: 10px 14px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 10px;
    color: var(--text-dark);
    font-size: 13px;
    transition: var(--transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 10px;
    transition: var(--transition);
}

.checkbox-item:hover {
    background: var(--bg-soft);
}

.checkbox-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
    cursor: pointer;
}

.checkbox-item label {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}

.checkbox-item label i {
    font-size: 13px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-map {
    font-size: 12px;
    font-weight: 600;
    padding: 12px;
    border-radius: 40px;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1px solid;
    background: transparent;
}

.btn-map.primary {
    border-color: var(--accent);
    color: var(--accent);
}

.btn-map.primary:hover {
    background: var(--accent);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.btn-map.secondary {
    border-color: var(--border-light);
    color: var(--text-muted);
}

.btn-map.secondary:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

/* Stats List */
.stats-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.stats-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-light);
    transition: var(--transition);
}

.stats-item:last-child {
    border-bottom: none;
}

.stats-item:hover {
    background: var(--bg-soft);
}

.stats-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-dark);
}

.stats-label i {
    font-size: 13px;
}

.stats-value {
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    color: var(--accent);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.stats-note {
    padding: 12px 16px;
    color: var(--text-muted);
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-top: 1px solid var(--border-light);
}

.stats-note i {
    color: var(--accent);
    font-size: 12px;
}

/* Table Card */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-top: 28px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.table-card:hover {
    box-shadow: var(--shadow-md);
}

.table-header {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.table-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-header h5 i {
    color: var(--accent);
    font-size: 16px;
}

.table-responsive {
    overflow-x: auto;
    max-height: 400px;
    overflow-y: auto;
}

.table-responsive::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: var(--bg-soft);
}

.table-responsive::-webkit-scrollbar-thumb {
    background: var(--border-light);
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    position: sticky;
    top: 0;
    z-index: 2;
    background: var(--bg-soft);
}

.data-table th {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table td {
    padding: 14px 16px;
    font-size: 13px;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-light);
}

.data-table tbody tr {
    transition: var(--transition);
    cursor: pointer;
}

.data-table tbody tr:hover {
    background: var(--bg-soft);
}

.data-table tbody tr.item-lost:hover {
    background: var(--error-soft);
}

.data-table tbody tr.item-found:hover {
    background: var(--success-soft);
}

/* Badges */
.badge-lost {
    background: var(--error-soft);
    color: var(--error);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    display: inline-block;
    text-transform: uppercase;
}

.badge-found {
    background: var(--success-soft);
    color: var(--success);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    display: inline-block;
    text-transform: uppercase;
}

.location-text {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--text-muted);
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.location-text i {
    font-size: 11px;
    flex-shrink: 0;
}

.location-text i.lost {
    color: var(--error);
}

.location-text i.found {
    color: var(--success);
}

/* View Button */
.btn-view {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid;
}

.btn-view.lost {
    border-color: var(--error-soft);
    color: var(--error);
}

.btn-view.lost:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
}

.btn-view.found {
    border-color: var(--success-soft);
    color: var(--success);
}

.btn-view.found:hover {
    background: var(--success);
    color: white;
    border-color: var(--success);
    transform: translateY(-2px);
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
    margin-bottom: 20px;
}

.empty-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Leaflet Customization */
.leaflet-popup-content-wrapper {
    background: var(--bg-card);
    color: var(--text-dark);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
    padding: 0;
    overflow: hidden;
}

.leaflet-popup-content {
    margin: 0;
    min-width: 260px;
}

.leaflet-popup-tip {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
}

.leaflet-popup-close-button {
    color: var(--text-muted) !important;
    width: 28px !important;
    height: 28px !important;
    font-size: 16px !important;
    right: 8px !important;
    top: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 50% !important;
    background: var(--bg-soft) !important;
    border: 1px solid var(--border-light) !important;
    transition: var(--transition) !important;
}

.leaflet-popup-close-button:hover {
    color: var(--error) !important;
    background: var(--error-soft) !important;
    transform: rotate(90deg) !important;
}

.leaflet-container {
    background: var(--bg-soft);
    font-family: 'Inter', sans-serif;
}

.leaflet-control-attribution {
    background: rgba(0, 0, 0, 0.5) !important;
    color: var(--text-muted) !important;
    font-size: 9px !important;
}

.leaflet-control-zoom {
    border: 1px solid var(--border-light) !important;
    background: var(--bg-card) !important;
}

.leaflet-control-zoom a {
    background: transparent !important;
    color: var(--text-dark) !important;
}

.leaflet-control-zoom a:hover {
    background: var(--accent-soft) !important;
    color: var(--accent) !important;
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

/* Responsive */
@media (max-width: 992px) {
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
    
    .empty-actions {
        flex-direction: column;
    }
    
    .empty-actions .btn {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .data-table thead {
        display: none;
    }

    .data-table tbody tr {
        display: block;
        margin-bottom: 12px;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
    }

    .data-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
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
        min-width: 80px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .location-text {
        max-width: 150px;
    }
}
</style>
@endpush

@section('content')
<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-map-marked-alt"></i>
                Item Map
            </h1>
            <p>View lost and found items on an interactive map</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Report Lost
            </a>
            <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                <i class="fas fa-check-circle"></i>
                Report Found
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-9">
            {{-- Map Card --}}
            <div class="map-card fade-in">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-map"></i>
                        Interactive Map
                    </h5>
                </div>
                <div id="map"></div>
            </div>
        </div>

        <div class="col-lg-3">
            {{-- Map Controls --}}
            <div class="sidebar-card fade-in">
                <div class="sidebar-header">
                    <h5>
                        <i class="fas fa-sliders-h"></i>
                        Map Controls
                    </h5>
                </div>
                <div class="sidebar-body">
                    {{-- Legend --}}
                    <div class="legend-section">
                        <div class="legend-title">
                            <i class="fas fa-info-circle"></i>
                            Legend
                        </div>
                        <div class="legend-item">
                            <div class="legend-color lost"></div>
                            <span class="legend-text">Lost Items</span>
                            <span class="legend-count">{{ $lostItems->count() }}</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color found"></div>
                            <span class="legend-text">Found Items</span>
                            <span class="legend-count">{{ $foundItems->count() }}</span>
                        </div>
                    </div>

                    {{-- Category Filter --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-tag"></i>
                            Category
                        </label>
                        <select id="categoryFilter" class="filter-select">
                            <option value="">All Categories</option>
                            @php
                                $categories = array_unique(array_merge(
                                    $lostItems->pluck('category')->toArray(),
                                    $foundItems->pluck('category')->toArray()
                                ));
                            @endphp
                            @foreach($categories as $category)
                                @if($category)
                                    <option value="{{ $category }}">{{ strtoupper($category) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Type Filter --}}
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-filter"></i>
                            Item Type
                        </label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="showLost" checked>
                                <label for="showLost">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Show Lost Items
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="showFound" checked>
                                <label for="showFound">
                                    <i class="fas fa-check-circle"></i>
                                    Show Found Items
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="action-buttons">
                        <button class="btn-map primary" onclick="getUserLocation()">
                            <i class="fas fa-location-arrow"></i>
                            My Location
                        </button>
                        <button class="btn-map secondary" onclick="fitAllMarkers()">
                            <i class="fas fa-expand"></i>
                            View All
                        </button>
                    </div>
                </div>
            </div>

            {{-- Map Stats --}}
            <div class="sidebar-card fade-in">
                <div class="sidebar-header">
                    <h5>
                        <i class="fas fa-chart-bar"></i>
                        Statistics
                    </h5>
                </div>
                <ul class="stats-list">
                    <li class="stats-item">
                        <span class="stats-label">
                            <i class="fas fa-exclamation-circle" style="color: var(--error);"></i>
                            Lost Items
                        </span>
                        <span class="stats-value">{{ $lostItems->count() }}</span>
                    </li>
                    <li class="stats-item">
                        <span class="stats-label">
                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            Found Items
                        </span>
                        <span class="stats-value">{{ $foundItems->count() }}</span>
                    </li>
                    <li class="stats-item">
                        <span class="stats-label">
                            <i class="fas fa-map-marker-alt" style="color: var(--accent);"></i>
                            Total
                        </span>
                        <span class="stats-value">{{ $lostItems->count() + $foundItems->count() }}</span>
                    </li>
                </ul>
                <div class="stats-note">
                    <i class="fas fa-info-circle"></i>
                    Items with location data shown
                </div>
            </div>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="table-card fade-in">
        <div class="table-header">
            <h5>
                <i class="fas fa-list"></i>
                Items List
            </h5>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="itemsTable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </thead>
                <tbody>
                    @forelse($lostItems as $item)
                    <tr class="item-lost" data-category="{{ $item->category }}" data-id="{{ $item->id }}" data-type="lost">
                        <td data-label="Type"><span class="badge-lost">LOST</span></td>
                        <td data-label="Item">{{ $item->item_name }}</td>
                        <td data-label="Category">{{ strtoupper($item->category) }}</td>
                        <td data-label="Location">
                            @if($item->lost_location)
                                <span class="location-text" title="{{ $item->lost_location }}">
                                    <i class="fas fa-map-marked-alt lost"></i>
                                    {{ Str::limit($item->lost_location, 30) }}
                                </span>
                            @elseif($item->latitude && $item->longitude)
                                <span class="location-text">
                                    <i class="fas fa-map-marker-alt lost"></i>
                                    {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                                </span>
                            @else
                                <span class="text-muted">No location</span>
                            @endif
                        </td>
                        <td data-label="Date">{{ $item->created_at->format('M d, Y') }}</td>
                        <td data-label="Actions">
                            <a href="{{ route('lost-items.show', $item) }}" class="btn-view lost">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach

                    @forelse($foundItems as $item)
                    <tr class="item-found" data-category="{{ $item->category }}" data-id="{{ $item->id }}" data-type="found">
                        <td data-label="Type"><span class="badge-found">FOUND</span></td>
                        <td data-label="Item">{{ $item->item_name }}</td>
                        <td data-label="Category">{{ strtoupper($item->category) }}</td>
                        <td data-label="Location">
                            @if($item->found_location)
                                <span class="location-text" title="{{ $item->found_location }}">
                                    <i class="fas fa-map-marked-alt found"></i>
                                    {{ Str::limit($item->found_location, 30) }}
                                </span>
                            @elseif($item->latitude && $item->longitude)
                                <span class="location-text">
                                    <i class="fas fa-map-marker-alt found"></i>
                                    {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                                </span>
                            @else
                                <span class="text-muted">No location</span>
                            @endif
                        </td>
                        <td data-label="Date">{{ $item->created_at->format('M d, Y') }}</td>
                        <td data-label="Actions">
                            <a href="{{ route('found-items.show', $item) }}" class="btn-view found">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach

                    @if($lostItems->isEmpty() && $foundItems->isEmpty())
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <h5>No Location Data</h5>
                                <p>Items need location coordinates to appear on the map.</p>
                                <div class="empty-actions">
                                    <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                        Report Lost
                                    </a>
                                    <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                                        <i class="fas fa-check-circle"></i>
                                        Report Found
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Notifications Container --}}
<div id="notificationsContainer"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let markers = [];
    let userMarker = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map with default view (Manila)
        map = L.map('map').setView([14.5995, 120.9842], 12);

        // Add tile layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);

        // Add markers
        @foreach($lostItems as $item)
            @if($item->latitude && $item->longitude)
                addMarker(
                    [{{ $item->latitude }}, {{ $item->longitude }}],
                    '{{ addslashes($item->item_name) }}',
                    '{{ addslashes($item->category) }}',
                    '{{ addslashes($item->description) }}',
                    '{{ $item->photo ? asset('storage/' . $item->photo) : '' }}',
                    'lost',
                    '{{ route('lost-items.show', $item) }}',
                    {{ $item->id }},
                    '{{ addslashes($item->lost_location) }}'
                );
            @endif
        @endforeach

        @foreach($foundItems as $item)
            @if($item->latitude && $item->longitude)
                addMarker(
                    [{{ $item->latitude }}, {{ $item->longitude }}],
                    '{{ addslashes($item->item_name) }}',
                    '{{ addslashes($item->category) }}',
                    '{{ addslashes($item->description) }}',
                    '{{ $item->photo ? asset('storage/' . $item->photo) : '' }}',
                    'found',
                    '{{ route('found-items.show', $item) }}',
                    {{ $item->id }},
                    '{{ addslashes($item->found_location) }}'
                );
            @endif
        @endforeach

        fitAllMarkers();

        // Add data-label attributes for responsive table
        document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            const headers = ['Type', 'Item', 'Category', 'Location', 'Date', 'Actions'];
            cells.forEach((cell, index) => {
                cell.setAttribute('data-label', headers[index]);
            });
        });
    });

    function addMarker(coords, name, category, description, photo, type, url, id, locationName) {
        const icon = L.divIcon({
            className: `${type}-marker`,
            html: `<i class="fas ${type === 'lost' ? 'fa-exclamation-circle' : 'fa-check-circle'}" style="font-size: 14px;"></i>`,
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -15]
        });

        const marker = L.marker(coords, { icon }).addTo(map);

        // Format location text
        let locationHtml = '';
        if (locationName && locationName.trim() !== '') {
            locationHtml = `<p style="margin: 0 0 6px 0;"><i class="fas ${type === 'lost' ? 'fa-map-marked-alt' : 'fa-map-marked-alt'}" style="color: ${type === 'lost' ? 'var(--error)' : 'var(--success)'}; margin-right: 6px;"></i> ${locationName}</p>`;
        } else {
            locationHtml = `<p style="margin: 0 0 6px 0;"><i class="fas fa-map-marker-alt" style="color: ${type === 'lost' ? 'var(--error)' : 'var(--success)'}; margin-right: 6px;"></i> ${coords[0].toFixed(4)}, ${coords[1].toFixed(4)}</p>`;
        }

        // Format description
        const descText = description.length > 100 ? description.substring(0, 100) + '...' : description;

        // Create popup
        const popupContent = `
            <div style="padding: 16px; min-width: 240px;">
                <h6 style="font-size: 16px; font-weight: 700; color: var(--text-dark); margin: 0 0 8px 0;">${name}</h6>
                <span class="badge-${type}" style="display: inline-block; margin-bottom: 12px;">${type === 'lost' ? 'LOST' : 'FOUND'}</span>
                <p style="margin: 0 0 6px 0;"><strong>Category:</strong> ${category.toUpperCase()}</p>
                ${locationHtml}
                <p style="margin: 0 0 12px 0; color: var(--text-muted); line-height: 1.5;">${descText}</p>
                ${photo ? `<img src="${photo}" style="width: 100%; border-radius: 8px; margin-bottom: 12px; max-height: 120px; object-fit: cover;" onerror="this.style.display='none'">` : ''}
                <a href="${url}" class="btn-view ${type}" style="display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 8px; border-radius: 40px; text-decoration: none; font-size: 12px; font-weight: 600;">
                    <i class="fas fa-eye"></i> View Details
                </a>
            </div>
        `;

        marker.bindPopup(popupContent);

        markers.push({
            marker: marker,
            type: type,
            category: category,
            id: id
        });
    }

    function fitAllMarkers() {
        if (markers.length === 0) return;
        const group = new L.featureGroup(markers.map(m => m.marker));
        map.fitBounds(group.getBounds().pad(0.1));
    }

    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const { latitude, longitude } = position.coords;

                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }

                    userMarker = L.marker([latitude, longitude], {
                        icon: L.divIcon({
                            className: 'user-marker',
                            html: '<i class="fas fa-user" style="font-size: 16px;"></i>',
                            iconSize: [36, 36],
                            iconAnchor: [18, 36],
                            popupAnchor: [0, -18]
                        })
                    }).addTo(map);

                    userMarker.bindPopup('<strong style="color: var(--accent);">Your Location</strong>').openPopup();
                    map.setView([latitude, longitude], 13);

                    showToast('Location found', 'success');
                },
                function(error) {
                    let message = 'Unable to get location. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message += 'Please enable location services.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message += 'Location unavailable.';
                            break;
                        case error.TIMEOUT:
                            message += 'Location request timed out.';
                            break;
                    }
                    showToast(message, 'error');
                }
            );
        } else {
            showToast('Geolocation not supported', 'error');
        }
    }

    // Filter controls
    document.getElementById('categoryFilter').addEventListener('change', filterMarkers);
    document.getElementById('showLost').addEventListener('change', filterMarkers);
    document.getElementById('showFound').addEventListener('change', filterMarkers);

    function filterMarkers() {
        const category = document.getElementById('categoryFilter').value;
        const showLost = document.getElementById('showLost').checked;
        const showFound = document.getElementById('showFound').checked;

        // Filter table rows
        document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
            const rowCategory = row.dataset.category;
            const rowType = row.dataset.type;

            let show = true;
            if (category && rowCategory !== category) show = false;
            if ((rowType === 'lost' && !showLost) || (rowType === 'found' && !showFound)) show = false;

            row.style.display = show ? '' : 'none';
        });

        // Filter markers
        markers.forEach(item => {
            const show = ((item.type === 'lost' && showLost) || (item.type === 'found' && showFound)) &&
                        (!category || item.category === category);

            if (show) {
                if (!map.hasLayer(item.marker)) {
                    item.marker.addTo(map);
                }
            } else {
                if (map.hasLayer(item.marker)) {
                    map.removeLayer(item.marker);
                }
            }
        });

        // Fit bounds to visible markers
        const visibleMarkers = markers.filter(m => map.hasLayer(m.marker));
        if (visibleMarkers.length > 0) {
            const group = new L.featureGroup(visibleMarkers.map(m => m.marker));
            map.fitBounds(group.getBounds().pad(0.1));
        }
    }

    // Click table row to focus marker
    document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('a')) return;

            const id = this.dataset.id;
            const type = this.dataset.type;
            const marker = markers.find(m => m.id == id && m.type === type);

            if (marker) {
                map.setView(marker.marker.getLatLng(), 15);
                marker.marker.openPopup();
            }
        });
    });

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