@extends('layouts.app')

@section('title', 'Report Found Item')

@section('content')
<div class="create-page-wrapper">
    <div class="page-header">
        <div class="page-title">
            <h1>
                <i class="fas fa-check-circle" style="color: var(--primary);"></i> Report Found Item
            </h1>
            <p>Help someone find their lost item by reporting it here</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('found-items.index') }}" class="btn-outline-pink">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Main Form Card -->
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle" style="color: var(--primary);"></i> Item Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('found-items.store') }}" method="POST" enctype="multipart/form-data" id="foundItemForm">
                        @csrf
                        
                        <div class="form-sections">
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
                                                       value="{{ old('item_name') }}" 
                                                       placeholder="e.g., iPhone, Wallet, Keys" 
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
                                                <i class="fas fa-chevron-down select-arrow"></i>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            @error('category')
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
                                                          rows="5" 
                                                          placeholder="Describe the item in detail (color, brand, condition, distinguishing marks, contents, etc.)" 
                                                          required>{{ old('description') }}</textarea>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            <div class="input-hint">
                                                <i class="fas fa-info-circle"></i>
                                                <span>Detailed descriptions increase the chances of finding the owner.</span>
                                            </div>
                                            @error('description')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Photo Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-calendar"></i>
                                    <h6>Date & Photo</h6>
                                </div>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="input-group-pink">
                                            <label for="date_found" class="input-label">
                                                <i class="fas fa-calendar-alt"></i> Date Found <span class="required">*</span>
                                            </label>
                                            <div class="date-wrapper">
                                                <input type="date" 
                                                       class="pink-date @error('date_found') error @enderror" 
                                                       id="date_found" 
                                                       name="date_found" 
                                                       value="{{ old('date_found', date('Y-m-d')) }}" 
                                                       required>
                                                <i class="fas fa-calendar-alt date-icon"></i>
                                                <div class="input-focus-effect"></div>
                                            </div>
                                            @error('date_found')
                                                <div class="error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="input-group-pink">
                                            <label for="photo" class="input-label">
                                                <i class="fas fa-camera"></i> Photo <span class="optional">(Optional)</span>
                                            </label>
                                            <div class="file-input-wrapper">
                                                <input type="file" 
                                                       class="pink-file @error('photo') error @enderror" 
                                                       id="photo" 
                                                       name="photo" 
                                                       accept="image/*">
                                                <div class="file-input-content">
                                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                                    <span class="file-text">Choose a file or drag it here</span>
                                                    <span class="file-hint">Max size: 2MB • JPG, PNG, GIF</span>
                                                </div>
                                            </div>
                                            @error('photo')
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
                                        <span>Providing location helps match with lost items from the same area. You can use your current location or enter manually.</span>
                                    </div>
                                    
                                    <div class="row g-4">
                                        <!-- Found Location Field -->
                                        <div class="col-12">
                                            <div class="input-group-pink">
                                                <label for="found_location" class="input-label">
                                                    <i class="fas fa-map-marked-alt"></i> Found Location <span class="optional">(Optional)</span>
                                                </label>
                                                <div class="input-wrapper">
                                                    <input type="text" 
                                                           class="pink-input @error('found_location') error @enderror" 
                                                           id="found_location" 
                                                           name="found_location" 
                                                           value="{{ old('found_location') }}" 
                                                           placeholder="e.g., Central Park, New York City or 123 Main St">
                                                    <div class="input-focus-effect"></div>
                                                </div>
                                                @error('found_location')
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
                                                           value="{{ old('latitude') }}" 
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
                                                           value="{{ old('longitude') }}" 
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
                                                    <i class="fas fa-location-arrow"></i> Use My Current Location
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

                            <!-- Photo Preview Section -->
                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-image"></i>
                                    <h6>Photo Preview</h6>
                                </div>
                                
                                <div class="photo-preview-container" id="photoPreview">
                                    <div class="preview-placeholder">
                                        <i class="fas fa-image placeholder-icon"></i>
                                        <p>No photo selected</p>
                                        <small>Preview will appear here</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="{{ route('found-items.index') }}" class="btn-cancel">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-paper-plane"></i> Submit Found Item
                                    <div class="btn-glow"></div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Information Card -->
            <div class="help-card">
                <div class="help-header">
                    <i class="fas fa-lightbulb" style="color: var(--primary);"></i>
                    <h6>Tips for Reporting Found Items</h6>
                </div>
                <div class="help-content">
                    <ul class="tips-list">
                        <li><i class="fas fa-check-circle"></i> Be as detailed as possible in your description</li>
                        <li><i class="fas fa-check-circle"></i> Upload clear photos from multiple angles</li>
                        <li><i class="fas fa-check-circle"></i> Provide accurate location information</li>
                        <li><i class="fas fa-check-circle"></i> Report the item as soon as possible</li>
                        <li><i class="fas fa-check-circle"></i> Keep the item safe until claimed</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Container -->
<div id="notificationsContainer"></div>

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

/* Create Page Wrapper */
.create-page-wrapper {
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

/* Photo Preview */
.photo-preview-container {
    min-height: 200px;
    border: 2px dashed var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    transition: var(--transition);
}

.photo-preview-container:hover {
    border-color: var(--primary);
    box-shadow: 0 0 20px var(--primary-glow);
}

.preview-placeholder {
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.placeholder-icon {
    font-size: 48px;
    color: var(--primary);
    opacity: 0.5;
    margin-bottom: 10px;
}

.preview-placeholder p {
    margin: 0;
    font-size: 14px;
}

.preview-placeholder small {
    font-size: 12px;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
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
    border-color: var(--error);
    color: var(--error);
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

/* Help Card */
.help-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    margin-top: 20px;
    transition: var(--transition);
}

.help-card:hover {
    border-color: var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
}

.help-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.help-header h6 {
    color: var(--text-primary);
    font-weight: 600;
    margin: 0;
}

.help-content {
    padding: 20px;
}

.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text-secondary);
    font-size: 14px;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
}

.tips-list li:last-child {
    border-bottom: none;
}

.tips-list li:hover {
    transform: translateX(5px);
    color: var(--text-primary);
}

.tips-list li i {
    color: var(--primary);
    font-size: 14px;
}

/* Toast Notifications */
#notificationsContainer {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 12px;
    min-width: 300px;
    box-shadow: 0 5px 20px var(--primary-glow);
}

.toast-body {
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
}

.btn-close-white {
    filter: invert(1);
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
    
    .location-actions {
        flex-direction: column;
    }
    
    .btn-location,
    .btn-location-outline {
        width: 100%;
        justify-content: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-cancel,
    .btn-submit {
        width: 100%;
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
                    <div class="position-relative" style="max-height: 200px; overflow: hidden;">
                        <img src="${e.target.result}" class="img-fluid" style="width: 100%; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <small class="text-white">${file.name} (${(file.size / 1024).toFixed(2)} KB)</small>
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = `
                <div class="preview-placeholder">
                    <i class="fas fa-image placeholder-icon"></i>
                    <p>No photo selected</p>
                    <small>Preview will appear here</small>
                </div>
            `;
        }
    });

    // Get current location
    function getCurrentLocation() {
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const foundLocationInput = document.getElementById('found_location');
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
                    reverseGeocode(lat, lng, foundLocationInput);
                    
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
        document.getElementById('found_location').value = '';
        document.getElementById('locationStatus').innerHTML = '';
        showToast('Location cleared', 'info');
    }

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
</script>
@endpush