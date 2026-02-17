@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="container-fluid px-2 px-md-3 px-lg-4">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-8 col-lg-9">
                <h1 class="h3 mb-0 d-flex align-items-center flex-wrap gap-2">
                    <i class="fas fa-users-cog" style="color: var(--primary);"></i>
                    <span>Users Management</span>
                </h1>
                <p class="text-muted mb-0 mt-1">Manage user accounts and permissions</p>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <a href="#" class="btn-add-user w-100 w-md-auto">
                    <i class="fas fa-user-plus me-2"></i>Add User
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('admin.users.index', ['role' => '', 'period' => '']) }}" class="stats-link">
                <div class="stats-card h-100">
                    <div class="stats-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ $totalUsers }}</div>
                        <div class="stats-label">Total Users</div>
                    </div>
                    <div class="stats-hover">
                        <i class="fas fa-arrow-right"></i> View Details
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => 'admin', 'period' => ''])) }}" class="stats-link">
                <div class="stats-card h-100">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #00fa9a, #00ff7f);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ $adminCount }}</div>
                        <div class="stats-label">Admins</div>
                    </div>
                    <div class="stats-hover">
                        <i class="fas fa-arrow-right"></i> View Details
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => 'user', 'period' => ''])) }}" class="stats-link">
                <div class="stats-card h-100">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #ffa500, #ffb52e);">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ $userCount }}</div>
                        <div class="stats-label">Regular Users</div>
                    </div>
                    <div class="stats-hover">
                        <i class="fas fa-arrow-right"></i> View Details
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['period' => 'this_week', 'role' => ''])) }}" class="stats-link">
                <div class="stats-card h-100">
                    <div class="stats-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-value">{{ $newThisWeek }}</div>
                        <div class="stats-label">New This Week</div>
                    </div>
                    <div class="stats-hover">
                        <i class="fas fa-arrow-right"></i> View Details
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Active Filter Indicators -->
    @if(request('role') || request('status') || request('period') || request('search'))
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <i class="fas fa-filter me-2" style="color: var(--primary);"></i>
            <strong style="color: var(--primary);">Active Filters:</strong>
            <div class="d-flex flex-wrap gap-2">
                @if(request('role'))
                    <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        Role: {{ ucfirst(request('role')) }}
                    </span>
                @endif
                @if(request('status'))
                    <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        Status: {{ ucfirst(request('status')) }}
                    </span>
                @endif
                @if(request('period'))
                    <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        Period: {{ str_replace('_', ' ', ucfirst(request('period'))) }}
                    </span>
                @endif
                @if(request('search'))
                    <span class="filter-badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        Search: "{{ request('search') }}"
                    </span>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-close" style="filter: invert(1);"></a>
    </div>
    @endif

    <!-- Search and Filter Bar for Mobile -->
    <div class="d-block d-md-none mb-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="search-form-mobile">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search users..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="users-table-card">
        <div class="card-header">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-6">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-list me-2" style="color: var(--primary);"></i>
                        <span>All Users</span>
                        <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                    </h5>
                </div>
                <div class="col-12 col-md-6">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="search-form d-none d-md-block">
                        <div class="input-group">
                            <input type="text" class="search-input" name="search" placeholder="Search users..." value="{{ request('search') }}">
                            <button class="search-btn" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Items</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td data-label="User">
                                <a href="{{ route('admin.users.show', $user) }}" class="user-cell-link">
                                    <div class="user-cell">
                                        <div class="user-avatar {{ $user->profile_photo ? 'has-image' : '' }}">
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
                                        <div class="user-info">
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-id">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td data-label="Email">
                                <a href="mailto:{{ $user->email }}" class="email-link">{{ $user->email }}</a>
                            </td>
                            <td data-label="Role">
                                <span class="role-badge {{ $user->role === 'admin' ? 'admin' : 'user' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td data-label="Items">
                                <div class="items-count">
                                    <span class="item-count lost" title="Lost Items">
                                        <i class="fas fa-exclamation-circle"></i> 
                                        <span class="d-none d-sm-inline">{{ $user->lost_items_count }}</span>
                                        <span class="d-inline d-sm-none">{{ $user->lost_items_count }}</span>
                                    </span>
                                    <span class="item-count found" title="Found Items">
                                        <i class="fas fa-check-circle"></i> 
                                        <span class="d-none d-sm-inline">{{ $user->found_items_count }}</span>
                                        <span class="d-inline d-sm-none">{{ $user->found_items_count }}</span>
                                    </span>
                                </div>
                            </td>
                            <td data-label="Joined">
                                <span class="join-date">{{ $user->created_at->format('M d, Y') }}</span>
                                <small class="d-block d-md-none text-muted">{{ $user->created_at->format('h:i A') }}</small>
                            </td>
                            <td data-label="Status">
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i> 
                                    <span class="d-none d-sm-inline">Active</span>
                                </span>
                            </td>
                            <td data-label="Actions">
                                <div class="table-actions">
                                    <a href="{{ route('admin.users.show', $user) }}" class="action-btn view" title="View User">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Edit User Modals -->
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
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
                                       id="profile_photo_{{ $user->id }}" 
                                       name="profile_photo" 
                                       accept="image/*">
                                <label for="profile_photo_{{ $user->id }}" class="btn-upload">
                                    <i class="fas fa-cloud-upload-alt"></i> Change Photo
                                </label>
                                @if($user->profile_photo)
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" name="remove_photo" id="remove_photo_{{ $user->id }}">
                                    <label class="form-check-label text-danger" for="remove_photo_{{ $user->id }}">
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
                        <input type="text" class="pink-input" name="phone" value="{{ $user->phone }}" placeholder="Optional">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Location
                        </label>
                        <input type="text" class="pink-input" name="location" value="{{ $user->location }}" placeholder="City, Country">
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
                            <input type="checkbox" name="is_active" id="active{{ $user->id }}" {{ $user->is_active ? 'checked' : '' }}>
                            <label for="active{{ $user->id }}">
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
@endforeach

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
    --error: #ff4444;
    --warning: #ffa500;
    --info: #8b5cf6;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Container Padding */
