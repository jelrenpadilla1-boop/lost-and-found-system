@extends('layouts.app')

@section('title', 'Edit Profile - Foundify')

@section('content')
@php
    $user = Auth::user();
@endphp

<style>
/* ── NETFLIX-STYLE EDIT PROFILE (matches login page) ───────────────── */
:root {
    --bg-primary: #141414;
    --bg-secondary: #0a0a0a;
    --bg-card: rgba(0,0,0,0.75);
    --text-primary: #ffffff;
    --text-secondary: #e5e5e5;
    --text-muted: #b3b3b3;
    --border-color: #404040;
    --input-bg: #333333;
    --input-border: #404040;
    --input-focus: #454545;
    --shadow-color: rgba(0,0,0,0.5);
    --accent: #e50914;
    --accent-light: #f6121d;
    --accent-soft: rgba(229,9,20,0.15);
    --error: #e50914;
    --error-soft: rgba(229,9,20,0.15);
    --success: #2e7d32;
    --success-soft: rgba(46,125,50,0.15);
    --warning: #f5c518;
    --warning-soft: rgba(245,197,24,0.15);
    --transition: all 0.2s ease;
}

.dashboard-container {
    max-width: 900px;
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
    border-bottom: 1px solid var(--border-color);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
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

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 4px;
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
    transform: scale(1.02);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-muted);
}

.btn-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: scale(1.02);
}

.btn-outline-danger {
    background: transparent;
    border: 1px solid var(--error-soft);
    color: var(--error);
}

.btn-outline-danger:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: scale(1.02);
}

.btn-danger {
    background: var(--error);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: scale(1.02);
}

/* Cards */
.card {
    background: var(--bg-card);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 28px;
    backdrop-filter: blur(2px);
    box-shadow: 0 4px 20px var(--shadow-color);
    border: 1px solid rgba(255,255,255,0.1);
}

.card-header {
    padding: 18px 24px;
    background: rgba(0,0,0,0.3);
    border-bottom: 1px solid var(--border-color);
}

.card-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
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
    padding: 28px;
}

/* Photo Section */
.photo-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
}

.photo-preview-wrapper {
    position: relative;
    width: 140px;
    height: 140px;
}

.photo-preview {
    width: 140px;
    height: 140px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    z-index: 2;
    transition: var(--transition);
    background: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--accent);
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 56px;
    font-weight: 800;
    color: white;
}

.photo-ring {
    position: absolute;
    inset: -5px;
    border-radius: 13px;
    background: var(--accent);
    opacity: 0;
    transition: var(--transition);
    z-index: 1;
    filter: blur(8px);
}

.photo-preview-wrapper:hover .photo-preview {
    transform: scale(0.95);
}

.photo-preview-wrapper:hover .photo-ring {
    opacity: 0.4;
    transform: scale(1.1);
}

.photo-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: center;
}

.hidden-input {
    display: none;
}

.photo-hint {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0;
}

