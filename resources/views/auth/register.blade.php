@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-container">
    <!-- Left Panel - Brand & Info -->
    <div class="left-panel">
        <div class="brand-wrapper">
            <div class="logo-circle">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="brand-name">Foundify</h1>
            <p class="brand-tagline">Join our community of helpers</p>
        </div>
        
        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="feature-text">
                    <h4>Secure Account</h4>
                    <p>Your information is protected</p>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="feature-text">
                    <h4>Find Items Faster</h4>
                    <p>AI-powered matching system</p>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="feature-text">
                    <h4>Help Others</h4>
                    <p>Make a difference in your community</p>
                </div>
            </div>
        </div>
        
        <div class="quote">
            <p>"Every registered user helps reunite lost items with their owners."</p>
        </div>
    </div>
    
    <!-- Right Panel - Registration Form -->
    <div class="right-panel">
        <div class="form-wrapper">
            <div class="form-header">
                <h2>Create Account</h2>
                <p>Join Foundify today</p>
            </div>
            
            @if($errors->any())
            <div class="alert-box">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-content">
                    <h4>Please check your input</h4>
                    @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif
            
            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf
                
                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Enter your full name"
                           required 
                           autofocus>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="you@example.com"
                           required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="password-field">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Create a password (min. 8 characters)"
                               required>
                        <button type="button" class="toggle-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <div class="password-strength">
                        <small>Password strength: <span id="strength-text">None</span></small>
                    </div>
                </div>
                
                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <div class="password-field">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Re-enter your password"
                               required>
                        <button type="button" class="toggle-password" data-target="password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Location Section -->
                <div class="location-section">
                    <button type="button" class="location-toggle" id="locationToggle">
                        <span>
                            <i class="fas fa-map-marker-alt"></i>
                            Add Location (Optional)
                        </span>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </button>
                    
                    <div class="location-content" id="locationContent">
                        <div class="location-help">
                            <small>Help us provide better location-based matches</small>
                        </div>
                        
                        <div class="location-grid">
                            <div class="location-input-group">
                                <label for="latitude">Latitude</label>
                                <input type="number" 
                                       step="any" 
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude') }}"
                                       placeholder="e.g., 40.7128">
                            </div>
                            
                            <div class="location-input-group">
                                <label for="longitude">Longitude</label>
                                <input type="number" 
                                       step="any" 
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude') }}"
                                       placeholder="e.g., -74.0060">
                            </div>
                        </div>
                        
                        <button type="button" class="location-btn" id="getLocationBtn">
                            <i class="fas fa-location-arrow"></i>
                            Use Current Location
                        </button>
                    </div>
                </div>
                
                <!-- Terms & Conditions -->
                <div class="terms-section">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span class="checkbox-custom"></span>
                        <span class="terms-text">
                            I agree to the 
                            <a href="#" class="terms-link">Terms of Service</a> 
                            and 
                            <a href="#" class="terms-link">Privacy Policy</a>
                        </span>
                    </label>
                    @error('terms')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Create Account</span>
                    <i class="fas fa-user-plus btn-icon"></i>
                </button>
                
                <!-- Divider -->
                <div class="divider">
                    <span>Already have an account?</span>
                </div>
                
                <!-- Login Link -->
                <div class="login-link">
                    <p>
                        <a href="{{ route('login') }}">Sign In Instead</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* CSS Variables - Black & White Theme */
    :root {
        --white: #ffffff;
        --off-white: #fafafa;
        --light-gray: #f5f5f5;
        --medium-gray: #e5e5e5;
        --gray: #a3a3a3;
        --dark-gray: #737373;
        --darker-gray: #404040;
        --black: #171717;
        --border: #e5e5e5;
        --error: #dc2626;
        --error-bg: #fef2f2;
        --error-border: #fecaca;
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        --transition: all 0.2s ease;
    }

    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--off-white);
        color: var(--black);
        line-height: 1.5;
    }
    
    .auth-container {
        display: flex;
        min-height: 100vh;
    }
    
    /* Left Panel - Black Theme */
    .left-panel {
        flex: 1;
        background: var(--black);
        color: var(--white);
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
    }
    
    .brand-wrapper {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .logo-circle {
        width: 80px;
        height: 80px;
        background: var(--darker-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 32px;
        border: 2px solid var(--dark-gray);
        color: var(--white);
    }
    
    .brand-name {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--white);
    }
    
    .brand-tagline {
        font-size: 14px;
        opacity: 0.8;
        max-width: 300px;
        margin: 0 auto;
        color: var(--gray);
    }
    
    .features {
        flex: 1;
        margin-bottom: 40px;
    }
    
    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 30px;
    }
    
    .feature-icon {
        width: 44px;
        height: 44px;
        background: var(--darker-gray);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        color: var(--white);
        border: 1px solid var(--dark-gray);
    }
    
    .feature-text h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--white);
    }
    
    .feature-text p {
        font-size: 14px;
        opacity: 0.7;
        line-height: 1.5;
        color: var(--gray);
    }
    
    .quote {
        padding: 20px;
        background: var(--darker-gray);
        border-radius: 12px;
        text-align: center;
        font-style: italic;
        font-size: 15px;
        border: 1px solid var(--dark-gray);
        color: var(--white);
    }
    
    /* Right Panel */
    .right-panel {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        overflow-y: auto;
        background: var(--white);
    }
    
    .form-wrapper {
        width: 100%;
        max-width: 440px;
    }
    
    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .form-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 8px;
    }
    
    .form-header p {
        color: var(--dark-gray);
        font-size: 14px;
    }
    
    /* Alert Box */
    .alert-box {
        background: var(--error-bg);
        border: 1px solid var(--error-border);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }
    
    .alert-icon {
        color: var(--error);
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .alert-content h4 {
        font-size: 14px;
        color: #991b1b;
        margin-bottom: 4px;
        font-weight: 600;
    }
    
    .alert-content p {
        font-size: 13px;
        color: #7f1d1d;
    }
    
    /* Form Styles */
    .auth-form {
        background: var(--white);
        padding: 32px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 500;
        color: var(--darker-gray);
        margin-bottom: 8px;
    }
    
    .form-group label i {
        color: var(--black);
        width: 16px;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: var(--border-radius-sm);
        font-size: 15px;
        transition: var(--transition);
        background: var(--white);
        color: var(--black);
    }
    
    .form-group input:focus {
        outline: none;
        border-color: var(--black);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }
    
    .form-group input::placeholder {
        color: var(--gray);
    }
    
    .password-field {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        font-size: 16px;
        padding: 4px;
        transition: var(--transition);
    }
    
    .toggle-password:hover {
        color: var(--black);
    }
    
    .password-strength {
        margin-top: 6px;
        font-size: 13px;
        color: var(--dark-gray);
    }
    
    #strength-text {
        font-weight: 500;
    }
    
    .error-message {
        display: block;
        color: var(--error);
        font-size: 13px;
        margin-top: 6px;
    }
    
    /* Location Section */
    .location-section {
        margin: 24px 0;
        border: 1px solid var(--border);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
    }
    
    .location-toggle {
        width: 100%;
        padding: 14px 16px;
        background: var(--off-white);
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        font-weight: 500;
        color: var(--darker-gray);
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .location-toggle:hover {
        background: var(--light-gray);
    }
    
    .location-toggle i {
        color: var(--dark-gray);
    }
    
    .location-toggle .toggle-icon {
        transition: transform 0.3s ease;
    }
    
    .location-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background: var(--white);
    }
    
    .location-content.expanded {
        max-height: 300px;
    }
    
    .location-help {
        padding: 16px 16px 8px;
        color: var(--dark-gray);
        font-size: 13px;
    }
    
    .location-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 0 16px;
    }
    
    .location-input-group {
        margin-bottom: 8px;
    }
    
    .location-input-group label {
        display: block;
        font-size: 13px;
        color: var(--darker-gray);
        margin-bottom: 4px;
        font-weight: 500;
    }
    
    .location-input-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--border-radius-sm);
        font-size: 14px;
        background: var(--white);
        color: var(--black);
    }
    
    .location-input-group input:focus {
        border-color: var(--black);
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
    }
    
    .location-btn {
        width: calc(100% - 32px);
        margin: 12px 16px 16px;
        padding: 10px;
        background: var(--white);
        border: 1px solid var(--black);
        color: var(--black);
        border-radius: var(--border-radius-sm);
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: var(--transition);
    }
    
    .location-btn:hover {
        background: var(--black);
        color: var(--white);
    }
    
    /* Terms Section */
    .terms-section {
        margin: 24px 0;
        padding: 16px;
        background: var(--off-white);
        border-radius: var(--border-radius-sm);
    }
    
    .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        cursor: pointer;
        font-size: 14px;
        color: var(--darker-gray);
        line-height: 1.4;
    }
    
    .checkbox-label input {
        display: none;
    }
    
    .checkbox-custom {
        width: 18px;
        height: 18px;
        border: 1px solid var(--border);
        border-radius: 4px;
        position: relative;
        transition: var(--transition);
        flex-shrink: 0;
        margin-top: 2px;
        background: var(--white);
    }
    
    .checkbox-label input:checked + .checkbox-custom {
        background: var(--black);
        border-color: var(--black);
    }
    
    .checkbox-label input:checked + .checkbox-custom::after {
        content: '✓';
        position: absolute;
        color: var(--white);
        font-size: 12px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .terms-text {
        flex: 1;
        color: var(--dark-gray);
    }
    
    .terms-link {
        color: var(--black);
        text-decoration: none;
        font-weight: 600;
    }
    
    .terms-link:hover {
        text-decoration: underline;
    }
    
    /* Submit Button */
    .submit-btn {
        width: 100%;
        background: var(--black);
        color: var(--white);
        border: 1px solid var(--black);
        border-radius: var(--border-radius-sm);
        padding: 14px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: var(--transition);
        margin-top: 16px;
    }
    
    .submit-btn:hover {
        background: var(--darker-gray);
        border-color: var(--darker-gray);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-icon {
        transition: transform 0.3s;
    }
    
    .submit-btn:hover .btn-icon {
        transform: translateX(4px);
    }
    
    /* Divider */
    .divider {
        position: relative;
        text-align: center;
        margin: 24px 0;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: var(--border);
    }
    
    .divider span {
        position: relative;
        background: var(--white);
        padding: 0 16px;
        color: var(--dark-gray);
        font-size: 14px;
    }
    
    /* Login Link */
    .login-link {
        text-align: center;
    }
    
    .login-link p {
        color: var(--dark-gray);
        font-size: 14px;
    }
    
    .login-link a {
        color: var(--black);
        text-decoration: none;
        font-weight: 600;
    }
    
    .login-link a:hover {
        text-decoration: underline;
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
        .auth-container {
            flex-direction: column;
            min-height: auto;
        }
        
        .left-panel {
            padding: 40px 20px;
        }
        
        .right-panel {
            padding: 20px;
        }
        
        .location-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .left-panel {
            display: none;
        }
        
        .right-panel {
            align-items: flex-start;
            padding-top: 40px;
        }
        
        .form-wrapper {
            max-width: 100%;
        }
        
        .auth-form {
            padding: 24px;
        }
    }
    
    @media (max-height: 700px) {
        .right-panel {
            align-items: flex-start;
            padding-top: 20px;
        }
        
        .form-wrapper {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .auth-form {
        animation: fadeIn 0.5s ease forwards;
    }

    /* Utility */
    .border-bottom {
        border-bottom: 1px solid var(--border);
    }
    
    .border-top {
        border-top: 1px solid var(--border);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                if (passwordInput) {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                }
            });
        });
        
        // Toggle location section
        const locationToggle = document.getElementById('locationToggle');
        const locationContent = document.getElementById('locationContent');
        
        if (locationToggle && locationContent) {
            const toggleIcon = locationToggle.querySelector('.toggle-icon');
            
            locationToggle.addEventListener('click', function() {
                locationContent.classList.toggle('expanded');
                toggleIcon.style.transform = locationContent.classList.contains('expanded') 
                    ? 'rotate(180deg)' 
                    : 'rotate(0deg)';
            });
        }
        
        // Get current location
        const getLocationBtn = document.getElementById('getLocationBtn');
        if (getLocationBtn) {
            getLocationBtn.addEventListener('click', function() {
                const originalText = this.innerHTML;
                
                if (navigator.geolocation) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Getting location...';
                    this.disabled = true;
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                            document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                            
                            getLocationBtn.innerHTML = '<i class="fas fa-check"></i> Location retrieved!';
                            getLocationBtn.style.background = '#171717';
                            getLocationBtn.style.color = 'white';
                            
                            setTimeout(() => {
                                getLocationBtn.innerHTML = originalText;
                                getLocationBtn.style.background = '';
                                getLocationBtn.style.color = '';
                                getLocationBtn.disabled = false;
                            }, 2000);
                        },
                        function(error) {
                            let message = 'Unable to retrieve location. Please enter manually.';
                            
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    message = 'Location access denied. Please enable location services.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    message = 'Location information unavailable.';
                                    break;
                                case error.TIMEOUT:
                                    message = 'Location request timed out.';
                                    break;
                            }
                            
                            alert(message);
                            getLocationBtn.innerHTML = originalText;
                            getLocationBtn.disabled = false;
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });
        }
        
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthText = document.getElementById('strength-text');
        
        if (passwordInput && strengthText) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                if (password.length >= 8) strength++;
                if (password.length >= 12) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                // Update strength text and color
                const strengthLevels = [
                    { text: 'None', color: '#737373' },
                    { text: 'Weak', color: '#dc2626' },
                    { text: 'Fair', color: '#f59e0b' },
                    { text: 'Good', color: '#404040' },
                    { text: 'Strong', color: '#171717' },
                    { text: 'Very Strong', color: '#171717' }
                ];
                
                const level = Math.min(strength, strengthLevels.length - 1);
                strengthText.textContent = strengthLevels[level].text;
                strengthText.style.color = strengthLevels[level].color;
            });
        }
        
        // Form validation and loading state
        const form = document.querySelector('.auth-form');
        const submitBtn = form.querySelector('.submit-btn');
        
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                // Check password match
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match. Please make sure both passwords are the same.');
                    return;
                }
                
                // Check terms agreement
                const termsCheckbox = document.getElementById('terms');
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Please agree to the Terms of Service and Privacy Policy.');
                    return;
                }
                
                // Add loading state
                const originalText = submitBtn.querySelector('.btn-text').textContent;
                submitBtn.querySelector('.btn-text').textContent = 'Creating Account...';
                submitBtn.disabled = true;
                
                // Re-enable after 5 seconds (in case of error)
                setTimeout(() => {
                    submitBtn.querySelector('.btn-text').textContent = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        }
        
        // Auto-focus name field
        const nameInput = document.getElementById('name');
        if (nameInput) {
            setTimeout(() => {
                nameInput.focus();
            }, 300);
        }
    });
</script>
@endpush
@endsection