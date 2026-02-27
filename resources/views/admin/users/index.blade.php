@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="dashboard-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <h1>
                <i class="fas fa-users-cog" style="color: var(--primary);"></i> Users Management
            </h1>
            <p>Manage user accounts and permissions</p>
        </div>
        <div class="header-actions">
            <div class="btn-group">
                <button type="button" class="btn-add-user" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-user-plus me-2"></i>Add User
                </button>
                <button type="button" class="btn-add-user dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Add Options</h6></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-user-plus me-2"></i>Add Single User
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                        <i class="fas fa-file-import me-2"></i>Bulk Import Users
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" id="downloadTemplateBtn">
                        <i class="fas fa-download me-2"></i>Download CSV Template
                    </a></li>
                    <li><a class="dropdown-item" href="#" id="exportUsersBtn">
                        <i class="fas fa-file-export me-2"></i>Export All Users
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Cards (same as before) -->
    <div class="stats-grid">
        <!-- ... existing stats cards ... -->
    </div>

    <!-- Active Filter Indicators (same as before) -->
    <!-- ... existing filter indicators ... -->

    <!-- Users Table (same as before) -->
    <!-- ... existing users table ... -->

    <!-- Add User Modal (Enhanced) -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus" style="color: var(--primary);"></i> Add New User
                    </h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="addUserTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab">
                            <i class="fas fa-info-circle"></i> Basic Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                            <i class="fas fa-shield-alt"></i> Permissions
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                            <i class="fas fa-bell"></i> Notifications
                        </button>
                    </li>
                </ul>
                
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="addUserForm">
                    @csrf
                    
                    <div class="tab-content">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                            <div class="modal-body">
                                <!-- Profile Photo Upload -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-camera"></i> Profile Photo
                                    </label>
                                    <div class="photo-upload-area" id="photoUploadArea">
                                        <div class="photo-preview" id="photoPreview">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="photo-upload-actions">
                                            <input type="file" 
                                                   class="photo-input" 
                                                   id="profile_photo" 
                                                   name="profile_photo" 
                                                   accept="image/*"
                                                   onchange="previewImage(this)">
                                            <label for="profile_photo" class="btn-upload">
                                                <i class="fas fa-cloud-upload-alt"></i> Choose Photo
                                            </label>
                                            <button type="button" class="btn-clear-photo" onclick="clearPhoto()" style="display: none;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <p class="upload-hint">Max 2MB. JPG, PNG, GIF. Recommended: 500x500px</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i> First Name <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-input" 
                                                   name="first_name" 
                                                   value="{{ old('first_name') }}" 
                                                   required 
                                                   placeholder="Enter first name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i> Last Name <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-input" 
                                                   name="last_name" 
                                                   value="{{ old('last_name') }}" 
                                                   required 
                                                   placeholder="Enter last name">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-input" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           placeholder="user@example.com">
                                    <small class="form-hint">A verification email will be sent to this address</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-lock"></i> Password <span class="required">*</span>
                                            </label>
                                            <div class="password-field">
                                                <input type="password" 
                                                       class="form-input" 
                                                       name="password" 
                                                       id="password"
                                                       required 
                                                       placeholder="Minimum 8 characters">
                                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-strength" id="passwordStrength">
                                                <div class="strength-bar"></div>
                                            </div>
                                            <small class="form-hint">Use 8+ characters with a mix of letters, numbers & symbols</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-lock"></i> Confirm Password <span class="required">*</span>
                                            </label>
                                            <div class="password-field">
                                                <input type="password" 
                                                       class="form-input" 
                                                       name="password_confirmation" 
                                                       id="password_confirmation"
                                                       required 
                                                       placeholder="Re-enter password">
                                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-match" id="passwordMatch"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-phone"></i> Phone Number
                                            </label>
                                            <input type="tel" 
                                                   class="form-input" 
                                                   name="phone" 
                                                   value="{{ old('phone') }}" 
                                                   placeholder="+63 XXX XXX XXXX">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar"></i> Date of Birth
                                            </label>
                                            <input type="date" 
                                                   class="form-input" 
                                                   name="dob" 
                                                   value="{{ old('dob') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> Location
                                    </label>
                                    <input type="text" 
                                           class="form-input" 
                                           name="location" 
                                           value="{{ old('location') }}" 
                                           placeholder="City, Country">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-address-card"></i> Address
                                    </label>
                                    <textarea class="form-input" 
                                              name="address" 
                                              rows="2" 
                                              placeholder="Street address, Barangay, City">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Tab -->
                        <div class="tab-pane fade" id="permissions" role="tabpanel">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tag"></i> User Role <span class="required">*</span>
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select" name="role" required>
                                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Regular User</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                            <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                        </select>
                                        <i class="fas fa-chevron-down select-arrow"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-check-circle"></i> Account Status
                                    </label>
                                    <div class="status-options">
                                        <label class="status-option">
                                            <input type="radio" name="status" value="active" checked>
                                            <span class="status-badge active">Active</span>
                                        </label>
                                        <label class="status-option">
                                            <input type="radio" name="status" value="inactive">
                                            <span class="status-badge inactive">Inactive</span>
                                        </label>
                                        <label class="status-option">
                                            <input type="radio" name="status" value="suspended">
                                            <span class="status-badge suspended">Suspended</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-shield-alt"></i> Email Verification
                                    </label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="email_verified" id="email_verified" checked>
                                        <label for="email_verified">Mark email as verified</label>
                                    </div>
                                    <small class="form-hint">If unchecked, user will need to verify their email</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-clock"></i> Account Expiry
                                    </label>
                                    <div class="expiry-options">
                                        <label class="expiry-option">
                                            <input type="radio" name="expiry" value="never" checked>
                                            <span>Never expires</span>
                                        </label>
                                        <label class="expiry-option">
                                            <input type="radio" name="expiry" value="custom">
                                            <span>Set expiration date</span>
                                        </label>
                                    </div>
                                    <input type="date" 
                                           class="form-input mt-2" 
                                           name="expiry_date" 
                                           id="expiry_date" 
                                           style="display: none;">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-list"></i> Custom Permissions
                                    </label>
                                    <div class="permissions-grid">
                                        <label class="permission-item">
                                            <input type="checkbox" name="permissions[]" value="create_items">
                                            <span>Create Items</span>
                                        </label>
                                        <label class="permission-item">
                                            <input type="checkbox" name="permissions[]" value="edit_items">
                                            <span>Edit Items</span>
                                        </label>
                                        <label class="permission-item">
                                            <input type="checkbox" name="permissions[]" value="delete_items">
                                            <span>Delete Items</span>
                                        </label>
                                        <label class="permission-item">
                                            <input type="checkbox" name="permissions[]" value="manage_matches">
                                            <span>Manage Matches</span>
                                        </label>
                                        <label class="permission-item">
                                            <input type="checkbox" name="permissions[]" value="send_messages">
                                            <span>Send Messages</span>
                                        </label>
                                        <label class="permission-item">
                                            <input type="checkbox" name="permissions[]" value="view_analytics">
                                            <span>View Analytics</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-bell"></i> Welcome Email
                                    </label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="send_welcome_email" id="send_welcome_email" checked>
                                        <label for="send_welcome_email">Send welcome email with login instructions</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i> Email Notifications
                                    </label>
                                    <div class="notifications-list">
                                        <label class="notification-item">
                                            <input type="checkbox" name="notifications[]" value="match_alerts" checked>
                                            <span>Match Alerts</span>
                                        </label>
                                        <label class="notification-item">
                                            <input type="checkbox" name="notifications[]" value="message_alerts" checked>
                                            <span>Message Alerts</span>
                                        </label>
                                        <label class="notification-item">
                                            <input type="checkbox" name="notifications[]" value="item_updates" checked>
                                            <span>Item Status Updates</span>
                                        </label>
                                        <label class="notification-item">
                                            <input type="checkbox" name="notifications[]" value="newsletter">
                                            <span>Newsletter</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-bell"></i> Push Notifications
                                    </label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="push_notifications" id="push_notifications" checked>
                                        <label for="push_notifications">Enable push notifications</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <div class="footer-actions">
                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn-reset" onclick="resetForm()">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <i class="fas fa-save"></i> Create User
                                <div class="btn-glow"></div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Import Modal -->
    <div class="modal fade" id="bulkImportModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-import" style="color: var(--primary);"></i> Bulk Import Users
                    </h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="import-options">
                        <div class="import-option">
                            <i class="fas fa-file-csv"></i>
                            <h6>CSV Import</h6>
                            <p>Upload a CSV file with user data</p>
                            <button class="btn-upload" onclick="document.getElementById('csvFile').click()">
                                <i class="fas fa-upload"></i> Choose CSV
                            </button>
                            <input type="file" id="csvFile" accept=".csv" style="display: none;">
                        </div>
                        
                        <div class="import-option">
                            <i class="fas fa-file-excel"></i>
                            <h6>Excel Import</h6>
                            <p>Upload an Excel file with user data</p>
                            <button class="btn-upload" onclick="document.getElementById('excelFile').click()">
                                <i class="fas fa-upload"></i> Choose Excel
                            </button>
                            <input type="file" id="excelFile" accept=".xlsx,.xls" style="display: none;">
                        </div>
                    </div>

                    <div class="import-template">
                        <p><i class="fas fa-info-circle"></i> Don't have a template?</p>
                        <button class="btn-template" id="downloadTemplateBtn">
                            <i class="fas fa-download"></i> Download Template
                        </button>
                    </div>

                    <div class="import-preview" id="importPreview" style="display: none;">
                        <h6>Preview (First 5 rows)</h6>
                        <div class="preview-table"></div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-submit" id="importBtn" disabled>
                        <i class="fas fa-upload"></i> Import Users
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Modal (Mini form) -->
    <div class="modal fade" id="quickAddModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-bolt" style="color: var(--primary);"></i> Quick Add User
                    </h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('admin.users.store') }}" method="POST" id="quickAddForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-input" name="name" placeholder="Full Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-input" name="email" placeholder="Email Address" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <select class="form-select" name="role">
                                <option value="user">Regular User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-submit">Quick Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="toast-container" id="toastContainer"></div>
