@extends('layouts.app')

@section('title', 'Item Locations Map')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    :root {
        --map-marker-lost: #ff4444;
        --map-marker-found: #00fa9a;
        --map-marker-user: var(--primary);
        --bg-table-header: #1a1a1a;
        --bg-table-row: #0a0a0a;
        --bg-table-row-hover: #1a1a1a;
        --border-color: #333;
        --text-primary: #ffffff;
        --text-secondary: #e0e0e0;
        --text-muted: #888;
    }

    #map {
        height: 600px;
        border-radius: 16px;
        z-index: 1;
        background: #f5f5f5;
    }

    /* Custom Legend */
    .legend-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 1rem;
    }

    .legend-title {
        color: white;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-title i {
        color: var(--primary);
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        background: #222;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .legend-item:hover {
        transform: translateX(5px);
        border-color: var(--primary);
    }

    .legend-color {
        width: 24px;
        height: 24px;
        margin-right: 12px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        box-shadow: 0 0 15px currentColor;
    }

    .legend-color.lost {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        box-shadow: 0 0 15px rgba(255, 68, 68, 0.5);
    }

    .legend-color.found {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        box-shadow: 0 0 15px rgba(0, 250, 154, 0.5);
    }

    .legend-text {
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .legend-count {
        margin-left: auto;
        background: #333;
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Filter Controls */
    .filter-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    .filter-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1rem 1.25rem;
    }

    .filter-header h5 {
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-header i {
        color: var(--primary);
    }

    .filter-body {
        padding: 1.25rem;
    }

    .filter-label {
        color: white;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label i {
        color: var(--primary);
    }

    .form-select {
        background: #222;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 0.75rem;
        color: white;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
        outline: none;
    }

    .form-check-input {
        background: #222;
        border: 2px solid #333;
        width: 1.2rem;
        height: 1.2rem;
        margin-right: 0.5rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-label {
        color: #a0a0a0;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-map {
        padding: 0.75rem 1rem;
        border-radius: 30px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        border: 2px solid transparent;
    }

    .btn-map.primary {
        background: transparent;
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-map.primary:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px var(--primary-glow);
    }

    .btn-map.secondary {
        background: transparent;
        border-color: #666;
        color: #a0a0a0;
    }

    .btn-map.secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    /* Stats Card */
    .stats-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
    }

    .stats-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .stats-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #333;
        transition: all 0.3s ease;
    }

    .stats-item:last-child {
        border-bottom: none;
    }

    .stats-item:hover {
        background: #222;
    }

    .stats-label {
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stats-label i {
        color: var(--primary);
    }

    .stats-badge {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 0 15px var(--primary-glow);
    }

    .stats-note {
        padding: 1rem 1.25rem;
        color: #a0a0a0;
        font-size: 0.875rem;
    }

    .stats-note i {
        color: var(--primary);
        margin-right: 0.5rem;
    }

    /* Items Table - Enhanced Dark Theme */
    .table-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        margin-top: 2rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .table-header {
        background: #222;
        border-bottom: 1px solid #333;
        padding: 1.25rem 1.5rem;
    }

    .table-header h5 {
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .table-header i {
        color: var(--primary);
        font-size: 1.25rem;
    }

    .table-responsive {
        padding: 0;
        background: #1a1a1a;
        max-height: 500px;
        overflow-y: auto;
    }

    /* Custom Scrollbar for Table */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #222;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
        box-shadow: 0 0 10px var(--primary-glow);
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--primary-light);
    }

    .dark-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--bg-table-row);
        border-radius: 0;
        overflow: hidden;
    }

    .dark-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .dark-table tbody tr {
        background: var(--bg-table-row);
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
    }

    .dark-table tbody tr:hover {
        background: var(--bg-table-row-hover);
        transform: translateX(5px);
        box-shadow: -5px 0 15px var(--primary-glow);
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

    .item-lost {
        border-left: 4px solid #ff4444;
    }

    .item-found {
        border-left: 4px solid #00fa9a;
    }

    .badge-lost {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        color: white;
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(255, 68, 68, 0.3);
    }

    .badge-found {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        color: black;
        padding: 0.375rem 0.875rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(0, 250, 154, 0.3);
    }

    .location-badge {
        color: var(--text-secondary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .location-badge i {
        margin-right: 0.25rem;
        font-size: 0.875rem;
    }

    .location-badge i.lost {
        color: #ff4444;
        text-shadow: 0 0 8px rgba(255, 68, 68, 0.5);
    }

    .location-badge i.found {
        color: #00fa9a;
        text-shadow: 0 0 8px rgba(0, 250, 154, 0.5);
    }

    .btn-view {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 0.375rem 1rem;
        border-radius: 30px;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        font-weight: 500;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px var(--primary-glow);
        border-color: transparent;
    }

    .btn-view.lost:hover {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        border-color: #ff4444;
        color: white;
        box-shadow: 0 5px 15px rgba(255, 68, 68, 0.4);
    }

    .btn-view.found:hover {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        border-color: #00fa9a;
        color: black;
        box-shadow: 0 5px 15px rgba(0, 250, 154, 0.4);
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #1a1a1a;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        background: #222;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 2px solid var(--primary);
        box-shadow: 0 0 20px var(--primary-glow);
    }

    .empty-icon i {
        font-size: 3rem;
        color: var(--primary);
    }

    .empty-state h5 {
        color: white;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #a0a0a0;
        margin-bottom: 1.5rem;
    }

    .empty-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    /* Marker Styles */
    .lost-marker {
        background: linear-gradient(135deg, #ff4444, #ff6b6b);
        border: 3px solid white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(255, 68, 68, 0.5);
    }
    
    .found-marker {
        background: linear-gradient(135deg, #00fa9a, #00ff7f);
        border: 3px solid white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(0, 250, 154, 0.5);
    }
    
    .user-marker {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: 3px solid white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 15px var(--primary-glow);
    }
    
    .lost-marker i,
    .found-marker i,
    .user-marker i {
        color: white;
        font-size: 14px;
    }

    .found-marker i {
        color: black;
    }
    
    .marker-popup {
        min-width: 250px;
        background: white;
        color: #333;
        border-radius: 8px;
        padding: 0.5rem;
    }
    
    .marker-popup img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 0.5rem;
        border: 1px solid #ddd;
    }

    .marker-popup h6 {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .marker-popup p {
        color: #666;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .marker-popup .badge-lost,
    .marker-popup .badge-found {
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    /* Toast Notifications */
    #notificationsContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        background: #1a1a1a;
        border: 1px solid var(--primary);
        border-radius: 12px;
        min-width: 300px;
    }

    .toast-body {
        color: white;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-close-white {
        filter: invert(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .empty-actions {
            flex-direction: column;
        }

        .btn-view {
            width: 100%;
            justify-content: center;
        }

        .dark-table thead {
            display: none;
        }

        .dark-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #333;
            border-radius: 12px;
            background: var(--bg-table-row);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .dark-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border: none;
            border-bottom: 1px solid #333;
            background: var(--bg-table-row);
        }

        .dark-table tbody td:last-child {
            border-bottom: none;
        }

        .dark-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: white;
            margin-right: 1rem;
            min-width: 80px;
            font-size: 0.875rem;
        }

        .location-badge {
            justify-content: flex-end;
            width: 100%;
        }

        .badge-lost,
        .badge-found {
            min-width: 60px;
            text-align: center;
        }
    }

    /* Leaflet Popup Customization */
    .leaflet-popup-content-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(0,0,0,0.2);
    }

    .leaflet-popup-tip {
        background: white;
    }

    .leaflet-popup-close-button {
        color: #666 !important;
    }

    .leaflet-popup-close-button:hover {
        color: var(--primary) !important;
    }

    /* Animation */
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

    .dark-table tbody tr {
        animation: fadeIn 0.3s ease forwards;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-map-marked-alt" style="color: var(--primary);"></i> Item Locations Map
        </h1>
        <p>View lost and found items on an interactive map</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
            <i class="fas fa-exclamation-circle"></i> Report Lost
        </a>
        <a href="{{ route('found-items.create') }}" class="btn btn-primary">
            <i class="fas fa-check-circle"></i> Report Found
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="map-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map" style="color: var(--primary);"></i> Interactive Map
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="map"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <!-- Map Controls -->
        <div class="filter-card">
            <div class="filter-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter"></i> Map Controls
                </h5>
            </div>
            <div class="filter-body">
                <!-- Legend -->
                <div class="legend-card mb-4">
                    <div class="legend-title">
                        <i class="fas fa-info-circle"></i> Map Legend
                    </div>
                    <div class="legend-item">
                        <div class="legend-color lost">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <span class="legend-text">Lost Items</span>
                        <span class="legend-count">{{ $lostItems->count() }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color found">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="legend-text">Found Items</span>
                        <span class="legend-count">{{ $foundItems->count() }}</span>
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="mb-4">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i> Filter by Category
                    </label>
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        @php
                            $categories = array_unique(array_merge(
                                $lostItems->pluck('category')->toArray(),
                                $foundItems->pluck('category')->toArray()
                            ));
                        @endphp
                        @foreach($categories as $category)
                            @if($category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <!-- Type Filter -->
                <div class="mb-4">
                    <label class="filter-label">
                        <i class="fas fa-filter"></i> Filter by Type
                    </label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="showLost" checked>
                        <label class="form-check-label" for="showLost">
                            <i class="fas fa-exclamation-circle" style="color: #ff4444;"></i> Show Lost Items
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showFound" checked>
                        <label class="form-check-label" for="showFound">
                            <i class="fas fa-check-circle" style="color: #00fa9a;"></i> Show Found Items
                        </label>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <button class="btn-map primary mb-2" onclick="getUserLocation()">
                    <i class="fas fa-location-arrow"></i> Go to My Location
                </button>
                
                <button class="btn-map secondary" onclick="fitAllMarkers()">
                    <i class="fas fa-expand"></i> View All Items
                </button>
            </div>
        </div>
        
        <!-- Map Stats -->
        <div class="stats-card">
            <div class="filter-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Map Statistics
                </h5>
            </div>
            <ul class="stats-list">
                <li class="stats-item">
                    <span class="stats-label">
                        <i class="fas fa-exclamation-circle" style="color: #ff4444;"></i> Lost Items
                    </span>
                    <span class="stats-badge">{{ $lostItems->count() }}</span>
                </li>
                <li class="stats-item">
                    <span class="stats-label">
                        <i class="fas fa-check-circle" style="color: #00fa9a;"></i> Found Items
                    </span>
                    <span class="stats-badge">{{ $foundItems->count() }}</span>
                </li>
                <li class="stats-item">
                    <span class="stats-label">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Total Markers
                    </span>
                    <span class="stats-badge">{{ $lostItems->count() + $foundItems->count() }}</span>
                </li>
                <li class="stats-note">
                    <i class="fas fa-info-circle"></i>
                    Only items with location data are shown.
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Items Table - Enhanced Dark Theme -->
<div class="table-card">
    <div class="table-header">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Items List
        </h5>
    </div>
    <div class="table-responsive">
        <table class="dark-table" id="itemsTable">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lostItems as $item)
                <tr class="item-lost" data-category="{{ $item->category }}" data-id="{{ $item->id }}">
                    <td data-label="Type">
                        <span class="badge-lost">Lost</span>
                    </td>
                    <td data-label="Item Name">{{ $item->item_name }}</td>
                    <td data-label="Category">{{ $item->category }}</td>
                    <td data-label="Location">
                        @if($item->latitude && $item->longitude)
                            <span class="location-badge">
                                <i class="fas fa-map-marker-alt lost"></i>
                                {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                            </span>
                        @else
                            <span class="text-muted">No location</span>
                        @endif
                    </td>
                    <td data-label="Date">
                        @if($item->created_at)
                            {{ $item->created_at->format('M d, Y') }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td data-label="Actions">
                        <a href="{{ route('lost-items.show', $item) }}" 
                           class="btn-view lost">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
                
                @foreach($foundItems as $item)
                <tr class="item-found" data-category="{{ $item->category }}" data-id="{{ $item->id }}">
                    <td data-label="Type">
                        <span class="badge-found">Found</span>
                    </td>
                    <td data-label="Item Name">{{ $item->item_name }}</td>
                    <td data-label="Category">{{ $item->category }}</td>
                    <td data-label="Location">
                        @if($item->latitude && $item->longitude)
                            <span class="location-badge">
                                <i class="fas fa-map-marker-alt found"></i>
                                {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                            </span>
                        @else
                            <span class="text-muted">No location</span>
                        @endif
                    </td>
                    <td data-label="Date">
                        @if($item->created_at)
                            {{ $item->created_at->format('M d, Y') }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td data-label="Actions">
                        <a href="{{ route('found-items.show', $item) }}" 
                           class="btn-view found">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
                
                @if($lostItems->isEmpty() && $foundItems->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <h5>No items with location data</h5>
                            <p>Items need to have location coordinates to appear on the map.</p>
                            <div class="empty-actions">
                                <a href="{{ route('lost-items.create') }}" class="btn-map primary">
                                    <i class="fas fa-exclamation-circle"></i> Report Lost
                                </a>
                                <a href="{{ route('found-items.create') }}" class="btn-map primary">
                                    <i class="fas fa-check-circle"></i> Report Found
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

<!-- Notifications Container -->
<div id="notificationsContainer"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map
    let map;
    let markers = [];
    let userMarker = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map with light theme tiles
        map = L.map('map').setView([20.0, 0.0], 2);
        
        // Add light theme map tiles (OpenStreetMap standard)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Add lost items markers
        @foreach($lostItems as $item)
            @if($item->latitude && $item->longitude)
                addMarker(
                    [{{ $item->latitude }}, {{ $item->longitude }}],
                    '{{ $item->item_name }}',
                    '{{ $item->category }}',
                    '{{ addslashes($item->description) }}',
                    '{{ $item->photo ? asset('storage/' . $item->photo) : '' }}',
                    'lost',
                    '{{ route('lost-items.show', $item) }}',
                    {{ $item->id }}
                );
            @endif
        @endforeach
        
        // Add found items markers
        @foreach($foundItems as $item)
            @if($item->latitude && $item->longitude)
                addMarker(
                    [{{ $item->latitude }}, {{ $item->longitude }}],
                    '{{ $item->item_name }}',
                    '{{ $item->category }}',
                    '{{ addslashes($item->description) }}',
                    '{{ $item->photo ? asset('storage/' . $item->photo) : '' }}',
                    'found',
                    '{{ route('found-items.show', $item) }}',
                    {{ $item->id }}
                );
            @endif
        @endforeach
        
        // Fit map bounds to show all markers
        fitAllMarkers();

        // Add data-label attributes for responsive table
        document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                const headers = ['Type', 'Item Name', 'Category', 'Location', 'Date', 'Actions'];
                cell.setAttribute('data-label', headers[index]);
            });
        });
    });
    
    // Add marker to map
    function addMarker(coords, name, category, description, photo, type, url, id) {
        const icon = L.divIcon({
            className: `${type}-marker`,
            html: `<i class="fas ${type === 'lost' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>`,
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -15]
        });
        
        const marker = L.marker(coords, { icon }).addTo(map);
        
        // Create popup content
        const popupContent = `
            <div class="marker-popup">
                <h6>${name}</h6>
                <span class="${type === 'lost' ? 'badge-lost' : 'badge-found'}">${type === 'lost' ? 'Lost' : 'Found'}</span>
                <p><strong>Category:</strong> ${category}</p>
                <p><strong>Description:</strong> ${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</p>
                ${photo ? `<img src="${photo}" alt="${name}">` : ''}
                <div class="mt-3">
                    <a href="${url}" class="btn-view ${type}">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        `;
        
        marker.bindPopup(popupContent, {
            className: 'custom-popup'
        });
        
        // Store marker reference
        markers.push({
            marker: marker,
            type: type,
            category: category,
            id: id
        });
        
        return marker;
    }
    
    // Fit map to show all markers
    function fitAllMarkers() {
        if (markers.length === 0) return;
        
        const group = new L.featureGroup(markers.map(m => m.marker));
        map.fitBounds(group.getBounds().pad(0.1));
    }
    
    // Get user's location
    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    
                    // Remove existing user marker
                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }
                    
                    // Add user location marker
                    userMarker = L.marker([userLat, userLng], {
                        icon: L.divIcon({
                            className: 'user-marker',
                            html: '<i class="fas fa-user"></i>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 30],
                            popupAnchor: [0, -15]
                        })
                    }).addTo(map);
                    
                    userMarker.bindPopup('<strong>Your Location</strong>').openPopup();
                    
                    // Center map on user location
                    map.setView([userLat, userLng], 13);
                    
                    showToast('Location found!', 'success');
                },
                function(error) {
                    let errorMessage = 'Unable to get location. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Please enable location services.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Location request timed out.';
                            break;
                        default:
                            errorMessage += 'Unknown error.';
                    }
                    showToast(errorMessage, 'error');
                }
            );
        } else {
            showToast('Geolocation not supported by your browser.', 'error');
        }
    }
    
    // Filter markers by category
    document.getElementById('categoryFilter').addEventListener('change', function() {
        const selectedCategory = this.value;
        filterMarkers(selectedCategory);
    });
    
    // Filter markers by type
    document.getElementById('showLost').addEventListener('change', filterMarkers);
    document.getElementById('showFound').addEventListener('change', filterMarkers);
    
    // Filter markers based on selected criteria
    function filterMarkers(category = null) {
        const showLost = document.getElementById('showLost').checked;
        const showFound = document.getElementById('showFound').checked;
        const selectedCategory = category || document.getElementById('categoryFilter').value;
        
        // Filter table rows
        document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
            const rowCategory = row.dataset.category;
            const isLost = row.classList.contains('item-lost');
            const isFound = row.classList.contains('item-found');
            
            let shouldShow = true;
            
            // Check type filter
            if ((isLost && !showLost) || (isFound && !showFound)) {
                shouldShow = false;
            }
            
            // Check category filter
            if (selectedCategory && rowCategory !== selectedCategory) {
                shouldShow = false;
            }
            
            row.style.display = shouldShow ? '' : 'none';
        });
        
        // Filter map markers
        markers.forEach(item => {
            const shouldShow = 
                ((item.type === 'lost' && showLost) || (item.type === 'found' && showFound)) &&
                (!selectedCategory || item.category === selectedCategory);
            
            if (shouldShow) {
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
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
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
        
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
    
    // Add click handlers to table rows
    document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on a link
            if (e.target.tagName === 'A' || e.target.closest('a')) return;
            
            const itemId = this.dataset.id;
            const isLost = this.classList.contains('item-lost');
            const marker = markers.find(m => m.id == itemId && m.type === (isLost ? 'lost' : 'found'));
            
            if (marker) {
                map.setView(marker.marker.getLatLng(), 15);
                marker.marker.openPopup();
            }
        });
    });
</script>
@endpush