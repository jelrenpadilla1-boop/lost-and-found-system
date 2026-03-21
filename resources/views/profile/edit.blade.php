@extends('layouts.app')

@section('title', 'Edit Profile - Foundify')

@section('content')
<style>
    /* ── Override with theme tokens ────────────────────────────── */
    :root {
        --teal: #00f0c8;
        --teal-dim: #00c4a4;
        --teal-glow: rgba(0,240,200,0.18);
        --teal-faint: rgba(0,240,200,0.06);
        --void: #050811;
        --deep: #090d1a;
        --surface: #0e1525;
        --glass: rgba(255,255,255,0.045);
        --glass-b: rgba(255,255,255,0.08);
        --glass-hover: rgba(255,255,255,0.07);
        --border: rgba(0,240,200,0.14);
        --border-dim: rgba(255,255,255,0.07);
        --white: #e8f0fe;
        --muted: #5a6a8a;
        --subtle: #8898b8;
        --success: #22d37a;
        --success-glow: rgba(34,211,122,0.2);
        --warning: #f0b400;
        --warning-glow: rgba(240,180,0,0.2);
        --error: #ff4d6a;
        --error-glow: rgba(255,77,106,0.2);
        --info: #a78bfa;
        --info-glow: rgba(167,139,250,0.2);
        --ff-display: 'Syne', sans-serif;
        --ff-mono: 'Space Mono', monospace;
        --ff-body: 'Outfit', sans-serif;
        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --radius-xl: 18px;
        --transition: all 0.22s ease;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
        position: relative;
        z-index: 1;
    }

    /* ========== PAGE HEADER ========== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-dim);
    }

    .page-title h1 {
        font-family: var(--ff-display);
        font-size: 28px;
        font-weight: 800;
        color: var(--white);
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.02em;
    }

    .page-title h1 i {
        color: var(--teal);
        font-size: 24px;
        filter: drop-shadow(0 0 8px var(--teal-glow));
    }

    .page-title p {
        font-family: var(--ff-body);
        font-size: 15px;
        color: var(--subtle);
        margin: 0;
        font-weight: 300;
    }

    .page-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* ========== BUTTONS ========== */
    .btn {
        font-family: var(--ff-mono);
        font-size: 11px;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 6px;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-primary {
        background: var(--teal);
        color: var(--void);
        border-color: var(--teal);
    }

    .btn-primary:hover {
        background: var(--teal-dim);
        border-color: var(--teal-dim);
        box-shadow: 0 0 24px var(--teal-glow);
        transform: translateY(-2px);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--border-dim);
        color: var(--subtle);
    }

    .btn-outline:hover {
        border-color: var(--teal);
        color: var(--teal);
        background: var(--teal-faint);
        transform: translateY(-2px);
    }

    .btn-outline-danger {
        background: transparent;
        border: 1px solid rgba(255,77,106,0.2);
        color: var(--error);
    }

    .btn-outline-danger:hover {
        background: var(--error);
        color: white;
        border-color: var(--error);
        transform: translateY(-2px);
        box-shadow: 0 0 20px var(--error-glow);
    }

    .btn-secondary {
        background: transparent;
        border: 1px solid var(--border-dim);
        color: var(--subtle);
    }

    .btn-secondary:hover {
        border-color: var(--teal);
        color: var(--teal);
        background: var(--teal-faint);
        transform: translateY(-2px);
    }

    .btn-danger {
        background: var(--error);
        color: white;
        border-color: var(--error);
    }

    .btn-danger:hover {
        background: #e63e5a;
        border-color: #e63e5a;
        box-shadow: 0 0 24px var(--error-glow);
        transform: translateY(-2px);
    }

    /* ========== CARDS ========== */
    .card {
        background: var(--surface);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-xl);
        overflow: hidden;
        margin-bottom: 24px;
        backdrop-filter: blur(12px);
        transition: var(--transition);
    }

    .card:hover {
        border-color: var(--border);
        box-shadow: 0 0 25px var(--teal-glow);
    }

    .card-header {
        background: rgba(0,0,0,0.2);
        border-bottom: 1px solid var(--border-dim);
        padding: 18px 24px;
    }

    .card-header h5 {
        font-family: var(--ff-mono);
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--white);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .card-header h5 i {
        color: var(--teal);
        font-size: 18px;
    }

    .card-body {
        padding: 28px;
    }

    /* ========== PHOTO CARD ========== */
    .photo-card .card-body {
        padding: 32px;
    }

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
        border-radius: 40px;
        overflow: hidden;
        position: relative;
        z-index: 2;
        transition: var(--transition);
        background: linear-gradient(135deg, var(--teal), var(--teal-dim));
        color: var(--void);
        display: flex;
        align-items: center;
        justify-content: center;
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
        font-family: var(--ff-display);
        font-size: 56px;
        font-weight: 800;
    }

    .photo-ring {
        position: absolute;
        inset: -5px;
        border-radius: 45px;
        background: linear-gradient(135deg, var(--teal), var(--teal-dim));
        opacity: 0;
        transition: var(--transition);
        z-index: 1;
        filter: blur(5px);
    }

    .photo-preview-wrapper:hover .photo-preview {
        transform: scale(0.95);
    }

    .photo-preview-wrapper:hover .photo-ring {
        opacity: 0.5;
        transform: scale(1.1);
    }

    .photo-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .hidden-input {
        display: none;
    }

    .photo-hint {
        font-family: var(--ff-mono);
        color: var(--muted);
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        letter-spacing: 0.04em;
    }

    .photo-hint i {
        color: var(--teal);
        font-size: 13px;
    }

    /* ========== EDIT CARD ========== */
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
        font-family: var(--ff-mono);
        color: var(--white);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .form-label i {
        color: var(--teal);
        font-size: 14px;
    }

    .required {
        color: var(--error);
        font-size: 14px;
        margin-left: 4px;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        background: var(--glass);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-md);
        color: var(--white);
        font-family: var(--ff-body);
        font-size: 15px;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--teal);
        background: var(--teal-faint);
        box-shadow: 0 0 0 3px rgba(0,240,200,0.08);
    }

    .form-control::placeholder {
        color: var(--muted);
        font-family: var(--ff-body);
        font-weight: 300;
        font-size: 14px;
    }

    .is-invalid {
        border-color: var(--error) !important;
    }

    .invalid-feedback {
        display: block;
        color: var(--error);
        font-family: var(--ff-mono);
        font-size: 11px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.04em;
    }

    .invalid-feedback i {
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        gap: 14px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid var(--border-dim);
    }

    @media (max-width: 576px) {
        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }

    /* ========== PASSWORD CARD ========== */
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
        padding-right: 50px;
    }

    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--muted);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: var(--teal);
    }

    /* Password Strength */
    .password-strength {
        margin-top: 12px;
    }

    .strength-bars {
        display: flex;
        gap: 6px;
        margin-bottom: 6px;
    }

    .strength-bar {
        flex: 1;
        height: 4px;
        background: var(--border-dim);
        border-radius: 2px;
        transition: var(--transition);
    }

    .strength-bar.active {
        background: var(--teal);
        box-shadow: 0 0 10px var(--teal-glow);
    }

    .strength-text {
        font-family: var(--ff-mono);
        font-size: 10px;
        color: var(--muted);
        letter-spacing: 0.04em;
    }

    /* Password Match */
    .password-match {
        font-family: var(--ff-mono);
        font-size: 11px;
        margin-top: 6px;
        min-height: 18px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .password-match.text-success {
        color: var(--success);
    }

    .password-match.text-danger {
        color: var(--error);
    }

    /* Requirements Box */
    .requirements-box {
        background: var(--glass);
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-lg);
        padding: 20px;
        margin-bottom: 24px;
        backdrop-filter: blur(8px);
    }

    .requirements-box h6 {
        font-family: var(--ff-mono);
        color: var(--white);
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 16px 0;
        letter-spacing: 0.06em;
        text-transform: uppercase;
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
        font-family: var(--ff-mono);
        color: var(--muted);
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        letter-spacing: 0.04em;
    }

    .requirements-list li.valid {
        color: var(--success);
    }

    .requirements-list li.valid i {
        color: var(--success);
    }

    .requirements-list li i {
        font-size: 8px;
        color: var(--border);
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

    /* ========== MODAL STYLES ========== */
    .modal-content {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: 0 25px 40px rgba(0,0,0,0.5);
        backdrop-filter: blur(12px);
    }

    .modal-header {
        background: rgba(0,0,0,0.2);
        border-bottom: 1px solid var(--border-dim);
        padding: 18px 24px;
        border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-family: var(--ff-display);
        font-size: 18px;
        font-weight: 700;
        color: var(--white);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-title i {
        color: var(--error);
    }

    .modal-close {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--glass);
        border: 1px solid var(--border-dim);
        color: var(--subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
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
        font-family: var(--ff-body);
        color: var(--subtle);
        margin: 0;
        font-size: 15px;
        font-weight: 300;
    }

    .modal-footer {
        background: rgba(0,0,0,0.2);
        border-top: 1px solid var(--border-dim);
        padding: 18px 24px;
        border-radius: 0 0 var(--radius-xl) var(--radius-xl);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    /* ========== TOAST NOTIFICATIONS ========== */
    #notificationsContainer {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        max-width: 350px;
    }

    .toast {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        min-width: 300px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3), 0 0 20px var(--teal-glow);
        margin-bottom: 8px;
        backdrop-filter: blur(12px);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(20px);
        }
    }

    .toast-body {
        font-family: var(--ff-mono);
        color: var(--white);
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        font-size: 12px;
    }

    .toast-body i {
        font-size: 16px;
    }

    .toast-close {
        background: transparent;
        border: none;
        color: var(--muted);
        margin-left: auto;
        cursor: pointer;
        padding: 0 5px;
        font-size: 18px;
        transition: var(--transition);
    }

    .toast-close:hover {
        color: var(--error);
        transform: rotate(90deg);
    }

    /* ========== ANIMATIONS ========== */
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

    .fade-in {
        animation: fadeIn 0.4s ease forwards;
    }