</div>

<style>
/* Add these new styles to your existing CSS */

/* Button Group */
.btn-group {
    display: flex;
    gap: 2px;
}

.btn-group .btn-add-user:first-child {
    border-radius: 30px 0 0 30px;
}

.btn-group .dropdown-toggle-split {
    border-radius: 0 30px 30px 0;
    padding: 12px 16px;
}

.btn-group .dropdown-toggle-split::after {
    margin-left: 0;
}

.dropdown-menu {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 16px;
    padding: 8px;
    margin-top: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    animation: slideDown 0.2s ease;
}

.dropdown-header {
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 8px 12px;
}

.dropdown-item {
    color: var(--text-secondary);
    padding: 8px 12px;
    border-radius: 8px;
    transition: var(--transition);
    font-size: 13px;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateX(5px);
}

.dropdown-item i {
    width: 18px;
    color: var(--primary);
}

.dropdown-item:hover i {
    color: white;
}

.dropdown-divider {
    border-top: 1px solid var(--border-color);
    margin: 8px 0;
}

/* Tab Navigation */
.nav-tabs {
    border-bottom: 1px solid var(--border-color);
    padding: 0 20px;
}

.nav-tabs .nav-link {
    color: var(--text-muted);
    border: none;
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 500;
    transition: var(--transition);
    background: transparent;
}

