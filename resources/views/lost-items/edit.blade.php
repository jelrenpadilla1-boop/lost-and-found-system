@extends('layouts.app')

@section('title', 'Edit Lost Item')

@section('content')
<div class="edit-page-wrapper">
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="fas fa-edit" style="color: var(--primary);"></i> Edit Lost Item
            </h1>
            <p>Update item details</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('lost-items.show', $lostItem) }}" class="btn-outline-pink">
                <i class="fas fa-arrow-left"></i> Back to Item
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Main Edit Form Card -->
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-pencil-alt" style="color: var(--primary);"></i> Edit Item Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('lost-items.update', $lostItem) }}" method="POST" enctype="multipart/form-data" id="editItemForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-sections">
                            <!-- Current Photo Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-image"></i>
                                    <h6>Current Photo</h6>
                                </div>
                                
                                <div class="photo-section">
                                    @if($lostItem->photo)
                                        <div class="current-photo-container">
                                            <img src="{{ asset('storage/' . $lostItem->photo) }}" 
                                                 class="current-photo" 
                                                 alt="{{ $lostItem->item_name }}">
                                            <div class="photo-actions">
                                                <div class="photo-checkbox">
                                                    <input type="checkbox" id="removePhoto" name="remove_photo">
                                                    <label for="removePhoto">
                                                        <i class="fas fa-trash"></i> Remove current photo
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="no-photo-container">
                                            <i class="fas fa-image placeholder-icon"></i>
                                            <p>No photo currently</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- New Photo Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-camera"></i>
                                    <h6>New Photo</h6>
                                </div>
                                
                                <div class="input-group-pink">
                                    <label for="photo" class="input-label">
                                        <i class="fas fa-camera"></i> Upload New Photo <span class="optional">(Optional)</span>
                                    </label>
                                    <div class="file-input-wrapper">
                                        <input type="file" 
                                               class="pink-file @error('photo') error @enderror" 
                                               id="photo" 
                                               name="photo" 
                                               accept="image/*">
                                        <div class="file-input-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <span class="file-text">Choose a new photo or drag it here</span>
                                            <span class="file-hint">Leave empty to keep current photo • Max size: 2MB</span>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-info-circle"></i>
                                    <h6>Basic Information</h6>
                                </div>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="input-group-pink">
                                            <label for="item_name" class="input-label">
                                                <i class="fas fa-tag"></i> Item Name <span class="required">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" 
                                                       class="pink-input @error('item_name') error @enderror" 
                                                       id="item_name" 
                                                       name="item_name" 
                                                       value="{{ old('item_name', $lostItem->item_name) }}" 
                                                       required>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            @error('item_name')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="input-group-pink">
                                            <label for="category" class="input-label">
                                                <i class="fas fa-list"></i> Category <span class="required">*</span>
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="pink-select @error('category') error @enderror" 
                                                        id="category" 
                                                        name="category" 
                                                        required>
                                                    <option value="">Select Category</option>
                                                    <option value="Electronics" {{ (old('category', $lostItem->category) == 'Electronics') ? 'selected' : '' }}>📱 Electronics</option>
                                                    <option value="Documents" {{ (old('category', $lostItem->category) == 'Documents') ? 'selected' : '' }}>📄 Documents</option>
                                                    <option value="Jewelry" {{ (old('category', $lostItem->category) == 'Jewelry') ? 'selected' : '' }}>💎 Jewelry</option>
                                                    <option value="Clothing" {{ (old('category', $lostItem->category) == 'Clothing') ? 'selected' : '' }}>👕 Clothing</option>
                                                    <option value="Bags" {{ (old('category', $lostItem->category) == 'Bags') ? 'selected' : '' }}>🎒 Bags</option>
                                                    <option value="Keys" {{ (old('category', $lostItem->category) == 'Keys') ? 'selected' : '' }}>🔑 Keys</option>
                                                    <option value="Wallet" {{ (old('category', $lostItem->category) == 'Wallet') ? 'selected' : '' }}>👛 Wallet</option>
                                                    <option value="Other" {{ (old('category', $lostItem->category) == 'Other') ? 'selected' : '' }}>📦 Other</option>
                                                </select>
                                                <i class="fas fa-chevron-down select-arrow"></i>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            @error('category')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="input-group-pink">
                                            <label for="date_lost" class="input-label">
                                                <i class="fas fa-calendar-alt"></i> Lost Date <span class="required">*</span>
                                            </label>
                                            <div class="date-wrapper">
                                                <input type="date" 
                                                       class="pink-date @error('date_lost') error @enderror" 
                                                       id="date_lost" 
                                                       name="date_lost" 
                                                       value="{{ old('date_lost', $lostItem->date_lost ? \Carbon\Carbon::parse($lostItem->date_lost)->format('Y-m-d') : '') }}" 
                                                       required>
                                                <i class="fas fa-calendar-alt date-icon"></i>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            @error('date_lost')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="input-group-pink">
                                            <label for="status" class="input-label">
                                                <i class="fas fa-circle"></i> Status <span class="required">*</span>
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="pink-select @error('status') error @enderror" 
                                                        id="status" 
                                                        name="status" 
                                                        required>
                                                    <option value="pending" {{ (old('status', $lostItem->status) == 'pending') ? 'selected' : '' }}>⏳ Still Lost</option>
                                                    <option value="found" {{ (old('status', $lostItem->status) == 'found') ? 'selected' : '' }}>✅ Found</option>
                                                    <option value="returned" {{ (old('status', $lostItem->status) == 'returned') ? 'selected' : '' }}>🔄 Returned</option>
                                                </select>
                                                <i class="fas fa-chevron-down select-arrow"></i>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            @error('status')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="input-group-pink">
                                            <label for="description" class="input-label">
                                                <i class="fas fa-align-left"></i> Description <span class="required">*</span>
                                            </label>
                                            <div class="textarea-wrapper">
                                                <textarea class="pink-textarea @error('description') error @enderror" 
                                                          id="description" 
                                                          name="description" 
                                                          rows="4" 
                                                          required>{{ old('description', $lostItem->description) }}</textarea>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            <div class="input-hint">
                                                <i class="fas fa-info-circle"></i>
                                                <span>Provide detailed description including color, size, brand, distinctive features, etc.</span>
                                            </div>
                                            @error('description')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information Section -->
                            <div class="form-section location-section">
                                <div class="section-header">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <h6>Location Information</h6>
                                </div>
                                
                                <div class="location-content">
                                    <div class="location-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Update the location where you lost the item.</span>
                                    </div>
                                    
                                    <div class="row g-4">
                                        <!-- Lost Location Field -->
                                        <div class="col-12">
                                            <div class="input-group-pink">
                                                <label for="lost_location" class="input-label">
                                                    <i class="fas fa-map-marked-alt"></i> Lost Location <span class="optional">(Optional)</span>
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="text" 
                                                           class="pink-input @error('lost_location') error @enderror" 
                                                           id="lost_location" 
                                                           name="lost_location" 
                                                           value="{{ old('lost_location', $lostItem->lost_location) }}" 
                                                           placeholder="e.g., Central Park, New York City or 123 Main St">
                                                    <div class="input-focus-effect"></div>
                                                </div>
                                                @error('lost_location')
                                                    <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="input-group-pink">
                                                <label for="latitude" class="input-label">
                                                    <i class="fas fa-map-pin"></i> Latitude
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="number" 
                                                           step="any" 
                                                           class="pink-input @error('latitude') error @enderror" 
                                                           id="latitude" 
                                                           name="latitude" 
                                                           value="{{ old('latitude', $lostItem->latitude) }}" 
                                                           placeholder="e.g., 40.7128" 
                                                           min="-90" 
                                                           max="90">
                                                    <div class="input-focus-effect"></div>
                                                </div>
                                                @error('latitude')
                                                    <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="input-group-pink">
                                                <label for="longitude" class="input-label">
                                                    <i class="fas fa-map-pin"></i> Longitude
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="number" 
                                                           step="any" 
                                                           class="pink-input @error('longitude') error @enderror" 
                                                           id="longitude" 
                                                           name="longitude" 
                                                           value="{{ old('longitude', $lostItem->longitude) }}" 
                                                           placeholder="e.g., -74.0060" 
                                                           min="-180" 
                                                           max="180">
                                                    <div class="input-focus-effect"></div>
                                                </div>
                                                @error('longitude')
                                                    <div class="error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="location-actions">
                                                <button type="button" class="btn-location" onclick="getCurrentLocation()">
                                                    <i class="fas fa-location-arrow"></i> Update with My Current Location
                                                </button>
                                                <button type="button" class="btn-location-outline" onclick="clearLocation()">
                                                    <i class="fas fa-times"></i> Clear Location
                                                </button>
                                            </div>
                                            <div id="locationStatus" class="location-status"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="button" class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash"></i> Delete Item
                                </button>
                                
                                <div class="action-group">
                                    <a href="{{ route('lost-items.show', $lostItem) }}" class="btn-cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn-submit">
                                        <i class="fas fa-save"></i> Update Item
                                        <div class="btn-glow"></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle" style="color: var(--error);"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <div class="delete-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <p class="warning-title">Are you sure you want to delete this lost item?</p>
                        <p class="warning-text">This action cannot be undone. All associated data will be permanently removed.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('lost-items.destroy', $lostItem) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete-confirm">
                        <i class="fas fa-trash"></i> Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-dark: #c71585;
    --primary-glow: rgba(255, 20, 147, 0.3);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-header: #222;
    --bg-input: #000000;
    --bg-input-hover: #1a1a1a;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #e0e0e0;
    --text-muted: #a0a0a0;
    --text-dark: #666;
    --success: #00fa9a;
    --error: #ff4444;
    --warning: #ffa500;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Edit Page Wrapper */
