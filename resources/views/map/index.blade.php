@extends('layouts.app')

@section('title', 'Item Locations Map')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        border-radius: 10px;
        z-index: 1;
    }
    .legend {
        background: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .legend-color {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        border-radius: 50%;
    }
    .lost-marker {
        background-color: #dc3545;
        border: 2px solid white;
    }
    .found-marker {
        background-color: #28a745;
        border: 2px solid white;
    }
    .marker-popup img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 5px;
    }
    .item-lost {
        border-left: 4px solid #dc3545;
    }
    .item-found {
        border-left: 4px solid #28a745;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-map-marked-alt"></i> Item Locations Map
        </h1>
        <p>View lost and found items on an interactive map</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
            <i class="fas fa-exclamation-circle"></i> Report Lost
        </a>
        <a href="{{ route('found-items.create') }}" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Report Found
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map"></i> Interactive Map
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="map"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3">
        <!-- Map Controls -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter"></i> Map Controls
                </h5>
            </div>
            <div class="card-body">
                <div class="legend mb-4">
                    <h6 class="mb-3">Map Legend</h6>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #dc3545;"></div>
                        <span>Lost Items</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #28a745;"></div>
                        <span>Found Items</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="mb-2">Filter by Category:</h6>
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
                
                <div class="mb-4">
                    <h6 class="mb-2">Filter by Type:</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="showLost" checked>
                        <label class="form-check-label" for="showLost">
                            <i class="fas fa-exclamation-circle text-danger"></i> Show Lost Items
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showFound" checked>
                        <label class="form-check-label" for="showFound">
                            <i class="fas fa-check-circle text-success"></i> Show Found Items
                        </label>
                    </div>
                </div>
                
                <button class="btn btn-outline-primary w-100 mb-3" onclick="getUserLocation()">
                    <i class="fas fa-location-arrow"></i> Go to My Location
                </button>
                
                <button class="btn btn-outline-secondary w-100" onclick="fitAllMarkers()">
                    <i class="fas fa-expand"></i> View All Items
                </button>
            </div>
        </div>
        
        <!-- Map Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Map Statistics
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-exclamation-circle text-danger"></i> Lost Items:
                        </span>
                        <span class="badge bg-danger">{{ $lostItems->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-check-circle text-success"></i> Found Items:
                        </span>
                        <span class="badge bg-success">{{ $foundItems->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-map-marker-alt text-primary"></i> Total Markers:
                        </span>
                        <span class="badge bg-primary">{{ $lostItems->count() + $foundItems->count() }}</span>
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Only items with location data are shown.
                        </small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Items Table -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Items List
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="itemsTable">
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
                    @foreach($lostItems as $item)
                    <tr class="item-lost" data-category="{{ $item->category }}">
                        <td>
                            <span class="badge bg-danger">Lost</span>
                        </td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->category }}</td>
                        <td>
                            @if($item->latitude && $item->longitude)
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                            @else
                                <span class="text-muted">No location</span>
                            @endif
                        </td>
                        <td>
                            @if($item->created_at)
                                {{ $item->created_at->format('M d, Y') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('lost-items.show', $item->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @foreach($foundItems as $item)
                    <tr class="item-found" data-category="{{ $item->category }}">
                        <td>
                            <span class="badge bg-success">Found</span>
                        </td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->category }}</td>
                        <td>
                            @if($item->latitude && $item->longitude)
                                <i class="fas fa-map-marker-alt text-success"></i>
                                {{ round($item->latitude, 4) }}, {{ round($item->longitude, 4) }}
                            @else
                                <span class="text-muted">No location</span>
                            @endif
                        </td>
                        <td>
                            @if($item->created_at)
                                {{ $item->created_at->format('M d, Y') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('found-items.show', $item->id) }}" 
                               class="btn btn-sm btn-outline-success">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($lostItems->isEmpty() && $foundItems->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h5>No items with location data</h5>
                            <p class="text-muted">Items need to have location coordinates to appear on the map.</p>
                            <a href="{{ route('lost-items.create') }}" class="btn btn-danger">
                                <i class="fas fa-plus-circle"></i> Report Lost Item
                            </a>
                            <a href="{{ route('found-items.create') }}" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> Report Found Item
                            </a>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map
    let map;
    let markers = [];
    let userMarker = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map with default view
        map = L.map('map').setView([20.0, 0.0], 2);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add lost items markers
        @foreach($lostItems as $item)
            @if($item->latitude && $item->longitude)
                addMarker(
                    [{{ $item->latitude }}, {{ $item->longitude }}],
                    '{{ $item->item_name }}',
                    '{{ $item->category }}',
                    '{{ $item->description }}',
                    '{{ $item->photo ? asset('storage/' . $item->photo) : '' }}',
                    'lost',
                    '{{ route('lost-items.show', $item->id) }}',
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
                    '{{ $item->description }}',
                    '{{ $item->photo ? asset('storage/' . $item->photo) : '' }}',
                    'found',
                    '{{ route('found-items.show', $item->id) }}',
                    {{ $item->id }}
                );
            @endif
        @endforeach
        
        // Fit map bounds to show all markers
        fitAllMarkers();
    });
    
    // Add marker to map
    function addMarker(coords, name, category, description, photo, type, url, id) {
        const icon = L.divIcon({
            className: `${type}-marker`,
            html: `<i class="fas ${type === 'lost' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>`,
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });
        
        const marker = L.marker(coords, { icon }).addTo(map);
        
        // Create popup content
        const popupContent = `
            <div class="marker-popup">
                <h6>${name}</h6>
                <p><strong>Type:</strong> <span class="badge ${type === 'lost' ? 'bg-danger' : 'bg-success'}">${type === 'lost' ? 'Lost' : 'Found'}</span></p>
                <p><strong>Category:</strong> ${category}</p>
                <p><strong>Description:</strong> ${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</p>
                ${photo ? `<img src="${photo}" alt="${name}" class="img-fluid mt-2">` : ''}
                <div class="mt-3">
                    <a href="${url}" class="btn btn-sm ${type === 'lost' ? 'btn-danger' : 'btn-success'}">
                        View Details
                    </a>
                </div>
            </div>
        `;
        
        marker.bindPopup(popupContent);
        
        // Store marker reference
        markers.push({
            marker: marker,
            type: type,
            category: category,
            id: id
        });
        
        // Add click event to table row
        const row = document.querySelector(`tr[data-category="${category}"]`);
        if (row) {
            row.addEventListener('click', function() {
                map.setView(coords, 15);
                marker.openPopup();
            });
        }
        
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
                            iconAnchor: [15, 30]
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
    document.getElementById('showLost').addEventListener('change', function() {
        filterMarkers();
    });
    
    document.getElementById('showFound').addEventListener('change', function() {
        filterMarkers();
    });
    
    // Filter markers based on selected criteria
    function filterMarkers(category = null) {
        const showLost = document.getElementById('showLost').checked;
        const showFound = document.getElementById('showFound').checked;
        
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
            if (category && rowCategory !== category) {
                shouldShow = false;
            }
            
            row.style.display = shouldShow ? '' : 'none';
        });
        
        // Filter map markers
        markers.forEach(item => {
            const shouldShow = 
                ((item.type === 'lost' && showLost) || (item.type === 'found' && showFound)) &&
                (!category || item.category === category);
            
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
    }
    
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
    
    // Load more items when map is moved (infinite scroll)
    let isLoading = false;
    map.on('moveend', function() {
        if (isLoading) return;
        
        const bounds = map.getBounds();
        const northEast = bounds.getNorthEast();
        const southWest = bounds.getSouthWest();
        
        // In a real application, you would make an AJAX call here
        // to load items within the current map bounds
        console.log('Map bounds changed:', {
            north: northEast.lat,
            south: southWest.lat,
            east: northEast.lng,
            west: southWest.lng
        });
    });
</script>

<style>
    .lost-marker {
        background-color: #dc3545;
        border: 3px solid white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    
    .found-marker {
        background-color: #28a745;
        border: 3px solid white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    
    .user-marker {
        background-color: #4361ee;
        border: 3px solid white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    
    .lost-marker i,
    .found-marker i,
    .user-marker i {
        color: white;
        font-size: 14px;
    }
    
    .marker-popup {
        min-width: 200px;
    }
    
    .marker-popup img {
        width: 100%;
        height: auto;
        border-radius: 5px;
    }
    
    #itemsTable tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    #itemsTable tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 250px;
    }
</style>
@endpush