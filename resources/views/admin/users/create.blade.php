@extends('layouts.app')

@section('title', 'Add User - Foundify')

@section('content')
<style>
/* ── NETFLIX-STYLE ADMIN CREATE USER PAGE ───────────────── */
:root {
    --netflix-red: #e50914;
    --netflix-red-dark: #b20710;
    --netflix-black: #141414;
    --netflix-dark: #0a0a0a;
    --netflix-card: #1a1a1a;
    --netflix-card-hover: #2a2a2a;
    --netflix-text: #ffffff;
    --netflix-text-secondary: #b3b3b3;
    --netflix-border: #333333;
    --netflix-success: #2e7d32;
    --netflix-warning: #f5c518;
    --netflix-info: #2196f3;
    --netflix-error: #e50914;
    --transition-netflix: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

/* Light Mode Overrides */
body.light {
    --netflix-black: #f5f5f5;
    --netflix-dark: #ffffff;
    --netflix-card: #ffffff;
    --netflix-card-hover: #f8f8f8;
    --netflix-text: #1a1a1a;
    --netflix-text-secondary: #666666;
    --netflix-border: #e0e0e0;
}

.form-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 24px;
}

.form-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 32px;
    transition: var(--transition-netflix);
}

.form-card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.form-header {
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--netflix-border);
}

.form-header h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--netflix-text);
    display: flex;
    align-items: center;
    gap: 12px;
}

.form-header h1 i {
    color: var(--netflix-red);
}

.form-header p {
    color: var(--netflix-text-secondary);
    margin-top: 8px;
    font-size: 14px;
}

/* Form Groups */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.form-label i {
    color: var(--netflix-red);
    margin-right: 6px;
}

.required {
    color: var(--netflix-red);
    margin-left: 2px;
}

.form-control,
.form-select {
    width: 100%;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text);
    font-size: 14px;
    transition: var(--transition-netflix);
}

body.light .form-control,
body.light .form-select {
    background: rgba(0, 0, 0, 0.02);
}

.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--netflix-red);
}

.form-control::placeholder {
    color: var(--netflix-text-secondary);
}

.is-invalid {
    border-color: var(--netflix-error) !important;
}

/* Form Row */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 640px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

/* Photo Section */
.photo-section {
    text-align: center;
    margin-bottom: 24px;
}

.photo-preview {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin: 0 auto 16px;
    border: 1px solid var(--netflix-border);
    transition: var(--transition-netflix);
}

.photo-preview:hover {
    border-color: var(--netflix-red);
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-preview i {
    font-size: 48px;
    color: var(--netflix-red);
}

.photo-hint {
    display: block;
    font-size: 11px;
    color: var(--netflix-text-secondary);
    margin-top: 8px;
}

/* Password Strength */
.password-strength {
    height: 4px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 2px;
    margin-top: 8px;
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0;
    transition: width 0.3s ease;
    border-radius: 2px;
}

.password-match {
    font-size: 11px;
    margin-top: 6px;
}

.password-match.success {
    color: var(--netflix-success);
}

.password-match.error {
    color: var(--netflix-error);
}

/* Error Message */
.error-message {
    color: var(--netflix-error);
    font-size: 11px;
    margin-top: 6px;
    display: block;
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    border: none;
    transition: var(--transition-netflix);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-primary {
    background: var(--netflix-red);
    color: white;
}

.btn-primary:hover {
    background: var(--netflix-red-dark);
    transform: scale(1.02);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
}

.btn-outline:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
    transform: scale(1.02);
}

.btn-group {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--netflix-border);
}

/* Responsive */
@media (max-width: 768px) {
    .form-container {
        padding: 16px;
    }
    
    .form-card {
        padding: 20px;
    }
    
    .form-header h1 {
        font-size: 24px;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        width: 100%;
        justify-content: center;
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

/* Alert Messages */
.alert {
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
}

.alert-error {
    background: rgba(229, 9, 20, 0.15);
    border-left: 3px solid var(--netflix-error);
    color: var(--netflix-error);
}
</style>

<div class="form-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1>
                <i class="fas fa-user-plus"></i>
                Add New User
            </h1>
            <p>Create a new user account</p>
        </div>

        {{-- Display validation errors --}}
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>Please fix the errors below and try again.</span>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="createUserForm">
            @csrf

            {{-- Photo Section --}}
            <div class="photo-section">
                <div class="photo-preview" id="photoPreview">
                    <i class="fas fa-user-circle"></i>
                </div>
                <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display:none;" onchange="previewPhoto(this)">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('profile_photo').click()">
                    <i class="fas fa-camera"></i> Upload Photo
                </button>
                <span class="photo-hint">Max 2MB (JPG, PNG, GIF, WEBP)</span>
                @error('profile_photo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Full Name (Single field to match controller) --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" 
                       placeholder="Enter full name" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" 
                       placeholder="user@example.com" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password Fields --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i> Password <span class="required">*</span>
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" id="password" placeholder="Create a password" 
                           required oninput="checkStrength()">
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i> Confirm Password <span class="required">*</span>
                    </label>
                    <input type="password" class="form-control" name="password_confirmation" 
                           id="passwordConfirm" placeholder="Confirm password" 
                           required oninput="checkMatch()">
                    <div class="password-match" id="matchMsg"></div>
                </div>
            </div>

            {{-- Contact Fields --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                           name="phone" value="{{ old('phone') }}" 
                           placeholder="+63 XXX XXX XXXX">
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                           name="location" value="{{ old('location') }}" 
                           placeholder="City, Country">
                    @error('location')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tag"></i> Role <span class="required">*</span>
                </label>
                <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Regular User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="btn-group">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview photo before upload
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a valid image (JPG, PNG, GIF, WEBP)');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    }
}

// Check password strength
function checkStrength() {
    const pw = document.getElementById('password').value;
    let strength = 0;
    
    if (pw.length >= 8) strength += 25;
    if (pw.length >= 12) strength += 25;
    if (/[A-Z]/.test(pw)) strength += 25;
    if (/[0-9]/.test(pw)) strength += 25;
    if (/[^A-Za-z0-9]/.test(pw)) strength += 25;
    
    if (strength > 100) strength = 100;
    
    const bar = document.getElementById('strengthBar');
    bar.style.width = strength + '%';
    
    if (strength <= 25) {
        bar.style.background = '#e50914';
    } else if (strength <= 50) {
        bar.style.background = '#f5c518';
    } else {
        bar.style.background = '#2e7d32';
    }
    
    checkMatch();
}

// Check password match
function checkMatch() {
    const pw = document.getElementById('password').value;
    const confirm = document.getElementById('passwordConfirm').value;
    const msg = document.getElementById('matchMsg');
    
    if (!confirm) { 
        msg.innerHTML = ''; 
        return; 
    }
    
    if (pw === confirm) {
        msg.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
        msg.className = 'password-match success';
    } else {
        msg.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
        msg.className = 'password-match error';
    }
}

// Form validation before submit
document.getElementById('createUserForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('passwordConfirm').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long');
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        
        // Re-enable after 10 seconds if something goes wrong
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Create User';
            }
        }, 10000);
    }
});

// Trigger initial strength check if there's old password input
if (document.getElementById('password').value) {
    checkStrength();
}
</script>
@endsection