@extends('layouts.app')

@section('title', 'Report Found Item')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-check-circle text-success"></i> Report Found Item
        </h1>
        <p>Help someone find their lost item by reporting it here</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('found-items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Item Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('found-items.store') }}" method="POST" enctype="multipart/form-data" id="foundItemForm">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-info-circle"></i> Basic Information
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_name" class="form-label">Item Name *</label>
                                <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                                       id="item_name" name="item_name" value="{{ old('item_name') }}" 
                                       placeholder="e.g., iPhone, Wallet, Keys" required>
                                @error('item_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>📱 Electronics</option>
                                    <option value="Documents" {{ old('category') == 'Documents' ? 'selected' : '' }}>📄 Documents</option>
                                    <option value="Jewelry" {{ old('category') == 'Jewelry' ? 'selected' : '' }}>💎 Jewelry</option>
                                    <option value="Clothing" {{ old('category') == 'Clothing' ? 'selected' : '' }}>👕 Clothing</option>
                                    <option value="Bags" {{ old('category') == 'Bags' ? 'selected' : '' }}>🎒 Bags</option>
                                    <option value="Keys" {{ old('category') == 'Keys' ? 'selected' : '' }}>🔑 Keys</option>
                                    <option value="Wallet" {{ old('category') == 'Wallet' ? 'selected' : '' }}>👛 Wallet/Purse</option>
                                    <option value="Books" {{ old('category') == 'Books' ? 'selected' : '' }}>📚 Books</option>
                                    <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>📦 Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Describe the item in detail (color, brand, condition, distinguishing marks, contents, etc.)" required>{{ old('description') }}</textarea>
                                <small class="text-muted">Detailed descriptions increase the chances of finding the owner.</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Date & Photo -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_found" class="form-label">Date Found *</label>
                                <input type="date" class="form-control @error('date_found') is-invalid @enderror" 
                                       id="date_found" name="date_found" value="{{ old('date_found', date('Y-m-d')) }}" required>
                                @error('date_found')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo" class="form-label">Photo (Optional)</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <small class="text-muted">Max size: 2MB • Formats: JPG, PNG, GIF</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Location Information -->
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-map-marker-alt"></i> Location Information
                                    </h6>
                                    <p class="text-muted small mb-3">
                                        Providing location helps match with lost items from the same area. 
                                        You can use your current location or enter manually.
                                    </p>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="latitude" class="form-label">Latitude</label>
                                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                                       id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                                       placeholder="e.g., 40.7128" min="-90" max="90">
                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="longitude" class="form-label">Longitude</label>
                                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                                       id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                                       placeholder="e.g., -74.0060" min="-180" max="180">
                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-primary" onclick="getCurrentLocation()">
                                                    <i class="fas fa-location-arrow"></i> Use My Current Location
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" onclick="clearLocation()">
                                                    <i class="fas fa-times"></i> Clear Location
                                                </button>
                                            </div>
                                            <div id="locationStatus" class="mt-2 small"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photo Preview -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Photo Preview</label>
                                <div class="border rounded p-3 text-center" id="photoPreview" style="min-height: 150px; background-color: #f8f9fa;">
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No photo selected</p>
                                        <small class="text-muted">Preview will appear here</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('found-items.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane"></i> Submit Found Item
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Information -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="fas fa-lightbulb text-warning"></i> Tips for Reporting Found Items
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Be as detailed as possible in your description</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Upload clear photos from multiple angles</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Provide accurate location information</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Report the item as soon as possible</li>
                    <li class="mb-0"><i class="fas fa-check text-success me-2"></i> Keep the item safe until claimed</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Photo preview
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('photoPreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">
                    <div class="mt-2">
                        <small class="text-muted">${file.name} (${(file.size / 1024).toFixed(2)} KB)</small>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <i class="fas fa-image fa-3x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No photo selected</p>
                    <small class="text-muted">Preview will appear here</small>
                </div>
            `;
        }
    });

    // Get current location
    function getCurrentLocation() {
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const statusDiv = document.getElementById('locationStatus');
        
        statusDiv.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Getting location...</span>';
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    
                    latitudeInput.value = lat;
                    longitudeInput.value = lng;
                    
                    statusDiv.innerHTML = `<span class="text-success"><i class="fas fa-check"></i> Location retrieved: ${lat}, ${lng}</span>`;
                    
                    // Show success toast
                    showToast('Location retrieved successfully!', 'success');
                },
                function(error) {
                    let errorMessage = 'Unable to retrieve location. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Please enable location services in your browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location information is unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Location request timed out.';
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage += 'An unknown error occurred.';
                            break;
                    }
                    
                    statusDiv.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${errorMessage}</span>`;
                    showToast('Failed to get location', 'error');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            statusDiv.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Geolocation is not supported by your browser.</span>';
            showToast('Geolocation not supported', 'error');
        }
    }

    // Clear location
    function clearLocation() {
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('locationStatus').innerHTML = '';
        showToast('Location cleared', 'info');
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

    // Form validation
    document.getElementById('foundItemForm').addEventListener('submit', function(e) {
        const itemName = document.getElementById('item_name').value.trim();
        const category = document.getElementById('category').value;
        const description = document.getElementById('description').value.trim();
        const dateFound = document.getElementById('date_found').value;
        
        if (!itemName || !category || !description || !dateFound) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds if still processing
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
</script>

<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }
    
    #photoPreview {
        transition: all 0.3s ease;
    }
    
    #photoPreview:hover {
        box-shadow: 0 0 0 2px #4361ee;
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