.container-fluid {
    padding-right: calc(var(--bs-gutter-x) * 0.5);
    padding-left: calc(var(--bs-gutter-x) * 0.5);
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: clamp(1.5rem, 5vw, 1.875rem);
    font-weight: 700;
    color: var(--text-primary);
}

.page-header p {
    color: var(--text-muted);
    font-size: clamp(0.875rem, 4vw, 0.9375rem);
}

/* Add User Button */
.btn-add-user {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: clamp(0.875rem, 4vw, 0.9375rem);
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 0 20px var(--primary-glow);
    white-space: nowrap;
}

.btn-add-user:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px var(--primary-glow);
}

/* Stats Links */
.stats-link {
    text-decoration: none;
    display: block;
    height: 100%;
}

/* Stats Cards */
.stats-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    height: 100%;
    min-height: 120px;
    cursor: pointer;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.stats-card:hover::before {
    opacity: 0.1;
}

.stats-icon {
    width: clamp(50px, 8vw, 60px);
    height: clamp(50px, 8vw, 60px);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: clamp(20px, 4vw, 24px);
    color: white;
    transition: var(--transition);
    flex-shrink: 0;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1) rotate(360deg);
}

.stats-content {
    flex: 1;
    min-width: 0;
}

.stats-value {
    font-size: clamp(24px, 5vw, 28px);
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
    word-break: break-word;
}

.stats-label {
    color: var(--text-muted);
    font-size: clamp(12px, 3vw, 13px);
    word-break: break-word;
}

.stats-hover {
    position: absolute;
    top: 0;
    right: 0;
    background: var(--primary);
    color: white;
    padding: 6px 15px;
    font-size: clamp(11px, 2.5vw, 12px);
    border-radius: 0 20px 0 20px;
    opacity: 0;
    transform: translateY(-100%);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.stats-card:hover .stats-hover {
    opacity: 1;
    transform: translateY(0);
}

/* Filter Badge */
.filter-badge {
    padding: 0.375rem 1rem;
    border-radius: 30px;
    color: white;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: 0 0 15px var(--primary-glow);
}

/* Alert */
.alert-info {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    color: white;
    border-radius: 12px;
    padding: 1rem 1.25rem;
}

.btn-close {
    filter: invert(1);
    opacity: 0.5;
    transition: var(--transition);
    background: transparent;
    border: none;
    cursor: pointer;
}

.btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
}

/* Mobile Search Form */
.search-form-mobile {
    width: 100%;
}

.search-form-mobile .input-group {
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 30px;
    overflow: hidden;
    transition: var(--transition);
}

.search-form-mobile .input-group:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
}

.search-form-mobile .form-control {
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 14px;
    padding: 12px 16px;
}

.search-form-mobile .form-control:focus {
    outline: none;
    box-shadow: none;
}

.search-form-mobile .btn {
    background: transparent;
    border: none;
    color: var(--primary);
    padding: 0 20px;
}

.search-form-mobile .btn:hover {
    color: var(--primary-light);
    transform: scale(1.1);
}

/* Users Table Card */
.users-table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: var(--transition);
    margin-bottom: 2rem;
}

.users-table-card:hover {
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.card-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 1.25rem 1.5rem;
}

.card-header h5 {
    color: var(--text-primary);
    font-weight: 600;
    font-size: clamp(1rem, 4vw, 1.125rem);
    flex-wrap: wrap;
}