.nav-tabs .nav-link i {
    margin-right: 6px;
    color: var(--primary);
}

.nav-tabs .nav-link:hover {
    color: var(--text-primary);
    background: var(--bg-header);
}

.nav-tabs .nav-link.active {
    color: var(--primary);
    background: transparent;
    border-bottom: 2px solid var(--primary);
}

.tab-content {
    padding: 0;
}

.tab-pane {
    animation: fadeIn 0.3s ease;
}

/* Password Field */
.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
}

.password-toggle:hover {
    color: var(--primary);
}

/* Password Strength */
.password-strength {
    height: 4px;
    background: var(--bg-header);
    border-radius: 2px;
    margin-top: 6px;
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0;
    transition: width 0.3s ease;
}

.password-strength.weak .strength-bar {
    width: 33%;
    background: var(--error);
}

.password-strength.medium .strength-bar {
    width: 66%;
    background: var(--warning);
}

.password-strength.strong .strength-bar {
    width: 100%;
    background: var(--success);
}

.password-match {
    font-size: 11px;
    margin-top: 4px;
}

.password-match.match {
    color: var(--success);
}

.password-match.error {
    color: var(--error);
}

/* Form Hints */
.form-hint {
    color: var(--text-muted);
    font-size: 11px;
    margin-top: 4px;
    display: block;
}