.edit-page-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 0;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title p {
    color: var(--text-muted);
    margin: 0;
    font-size: 15px;
}

/* Back Button */
.btn-outline-pink {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: 2px solid var(--primary);
    border-radius: 30px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.btn-outline-pink::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: var(--primary-glow);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: -1;
}

.btn-outline-pink:hover {
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
}

.btn-outline-pink:hover::before {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
}

/* Form Card */
.form-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 20px;
    transition: var(--transition);
}

.form-card:hover {
    border-color: var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
}

.card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 20px 24px;
}

.card-header h5 {
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

.card-body {
    padding: 30px;
}

/* Form Sections */
.form-sections {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.form-section {
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 30px;
}

.form-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.section-header i {
    color: var(--primary);
    font-size: 18px;
    background: rgba(255, 20, 147, 0.1);
    padding: 8px;
    border-radius: 12px;
}

.section-header h6 {
    color: var(--text-primary);
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

/* Photo Section */
.photo-section {
    text-align: center;
}

.current-photo-container {
    position: relative;
    display: inline-block;
    max-width: 100%;
}

.current-photo {
    max-height: 200px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    transition: var(--transition);
}

.current-photo:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px var(--primary-glow);
}

.photo-actions {
    margin-top: 15px;
}

.photo-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255, 68, 68, 0.1);
    border: 1px solid var(--error);
    border-radius: 30px;
    transition: var(--transition);
}

