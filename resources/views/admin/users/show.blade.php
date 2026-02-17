@extends('layouts.app')

@section('title', 'User Details - Admin')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-user-circle" style="color: var(--primary);"></i> User Details
        </h1>
        <p>View and manage user information</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">
            <i class="fas fa-edit"></i> Edit User
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- User Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar {{ $user->profile_photo ? 'has-image' : '' }}">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                             alt="{{ $user->name }}" 
                             class="avatar-image">
                    @else
                        <div class="avatar-initial" style="background: linear-gradient(135deg, {{ '#' . substr(md5($user->name), 0, 6) }}, var(--primary));">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <div class="profile-badges">
                        @if($user->isAdmin())
                            <span class="role-badge admin">Admin</span>
                        @else
                            <span class="role-badge user">User</span>
                        @endif
                        
                        @if($user->is_active ?? true)
                            <span class="status-badge active">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @else
                            <span class="status-badge inactive">
                                <i class="fas fa-times-circle"></i> Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="profile-details">
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Email Address</span>
                        <a href="mailto:{{ $user->email }}" class="detail-value">{{ $user->email }}</a>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Member Since</span>
                        <span class="detail-value">{{ $user->created_at->format('F d, Y') }}</span>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Last Updated</span>
                        <span class="detail-value">{{ $user->updated_at->format('F d, Y') }}</span>
                    </div>
                </div>
                
                @if($user->phone)
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Phone</span>
                        <a href="tel:{{ $user->phone }}" class="detail-value">{{ $user->phone }}</a>
                    </div>
                </div>
                @endif
                
                @if($user->location)
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Location</span>
                        <span class="detail-value">{{ $user->location }}</span>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="profile-actions">
                <button type="button" class="action-btn reset-password" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                    <i class="fas fa-key"></i> Reset Password
                </button>
                
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete">
                        <i class="fas fa-trash"></i> Delete User
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- User Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card-small">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['lost_items'] }}</div>
                        <div class="stat-label">Lost Items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-small">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['found_items'] }}</div>
                        <div class="stat-label">Found Items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card-small">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $stats['confirmed_matches'] }}</div>
                        <div class="stat-label">Matches</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity Tabs -->
        <div class="activity-card">
            <div class="card-header">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="lost-tab" data-bs-toggle="tab" data-bs-target="#lost" type="button" role="tab">
                            <i class="fas fa-exclamation-circle"></i> Lost Items
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="found-tab" data-bs-toggle="tab" data-bs-target="#found" type="button" role="tab">
                            <i class="fas fa-check-circle"></i> Found Items
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#matches" type="button" role="tab">
                            <i class="fas fa-exchange-alt"></i> Matches
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Lost Items Tab -->
                    <div class="tab-pane fade show active" id="lost" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['lostItems'] ?? [] as $item)
                            <a href="{{ route('lost-items.show', $item) }}" class="item-row">
                                <div class="item-icon lost">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="item-info">
                                    <div class="item-name">{{ $item->item_name }}</div>
                                    <div class="item-meta">
                                        <span><i class="fas fa-calendar"></i> {{ $item->created_at->format('M d, Y') }}</span>
                                        <span class="status-badge status-{{ $item->status }}">{{ $item->status }}</span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-box-open fa-3x" style="color: var(--text-muted); opacity: 0.3;"></i>
                                <p class="mt-2">No lost items found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Found Items Tab -->
                    <div class="tab-pane fade" id="found" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['foundItems'] ?? [] as $item)
                            <a href="{{ route('found-items.show', $item) }}" class="item-row">
                                <div class="item-icon found">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="item-info">
                                    <div class="item-name">{{ $item->item_name }}</div>
                                    <div class="item-meta">
                                        <span><i class="fas fa-calendar"></i> {{ $item->created_at->format('M d, Y') }}</span>
                                        <span class="status-badge status-{{ $item->status }}">{{ $item->status }}</span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-box-open fa-3x" style="color: var(--text-muted); opacity: 0.3;"></i>
                                <p class="mt-2">No found items</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Matches Tab -->
                    <div class="tab-pane fade" id="matches" role="tabpanel">
                        <div class="items-list">
                            @forelse($recentActivities['matches'] ?? [] as $match)
                            <a href="{{ route('matches.show', $match) }}" class="item-row">
                                <div class="item-icon match">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="item-info">
                                    <div class="item-name">Match #{{ $match->id }}</div>
                                    <div class="item-meta">
                                        <span><i class="fas fa-calendar"></i> {{ $match->created_at->format('M d, Y') }}</span>
                                        <span class="score-badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                            {{ $match->match_score }}%
                                        </span>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right arrow"></i>
                            </a>
                            @empty
                            <div class="text-center py-4">
                                <i class="fas fa-exchange-alt fa-3x" style="color: var(--text-muted); opacity: 0.3;"></i>
                                <p class="mt-2">No matches found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit" style="color: var(--primary);"></i> Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Profile Photo Section -->
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <i class="fas fa-camera"></i> Profile Photo
                        </label>
                        <div class="photo-upload-wrapper">
                            <div class="current-photo mb-3 text-center">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                         alt="{{ $user->name }}" 
                                         class="profile-photo-preview">
                                @else
                                    <div class="profile-photo-placeholder" style="background: linear-gradient(135deg, {{ '#' . substr(md5($user->name), 0, 6) }}, var(--primary));">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="photo-actions">
                                <input type="file" 
                                       class="photo-input" 
                                       id="profile_photo" 
                                       name="profile_photo" 
                                       accept="image/*">
                                <label for="profile_photo" class="btn-upload">
                                    <i class="fas fa-cloud-upload-alt"></i> Change Photo
                                </label>
                                @if($user->profile_photo)
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" name="remove_photo" id="remove_photo">
                                    <label class="form-check-label text-danger" for="remove_photo">
                                        <i class="fas fa-trash"></i> Remove current photo
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Name
                        </label>
                        <input type="text" class="pink-input" name="name" value="{{ $user->name }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" class="pink-input" name="email" value="{{ $user->email }}" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Phone
                        </label>
                        <input type="text" class="pink-input" name="phone" value="{{ $user->phone ?? '' }}" placeholder="Optional">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Location
                        </label>
                        <input type="text" class="pink-input" name="location" value="{{ $user->location ?? '' }}" placeholder="Optional">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-tag"></i> Role
                        </label>
                        <div class="select-wrapper">
                            <select class="pink-select" name="role">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="toggle-checkbox">
                            <input type="checkbox" name="is_active" id="is_active" {{ ($user->is_active ?? true) ? 'checked' : '' }}>
                            <label for="is_active">
                                <i class="fas fa-check-circle"></i> Active Account
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Changes
                        <div class="btn-glow"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key" style="color: var(--primary);"></i> Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>
            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> New Password
                        </label>
                        <input type="password" class="pink-input" name="password" required minlength="8">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-lock"></i> Confirm Password
                        </label>
                        <input type="password" class="pink-input" name="password_confirmation" required minlength="8">
                    </div>
                    
                    <div class="alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Password must be at least 8 characters long.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-key"></i> Reset Password
                        <div class="btn-glow"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Profile Card */
