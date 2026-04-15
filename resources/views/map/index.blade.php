@extends('layouts.app')

@section('title', 'Item Locations Map - Foundify')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.dashboard-container {
    position: relative;
    z-index: 1;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px 32px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-color);
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

.page-title h1 i { color: var(--accent); font-size: 26px; }
.page-title p { font-size: 14px; color: var(--text-muted); margin: 0; }

/* Map card */
.map-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.card-header {
    padding: 16px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-header h5 i { color: var(--accent); font-size: 16px; }

#markerCountBadge {
    font-size: 12px;
    color: var(--text-muted);
    background: var(--bg-primary);
    padding: 4px 12px;
    border-radius: 20px;
}

/* Geocoding progress bar */
#geocodeProgress {
    padding: 12px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    display: none;
}

#geocodeProgressBar {
    height: 6px;
    background: var(--border-color);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 8px;
}

#geocodeProgressFill {
    height: 100%;
    background: var(--accent);
    border-radius: 3px;
    width: 0%;
    transition: width 0.3s ease;
}

#geocodeProgressText {
    font-size: 12px;
    color: var(--text-muted);
}

.map-wrapper { position: relative; }

#map {
    height: 520px;
    width: 100%;
    background: #1a1a1a;
    z-index: 1;
}

/* Leaflet dark mode overrides */
.leaflet-container {
    background: #1a1a1a !important;
}

body.light .leaflet-container {
    background: #f0f0f0 !important;
}

.leaflet-control-attribution {
    background: rgba(26, 26, 26, 0.8) !important;
    color: #888 !important;
}

body.light .leaflet-control-attribution {
    background: rgba(255, 255, 255, 0.8) !important;
    color: #666 !important;
}

.leaflet-control-zoom a {
    background: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-color) !important;
}

.leaflet-control-zoom a:hover {
    background: var(--bg-secondary) !important;
}

#loading {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    z-index: 1000;
}

.spinner {
    border: 3px solid rgba(255,255,255,0.2);
    border-top: 3px solid var(--accent);
    border-radius: 50%;
    width: 44px; height: 44px;
    animation: spin 1s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

#loadingText {
    margin-top: 14px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

/* Sidebar */
.sidebar-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
}

.sidebar-header {
    padding: 16px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
}

.sidebar-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sidebar-header h5 i { color: var(--accent); font-size: 16px; }
.sidebar-body { padding: 20px; }

.legend-section { margin-bottom: 24px; }

.legend-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.legend-title i { color: var(--accent); }

.legend-item {
    display: flex;
    align-items: center;
    padding: 10px 14px;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 10px;
}

.legend-color {
    width: 28px; height: 28px;
    border-radius: 50%;
    margin-right: 12px;
    flex-shrink: 0;
}

.legend-color.lost  { background: #ef4444; box-shadow: 0 0 0 2px rgba(239,68,68,0.3); }
.legend-color.found { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,0.3); }
.legend-text  { flex: 1; font-size: 12px; font-weight: 500; color: var(--text-muted); }
.legend-count {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    color: var(--accent);
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

.filter-group { margin-bottom: 24px; }

.filter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 8px;
    text-transform: uppercase;
}

.filter-select {
    width: 100%;
    padding: 10px 14px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 13px;
    cursor: pointer;
}

.filter-select:focus { outline: none; border-color: var(--accent); }

.checkbox-group { display: flex; flex-direction: column; gap: 10px; }

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
}

.checkbox-item:hover { background: var(--bg-secondary); }

.checkbox-item input[type="checkbox"] {
    width: 18px; height: 18px;
    accent-color: var(--accent);
    cursor: pointer;
}

.checkbox-item label {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-buttons { display: flex; flex-direction: column; gap: 10px; }

.btn-map {
    font-size: 12px;
    font-weight: 600;
    padding: 11px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1px solid;
    background: transparent;
}

.btn-map.primary  { border-color: var(--accent); color: var(--accent); }
.btn-map.primary:hover  { background: var(--accent); color: white; }
.btn-map.secondary { border-color: var(--border-color); color: var(--text-muted); }
.btn-map.secondary:hover { border-color: var(--accent); color: var(--accent); }

.stats-list { list-style: none; padding: 0; margin: 0; }

.stats-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
}

.stats-item:last-child { border-bottom: none; }

.stats-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
}

.stats-value {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    color: var(--accent);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.stats-note {
    padding: 12px 16px;
    color: var(--text-muted);
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-top: 1px solid var(--border-color);
}

/* Table */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 28px;
}

