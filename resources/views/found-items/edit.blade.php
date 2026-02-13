@extends('layouts.app')

@section('title', 'Edit Found Item')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-edit text-primary"></i> Edit Found Item
        </h1>
        <p>Update item details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('found-items.show', $foundItem) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Item
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-pencil-alt"></i> Edit Item Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('found-items.update', $foundItem) }}" method="POST" enctype="multipart/form-data" id="editItemForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Current Photo -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Current Photo</label>
                                <div class="text-center">
                                    @if($foundItem->photo)
                                        <img src="{{ asset('storage/' . $foundItem->photo) }}" 
                                             class="img-fluid rounded mb-3" 
                                             style="max-height: 200px;"
                                             alt="{{ $foundItem->item_name }}">
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="removePhoto" name="remove_photo">
                                                <label class="form-check-label text-danger" for="removePhoto">
                                                    <i class="fas fa-trash"></i> Remove photo
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center mb-3" 
                                             style="height: 150px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                        <small class="text-muted">No photo currently</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- New Photo -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="photo" class="form-label">New Photo (Optional)</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <small class="text-muted">Leave empty to keep current photo. Max size: 2MB</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_name" class="form-label">Item Name *</label>
                                <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                                       id="item_name" name="item_name" value="{{ old('item_name', $foundItem->item_name) }}" required>
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
                                    <option value="Electronics" {{ (old('category', $foundItem->category) == 'Electronics') ? 'selected' : '' }}>Electronics</option>
                                    <option value="Documents" {{ (old('category', $foundItem->category) == 'Documents') ? 'selected' : '' }}>Documents</option>
                                    <option value="Jewelry" {{ (old('category', $foundItem->category) == 'Jewelry') ? 'selected' : '' }}>Jewelry</option>
                                    <option value="Clothing" {{ (old('category', $foundItem->category) == 'Clothing') ? 'selected' : '' }}>Clothing</option>
                                    <option value="Bags" {{ (old('category', $foundItem->category) == 'Bags') ? 'selected' : '' }}>Bags</option>
                                    <option value="Keys" {{ (old('category', $foundItem->category) == 'Keys') ? 'selected' : '' }}>Keys</option>
                                    <option value="Wallet" {{ (old('category', $foundItem->category) == 'Wallet') ? 'selected' : '' }}>Wallet</option>
                                    <option value="Other" {{ (old('category', $foundItem->category) == 'Other') ? 'selected' : '' }}>Other</option>
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
                                          id="description" name="description" rows="4" required>{{ old('description', $foundItem->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Date & Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_found" class="form-label">Date Found *</label>
                                <input type="date" class="form-control @error('date_found') is-invalid @enderror" 
                                       id="date_found" name="date_found" value="{{ old('date_found', $foundItem->date_found->format('Y-m-d')) }}" required>
                                @error('date_found')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ (old('status', $foundItem->status) == 'pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="claimed" {{ (old('status', $foundItem->status) == 'claimed') ? 'selected' : '' }}>Claimed</option>
                                    <option value="disposed" {{ (old('status', $foundItem->status) == 'disposed') ? 'selected' : '' }}>Disposed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Location -->
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-map-marker-alt"></i> Location Information
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="latitude" class="form-label">Latitude</label>
                                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                                       id="latitude" name="latitude" value="{{ old('latitude', $foundItem->latitude) }}" 
                                                       min="-90" max="90">
                                                @error('latitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="longitude" class="form-label">Longitude</label>
                                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                                       id="longitude" name="longitude" value="{{ old('longitude', $foundItem->longitude) }}" 
                                                       min="-180" max="180">
                                                @error('longitude')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <button type="button" class="btn btn-outline-primary" onclick="getCurrentLocation()">
                                                <i class="fas fa-location-arrow"></i> Use Current Location
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="clearLocation()">
                                                <i class="fas fa-times"></i> Clear Location
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('found-items.show', $foundItem) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Item
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Warning Card -->
        <div class="card mt-4 border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Important Notes
                </h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Updating item details will trigger the AI matching system to find new potential matches</li>
                    <li>Changing the status to "Claimed" will notify the owner of any confirmed matches</li>
                    <li>Location updates may affect match scores with lost items</li>
                    <li>All changes are logged and can be reviewed</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Get current location
    function getCurrentLocation() {
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitudeInput.value = position.coords.latitude.toFixed(6);
                    longitudeInput.value = position.coords.longitude.toFixed(6);
                    showToast('Location updated successfully!', 'success');
                },
                function(error) {
                    showToast('Unable to retrieve location. Please enter manually.', 'error');
                }
            );
        } else {
            showToast('Geolocation is not supported by your browser.', 'error');
        }
    }
    
    // Clear location
    function clearLocation() {
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
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
    document.getElementById('editItemForm').addEventListener('submit', function(e) {
        const itemName = document.getElementById('item_name').value.trim();
        const category = document.getElementById('category').value;
        const description = document.getElementById('description').value.trim();
        const dateFound = document.getElementById('date_found').value;
        const status = document.getElementById('status').value;
        
        if (!itemName || !category || !description || !dateFound || !status) {
            e.preventDefault();
            showToast('Please fill in all required fields', 'error');
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds if still processing
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
</script>
@endpush