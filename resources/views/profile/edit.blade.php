@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="edit-wrapper">
    <!-- Cover Photo -->
    <div class="edit-cover">
        <div class="cover-gradient"></div>
        <div class="cover-pattern"></div>
    </div>

    <div class="edit-content">
        <!-- Main Edit Card -->
        <div class="edit-card">
            <div class="edit-card-header">
                <div class="header-left">
                    <a href="{{ route('profile.show') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="header-title">
                        <i class="fas fa-user-edit"></i>
                        <div>
                            <h2>Edit Profile</h2>
                            <p>Update your personal information</p>
                        </div>
                    </div>
                </div>
                <div class="header-status">
                    <span class="status-badge">
                        <i class="fas fa-check-circle"></i> Active Account
                    </span>
                </div>
            </div>

            <!-- Profile Photo Section -->
            <div class="photo-section">
                <div class="photo-wrapper">
                    <div class="photo-preview" id="avatarPreview">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="preview-image">
                        @else
                            <div class="preview-initial" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="photo-ring"></div>
                    </div>
                    
                    <div class="photo-actions">
                        <label for="profile_photo" class="btn-upload">
                            <i class="fas fa-camera"></i>
                            <span>Choose New Photo</span>
                        </label>
                        <input type="file" 
                               id="profile_photo" 
                               name="profile_photo" 
                               form="profileForm" 
                               accept="image/*" 
                               class="hidden-input">
                        
                        @if(Auth::user()->profile_photo)
                            <button type="button" class="btn-remove" data-bs-toggle="modal" data-bs-target="#removePhotoModal">
                                <i class="fas fa-trash"></i>
                                <span>Remove</span>
                            </button>
                        @endif
                    </div>
                    
                    <div class="photo-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>JPG, PNG or GIF. Max 2MB</span>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('profile.update') }}" method="POST" class="edit-form" id="profileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Personal Information -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="section-title">
                            <h3>Personal Information</h3>
                            <p>Update your personal details</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                <span>Full Name</span>
                            </label>
                            <input type="text" 
                                   class="form-input @error('name') error @enderror" 
                                   name="name" 
                                   value="{{ old('name', Auth::user()->name) }}" 
                                   placeholder="Enter your full name"
                                   required>
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                <span>Email Address</span>
                            </label>
                            <input type="email" 
                                   class="form-input @error('email') error @enderror" 
                                   name="email" 
                                   value="{{ old('email', Auth::user()->email) }}" 
                                   placeholder="Enter your email"
                                   required>
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone-alt"></i>
                                <span>Phone Number</span>
                            </label>
                            <input type="tel" 
                                   class="form-input @error('phone') error @enderror" 
                                   name="phone" 
                                   value="{{ old('phone', Auth::user()->phone) }}" 
                                   placeholder="+1 (555) 000-0000">
                            @error('phone')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Location</span>
                            </label>
                            <input type="text" 
                                   class="form-input @error('location') error @enderror" 
                                   name="location" 
                                   value="{{ old('location', Auth::user()->location) }}" 
                                   placeholder="City, Country">
                            @error('location')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('profile.show') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Save Changes
                        <div class="btn-glow"></div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Card -->
        <div class="password-card">
            <div class="password-header">
                <div class="password-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="password-title">
                    <h3>Change Password</h3>
                    <p>Update your password to keep your account secure</p>
                </div>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="password-form">
                @csrf
                @method('PUT')

                <div class="password-grid">
                    <!-- Current Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            <span>Current Password</span>
                        </label>
                        <div class="password-field">
                            <input type="password" 
                                   class="form-input @error('current_password') error @enderror" 
                                   name="current_password" 
                                   placeholder="Enter current password"
                                   required>
                            <button type="button" class="toggle-password" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            <span>New Password</span>
                        </label>
                        <div class="password-field">
                            <input type="password" 
                                   class="form-input @error('new_password') error @enderror" 
                                   name="new_password" 
                                   id="new_password"
                                   placeholder="Enter new password"
                                   required>
                            <button type="button" class="toggle-password" data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        
                        <!-- Password Strength Meter -->
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

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            <span>Confirm Password</span>
                        </label>
                        <div class="password-field">
                            <input type="password" 
                                   class="form-input" 
                                   name="new_password_confirmation" 
                                   id="password_confirmation"
                                   placeholder="Confirm new password"
                                   required>
                            <button type="button" class="toggle-password" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="passwordMatch"></div>
                    </div>
                </div>

                <div class="password-requirements">
                    <h4>Password Requirements:</h4>
                    <ul>
                        <li id="req-length">✓ At least 8 characters</li>
                        <li id="req-uppercase">✓ At least one uppercase letter</li>
                        <li id="req-lowercase">✓ At least one lowercase letter</li>
                        <li id="req-number">✓ At least one number</li>
                        <li id="req-special">✓ At least one special character</li>
                    </ul>
                </div>

                <div class="password-actions">
                    <button type="submit" class="btn-update">
                        <i class="fas fa-key"></i>
                        Update Password
                        <div class="btn-glow"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Photo Modal -->