.card-header .badge {
    background: var(--primary);
    color: white;
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

/* Search Form */
.search-form {
    max-width: 300px;
    margin-left: auto;
}

.search-form .input-group {
    display: flex;
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 30px;
    overflow: hidden;
    transition: var(--transition);
}

.search-form .input-group:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
}

.search-input {
    flex: 1;
    padding: 10px 18px;
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 14px;
    outline: none;
    min-width: 0;
}

.search-input::placeholder {
    color: var(--text-muted);
}

.search-btn {
    width: 42px;
    background: transparent;
    border: none;
    color: var(--primary);
    cursor: pointer;
    transition: var(--transition);
    flex-shrink: 0;
}

.search-btn:hover {
    color: var(--primary-light);
    transform: scale(1.1);
}

/* Users Table */
.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table thead th {
    background: var(--bg-header);
    color: var(--text-primary);
    font-weight: 600;
    font-size: 12px;
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 2px solid var(--primary);
    white-space: nowrap;
}

.users-table tbody tr {
    transition: var(--transition);
}

.users-table tbody tr:hover {
    background: var(--bg-header);
}

.users-table tbody td {
    padding: 1rem 1.5rem;
    color: var(--text-secondary);
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
}

/* User Cell with Avatar */
.user-cell-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: clamp(36px, 5vw, 40px);
    height: clamp(36px, 5vw, 40px);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: clamp(14px, 3vw, 16px);
    transition: var(--transition);
    box-shadow: 0 0 15px var(--primary-glow);
    flex-shrink: 0;
    overflow: hidden;
}

.user-avatar.has-image {
    background: none;
    box-shadow: 0 0 15px var(--primary-glow);
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border-radius: 12px;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.user-cell-link:hover .user-avatar .avatar-image {
    transform: scale(1.1);
}

.user-cell-link:hover .avatar-initial {
    transform: scale(1.1) rotate(360deg);
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 2px;
    font-size: clamp(13px, 3vw, 14px);
    word-break: break-word;
}

.user-id {
    color: var(--text-muted);
    font-size: 11px;
    word-break: break-word;
}

/* Email Link */
.email-link {
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
    word-break: break-all;
    font-size: clamp(13px, 3vw, 14px);
}

.email-link:hover {
    color: var(--primary);
    text-decoration: underline;
}

/* Role Badges */
.role-badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap;
}

.role-badge.admin {
    background: rgba(255, 20, 147, 0.15);
    color: var(--primary);
    border: 1px solid var(--primary);
}

.role-badge.user {
    background: rgba(160, 160, 160, 0.1);
    color: var(--text-muted);
    border: 1px solid var(--border-color);
}

/* Items Count */
.items-count {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.item-count {
    font-size: clamp(11px, 2.5vw, 12px);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

.item-count.lost i {
    color: #ff4444;
}

.item-count.found i {
    color: #00fa9a;
}

/* Join Date */
.join-date {
    color: var(--text-muted);
    font-size: clamp(12px, 2.5vw, 13px);
    white-space: nowrap;
}

/* Status Badge */
.status-badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(0, 250, 154, 0.1);
    color: var(--success);
    border: 1px solid var(--success);
    white-space: nowrap;
}

.status-badge i {
    font-size: 10px;
}

/* Table Actions */
.table-actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.action-btn {
    width: clamp(28px, 4vw, 32px);
    height: clamp(28px, 4vw, 32px);
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    background: transparent;
    font-size: clamp(12px, 3vw, 14px);
    flex-shrink: 0;
}

.action-btn.view {
    color: var(--primary);
    border: 1px solid var(--primary);
}

.action-btn.view:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.action-btn.edit {
    color: #00fa9a;
    border: 1px solid #00fa9a;
}

.action-btn.edit:hover {
    background: #00fa9a;
    color: black;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 250, 154, 0.3);
}

.action-btn.delete {
    color: #ff4444;
    border: 1px solid #ff4444;
}

.action-btn.delete:hover {
    background: #ff4444;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
}

/* Modal Styles */
.modal-dialog {
    margin: 0.5rem;
    max-width: 500px;
}

@media (min-width: 576px) {
    .modal-dialog {
        margin: 1.75rem auto;
    }
}

.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--primary);
    border-radius: 20px;
    box-shadow: 0 10px 40px var(--primary-glow);
}

.modal-header {
    background: var(--bg-header);
    border-bottom: 1px solid var(--border-color);
    padding: 1.25rem 1.5rem;
}

.modal-header .btn-close {
    filter: invert(1);
    opacity: 0.5;
    transition: var(--transition);
}

.modal-header .btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
}

.modal-title {
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: clamp(1rem, 4vw, 1.25rem);
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    background: var(--bg-header);
    border-top: 1px solid var(--border-color);
    padding: 1.25rem 1.5rem;
}