.photo-checkbox:hover {
    background: rgba(255, 68, 68, 0.2);
    transform: translateY(-2px);
}

.photo-checkbox input[type="checkbox"] {
    accent-color: var(--error);
    width: 16px;
    height: 16px;
}

.photo-checkbox label {
    color: var(--error);
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
}

.photo-checkbox label i {
    font-size: 12px;
}

.no-photo-container {
    padding: 30px;
    background: var(--bg-input);
    border-radius: 16px;
    border: 2px dashed var(--border-color);
}

.no-photo-container .placeholder-icon {
    font-size: 48px;
    color: var(--primary);
    opacity: 0.5;
    margin-bottom: 10px;
}

.no-photo-container p {
    color: var(--text-muted);
    margin: 0;
}

/* Input Groups */
.input-group-pink {
    width: 100%;
}

.input-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
}

.input-label i {
    color: var(--primary);
    font-size: 14px;
}

.required {
    color: var(--error);
    margin-left: 4px;
}

.optional {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 400;
    margin-left: 4px;
}

/* Input Wrappers */
.input-wrapper,
.select-wrapper,
.textarea-wrapper,
.date-wrapper {
    position: relative;
    width: 100%;
}

/* Pink Input */
.pink-input,
.pink-select,
.pink-textarea,
.pink-date {
    width: 100%;
    padding: 14px 18px;
    background: var(--bg-input);
    border: 2px solid var(--border-color);
    border-radius: 14px;
    color: var(--text-primary);
    font-size: 15px;
    transition: var(--transition);
    position: relative;
    z-index: 1;
}