.table-header {
    padding: 16px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
}

.table-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-responsive { overflow-x: auto; max-height: 400px; overflow-y: auto; }
.data-table { width: 100%; border-collapse: collapse; }

.data-table thead {
    position: sticky;
    top: 0; z-index: 2;
    background: var(--bg-secondary);
}

.data-table th {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
    text-transform: uppercase;
}

.data-table td {
    padding: 14px 16px;
    font-size: 13px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
}

.data-table tbody tr { transition: background 0.15s; cursor: pointer; }
.data-table tbody tr:hover { background: var(--bg-secondary); }

.data-table tbody tr.row-active {
    background: var(--bg-secondary);
    outline: 2px solid var(--accent);
    outline-offset: -2px;
}

.badge-lost  { background: rgba(239,68,68,0.18);  color: #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block; }
.badge-found { background: rgba(16,185,129,0.18); color: #10b981; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block; }

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

.btn-view {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 20px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid;
    white-space: nowrap;
}

.btn-view.lost  { border-color: rgba(239,68,68,0.3);  color: #ef4444; }
.btn-view.lost:hover  { background: #ef4444; color: white; }
.btn-view.found { border-color: rgba(16,185,129,0.3); color: #10b981; }
.btn-view.found:hover { background: #10b981; color: white; }

/* Toast */
#notificationsContainer {
    position: fixed;
    top: 80px; right: 20px;
    z-index: 9999;
    max-width: 360px;
}

.toast {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    margin-bottom: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    animation: slideIn 0.3s ease;
}

.toast-body {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    font-size: 13px;
    color: var(--text-primary);
}

.toast-close {
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    font-size: 18px;
    margin-left: auto;
    line-height: 1;
}

@keyframes slideIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }

.fade-in { animation: fadeIn 0.4s ease forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

/* Leaflet Popup Styling */
.leaflet-popup-content-wrapper {
    background: var(--bg-card) !important;
    color: var(--text-primary) !important;
    border-radius: 12px !important;
    border: 1px solid var(--border-color) !important;
}

.leaflet-popup-content {
    margin: 0 !important;
    padding: 0 !important;
}

.leaflet-popup-tip {
    background: var(--bg-card) !important;
    border: 1px solid var(--border-color) !important;
}

.leaflet-popup-close-button {
    color: var(--text-muted) !important;
    padding: 8px 12px !important;
}

.leaflet-popup-close-button:hover {
    color: var(--accent) !important;
    background: transparent !important;
}

@media (max-width: 992px) {
    .dashboard-container { padding: 20px; }
    .page-header { flex-direction: column; align-items: flex-start; }
}

@media (max-width: 768px) {
    .data-table thead { display: none; }
    .data-table tbody tr { display: block; margin-bottom: 12px; border: 1px solid var(--border-color); border-radius: 8px; }
    .data-table tbody td { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid var(--border-color); }
    .data-table tbody td:last-child { border-bottom: none; }
    .data-table tbody td::before { content: attr(data-label); font-weight: 600; color: var(--text-primary); margin-right: 15px; min-width: 80px; font-size: 11px; text-transform: uppercase; }
}
</style>
@endpush

@section('content')
<div class="dashboard-container">

    <div class="page-header fade-in">
        <div class="page-title">
            <h1><i class="fas fa-map-marked-alt"></i> Item Map</h1>
            <p>View approved lost and found items on an interactive map</p>
        </div>
    </div>

    <div class="row g-4">

        {{-- MAP --}}
        <div class="col-lg-9">
            <div class="map-card fade-in">
                <div class="card-header">
                    <h5><i class="fas fa-map"></i> Interactive Map (OpenStreetMap)</h5>
                    <span id="markerCountBadge"></span>
                </div>

                <div id="geocodeProgress">
                    <div id="geocodeProgressBar">
                        <div id="geocodeProgressFill"></div>
                    </div>
                    <div id="geocodeProgressText">Resolving locations…</div>
                </div>

                <div class="map-wrapper">
                    <div id="map"></div>
                    <div id="loading">
                        <div class="spinner"></div>
                        <div id="loadingText">Loading map…</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-3">
            <div class="sidebar-card fade-in">
                <div class="sidebar-header">
                    <h5><i class="fas fa-sliders-h"></i> Map Controls</h5>
                </div>
                <div class="sidebar-body">

                    <div class="legend-section">
                        <div class="legend-title"><i class="fas fa-info-circle"></i> Legend</div>
                        <div class="legend-item">
                            <div class="legend-color lost"></div>
                            <span class="legend-text">Lost Items</span>
                            <span class="legend-count">{{ isset($lostItems) ? $lostItems->count() : 0 }}</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color found"></div>
                            <span class="legend-text">Found Items</span>
                            <span class="legend-count">{{ isset($foundItems) ? $foundItems->count() : 0 }}</span>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-tag"></i> Category</label>
                        <select id="categoryFilter" class="filter-select">
                            <option value="">All Categories</option>
                            @php
                                $categories = [];
                                if (isset($lostItems) && isset($foundItems)) {
                                    $categories = array_unique(array_merge(
                                        $lostItems->pluck('category')->filter()->toArray(),
                                        $foundItems->pluck('category')->filter()->toArray()
                                    ));
                                    sort($categories);
                                }
                            @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label"><i class="fas fa-filter"></i> Item Type</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="showLost" checked>
                                <label for="showLost"><i class="fas fa-exclamation-circle"></i> Show Lost Items</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="showFound" checked>
                                <label for="showFound"><i class="fas fa-check-circle"></i> Show Found Items</label>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-map primary" onclick="getUserLocation()">
                            <i class="fas fa-location-arrow"></i> My Location
                        </button>
                        <button class="btn-map secondary" onclick="fitAllMarkers()">
                            <i class="fas fa-expand"></i> View All
                        </button>
                    </div>
                </div>
            </div>

            <div class="sidebar-card fade-in">
                <div class="sidebar-header">
                    <h5><i class="fas fa-chart-bar"></i> Statistics</h5>
                </div>
                <ul class="stats-list">
                    <li class="stats-item">
                        <span class="stats-label">
                            <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i> Lost Items
                        </span>
                        <span class="stats-value">{{ isset($lostItems) ? $lostItems->count() : 0 }}</span>
                    </li>
                    <li class="stats-item">
                        <span class="stats-label">
                            <i class="fas fa-check-circle" style="color:#10b981;"></i> Found Items
                        </span>
                        <span class="stats-value">{{ isset($foundItems) ? $foundItems->count() : 0 }}</span>
                    </li>
                    <li class="stats-item">
                        <span class="stats-label">
                            <i class="fas fa-map-marker-alt" style="color:var(--accent);"></i> Total on Map
                        </span>
                        <span class="stats-value">
                            {{ (isset($lostItems) ? $lostItems->count() : 0) + (isset($foundItems) ? $foundItems->count() : 0) }}
                        </span>
                    </li>
                </ul>
                <div class="stats-note">
                    <i class="fas fa-check-circle" style="color:#2e7d32;"></i>
                    Only approved items are shown
                </div>
            </div>
        </div>
    </div>

    {{-- ITEMS TABLE --}}
    <div class="table-card fade-in">
        <div class="table-header">
            <h5><i class="fas fa-list"></i> Items List</h5>
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
                    </tr>
                </thead>
                <tbody>
                    @isset($lostItems)
                        @foreach($lostItems as $item)
                        <tr class="item-row item-lost"
                            data-category="{{ $item->category }}"
                            data-id="{{ $item->id }}"
                            data-type="lost"
                            data-lat="{{ $item->latitude ?? '' }}"
                            data-lng="{{ $item->longitude ?? '' }}"
                            data-location="{{ $item->lost_location ?? '' }}">
                            <td data-label="Type"><span class="badge-lost">LOST</span></td>
                            <td data-label="Item">{{ $item->item_name }}</td>
                            <td data-label="Category">{{ ucfirst($item->category ?? '') }}</td>
                            <td data-label="Location">
                                @if($item->lost_location)
                                    <span class="location-text" title="{{ $item->lost_location }}">
                                        <i class="fas fa-map-marked-alt" style="color:#ef4444;"></i>
                                        {{ Str::limit($item->lost_location, 30) }}
                                    </span>
                                @elseif($item->latitude && $item->longitude)
                                    <span class="location-text">
                                        <i class="fas fa-map-marker-alt" style="color:#ef4444;"></i>
                                        {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                                    </span>
                                @else
                                    <span style="color:var(--text-muted);font-size:12px;">No location</span>
                                @endif
                            </td>
                            <td data-label="Date">{{ $item->created_at->format('M d, Y') }}</td>
                            <td data-label="Actions">
                                <a href="{{ route('lost-items.show', $item) }}" class="btn-view lost">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endisset

                    @isset($foundItems)
                        @foreach($foundItems as $item)
                        <tr class="item-row item-found"
                            data-category="{{ $item->category }}"
                            data-id="{{ $item->id }}"
                            data-type="found"
                            data-lat="{{ $item->latitude ?? '' }}"
                            data-lng="{{ $item->longitude ?? '' }}"
                            data-location="{{ $item->found_location ?? '' }}">
                            <td data-label="Type"><span class="badge-found">FOUND</span></td>
                            <td data-label="Item">{{ $item->item_name }}</td>
                            <td data-label="Category">{{ ucfirst($item->category ?? '') }}</td>
                            <td data-label="Location">
                                @if($item->found_location)
                                    <span class="location-text" title="{{ $item->found_location }}">
                                        <i class="fas fa-map-marked-alt" style="color:#10b981;"></i>
                                        {{ Str::limit($item->found_location, 30) }}
                                    </span>
                                @elseif($item->latitude && $item->longitude)
                                    <span class="location-text">
                                        <i class="fas fa-map-marker-alt" style="color:#10b981;"></i>
                                        {{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}
                                    </span>
                                @else
                                    <span style="color:var(--text-muted);font-size:12px;">No location</span>
                                @endif
                            </td>
                            <td data-label="Date">{{ $item->created_at->format('M d, Y') }}</td>
                            <td data-label="Actions">
                                <a href="{{ route('found-items.show', $item) }}" class="btn-view found">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="notificationsContainer"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    window.__mapItems = @json($allItems);
    window.__routes   = {
        lost:  "{{ url('/lost-items') }}/",
        found: "{{ url('/found-items') }}/"
    };
</script>

<script>
'use strict';

/* State */
let map;
let markers = [];        // { marker, type, category, id, lat, lng }
let userMarker = null;
let geocodeCache = {};

/* Nominatim (OpenStreetMap) geocoding - FREE, no API key required! */
async function geocodeAddress(address) {
    if (!address || typeof address !== 'string') return null;
    const trimmed = address.trim();
    if (!trimmed) return null;
    
    if (geocodeCache[trimmed]) return geocodeCache[trimmed];
    
    const queries = [trimmed];
    const lower = trimmed.toLowerCase();
    if (!lower.includes('philippines')) queries.push(`${trimmed}, Philippines`);
    if (!lower.includes('bohol')) queries.push(`${trimmed}, Bohol, Philippines`);
    
    for (const query of queries) {
        try {
            // Add delay to respect Nominatim usage policy (1 request per second)
            await new Promise(r => setTimeout(r, 1000));
            
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`;
            const response = await fetch(url, {
                headers: { 'Accept-Language': 'en' }
            });
            
            if (!response.ok) continue;
            
            const data = await response.json();
            if (data && data.length > 0) {
                const result = {
                    lat: parseFloat(data[0].lat),
                    lng: parseFloat(data[0].lon)
                };
                geocodeCache[trimmed] = result;
                console.log(`✓ Geocoded "${trimmed}" → (${result.lat.toFixed(5)}, ${result.lng.toFixed(5)})`);
                return result;
            }
        } catch (e) {
            console.warn(`Geocoding error for "${query}":`, e);
        }
    }
    
    console.warn(`✗ Could not geocode: "${trimmed}"`);
    return null;
}

function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

function initMap() {
    map = L.map('map').setView([9.8800, 124.2000], 10);
    
    // OpenStreetMap standard tiles - closest free alternative to Google Maps
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);
    
    wireFilters();
    wireTableRows();
    loadAllMarkers();
}

function hasValidCoords(item) {
    if (item.latitude === null || item.latitude === '' || item.latitude === undefined) return false;
    if (item.longitude === null || item.longitude === '' || item.longitude === undefined) return false;
    const lat = parseFloat(item.latitude);
    const lng = parseFloat(item.longitude);
    return !isNaN(lat) && !isNaN(lng) && !(lat === 0 && lng === 0);
}

function hasLocation(item) {
    return !!(item.location_name && String(item.location_name).trim().length > 0);
}

async function loadAllMarkers() {
    const items = window.__mapItems || [];
    
    if (!items.length) {
        hideLoading();
        showToast('No approved items with location data found.', 'error');
        return;
    }
    
    const withCoords = items.filter(i => hasValidCoords(i));
    const needGeocode = items.filter(i => !hasValidCoords(i) && hasLocation(i));
    const noLocation = items.filter(i => !hasValidCoords(i) && !hasLocation(i));
    
    // Place items with coordinates immediately
    withCoords.forEach(i => placeMarker(i, parseFloat(i.latitude), parseFloat(i.longitude)));
    
    // Geocode items with location names
    if (needGeocode.length) {
        showGeocodeProgress(0, needGeocode.length);
        
        for (let idx = 0; idx < needGeocode.length; idx++) {
            const item = needGeocode[idx];
            
            updateGeocodeProgress(
                idx + 1,
                needGeocode.length,
                `Locating "${item.item_name}"…`
            );
            
            const pos = await geocodeAddress(item.location_name);
            
            if (pos) {
                placeMarker(item, pos.lat, pos.lng);
            } else {
                console.warn(`✗ Could not geocode #${item.id} "${item.item_name}" — "${item.location_name}"`);
            }
        }
        
        hideGeocodeProgress();
    }
    
    if (noLocation.length) {
        console.warn(`${noLocation.length} item(s) have neither coordinates nor a location name`);
    }
    
    hideLoading();
    
    const placed = markers.length;
    updateMarkerCountBadge(placed, items.length);
    
    if (placed > 0) {
        fitAllMarkers();
        showToast(`${placed} of ${items.length} items placed on map`, placed === items.length ? 'success' : 'info');
    } else {
        showToast('No items could be located.', 'error');
    }
}

function placeMarker(item, lat, lng) {
    const type = item.type;
    const color = type === 'lost' ? '#ef4444' : '#10b981';
    const letter = type === 'lost' ? '!' : '✓';
    
    // Create custom div icon
    const icon = L.divIcon({
        html: `<div style="background:${color};width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);"><span style="color:white;font-weight:bold;font-size:16px;">${letter}</span></div>`,
        className: 'custom-marker',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
    
    const marker = L.marker([lat, lng], { icon }).addTo(map);
    
    const detailUrl = item.url || (window.__routes[type] + item.id);
    const locationText = item.location_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
    const desc = item.description ? (item.description.length > 130 ? item.description.substring(0, 130) + '…' : item.description) : 'No description provided';
    const photoHtml = item.photo 
        ? `<img src="${item.photo}" style="width:100%;border-radius:8px;margin-bottom:12px;max-height:120px;object-fit:cover;" onerror="this.style.display='none'">` 
        : '';
    
    const popupContent = `
        <div style="padding:16px;min-width:260px;max-width:310px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
            <h6 style="font-size:15px;font-weight:700;margin:0 0 6px;color:var(--text-primary);">${escapeHtml(item.item_name)}</h6>
            <span style="display:inline-block;margin-bottom:10px;padding:2px 10px;border-radius:20px;font-size:10px;font-weight:800;letter-spacing:.05em;background:${color}18;color:${color};">
                ${type.toUpperCase()}
            </span>
            <p style="margin:0 0 6px;font-size:12px;"><strong>Category:</strong> ${escapeHtml((item.category || 'Uncategorized').toUpperCase())}</p>
            <p style="margin:0 0 10px;font-size:12px;display:flex;gap:4px;align-items:flex-start;">
                <span>📍</span><span>${escapeHtml(locationText)}</span>
            </p>
            <p style="margin:0 0 12px;line-height:1.55;font-size:12px;">${escapeHtml(desc)}</p>
            ${photoHtml}
            <a href="${detailUrl}" style="display:flex;align-items:center;justify-content:center;padding:9px 16px;border-radius:8px;text-decoration:none;font-size:12px;font-weight:700;background:#e50914;color:#fff;">
                View Details
            </a>
        </div>
    `;
    
    marker.bindPopup(popupContent);
    
    marker.on('click', () => {
        highlightRow(item.id, item.type);
    });
    
    markers.push({ marker, type, category: item.category, id: item.id, lat, lng });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = String(text);
    return div.innerHTML;
}

function wireTableRows() {
    document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
        row.addEventListener('click', async function(e) {
            if (e.target.closest('a')) return;
            
            const id = this.dataset.id;
            const type = this.dataset.type;
            const lat = this.dataset.lat;
            const lng = this.dataset.lng;
            const location = this.dataset.location;
            
            const panAndOpen = (posLat, posLng) => {
                map.setView([posLat, posLng], 16);
                const found = markers.find(m => String(m.id) === id && m.type === type);
                if (found) {
                    found.marker.openPopup();
                    highlightRow(id, type);
                }
            };
            
            if (lat && lng && lat !== '' && lng !== '') {
                const flat = parseFloat(lat), flng = parseFloat(lng);
                if (!isNaN(flat) && !isNaN(flng)) {
                    panAndOpen(flat, flng);
                    return;
                }
            }
            
            if (location && location.trim()) {
                showToast('Locating address…', 'info');
                const pos = await geocodeAddress(location);
                if (pos) panAndOpen(pos.lat, pos.lng);
                else showToast('Could not locate this address', 'error');
            } else {
                showToast('No location data for this item', 'error');
            }
        });
    });
}

function highlightRow(id, type) {
    document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(r => r.classList.remove('row-active'));
    const row = document.querySelector(`#itemsTable tbody tr.item-row[data-id="${id}"][data-type="${type}"]`);
    if (row) {
        row.classList.add('row-active');
        row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function wireFilters() {
    document.getElementById('categoryFilter')?.addEventListener('change', applyFilters);
    document.getElementById('showLost')?.addEventListener('change', applyFilters);
    document.getElementById('showFound')?.addEventListener('change', applyFilters);
}

function applyFilters() {
    const category = document.getElementById('categoryFilter')?.value || '';
    const showLost = document.getElementById('showLost')?.checked ?? true;
    const showFound = document.getElementById('showFound')?.checked ?? true;
    
    document.querySelectorAll('#itemsTable tbody tr.item-row').forEach(row => {
        const show = (!category || row.dataset.category === category) &&
                     !((row.dataset.type === 'lost' && !showLost) || (row.dataset.type === 'found' && !showFound));
        row.style.display = show ? '' : 'none';
    });
    
    let visible = 0;
    const bounds = L.latLngBounds([]);
    
    markers.forEach(m => {
        const show = ((m.type === 'lost' && showLost) || (m.type === 'found' && showFound)) &&
                    (!category || m.category === category);
        
        if (show) {
            m.marker.addTo(map);
            bounds.extend([m.lat, m.lng]);
            visible++;
        } else {
            m.marker.remove();
        }
    });
    
    if (visible > 0) {
        map.fitBounds(bounds, { padding: [30, 30] });
    }
    
    updateMarkerCountBadge(visible, markers.length);
    showToast(`Showing ${visible} item(s) on map`, 'info');
}

function fitAllMarkers() {
    if (!markers.length) {
        map.setView([9.8800, 124.2000], 10);
        return;
    }
    
    const bounds = L.latLngBounds(markers.map(m => [m.lat, m.lng]));
    map.fitBounds(bounds, { padding: [30, 30] });
}

function getUserLocation() {
    if (!navigator.geolocation) {
        showToast('Geolocation not supported', 'error');
        return;
    }
    
    showToast('Getting your location…', 'info');
    
    navigator.geolocation.getCurrentPosition(
        pos => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            
            if (userMarker) map.removeLayer(userMarker);
            
            const userIcon = L.divIcon({
                html: `<div style="background:#e50914;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid white;box-shadow:0 2px 10px rgba(0,0,0,0.3);"><span style="color:white;font-size:20px;">●</span></div>`,
                className: 'user-marker',
                iconSize: [36, 36],
                iconAnchor: [18, 18]
            });
            
            userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map);
            userMarker.bindPopup('<div style="padding:12px;font-weight:700;">📍 Your Location</div>');
            
            map.setView([lat, lng], 15);
            showToast('Location found!', 'success');
        },
        () => showToast('Unable to get location', 'error')
    );
}

function showGeocodeProgress(done, total) {
    document.getElementById('geocodeProgress').style.display = 'block';
    updateGeocodeProgress(done, total, 'Resolving locations…');
}

function updateGeocodeProgress(done, total, label) {
    document.getElementById('geocodeProgressFill').style.width = `${Math.round((done / total) * 100)}%`;
    document.getElementById('geocodeProgressText').textContent = `${label} (${done} / ${total})`;
    document.getElementById('loadingText').textContent = `${label} (${done} / ${total})`;
}

function hideGeocodeProgress() {
    document.getElementById('geocodeProgress').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loading').style.display = 'none';
}

function updateMarkerCountBadge(placed, total) {
    const el = document.getElementById('markerCountBadge');
    if (el) el.textContent = `${placed} of ${total} items on map`;
}

function showToast(message, type = 'info') {
    const container = document.getElementById('notificationsContainer');
    if (!container) return;
    
    const color = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : 'var(--accent)';
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
    
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<div class="toast-body">
        <span style="color:${color};font-weight:700;font-size:15px;">${icon}</span>
        <span>${escapeHtml(message)}</span>
        <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
    </div>`;
    
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initMap);
</script>
@endpush