/* Status Options */
.status-options {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.status-option {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.status-option input[type="radio"] {
    display: none;
}

.status-option .status-badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
    transition: var(--transition);
    opacity: 0.6;
}

.status-option input[type="radio"]:checked + .status-badge {
    opacity: 1;
    transform: scale(1.05);
    box-shadow: 0 0 15px currentColor;
}

.status-badge.active {
    background: rgba(0, 250, 154, 0.15);
    color: var(--success);
    border: 1px solid var(--success);
}

.status-badge.inactive {
    background: rgba(160, 160, 160, 0.15);
    color: var(--text-muted);
    border: 1px solid var(--text-muted);
}

.status-badge.suspended {
    background: rgba(255, 68, 68, 0.15);
    color: var(--error);
    border: 1px solid var(--error);
}

/* Toggle Switch */
.toggle-switch {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 30px;
}

.toggle-switch input[type="checkbox"] {
    appearance: none;
    width: 40px;
    height: 20px;
    background: var(--border-color);
    border-radius: 20px;
    position: relative;
    cursor: pointer;
    transition: var(--transition);
}

.toggle-switch input[type="checkbox"]:checked {
    background: var(--primary);
}

.toggle-switch input[type="checkbox"]::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    background: white;
    border-radius: 50%;
    top: 2px;
    left: 2px;
    transition: var(--transition);
}

.toggle-switch input[type="checkbox"]:checked::before {
    left: 22px;
}

.toggle-switch label {
    color: var(--text-secondary);
    font-size: 13px;
    cursor: pointer;
}

/* Expiry Options */
.expiry-options {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
}

.expiry-option {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.expiry-option input[type="radio"] {
    accent-color: var(--primary);
}

/* Permissions Grid */
.permissions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 15px;
}

.permission-item {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 12px;
    color: var(--text-secondary);
}

.permission-item input[type="checkbox"] {
    accent-color: var(--primary);
}

/* Notifications List */
.notifications-list {
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 15px;
}

.notification-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
    cursor: pointer;
    color: var(--text-secondary);
    font-size: 12px;
    border-bottom: 1px solid var(--border-color);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item input[type="checkbox"] {
    accent-color: var(--primary);
}

/* Footer Actions */
.footer-actions {
    display: flex;
    gap: 10px;
    width: 100%;
}

.btn-reset {
    padding: 10px 20px;
    background: transparent;
    border: 2px solid var(--text-muted);
    color: var(--text-muted);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-reset:hover {
    border-color: var(--warning);
    color: var(--warning);
}

/* Bulk Import Modal */
.import-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.import-option {
    background: var(--bg-header);
    border: 2px dashed var(--border-color);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    transition: var(--transition);
}

.import-option:hover {
    border-color: var(--primary);
}

.import-option i {
    font-size: 32px;
    color: var(--primary);
    margin-bottom: 10px;
}

.import-option h6 {
    color: var(--text-primary);
    margin-bottom: 5px;
    font-size: 14px;
}

.import-option p {
    color: var(--text-muted);
    font-size: 11px;
    margin-bottom: 15px;
}

.import-template {
    text-align: center;
    padding: 15px;
    background: rgba(255, 20, 147, 0.1);
    border: 1px solid var(--primary);
    border-radius: 12px;
    margin-bottom: 20px;
}

.import-template p {
    color: var(--text-muted);
    margin-bottom: 10px;
    font-size: 12px;
}

.btn-template {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-template:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

/* Toast Container */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.custom-toast {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 12px;
    padding: 12px 20px;
    margin-bottom: 10px;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    animation: slideInRight 0.3s ease;
    min-width: 300px;
}

.custom-toast.success {
    border-left: 4px solid var(--success);
}

.custom-toast.error {
    border-left: 4px solid var(--error);
}

.custom-toast i {
    font-size: 18px;
}

.custom-toast.success i {
    color: var(--success);
}

.custom-toast.error i {
    color: var(--error);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Clear Photo Button */
.btn-clear-photo {
    background: transparent;
    border: 2px solid var(--error);
    color: var(--error);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    margin-left: 8px;
}

.btn-clear-photo:hover {
    background: var(--error);
    color: white;
    transform: rotate(90deg);
}

/* Quick Add Modal */
#quickAddModal .modal-dialog {
    max-width: 400px;
}

#quickAddModal .form-group {
    margin-bottom: 12px;
}
</style>

@push('scripts')
<script>
// Image preview for add user modal
function previewImage(input) {
    const preview = document.getElementById('photoPreview');
    const clearBtn = document.querySelector('.btn-clear-photo');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;">`;
            clearBtn.style.display = 'inline-flex';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function clearPhoto() {
    const preview = document.getElementById('photoPreview');
    const fileInput = document.getElementById('profile_photo');
    const clearBtn = document.querySelector('.btn-clear-photo');
    
    preview.innerHTML = '<i class="fas fa-user"></i>';
    fileInput.value = '';
    clearBtn.style.display = 'none';
}

// Password visibility toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Password strength checker
document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.querySelector('.password-strength');
    const strengthDiv = document.getElementById('passwordStrength');
    
    let strength = 0;
    
    // Check length
    if (password.length >= 8) strength += 1;
    if (password.length >= 10) strength += 1;
    
    // Check for numbers
    if (/\d/.test(password)) strength += 1;
    
    // Check for special characters
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;
    
    // Check for uppercase
    if (/[A-Z]/.test(password)) strength += 1;
    
    // Remove all classes
    strengthDiv.classList.remove('weak', 'medium', 'strong');
    
    if (password.length === 0) {
        strengthBar.style.width = '0';
    } else if (strength <= 2) {
        strengthDiv.classList.add('weak');
    } else if (strength <= 4) {
        strengthDiv.classList.add('medium');
    } else {
        strengthDiv.classList.add('strong');
    }
});