.pink-input:focus,
.pink-select:focus,
.pink-textarea:focus,
.pink-date:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--bg-input-hover);
    transform: scale(1.02);
}

.pink-input.error,
.pink-select.error,
.pink-textarea.error,
.pink-date.error {
    border-color: var(--error);
}

.pink-input::placeholder,
.pink-textarea::placeholder {
    color: var(--text-dark);
}

/* Select Styling */
.select-wrapper {
    position: relative;
}

.pink-select {
    appearance: none;
    cursor: pointer;
}

.select-arrow {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    pointer-events: none;
    z-index: 2;
    transition: var(--transition);
}

.select-wrapper:hover .select-arrow {
    transform: translateY(-50%) rotate(180deg);
}

/* Date Input Styling */
.date-wrapper {
    position: relative;
}

.pink-date {
    padding-right: 45px;
}

.date-icon {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    pointer-events: none;
    z-index: 2;
}

/* Input Focus Effect */
.input-focus-effect {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 14px;
    background: radial-gradient(circle at var(--x, 50%) var(--y, 50%), var(--primary-glow) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    z-index: 2;
}

.pink-input:focus ~ .input-focus-effect,
.pink-select:focus ~ .input-focus-effect,
.pink-textarea:focus ~ .input-focus-effect,
.pink-date:focus ~ .input-focus-effect {
    opacity: 0.3;
}

/* Input Hint */
.input-hint {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 8px;
    color: var(--text-muted);
    font-size: 12px;
    line-height: 1.5;
}

.input-hint i {
    color: var(--primary);
    font-size: 12px;
    margin-top: 2px;
}

/* Error Message */
.error-message {
    color: var(--error);
    font-size: 12px;
    margin-top: 6px;
    animation: slideIn 0.3s ease;
}

/* File Input */
.file-input-wrapper {
    position: relative;
    cursor: pointer;
}

.pink-file {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 3;
}

.file-input-content {
    border: 2px dashed var(--border-color);
    border-radius: 14px;
    padding: 30px 20px;
    text-align: center;
    transition: var(--transition);
    background: var(--bg-input);
}

.file-input-wrapper:hover .file-input-content {
    border-color: var(--primary);
    background: var(--bg-input-hover);
    transform: translateY(-2px);
    box-shadow: 0 5px 20px var(--primary-glow);
}

.upload-icon {
    font-size: 40px;
    color: var(--primary);
    margin-bottom: 10px;
    animation: float 3s infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.file-text {
    display: block;
    color: var(--text-primary);
    font-size: 14px;
    margin-bottom: 5px;
}

.file-hint {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
}

/* Location Section */
.location-section {
    background: rgba(255, 20, 147, 0.05);
    border-radius: 20px;
    padding: 20px;
}

.location-hint {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 12px 16px;
    background: rgba(255, 20, 147, 0.1);
    border-radius: 12px;
    margin-bottom: 20px;
    color: var(--text-muted);
    font-size: 13px;
}

.location-hint i {
    color: var(--primary);
    font-size: 14px;
    margin-top: 2px;
}

.location-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-location {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.btn-location::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-location:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px var(--primary-glow);
}

.btn-location:hover::before {
    width: 300px;
    height: 300px;
}

.btn-location-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-location-outline:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px var(--primary-glow);
    border-color: transparent;
}

.location-status {
    margin-top: 12px;
    font-size: 13px;
    animation: fadeIn 0.3s ease;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.action-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: transparent;
    border: 2px solid var(--error);
    color: var(--error);
    border-radius: 30px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-delete:hover {
    background: var(--error);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
}

.btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: transparent;
    border: 2px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 30px;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
}

.btn-cancel:hover {
    border-color: var(--text-muted);
    color: var(--text-primary);
    transform: translateY(-2px);
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 20px var(--primary-glow);
}

.btn-submit .btn-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: 1;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.btn-submit:hover .btn-glow {
    width: 300px;
    height: 300px;
}

.btn-submit i,
.btn-submit span {
    position: relative;
    z-index: 2;
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Modal Styles */
.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 20px;
    box-shadow: 0 10px 40px var(--primary-glow);
}

.modal-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 20px 24px;
}

.modal-title {
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
    padding: 16px 24px;
}

.delete-warning {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: rgba(255, 68, 68, 0.1);
    border: 1px solid var(--error);
    border-radius: 16px;
}

