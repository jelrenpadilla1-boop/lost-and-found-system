@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <div class="profile-header">
                    <h2>
                        <i class="fas fa-user-edit" style="color: var(--primary);"></i>
                        Edit Profile
                    </h2>
                    <a href="{{ route('profile.show') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                </div>

                <div class="profile-body">
                    <!-- Profile Picture Section with Upload -->
                    <div class="avatar-section">
                        <div class="avatar-upload-wrapper">
                            <div class="avatar-preview" id="avatarPreview">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="avatar-image">
                                @else
                                    <div class="avatar-large" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <label for="profile_photo" class="avatar-upload-btn">
                                <i class="fas fa-camera"></i>
                                <span>Change Photo</span>
                            </label>
                            <input type="file" 
                                   id="profile_photo" 
                                   name="profile_photo" 
                                   form="profileForm" 
                                   accept="image/*" 
                                   class="hidden-input">
                            <p class="photo-hint">Max size: 2MB. JPG, PNG, GIF</p>
                        </div>
                        <div class="avatar-info">
                            <h4>{{ Auth::user()->name }}</h4>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <form action="{{ route('profile.update') }}" method="POST" class="profile-form" id="profileForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-user-circle" style="color: var(--primary);"></i>
                                Personal Information
                            </h5>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user"></i> Full Name
                                        </label>
                                        <input type="text" 
                                               class="pink-input @error('name') error @enderror" 
                                               name="name" 
                                               value="{{ old('name', Auth::user()->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-envelope"></i> Email Address
                                        </label>
                                        <input type="email" 
                                               class="pink-input @error('email') error @enderror" 
                                               name="email" 
                                               value="{{ old('email', Auth::user()->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-phone"></i> Phone Number
                                        </label>
                                        <input type="text" 
                                               class="pink-input @error('phone') error @enderror" 
                                               name="phone" 
                                               value="{{ old('phone', Auth::user()->phone) }}" 
                                               placeholder="Optional">
                                        @error('phone')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-map-marker-alt"></i> Location
                                        </label>
                                        <input type="text" 
                                               class="pink-input @error('location') error @enderror" 
                                               name="location" 
                                               value="{{ old('location', Auth::user()->location) }}" 
                                               placeholder="City, Country">
                                        @error('location')
                                            <div class="error-message">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn-cancel">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="password-card mt-4">
                <div class="password-header">
                    <h5>
                        <i class="fas fa-lock" style="color: var(--primary);"></i>
                        Change Password
                    </h5>
                </div>

                <div class="password-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" 
                                           class="pink-input @error('current_password') error @enderror" 
                                           name="current_password" 
                                           required>
                                    @error('current_password')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <input type="password" 
                                           class="pink-input @error('new_password') error @enderror" 
                                           name="new_password" 
                                           required>
                                    @error('new_password')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" 
                                           class="pink-input" 
                                           name="new_password_confirmation" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="password-actions">
                            <button type="submit" class="btn-update-password">
                                <i class="fas fa-key"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Remove Photo Modal -->
            <div class="modal fade" id="removePhotoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i>
                                Remove Profile Photo
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to remove your profile photo?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('profile.photo.remove') }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <i class="fas fa-trash"></i>
                                    Remove Photo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-card,
.password-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-card:hover,
.password-card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.profile-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--bg-header);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.profile-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
    border-color: transparent;
}

.profile-body {
    padding: 2rem;
}

/* Avatar Upload Section */
.avatar-section {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
    flex-wrap: wrap;
}

.avatar-upload-wrapper {
    position: relative;
    text-align: center;
}

.avatar-preview {
    width: 120px;
    height: 120px;
    margin-bottom: 1rem;
    position: relative;
}

.avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 48px;
    box-shadow: 0 0 30px var(--primary-glow);
    transition: all 0.3s ease;
}

.avatar-image {
    width: 120px;
    height: 120px;
    border-radius: 40px;
    object-fit: cover;
    border: 3px solid var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
    transition: all 0.3s ease;
}

.avatar-image:hover {
    transform: scale(1.05);
    box-shadow: 0 0 40px var(--primary-glow);
}

.avatar-upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 0 15px var(--primary-glow);
}

.avatar-upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--primary-glow);
}

.avatar-upload-btn i {
    font-size: 14px;
}

.hidden-input {
    display: none;
}

.photo-hint {
    color: var(--text-muted);
    font-size: 11px;
    margin-top: 8px;
}

.avatar-info {
    flex: 1;
}

.avatar-info h4 {
    color: var(--text-primary);
    margin-bottom: 4px;
    font-size: 1.25rem;
}

/* Form Styles */
.form-section {
    margin-bottom: 2rem;
}

.section-title {
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-primary);
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 8px;
}

.form-label i {
    color: var(--primary);
    font-size: 14px;
}

.pink-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 14px;
    transition: all 0.3s ease;
}

.pink-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    background: var(--bg-card);
}

.pink-input.error {
    border-color: var(--danger);
}

.error-message {
    color: var(--danger);
    font-size: 12px;
    margin-top: 4px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 2rem;
}

.btn-save {
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 0 20px var(--primary-glow);
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--primary-glow);
}

.btn-cancel {
    padding: 12px 24px;
    background: transparent;
    border: 2px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-cancel:hover {
    border-color: var(--danger);
    color: var(--danger);
}

/* Password Section */
.password-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--bg-header);
}

.password-header h5 {
    margin: 0;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.password-body {
    padding: 1.5rem;
}

.password-actions {
    margin-top: 1.5rem;
    display: flex;
    justify-content: flex-end;
}

.btn-update-password {
    padding: 10px 20px;
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-update-password:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
    border-color: transparent;
}

/* Modal Styles */
.btn-danger {
    padding: 10px 20px;
    background: transparent;
    border: 2px solid var(--danger);
    color: var(--danger);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-danger:hover {
    background: linear-gradient(135deg, var(--danger), #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
    border-color: transparent;
}

/* Responsive */
@media (max-width: 768px) {
    .avatar-section {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }
    
    .avatar-info {
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-save, .btn-cancel {
        width: 100%;
        justify-content: center;
    }
    
    .password-actions {
        justify-content: center;
    }
    
    .btn-update-password {
        width: 100%;
        justify-content: center;
    }
}
</style>

@push('scripts')
<script>
    // Profile photo preview
    document.getElementById('profile_photo')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                showToast('File size must be less than 2MB', 'error');
                this.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                showToast('Please upload a valid image file (JPG, PNG, GIF)', 'error');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Profile" class="avatar-image">`;
            };
            reader.readAsDataURL(file);
            
            showToast('Photo selected successfully', 'success');
        }
    });

    // Show toast notification
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;
        
        const toast = document.createElement('div');
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
</script>
@endpush