// Password match checker
document.getElementById('password_confirmation')?.addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const matchDiv = document.getElementById('passwordMatch');
    
    if (confirm.length === 0) {
        matchDiv.innerHTML = '';
        matchDiv.className = 'password-match';
    } else if (password === confirm) {
        matchDiv.innerHTML = '✓ Passwords match';
        matchDiv.className = 'password-match match';
    } else {
        matchDiv.innerHTML = '✗ Passwords do not match';
        matchDiv.className = 'password-match error';
    }
});

// Form validation for add user modal
document.getElementById('addUserForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;
    const submitBtn = document.getElementById('submitBtn');
    
    if (password !== confirm) {
        e.preventDefault();
        showToast('Passwords do not match!', 'error');
        return;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        showToast('Password must be at least 8 characters long!', 'error');
        return;
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    submitBtn.disabled = true;
});

// Reset form
function resetForm() {
    document.getElementById('addUserForm').reset();
    clearPhoto();
    
    // Reset tabs to first tab
    const firstTab = new bootstrap.Tab(document.getElementById('basic-info-tab'));
    firstTab.show();
}

// Expiry date toggle
document.querySelectorAll('input[name="expiry"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const expiryDate = document.getElementById('expiry_date');
        if (this.value === 'custom') {
            expiryDate.style.display = 'block';
        } else {
            expiryDate.style.display = 'none';
        }
    });
});

// CSV Import handling
document.getElementById('csvFile')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        showToast(`Selected: ${file.name}`, 'info');
        document.getElementById('importBtn').disabled = false;
        
        // Preview first few rows (mock)
        const preview = document.getElementById('importPreview');
        preview.style.display = 'block';
        
        const previewTable = preview.querySelector('.preview-table');
        previewTable.innerHTML = `
            <table class="preview-table-content">
                <tr><th>Name</th><th>Email</th><th>Role</th></tr>
                <tr><td>John Doe</td><td>john@example.com</td><td>user</td></tr>
                <tr><td>Jane Smith</td><td>jane@example.com</td><td>admin</td></tr>
                <tr><td>Bob Johnson</td><td>bob@example.com</td><td>user</td></tr>
            </table>
        `;
    }
});

// Download template
document.getElementById('downloadTemplateBtn')?.addEventListener('click', function() {
    const csvContent = "name,email,password,role,phone,location\nJohn Doe,john@example.com,password123,user,1234567890,New York\nJane Smith,jane@example.com,password456,admin,0987654321,Los Angeles";
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'user_import_template.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    showToast('Template downloaded successfully!', 'success');
});

// Export users
document.getElementById('exportUsersBtn')?.addEventListener('click', function() {
    showToast('Preparing export...', 'info');
    setTimeout(() => {
        showToast('Users exported successfully!', 'success');
    }, 1500);
});

// Show toast notification
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `custom-toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    document.querySelectorAll('.custom-alert').forEach(alert => {
        alert.style.transition = 'opacity 0.5s, transform 0.5s';
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(20px)';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Initialize tooltips
document.querySelectorAll('[title]').forEach(el => {
    new bootstrap.Tooltip(el);
});

// Add animation to cards
const cards = document.querySelectorAll('.stat-card');
cards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
});
</script>
@endpush
@endsection