.delete-warning i {
    color: var(--error);
    font-size: 24px;
}

.warning-title {
    color: var(--error);
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 15px;
}

.warning-text {
    color: var(--text-muted);
    font-size: 13px;
    margin: 0;
}

.btn-delete-confirm {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--error);
    border: none;
    color: white;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-delete-confirm:hover {
    background: #ff6b6b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

/* Animations */
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

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Mouse move effect for inputs */
.input-wrapper,
.select-wrapper,
.textarea-wrapper,
.date-wrapper {
    --x: 50%;
    --y: 50%;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .btn-outline-pink {
        width: 100%;
        justify-content: center;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .action-group {
        width: 100%;
    }
    
    .btn-cancel,
    .btn-submit,
    .btn-delete {
        flex: 1;
        justify-content: center;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 5px;
    box-shadow: 0 0 10px var(--primary-glow);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
}
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('editItemForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const lostDate = document.getElementById('date_lost');
                const today = new Date().toISOString().split('T')[0];
                
                if (lostDate.value > today) {
                    e.preventDefault();
                    showToast('Lost date cannot be in the future.', 'error');
                    lostDate.focus();
                }
            });
        }
        
        // Image preview for new photo
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    showToast('New image selected: ' + file.name, 'info');
                }
            });
        }
        
        // Handle remove photo checkbox
        const removePhotoCheckbox = document.getElementById('removePhoto');
        if (removePhotoCheckbox) {
            removePhotoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    photoInput.disabled = true;
                    photoInput.value = '';
                    showToast('Current photo will be removed upon save', 'warning');
                } else {
                    photoInput.disabled = false;
                }
            });
        }

        // Mouse move effect for inputs
        const inputs = document.querySelectorAll('.pink-input, .pink-select, .pink-textarea, .pink-date');
        
        inputs.forEach(input => {
            input.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                
                const wrapper = this.closest('.input-wrapper, .select-wrapper, .textarea-wrapper, .date-wrapper');
                if (wrapper) {
                    wrapper.style.setProperty('--x', `${x}%`);
                    wrapper.style.setProperty('--y', `${y}%`);
                }
            });
        });

        // Show toast notification
        function showToast(message, type = 'info') {
            const container = document.getElementById('notificationsContainer');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center border-0 mb-2';
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
    });

    // Get current location
    function getCurrentLocation() {
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const lostLocationInput = document.getElementById('lost_location');
        const statusDiv = document.getElementById('locationStatus');
        
        statusDiv.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Getting location...</span>';
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    
                    latitudeInput.value = lat;
                    longitudeInput.value = lng;
                    
                    statusDiv.innerHTML = `<span class="text-success" style="color: var(--success);"><i class="fas fa-check-circle"></i> Location retrieved: ${lat}, ${lng}</span>`;
                    
                    // Reverse geocode to get address
                    reverseGeocode(lat, lng, lostLocationInput);
                    
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
                    
                    statusDiv.innerHTML = `<span class="text-danger" style="color: var(--error);"><i class="fas fa-exclamation-circle"></i> ${errorMessage}</span>`;
                    showToast('Failed to get location', 'error');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            statusDiv.innerHTML = '<span class="text-danger" style="color: var(--error);"><i class="fas fa-exclamation-circle"></i> Geolocation is not supported by your browser.</span>';
            showToast('Geolocation not supported', 'error');
        }
    }

    // Reverse geocoding function using OpenStreetMap Nominatim
    function reverseGeocode(lat, lng, inputElement) {
        if (!inputElement) return;
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    inputElement.value = data.display_name;
                    showToast('Address found!', 'success');
                }
            })
            .catch(error => {
                console.error('Reverse geocoding failed:', error);
            });
    }

    // Clear location
    function clearLocation() {
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('lost_location').value = '';
        document.getElementById('locationStatus').innerHTML = '';
        showToast('Location cleared', 'info');
    }

    // Show toast notification (duplicate function for edit page)
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) {
            // Create container if it doesn't exist
            const newContainer = document.createElement('div');
            newContainer.id = 'notificationsContainer';
            document.body.appendChild(newContainer);
        }
        
        const toastContainer = document.getElementById('notificationsContainer');
        
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center border-0 mb-2';
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
        
        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
</script>
@endsection