/* Photo Upload Styles */
.photo-upload-wrapper {
    text-align: center;
    padding: 1rem;
    background: var(--bg-header);
    border-radius: 16px;
    border: 2px dashed var(--border-color);
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
    transition: var(--transition);
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
    accent-color: var(--error);
    width: 16px;
    height: 16px;
    margin-right: 6px;
}

.form-check-label {
    color: var(--error);
    font-size: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Form Styles */
.form-label {
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: clamp(13px, 3vw, 14px);
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
    font-size: clamp(13px, 3vw, 14px);
    transition: var(--transition);
}

.pink-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    background: var(--bg-card);
}

.select-wrapper {
    position: relative;
}

.pink-select {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-header);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: clamp(13px, 3vw, 14px);
    appearance: none;
    cursor: pointer;
    transition: var(--transition);
}

.pink-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-glow);
    background: var(--bg-card);
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    pointer-events: none;
}

/* Toggle Checkbox */
.toggle-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255, 20, 147, 0.1);
    border: 1px solid var(--primary);
    border-radius: 30px;
    transition: var(--transition);
    width: 100%;
    justify-content: center;
}

.toggle-checkbox:hover {
    background: rgba(255, 20, 147, 0.2);
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

/* Modal Buttons */
.btn-cancel {
    padding: 10px 20px;
    background: transparent;
    border: 2px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 30px;
    font-size: clamp(13px, 3vw, 14px);
    font-weight: 500;
    transition: var(--transition);
    flex: 1;
}

.btn-cancel:hover {
    border-color: var(--error);
    color: var(--error);
}

.btn-submit {
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    border-radius: 30px;
    font-size: clamp(13px, 3vw, 14px);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 20px var(--primary-glow);
    flex: 1;
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

.btn-submit i,
.btn-submit span {
    position: relative;
    z-index: 2;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    overflow-x: auto;
    padding: 0.5rem 0;
}

.pagination {
    display: flex;
    gap: 0.25rem;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
    justify-content: center;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    background: var(--bg-header);
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 8px !important;
    text-decoration: none;
    transition: var(--transition);
    font-size: clamp(12px, 3vw, 14px);
    padding: 0 8px;
}

.page-link:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-color: var(--primary);
    box-shadow: 0 5px 15px var(--primary-glow);
}

.page-item.disabled .page-link {
    background: var(--bg-card);
    border-color: var(--border-color);
    color: var(--text-muted);
    opacity: 0.5;
    pointer-events: none;
}

/* Responsive Table */
@media (max-width: 768px) {
    .users-table thead {
        display: none;
    }
    
    .users-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-card);
    }
    
    .users-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border: none;
        border-bottom: 1px solid var(--border-color);
        word-break: break-word;
    }
    
    .users-table tbody td:last-child {
        border-bottom: none;
    }
    
    .users-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--text-primary);
        margin-right: 1rem;
        min-width: 80px;
        font-size: 12px;
    }

    .user-cell {
        flex: 1;
    }

    .items-count {
        justify-content: flex-end;
    }

    .table-actions {
        justify-content: flex-end;
    }
}

/* Small Mobile Devices */
@media (max-width: 480px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }

    .stats-card {
        padding: 1rem;
    }

    .stats-icon {
        width: 45px;
        height: 45px;
        font-size: 20px;
    }

    .stats-value {
        font-size: 22px;
    }

    .stats-label {
        font-size: 11px;
    }

    .card-header {
        padding: 1rem;
    }

    .users-table tbody td {
        padding: 0.5rem 0.75rem;
        font-size: 13px;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }

    .user-name {
        font-size: 13px;
    }

    .user-id {
        font-size: 10px;
    }

    .role-badge {
        padding: 4px 8px;
        font-size: 10px;
    }

    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }

    .page-link {
        min-width: 32px;
        height: 32px;
        font-size: 12px;
    }
}

/* Tablet Devices */
@media (min-width: 769px) and (max-width: 1024px) {
    .stats-card {
        padding: 1.25rem;
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 22px;
    }

    .stats-value {
        font-size: 24px;
    }

    .users-table td {
        padding: 0.875rem 1rem;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
    }
}

/* Landscape Mode */
@media (max-height: 600px) and (orientation: landscape) {
    .modal-dialog {
        max-height: 90vh;
        margin: 0.5rem auto;
    }

    .modal-body {
        max-height: 50vh;
        overflow-y: auto;
    }
}

/* High-DPI Screens */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .stats-card,
    .users-table-card,
    .modal-content {
        border-width: 0.5px;
    }
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

.stats-card,
.users-table-card {
    animation: fadeIn 0.5s ease forwards;
}

/* Utility Classes */
.h-100 {
    height: 100%;
}

.w-100 {
    width: 100%;
}

@media (min-width: 768px) {
    .w-md-auto {
        width: auto !important;
    }
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 4px;
    box-shadow: 0 0 10px var(--primary-glow);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-light);
}
</style>
@endsection