<div class="modal fade" id="removePhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header">
                <div class="modal-icon warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="modal-title">Remove Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove your profile photo? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

<style>
:root {
    --primary: #ff1493;
    --primary-light: #ff69b4;
    --primary-glow: rgba(255, 20, 147, 0.3);
    --bg-dark: #0a0a0a;
    --bg-card: #1a1a1a;
    --bg-header: #222;
    --border-color: #333;
    --text-primary: #ffffff;
    --text-secondary: #e0e0e0;
    --text-muted: #a0a0a0;
    --success: #00fa9a;
    --warning: #ffa500;
    --danger: #ff4444;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.edit-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Cover Photo */
.edit-cover {
    height: 150px;
    background: linear-gradient(135deg, #000000, #1a1a1a);
    border-radius: 30px 30px 0 0;
    position: relative;
    overflow: hidden;
    margin-bottom: -50px;
}

.cover-gradient {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 50%, var(--primary-glow) 0%, transparent 70%);
    animation: pulse 8s infinite;
}

.cover-pattern {
    position: absolute;
    inset: 0;
    background-image: 
        radial-gradient(circle at 20px 20px, rgba(255, 20, 147, 0.1) 2px, transparent 2px),
        radial-gradient(circle at 40px 70px, rgba(255, 20, 147, 0.1) 2px, transparent 2px);
    background-size: 50px 50px, 80px 80px;
    opacity: 0.3;
}

@keyframes pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 0.8; }
}

/* Edit Content */
.edit-content {
    position: relative;
    z-index: 2;
}

/* Main Edit Card */
.edit-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    overflow: hidden;
    margin-bottom: 30px;
    transition: var(--transition);
}

.edit-card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 40px var(--primary-glow);
}

.edit-card-header {
    padding: 25px 30px;
    border-bottom: 1px solid var(--border-color);
    background: var(--bg-header);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.back-button {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    background: var(--bg-card);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid var(--border-color);
}

.back-button:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateX(-3px);
    border-color: transparent;
    box-shadow: 0 5px 15px var(--primary-glow);
}