.photo-hint i {
    color: var(--accent);
    font-size: 12px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-label i {
    color: var(--accent);
    font-size: 12px;
}

.required {
    color: var(--error);
    font-size: 12px;
    margin-left: 2px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: var(--input-bg);
    border: 1px solid var(--input-border);
    border-radius: 4px;
    color: var(--text-primary);
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent);
    background: var(--input-focus);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.is-invalid {
    border-color: var(--error) !important;
}

.invalid-feedback {
    display: block;
    color: var(--error);
    font-size: 11px;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
}

@media (max-width: 576px) {
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Password Section */
.password-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

@media (max-width: 992px) {
    .password-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .password-grid {
        grid-template-columns: 1fr;
    }
}

.password-field {
    position: relative;
}

.password-field .form-control {
    padding-right: 45px;
}

.password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    padding: 5px;
}

.password-toggle:hover {
    color: var(--accent);
}

/* Password Strength */
.password-strength {
    margin-top: 10px;
}

.strength-bars {
    display: flex;
    gap: 6px;
    margin-bottom: 6px;
}

.strength-bar {
    flex: 1;
    height: 4px;
    background: var(--border-color);
    border-radius: 2px;
    transition: var(--transition);
}

.strength-bar.active {
    background: var(--accent);
}

.strength-text {
    font-size: 10px;
    color: var(--text-muted);
    letter-spacing: 0.05em;
}

/* Password Match */
.password-match {
    font-size: 11px;
    margin-top: 6px;
    min-height: 18px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.password-match.success {
    color: var(--success);
}

.password-match.error {
    color: var(--error);
}

/* Requirements Box */
.requirements-box {
    background: rgba(0,0,0,0.3);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    padding: 18px;
    margin-bottom: 24px;
}

.requirements-box h6 {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 14px 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

@media (max-width: 576px) {
    .requirements-list {
        grid-template-columns: 1fr;
    }
}

.requirements-list li {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
}

.requirements-list li.valid {
    color: var(--success);
}

.requirements-list li i {
    font-size: 8px;
    color: var(--border-color);
}

.requirements-list li.valid i {
    color: var(--success);
}

.password-actions {
    display: flex;
    justify-content: flex-end;
}

@media (max-width: 576px) {
    .password-actions .btn {
        width: 100%;
    }
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal.fade {
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    max-width: 400px;
    width: 90%;
    backdrop-filter: blur(2px);
}

.modal-header {
    padding: 18px 24px;
    background: rgba(0,0,0,0.3);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-title i {
    color: var(--error);
}

.modal-close {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    border-color: var(--error);
    color: var(--error);
    transform: rotate(90deg);
}

.modal-body {
    padding: 24px;
}

.modal-body p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.modal-footer {
    padding: 16px 24px;
    background: rgba(0,0,0,0.3);
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

/* Toast Notifications */
#notificationsContainer {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    max-width: 350px;
}

.toast {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 12px;
    box-shadow: 0 4px 20px var(--shadow-color);
    animation: slideInRight 0.3s ease;
    backdrop-filter: blur(2px);
}

.toast-body {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    font-size: 13px;
    color: var(--text-primary);
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
    margin-left: auto;
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
    
    .card-body {
        padding: 20px;
    }
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-user-edit"></i>
                Edit Profile
            </h1>
            <p>Update your personal information and account settings</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('profile.show') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Profile
            </a>
        </div>
    </div>

    {{-- Profile Photo Card --}}
    <div class="card photo-card fade-in">
        <div class="card-header">
            <h5><i class="fas fa-camera"></i> Profile Photo</h5>
        </div>
        <div class="card-body">
            <div class="photo-section">
                <div class="photo-preview-wrapper">
                    <div class="photo-preview" id="avatarPreview">
                        @if($user->profile_photo && file_exists(public_path('storage/' . $user->profile_photo)))
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 alt="{{ $user->name }}" 
                                 class="preview-image">
                        @else
                            <div class="preview-initial">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="photo-ring"></div>
                </div>

                <div class="photo-actions">
                    <label for="profile_photo" class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt"></i> Choose Photo
                    </label>
                    <input type="file" 
                           id="profile_photo" 
                           name="profile_photo" 
                           form="profileForm" 
                           accept="image/*" 
                           class="hidden-input">

                    @if($user->profile_photo)
                        <button type="button" class="btn btn-outline-danger" onclick="showModal('removePhotoModal')">
                            <i class="fas fa-trash-alt"></i> Remove
                        </button>
                    @endif
                </div>

                <p class="photo-hint">
                    <i class="fas fa-info-circle"></i>
                    JPG, PNG or GIF • Max 2MB
                </p>
            </div>
        </div>
    </div>

    {{-- Edit Profile Card --}}
    <div class="card edit-card fade-in">
        <div class="card-header">
            <h5><i class="fas fa-user-circle"></i> Personal Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" class="edit-form" id="profileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               placeholder="Enter your full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               placeholder="Enter your email"
                               required>
                        @error('email')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-phone-alt"></i> Phone Number</label>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="+63 XXX XXX XXXX">
                        @error('phone')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Location</label>
                        <input type="text" 
                               class="form-control @error('location') is-invalid @enderror" 
                               name="location" 
                               value="{{ old('location', $user->location) }}" 
                               placeholder="City, Country">
                        @error('location')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline"><i class="fas fa-times"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary" id="profileSubmitBtn"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Password Card --}}
    <div class="card password-card fade-in">
        <div class="card-header">
            <h5><i class="fas fa-lock"></i> Change Password</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.password') }}" method="POST" class="password-form">
                @csrf
                @method('PUT')

                <div class="password-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-lock"></i> Current Password <span class="required">*</span></label>
                        <div class="password-field">
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password" 
                                   id="current_password"
                                   placeholder="Enter current password"
                                   required>
                            <button type="button" class="password-toggle" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-lock"></i> New Password <span class="required">*</span></label>
                        <div class="password-field">
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password" 
                                   id="new_password"
                                   placeholder="Enter new password"
                                   required>
                            <button type="button" class="password-toggle" data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <div class="invalid-feedback"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                        <div class="password-strength">
                            <div class="strength-bars">
                                <div class="strength-bar" id="strengthBar1"></div>
                                <div class="strength-bar" id="strengthBar2"></div>
                                <div class="strength-bar" id="strengthBar3"></div>
                                <div class="strength-bar" id="strengthBar4"></div>
                            </div>
                            <span class="strength-text" id="strengthText">Enter password</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-lock"></i> Confirm Password <span class="required">*</span></label>
                        <div class="password-field">
                            <input type="password" 
                                   class="form-control" 
                                   name="new_password_confirmation" 
                                   id="password_confirmation"
                                   placeholder="Confirm new password"
                                   required>
                            <button type="button" class="password-toggle" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="passwordMatch"></div>
                    </div>
                </div>

                <div class="requirements-box">
                    <h6>Password Requirements:</h6>
                    <ul class="requirements-list">
                        <li id="req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                        <li id="req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                        <li id="req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                        <li id="req-number"><i class="fas fa-circle"></i> One number</li>
                        <li id="req-special"><i class="fas fa-circle"></i> One special character</li>
                    </ul>
                </div>

                <div class="password-actions">
                    <button type="submit" class="btn btn-primary" id="passwordSubmitBtn"><i class="fas fa-key"></i> Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Remove Photo Modal --}}
<div class="modal fade" id="removePhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Remove Photo</h5>
                <button type="button" class="modal-close" onclick="hideModal('removePhotoModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove your profile photo? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="hideModal('removePhotoModal')">Cancel</button>
                <form action="{{ route('profile.photo.remove') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Remove</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Notifications Container --}}
<div id="notificationsContainer"></div>

@push('scripts')
<script>
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        modal.style.display = 'flex';
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal
    const modal = document.getElementById('removePhotoModal');
    if (modal) modal.style.display = 'none';
    
    // Close modal on background click
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
            hideModal(e.target.id);
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.show').forEach(m => hideModal(m.id));
        }
    });

    // Profile photo preview
    const photoInput = document.getElementById('profile_photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showToast('File size must be less than 2MB', 'error');
                    this.value = '';
                    return;
                }
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showToast('Please upload a valid image (JPG, PNG, GIF, WEBP)', 'error');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile" class="preview-image">`;
                };
                reader.readAsDataURL(file);
                showToast('Photo selected', 'success');
            }
        });
    }

    // Toggle password visibility
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            if (input) {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            }
        });
    });

    // Password strength checker
    const passwordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBars = [1, 2, 3, 4].map(i => document.getElementById(`strengthBar${i}`));
    const strengthText = document.getElementById('strengthText');
    const passwordMatchDiv = document.getElementById('passwordMatch');

    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    function checkPasswordStrength() {
        if (!passwordInput) return;
        const password = passwordInput.value;
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);

        if (reqLength) reqLength.className = hasLength ? 'valid' : '';
        if (reqUppercase) reqUppercase.className = hasUppercase ? 'valid' : '';
        if (reqLowercase) reqLowercase.className = hasLowercase ? 'valid' : '';
        if (reqNumber) reqNumber.className = hasNumber ? 'valid' : '';
        if (reqSpecial) reqSpecial.className = hasSpecial ? 'valid' : '';

        let strength = 0;
        if (hasLength) strength++;
        if (hasUppercase) strength++;
        if (hasLowercase) strength++;
        if (hasNumber) strength++;
        if (hasSpecial) strength++;

        for (let i = 0; i < strengthBars.length; i++) {
            if (i < strength) strengthBars[i].classList.add('active');
            else strengthBars[i].classList.remove('active');
        }

        if (strength < 2) { strengthText.textContent = 'Weak'; strengthText.style.color = 'var(--error)'; }
        else if (strength < 4) { strengthText.textContent = 'Medium'; strengthText.style.color = 'var(--warning)'; }
        else { strengthText.textContent = 'Strong'; strengthText.style.color = 'var(--success)'; }

        checkPasswordMatch();
    }

    function checkPasswordMatch() {
        if (!passwordInput || !confirmInput || !passwordMatchDiv) return;
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        if (confirm.length === 0) {
            passwordMatchDiv.innerHTML = '';
        } else if (password === confirm) {
            passwordMatchDiv.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
            passwordMatchDiv.className = 'password-match success';
        } else {
            passwordMatchDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
            passwordMatchDiv.className = 'password-match error';
        }
    }

    if (passwordInput) passwordInput.addEventListener('input', checkPasswordStrength);
    if (confirmInput) confirmInput.addEventListener('input', checkPasswordMatch);

    // Form submission loading states
    const profileForm = document.getElementById('profileForm');
    const profileSubmitBtn = document.getElementById('profileSubmitBtn');
    if (profileForm && profileSubmitBtn) {
        profileForm.addEventListener('submit', function() {
            profileSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            profileSubmitBtn.disabled = true;
            setTimeout(() => {
                if (profileSubmitBtn.disabled) {
                    profileSubmitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                    profileSubmitBtn.disabled = false;
                }
            }, 5000);
        });
    }

    const passwordForm = document.querySelector('.password-form');
    const passwordSubmitBtn = document.getElementById('passwordSubmitBtn');
    if (passwordForm && passwordSubmitBtn) {
        passwordForm.addEventListener('submit', function() {
            passwordSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            passwordSubmitBtn.disabled = true;
            setTimeout(() => {
                if (passwordSubmitBtn.disabled) {
                    passwordSubmitBtn.innerHTML = '<i class="fas fa-key"></i> Update Password';
                    passwordSubmitBtn.disabled = false;
                }
            }, 5000);
        });
    }

    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = 'toast';
        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
        const color = type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--error)' : 'var(--accent)';
        toast.innerHTML = `
            <div class="toast-body">
                <i class="fas fa-${icon}" style="color: ${color};"></i>
                <span>${message}</span>
                <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
});
</script>
@endpush
@endsection