@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0">User Details</h1>
                <p class="text-muted mb-0">{{ $user->name }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Users
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Profile -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="user-avatar-lg mb-3 mx-auto">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    <h4 class="mb-2">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="badge bg-success">
                            Active
                        </span>
                    </div>
                    
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <div class="h5 mb-0">{{ $stats['total_items'] }}</div>
                                <small class="text-muted">Total Items</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <div class="h5 mb-0">{{ $stats['successful_matches'] }}</div>
                                <small class="text-muted">Matches</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editUserModal">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </button>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete User
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity -->
        <div class="col-lg-8">
            <!-- Recent Lost Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Recent Lost Items</h5>
                </div>
                <div class="card-body">
                    @if($user->lostItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Date Lost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->lostItems->take(5) as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->date_lost->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $item->status === 'found' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('lost-items.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">No lost items reported</p>
                    @endif
                </div>
            </div>

            <!-- Recent Found Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Found Items</h5>
                </div>
                <div class="card-body">
                    @if($user->foundItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Date Found</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->foundItems->take(5) as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->date_found->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $item->status === 'claimed' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('found-items.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center mb-0">No found items reported</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .user-avatar-lg {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), #3b82f6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }
</style>
@endsection