</style>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-user-edit"></i>
                EDIT_PROFILE
            </h1>
            <p>Update your personal information and account settings</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('profile.show') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                BACK
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Profile Photo Card -->
            <div class="card photo-card fade-in">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-camera"></i>
                        PROFILE_PHOTO
                    </h5>
                </div>
                <div class="card-body">
                    <div class="photo-section">
                        <div class="photo-preview-wrapper">
                            <div class="photo-preview" id="avatarPreview">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="preview-image">
                                @else
                                    <div class="preview-initial">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="photo-ring"></div>
                        </div>

                        <div class="photo-actions">
                            <label for="profile_photo" class="btn btn-primary">
                                <i class="fas fa-cloud-upload-alt"></i>
                                CHOOSE_PHOTO
                            </label>
                            <input type="file" 
                                   id="profile_photo" 
                                   name="profile_photo" 
                                   form="profileForm" 
                                   accept="image/*" 
                                   class="hidden-input">

                            @if(Auth::user()->profile_photo)
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removePhotoModal">
                                    <i class="fas fa-trash-alt"></i>
                                    REMOVE
                                </button>
                            @endif
                        </div>

                        <p class="photo-hint">
                            <i class="fas fa-info-circle"></i>
                            JPG, PNG or GIF • MAX 2MB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Card -->
            <div class="card edit-card fade-in">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-user-circle"></i>
                        PERSONAL_INFO
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" class="edit-form" id="profileForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <!-- Full Name -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    FULL_NAME <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name', Auth::user()->name) }}" 
                                       placeholder="Enter your full name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    EMAIL <span class="required">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email', Auth::user()->email) }}" 
                                       placeholder="Enter your email"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone-alt"></i>
                                    PHONE
                                </label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" 
                                       value="{{ old('phone', Auth::user()->phone) }}" 
                                       placeholder="+63 XXX XXX XXXX">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    LOCATION
                                </label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       name="location" 
                                       value="{{ old('location', Auth::user()->location) }}" 
                                       placeholder="City, Country">
                                @error('location')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                CANCEL
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                SAVE
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card password-card fade-in">
                <div class="card-header">
                    <h5>
                        <i class="fas fa-lock"></i>
                        CHANGE_PASSWORD
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST" class="password-form">
                        @csrf
                        @method('PUT')

                        <div class="password-grid">
                            <!-- Current Password -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    CURRENT <span class="required">*</span>
                                </label>
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
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    NEW <span class="required">*</span>
                                </label>
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
                                    <div class="invalid-feedback">
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
                                    <span class="strength-text" id="strengthText">ENTER_PASSWORD</span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>
                                    CONFIRM <span class="required">*</span>
                                </label>
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

                        <!-- Password Requirements -->
                        <div class="requirements-box">
                            <h6>REQUIREMENTS:</h6>
                            <ul class="requirements-list">
                                <li id="req-length">
                                    <i class="fas fa-circle"></i>
                                    AT LEAST 8 CHARACTERS
                                </li>
                                <li id="req-uppercase">
                                    <i class="fas fa-circle"></i>
                                    ONE UPPERCASE LETTER
                                </li>
                                <li id="req-lowercase">
                                    <i class="fas fa-circle"></i>
                                    ONE LOWERCASE LETTER
                                </li>
                                <li id="req-number">
                                    <i class="fas fa-circle"></i>
                                    ONE NUMBER
                                </li>
                                <li id="req-special">
                                    <i class="fas fa-circle"></i>
                                    ONE SPECIAL CHARACTER
                                </li>
                            </ul>
                        </div>

                        <div class="password-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i>
                                UPDATE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Remove Photo Modal -->
