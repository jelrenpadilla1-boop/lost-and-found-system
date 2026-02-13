@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0">Users Management</h1>
                <p class="text-muted mb-0">Manage user accounts and permissions</p>
            </div>
            <div class="col-auto">
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Add User
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $users->total() }}</div>
                    <div class="stats-label">Total Users</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $users->where('role', 'admin')->count() }}</div>
                    <div class="stats-label">Admins</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $users->where('role', 'user')->count() }}</div>
                    <div class="stats-label">Regular Users</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stats-content">
                    <div class="stats-value">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                    <div class="stats-label">New This Week</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">All Users</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search users...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
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
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <span class="text-muted">
                                        <i class="fas fa-lost"></i> {{ $user->lost_items_count }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-found"></i> {{ $user->found_items_count }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Active
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>

                                <!-- Edit User Modal -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Role</label>
                                                        <select class="form-select" name="role">
                                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_active" id="active{{ $user->id }}" checked>
                                                            <label class="form-check-label" for="active{{ $user->id }}">
                                                                Active Account
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>

<style>
    .user-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--primary-color), #3b82f6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .stats-icon.info {
        background-color: #dbeafe;
        color: #3b82f6;
    }
</style>
@endsection