.header-title {
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-title i {
    font-size: 28px;
    color: var(--primary);
    background: rgba(255, 20, 147, 0.1);
    padding: 12px;
    border-radius: 16px;
}

.header-title h2 {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.header-title p {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0;
}

.header-status {
    padding: 8px 16px;
    background: rgba(0, 250, 154, 0.1);
    border: 1px solid var(--success);
    border-radius: 30px;
    color: var(--success);
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Photo Section */
.photo-section {
    padding: 30px;
    border-bottom: 1px solid var(--border-color);
}

.photo-wrapper {
    text-align: center;
}

.photo-preview {
    position: relative;
    width: 140px;
    height: 140px;
    margin: 0 auto 20px;
}

.preview-image {
    width: 140px;
    height: 140px;
    border-radius: 50px;
    object-fit: cover;
    position: relative;
    z-index: 2;
    transition: var(--transition);
}

.preview-initial {
    width: 140px;
    height: 140px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 56px;
    position: relative;
    z-index: 2;
    transition: var(--transition);
}

.photo-ring {
    position: absolute;
    inset: -5px;
    border-radius: 55px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    opacity: 0;
    transition: var(--transition);
    z-index: 1;
}

.photo-preview:hover .photo-ring {
    opacity: 1;
    transform: scale(1.05);
}

.photo-preview:hover .preview-image,
.photo-preview:hover .preview-initial {
    transform: scale(0.95);
}

.photo-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.btn-upload {
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: 0 5px 20px var(--primary-glow);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--primary-glow);
}

.btn-remove {
    padding: 12px 24px;
    background: transparent;
    border: 2px solid var(--danger);
    color: var(--danger);
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-remove:hover {
    background: linear-gradient(135deg, var(--danger), #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
    border-color: transparent;
}

.hidden-input {
    display: none;
}

.photo-hint {
    color: var(--text-muted);
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.photo-hint i {
    color: var(--primary);
}

/* Form Sections */
.form-section {
    padding: 30px;
    border-bottom: 1px solid var(--border-color);
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.section-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 20, 147, 0.1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 20px;
    transition: var(--transition);
}

.section-header:hover .section-icon {
    transform: rotate(360deg);
    background: var(--primary);
    color: white;
}

.section-title h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.section-title p {
    color: var(--text-muted);
    font-size: 13px;
    margin: 0;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

/* Form Elements */
.form-group {
    margin-bottom: 20px;
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

.form-input {
    width: 100%;
    padding: 14px 16px;
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 14px;
    color: var(--text-primary);
    font-size: 14px;
    transition: var(--transition);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    background: var(--bg-card);
}

.form-input.error {
    border-color: var(--danger);
}

/* Password Field */
.password-field {
    position: relative;
}

.password-field .form-input {
    padding-right: 45px;
}

.toggle-password {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 8px;
    transition: var(--transition);
}

.toggle-password:hover {
    color: var(--primary);
}

/* Password Strength */
.password-strength {
    margin-top: 10px;
}

.strength-bars {
    display: flex;
    gap: 4px;
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
    background: var(--success);
}

.strength-text {
    font-size: 11px;
    color: var(--text-muted);
}

/* Password Match */
.password-match {
    font-size: 12px;
    margin-top: 6px;
}

.password-match.match-success {
    color: var(--success);
}

.password-match.match-error {
    color: var(--danger);
}

/* Password Requirements */
.password-requirements {
    background: var(--bg-header);
    border-radius: 16px;
    padding: 20px;
    margin: 20px 0;
}

.password-requirements h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.password-requirements li {
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.password-requirements li.valid {
    color: var(--success);
}

/* Form Actions */
.form-actions {
    padding: 30px;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    border-top: 1px solid var(--border-color);
}

.btn-primary,
.btn-secondary {
    padding: 14px 28px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    box-shadow: 0 5px 20px var(--primary-glow);
    position: relative;
    overflow: hidden;
}

.btn-primary .btn-glow {
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

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--primary-glow);
}

.btn-primary:hover .btn-glow {
    width: 300px;
    height: 300px;
}

.btn-primary span,
.btn-primary i {
    position: relative;
    z-index: 2;
}

.btn-secondary {
    background: transparent;
    border: 2px solid var(--border-color);
    color: var(--text-muted);
}

.btn-secondary:hover {
    border-color: var(--danger);
    color: var(--danger);
    transform: translateY(-2px);
}

/* Password Card */
.password-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 30px;
    overflow: hidden;
    transition: var(--transition);
}

.password-card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 40px var(--primary-glow);
}

.password-header {
    padding: 25px 30px;
    border-bottom: 1px solid var(--border-color);
    background: var(--bg-header);
    display: flex;
    align-items: center;
    gap: 15px;
}

.password-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 20, 147, 0.1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 20px;
}

.password-title h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.password-title p {
    color: var(--text-muted);
    font-size: 13px;
    margin: 0;
}

.password-form {
    padding: 30px;
}

.password-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.password-actions {
    display: flex;
    justify-content: flex-end;
}

.btn-update {
    padding: 14px 32px;
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
}

.btn-update .btn-glow {
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

.btn-update:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px var(--primary-glow);
    border-color: transparent;
}

.btn-update:hover .btn-glow {
    width: 300px;
    height: 300px;
    z-index: 1;
}

.btn-update i,
.btn-update span {
    position: relative;
    z-index: 2;
}

/* Error Message */
.error-message {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--danger);
    font-size: 12px;
    margin-top: 6px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modal Styles */
.modern-modal {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 24px;
    box-shadow: 0 20px 50px var(--primary-glow);
}

.modern-modal .modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 25px 30px;
    background: var(--bg-header);
}

.modern-modal .modal-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 15px;
}

.modern-modal .modal-icon.warning {
    background: rgba(255, 68, 68, 0.1);
    color: var(--danger);
}

.modern-modal .modal-title {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 18px;
}

.modern-modal .modal-body {
    padding: 30px;
    color: var(--text-secondary);
}

.modern-modal .modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 20px 30px;
    background: var(--bg-header);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn-danger {
    padding: 12px 24px;
    background: transparent;
    border: 2px solid var(--danger);
    color: var(--danger);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-danger:hover {
    background: linear-gradient(135deg, var(--danger), #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
    border-color: transparent;
}

/* Toast Notifications */
.toast {
    background: var(--bg-card) !important;
    border: 1px solid var(--primary) !important;
    border-radius: 12px !important;
}

.toast-body {
    color: var(--text-primary) !important;
}

.btn-close-white {
    filter: invert(1);
}

/* Responsive */
@media (max-width: 992px) {
    .form-grid,
    .password-grid {
        grid-template-columns: 1fr;
    }
    
    .password-requirements ul {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .edit-card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-left {
        width: 100%;
    }
    
    .header-title {
        flex: 1;
    }
    
    .photo-actions {
        flex-direction: column;
    }
    
    .btn-upload,
    .btn-remove {
        width: 100%;
        justify-content: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }
    
    .password-actions {
        justify-content: center;
    }
    
    .btn-update {
        width: 100%;
        justify-content: center;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile photo preview
    const photoInput = document.getElementById('profile_photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
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
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile" class="preview-image">`;
                };
                reader.readAsDataURL(file);
                
                showToast('Photo selected successfully', 'success');
            }
        });
    }

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            
            if (input) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            }
        });
    });

    // Password strength checker
    const passwordInput = document.getElementById('new_password');
    const strengthBars = [
        document.getElementById('strengthBar1'),
        document.getElementById('strengthBar2'),
        document.getElementById('strengthBar3'),
        document.getElementById('strengthBar4')
    ];
    const strengthText = document.getElementById('strengthText');
    const confirmInput = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');

    // Password requirements elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    function checkPasswordStrength() {
        if (!passwordInput || !strengthBars[0]) return;
        
        const password = passwordInput.value;
        
        // Check requirements
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);
        
        // Update requirement indicators
        reqLength.className = hasLength ? 'valid' : '';
        reqUppercase.className = hasUppercase ? 'valid' : '';
        reqLowercase.className = hasLowercase ? 'valid' : '';
        reqNumber.className = hasNumber ? 'valid' : '';
        reqSpecial.className = hasSpecial ? 'valid' : '';
        
        // Calculate strength
        let strength = 0;
        if (hasLength) strength++;
        if (hasUppercase) strength++;
        if (hasLowercase) strength++;
        if (hasNumber) strength++;
        if (hasSpecial) strength++;
        
        // Update strength bars
        for (let i = 0; i < strengthBars.length; i++) {
            if (i < strength) {
                strengthBars[i].classList.add('active');
            } else {
                strengthBars[i].classList.remove('active');
            }
        }
        
        // Update strength text
        if (strength < 2) {
            strengthText.textContent = 'Weak password';
            strengthText.style.color = '#ff4444';
        } else if (strength < 4) {
            strengthText.textContent = 'Medium password';
            strengthText.style.color = '#ffa500';
        } else {
            strengthText.textContent = 'Strong password!';
            strengthText.style.color = '#00fa9a';
        }
        
        checkPasswordMatch();
    }

    function checkPasswordMatch() {
        if (!confirmInput || !passwordMatch) return;
        
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        
        if (confirm.length === 0) {
            passwordMatch.innerHTML = '';
        } else if (password === confirm) {
            passwordMatch.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
            passwordMatch.className = 'password-match match-success';
        } else {
            passwordMatch.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
            passwordMatch.className = 'password-match match-error';
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', checkPasswordStrength);
    }

    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }

    // Form submission loading states
    const profileForm = document.getElementById('profileForm');
    const profileSubmitBtn = profileForm?.querySelector('button[type="submit"]');
    
    if (profileForm && profileSubmitBtn) {
        profileForm.addEventListener('submit', function() {
            const originalText = profileSubmitBtn.innerHTML;
            profileSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            profileSubmitBtn.disabled = true;
            
            // Re-enable after 5 seconds (prevents stuck loading state)
            setTimeout(() => {
                profileSubmitBtn.innerHTML = originalText;
                profileSubmitBtn.disabled = false;
            }, 5000);
        });
    }

    // Password form loading state
    const passwordForm = document.querySelector('.password-form');
    const passwordSubmitBtn = passwordForm?.querySelector('button[type="submit"]');
    
    if (passwordForm && passwordSubmitBtn) {
        passwordForm.addEventListener('submit', function() {
            const originalText = passwordSubmitBtn.innerHTML;
            passwordSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            passwordSubmitBtn.disabled = true;
            
            setTimeout(() => {
                passwordSubmitBtn.innerHTML = originalText;
                passwordSubmitBtn.disabled = false;
            }, 5000);
        });
    }

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
});
</script>
@endpush