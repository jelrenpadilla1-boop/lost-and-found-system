@extends('layouts.app')

@section('title', 'Edit User - Foundify')

@section('content')
<style>
/* ── NETFLIX-STYLE ADMIN EDIT USER PAGE ───────────────── */
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

/* Required Star */
.required {
    color: var(--netflix-red);
    margin-left: 2px;
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

.photo-placeholder {
    font-size: 48px;
    color: var(--netflix-red);
}

.photo-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
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

/* Password Match Message */
.password-match {
    font-size: 11px;
    margin-top: 6px;
}

.password-match.success {
    color: var(--netflix-success);
}

.password-match.error {
    color: var(--netflix-red);
}

/* Error Message */
.error-message {
    color: var(--netflix-red);
    font-size: 11px;
    margin-top: 6px;
    display: block;
}

/* Info Text */
.info-text {
    font-size: 11px;
    color: var(--netflix-text-secondary);
    margin-top: 4px;
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
    
    .photo-actions {
        flex-direction: column;
    }
    
    .photo-actions .btn {
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
</style>

<div class="form-container">
    <div class="form-card fade-in">
        <div class="form-header">
            <h1>
                <i class="fas fa-user-edit"></i>
                Edit User
            </h1>
            <p>Update user information and settings</p>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="editUserForm">
            @csrf
            @method('PUT')

            {{-- Photo Section --}}
            <div class="photo-section">
                <div class="photo-preview" id="photoPreview">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                    @else
                        <i class="fas fa-user-circle photo-placeholder"></i>
                    @endif
                </div>
                <div class="photo-actions">
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display:none;" onchange="previewPhoto(this)">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('profile_photo').click()">
                        <i class="fas fa-camera"></i> Change Photo
                    </button>
                    @if($user->profile_photo)
                    <button type="button" class="btn btn-outline" onclick="clearPhoto()">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                    @endif
                </div>
            </div>

            {{-- Full Name --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name', $user->name) }}" 
                       placeholder="Enter full name" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email Address --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email', $user->email) }}" 
                       placeholder="user@example.com" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Phone Number --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-phone"></i> Phone Number
                </label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                       name="phone" value="{{ old('phone', $user->phone) }}" 
                       placeholder="+1 234 567 8900">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Location --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-map-marker-alt"></i> Location
                </label>
                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                       name="location" value="{{ old('location', $user->location) }}" 
                       placeholder="City, Country">
                @error('location')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-tag"></i> Role <span class="required">*</span>
                </label>
                <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                    <option value="user" {{ ($user->role ?? 'user') == 'user' ? 'selected' : '' }}>Regular User</option>
                    <option value="admin" {{ ($user->role ?? '') == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Account Status --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-toggle-on"></i> Account Status
                </label>
                <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('is_active')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- New Password --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock"></i> New Password
                </label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" id="password" placeholder="Leave blank to keep current" 
                       oninput="checkMatch()">
                <div class="info-text">Password must be at least 8 characters long.</div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock"></i> Confirm New Password
                </label>
                <input type="password" class="form-control" name="password_confirmation" 
                       id="passwordConfirm" placeholder="Confirm new password" 
                       oninput="checkMatch()">
                <div class="password-match" id="matchMsg"></div>
            </div>

            {{-- Form Actions --}}
            <div class="btn-group">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview photo before upload
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Remove profile photo
function clearPhoto() {
    if (confirm('Remove profile photo?')) {
        const form = document.querySelector('form');
        let input = document.querySelector('input[name="remove_photo"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_photo';
            input.value = '1';
            form.appendChild(input);
        } else {
            input.value = '1';
        }
        document.getElementById('photoPreview').innerHTML = '<i class="fas fa-user-circle photo-placeholder"></i>';
        
        // Clear the file input
        const fileInput = document.getElementById('profile_photo');
        if (fileInput) fileInput.value = '';
    }
}

// Check password match
function checkMatch() {
    const pw = document.getElementById('password').value;
    const confirm = document.getElementById('passwordConfirm').value;
    const msg = document.getElementById('matchMsg');
    
    if (!confirm && !pw) { 
        msg.innerHTML = ''; 
        return; 
    }
    if (!confirm && pw) { 
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
document.getElementById('editUserForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('passwordConfirm').value;
    
    // If password field is filled, check if it matches
    if (password || confirm) {
        if (password !== confirm) {
            e.preventDefault();
            document.getElementById('matchMsg').innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
            document.getElementById('matchMsg').className = 'password-match error';
            alert('Passwords do not match');
            return false;
        }
        
        if (password.length > 0 && password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long');
            return false;
        }
    }
    
    // Show loading state on submit button
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
            }
        }, 10000);
    }
});

// Auto-dismiss error messages after 5 seconds
setTimeout(() => {
    document.querySelectorAll('.error-message').forEach(el => {
        if (el.innerText) {
            setTimeout(() => {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }, 5000);
        }
    });
}, 100);

// Animation for form card
document.querySelector('.form-card')?.classList.add('fade-in');
</script>
@endsection