<div class="modal fade" id="removePhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle" style="color: var(--error);"></i>
                    REMOVE_PHOTO
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove your profile photo? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                <form action="{{ route('profile.photo.remove') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i>
                        REMOVE
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Container -->
<div id="notificationsContainer"></div>

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
                    showToast('FILE_SIZE_MUST_BE_LESS_THAN_2MB', 'error');
                    this.value = '';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    showToast('PLEASE_UPLOAD_VALID_IMAGE (JPG, PNG, GIF)', 'error');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile" class="preview-image">`;
                };
                reader.readAsDataURL(file);

                showToast('PHOTO_SELECTED', 'success');
            }
        });
    }

    // Toggle password visibility
    document.querySelectorAll('.password-toggle').forEach(button => {
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
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBars = [
        document.getElementById('strengthBar1'),
        document.getElementById('strengthBar2'),
        document.getElementById('strengthBar3'),
        document.getElementById('strengthBar4')
    ];
    const strengthText = document.getElementById('strengthText');
    const passwordMatch = document.querySelector('.password-match');

    // Requirements elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    function checkPasswordStrength() {
        if (!passwordInput) return;

        const password = passwordInput.value;

        // Check requirements
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);

        // Update requirement indicators
        if (reqLength) reqLength.className = hasLength ? 'valid' : '';
        if (reqUppercase) reqUppercase.className = hasUppercase ? 'valid' : '';
        if (reqLowercase) reqLowercase.className = hasLowercase ? 'valid' : '';
        if (reqNumber) reqNumber.className = hasNumber ? 'valid' : '';
        if (reqSpecial) reqSpecial.className = hasSpecial ? 'valid' : '';

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
            strengthText.textContent = 'WEAK';
            strengthText.style.color = '#ff4d6a';
        } else if (strength < 4) {
            strengthText.textContent = 'MEDIUM';
            strengthText.style.color = '#f0b400';
        } else {
            strengthText.textContent = 'STRONG';
            strengthText.style.color = '#22d37a';
        }

        checkPasswordMatch();
    }

    function checkPasswordMatch() {
        if (!passwordInput || !confirmInput || !passwordMatch) return;

        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (confirm.length === 0) {
            passwordMatch.innerHTML = '';
        } else if (password === confirm) {
            passwordMatch.innerHTML = '<i class="fas fa-check-circle"></i> PASSWORDS_MATCH';
            passwordMatch.className = 'password-match text-success';
        } else {
            passwordMatch.innerHTML = '<i class="fas fa-exclamation-circle"></i> PASSWORDS_DO_NOT_MATCH';
            passwordMatch.className = 'password-match text-danger';
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
        profileForm.addEventListener('submit', function(e) {
            const requiredFields = ['name', 'email'];
            let isValid = true;

            requiredFields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showToast('PLEASE_FILL_ALL_REQUIRED_FIELDS', 'error');
                return;
            }

            const originalText = profileSubmitBtn.innerHTML;
            profileSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SAVING...';
            profileSubmitBtn.disabled = true;

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
        passwordForm.addEventListener('submit', function(e) {
            const requiredFields = ['current_password', 'new_password', 'new_password_confirmation'];
            let isValid = true;

            requiredFields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showToast('PLEASE_FILL_ALL_REQUIRED_FIELDS', 'error');
                return;
            }

            const originalText = passwordSubmitBtn.innerHTML;
            passwordSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> UPDATING...';
            passwordSubmitBtn.disabled = true;

            setTimeout(() => {
                passwordSubmitBtn.innerHTML = originalText;
                passwordSubmitBtn.disabled = false;
            }, 5000);
        });
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const container = document.getElementById('notificationsContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'toast';

        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
        const color = type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--error)' : 'var(--teal)';

        toast.innerHTML = `
            <div class="toast-body">
                <i class="fas fa-${icon}" style="color: ${color};"></i>
                <span>${message}</span>
                <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
});
</script>
@endpush
@endsection