.profile-card {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 20px;
}

.profile-header {
    background: linear-gradient(135deg, #222, #1a1a1a);
    padding: 30px 20px;
    text-align: center;
    border-bottom: 1px solid #333;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 30px;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 40px;
    box-shadow: 0 0 30px var(--primary-glow);
    overflow: hidden;
}

.profile-avatar.has-image {
    background: none;
    box-shadow: 0 0 30px var(--primary-glow);
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 30px;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 30px;
    transition: transform 0.3s ease;
}

.profile-avatar:hover .avatar-image {
    transform: scale(1.05);
}

.profile-name {
    color: white;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
}

.profile-badges {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.role-badge {
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
}

.role-badge.admin {
    background: rgba(255, 20, 147, 0.15);
    color: var(--primary);
    border: 1px solid var(--primary);
}

.role-badge.user {
    background: rgba(160, 160, 160, 0.1);
    color: #a0a0a0;
    border: 1px solid #333;
}

.status-badge {
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-badge.active {
    background: rgba(0, 250, 154, 0.1);
    color: #00fa9a;
    border: 1px solid #00fa9a;
}

.status-badge.inactive {
    background: rgba(255, 68, 68, 0.1);
    color: #ff4444;
    border: 1px solid #ff4444;
}

.profile-details {
    padding: 20px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px;
    border-bottom: 1px solid #333;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: #222;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 18px;
}

.detail-content {
    flex: 1;
}

.detail-label {
    display: block;
    color: #a0a0a0;
    font-size: 11px;
    margin-bottom: 2px;
}

.detail-value {
    color: white;
    font-size: 14px;
    text-decoration: none;
}

.detail-value:hover {
    color: var(--primary);
}

.profile-actions {
    padding: 20px;
    display: flex;
    gap: 10px;
}

.action-btn {
    flex: 1;
    padding: 12px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    border: 2px solid transparent;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.action-btn.reset-password {
    border-color: #00fa9a;
    color: #00fa9a;
}

.action-btn.reset-password:hover {
    background: linear-gradient(135deg, #00fa9a, #00ff7f);
    color: black;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 250, 154, 0.3);
}

.action-btn.delete {
    border-color: #ff4444;
    color: #ff4444;
}

.action-btn.delete:hover {
    background: linear-gradient(135deg, #ff4444, #ff6b6b);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(255, 68, 68, 0.3);
}

/* Stat Cards Small */
.stat-card-small {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 16px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
}

.stat-card-small:hover {
    transform: translateY(-3px);
    border-color: var(--primary);
    box-shadow: 0 10px 25px var(--primary-glow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: white;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    color: #a0a0a0;
    font-size: 11px;
}

/* Activity Card */
.activity-card {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 20px;
    overflow: hidden;
}

.nav-tabs {
    border-bottom: 1px solid #333;
    padding: 0 10px;
}

.nav-tabs .nav-link {
    color: #a0a0a0;
    border: none;
    padding: 15px 20px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link i {
    margin-right: 8px;
}

.nav-tabs .nav-link:hover {
    color: var(--primary);
    border: none;
}

.nav-tabs .nav-link.active {
    color: var(--primary);
    background: transparent;
    border: none;
    border-bottom: 2px solid var(--primary);
}

/* Items List */
.items-list {
    padding: 10px;
}

.item-row {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.item-row:last-child {
    border-bottom: none;
}

.item-row:hover {
    background: #222;
    transform: translateX(5px);
}

.item-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.item-icon.lost {
    background: rgba(255, 68, 68, 0.1);
    color: #ff4444;
}

.item-icon.found {
    background: rgba(0, 250, 154, 0.1);
    color: #00fa9a;
}

.item-icon.match {
    background: rgba(255, 20, 147, 0.1);
    color: var(--primary);
}

.item-info {
    flex: 1;
}

.item-name {
    color: white;
    font-size: 15px;
    font-weight: 500;
    margin-bottom: 4px;
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 15px;
}

.item-meta span {
    color: #a0a0a0;
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.arrow {
    color: #a0a0a0;
    font-size: 14px;
    transition: all 0.3s ease;
}

.item-row:hover .arrow {
    color: var(--primary);
    transform: translateX(5px);
}

/* Score Badge */
.score-badge {
    padding: 2px 8px;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 600;
}

.score-high {
    background: rgba(0, 250, 154, 0.15);
    color: #00fa9a;
    border: 1px solid #00fa9a;
}

.score-medium {
    background: rgba(255, 165, 0, 0.15);
    color: #ffa500;
    border: 1px solid #ffa500;
}

.score-low {
    background: rgba(255, 20, 147, 0.15);
    color: var(--primary);
    border: 1px solid var(--primary);
}

/* Alert Info */
.alert-info {
    background: rgba(255, 20, 147, 0.1);
    border: 1px solid var(--primary);
    border-radius: 12px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #a0a0a0;
    font-size: 12px;
}

.alert-info i {
    color: var(--primary);
    font-size: 16px;
}

/* Photo Upload Styles */
.photo-upload-wrapper {
    text-align: center;
    padding: 1rem;
    background: #222;
    border-radius: 16px;
    border: 2px dashed #333;
}

.current-photo {
    display: flex;
    justify-content: center;
    align-items: center;
}

.profile-photo-preview {
    width: 100px;
    height: 100px;
    border-radius: 30px;
    object-fit: cover;
    border: 3px solid var(--primary);
    box-shadow: 0 0 20px var(--primary-glow);
}

.profile-photo-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 36px;
    border: 3px solid var(--primary);
    box-shadow: 0 0 20px var(--primary-glow);
    margin: 0 auto;
}

.photo-input {
    display: none;
}

.btn-upload {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px var(--primary-glow);
}

.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--primary-glow);
}

.form-check {
    margin-top: 0.75rem;
}

.form-check-input {
    accent-color: #ff4444;
    width: 16px;
    height: 16px;
    margin-right: 6px;
}

.form-check-label {
    color: #ff4444;
    font-size: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Form Elements */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: white;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 8px;
}

.form-label i {
    color: var(--primary);
}

.pink-input {
    width: 100%;
    padding: 12px 16px;
    background: #222;
    border: 2px solid #333;
    border-radius: 12px;
    color: white;
    font-size: 14px;
    transition: all 0.3s ease;
}

.pink-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    background: #1a1a1a;
}

.select-wrapper {
    position: relative;
}

.pink-select {
    width: 100%;
    padding: 12px 16px;
    background: #222;
    border: 2px solid #333;
    border-radius: 12px;
    color: white;
    font-size: 14px;
    appearance: none;
    cursor: pointer;
}

.pink-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    pointer-events: none;
}

.toggle-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255, 20, 147, 0.1);
    border: 1px solid var(--primary);
    border-radius: 30px;
}

.toggle-checkbox input[type="checkbox"] {
    accent-color: var(--primary);
    width: 16px;
    height: 16px;
}

.toggle-checkbox label {
    color: var(--primary);
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Modal */
.modal-content {
    background: #1a1a1a;
    border: 1px solid var(--primary);
    border-radius: 20px;
}

.modal-header {
    background: #222;
    border-bottom: 1px solid #333;
    padding: 20px 24px;
}

.modal-title {
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    background: #222;
    border-top: 1px solid #333;
    padding: 16px 24px;
}

.btn-cancel {
    padding: 10px 20px;
    background: transparent;
    border: 2px solid #333;
    color: #a0a0a0;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    border-color: #ff4444;
    color: #ff4444;
}

.btn-submit {
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
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
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.btn-submit:hover .btn-glow {
    width: 300px;
    height: 300px;
}

.btn-submit i {
    position: relative;
    z-index: 2;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-actions {
        flex-direction: column;
    }
    
    .item-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>

@push('scripts')
<script>
    // Profile photo preview in edit modal
    document.getElementById('profile_photo')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a valid image file (JPG, PNG, GIF)');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.profile-photo-preview, .profile-photo-placeholder');
                if (preview) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace placeholder with image
                        const newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.className = 'profile-photo-preview';
                        preview.parentNode.replaceChild(newImg, preview);
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection