@extends('layouts.app')

@section('title', 'Users Management - Foundify')

@section('content')
<style>
/* ── NETFLIX-STYLE ADMIN USERS PAGE ───────────────── */
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

.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--netflix-text);
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title h1 i {
    color: var(--netflix-red);
}

.page-title p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin-top: 8px;
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition-netflix);
    cursor: pointer;
    text-decoration: none;
    border: none;
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

.btn-danger-outline {
    background: rgba(229, 9, 20, 0.1);
    border: 1px solid var(--netflix-red);
    color: var(--netflix-red);
}

.btn-danger-outline:hover {
    background: var(--netflix-red);
    color: white;
    transform: scale(1.02);
}

/* Filters Section */
.filters-section {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 20px 24px;
    margin-bottom: 24px;
}

.filters-grid {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: center;
}

.search-box {
    flex: 1;
    min-width: 280px;
    position: relative;
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--netflix-text-secondary);
}

.search-box input {
    width: 100%;
    padding: 10px 16px 10px 42px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text);
    font-size: 14px;
    transition: var(--transition-netflix);
}

body.light .search-box input {
    background: rgba(0, 0, 0, 0.02);
}

.search-box input:focus {
    outline: none;
    border-color: var(--netflix-red);
}

.search-box input::placeholder {
    color: var(--netflix-text-secondary);
}

.filter-select {
    padding: 10px 35px 10px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text);
    font-size: 13px;
    cursor: pointer;
    transition: var(--transition-netflix);
}

body.light .filter-select {
    background: rgba(0, 0, 0, 0.02);
}

.filter-select:focus {
    outline: none;
    border-color: var(--netflix-red);
}

/* Table Card */
.table-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    padding: 14px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.data-table td {
    padding: 14px 16px;
    font-size: 13px;
    border-bottom: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
}

.data-table tbody tr {
    transition: var(--transition-netflix);
}

.data-table tbody tr:hover {
    background: rgba(229, 9, 20, 0.05);
}

/* User Info with profile photo support */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: var(--netflix-red);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 16px;
    overflow: hidden;
    flex-shrink: 0;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-name {
    font-weight: 700;
    color: var(--netflix-text);
}

.user-id {
    font-size: 10px;
    color: var(--netflix-text-secondary);
}

/* Role Badges */
.role-badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.role-admin {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.role-user {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.role-moderator {
    background: rgba(33, 150, 243, 0.2);
    color: var(--netflix-info);
}

/* Action Buttons */
.action-group {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--netflix-border);
    background: rgba(255, 255, 255, 0.05);
    cursor: pointer;
    transition: var(--transition-netflix);
    color: var(--netflix-text-secondary);
    text-decoration: none;
}

.action-btn:hover {
    transform: scale(1.05);
}

.action-btn.view:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
}

.action-btn.edit:hover {
    background: var(--netflix-warning);
    color: white;
    border-color: var(--netflix-warning);
}

.action-btn.delete:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
}

/* Checkbox */
input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--netflix-red);
    cursor: pointer;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 30px;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 2px dashed var(--netflix-border);
    color: var(--netflix-red);
    font-size: 32px;
}

.empty-state h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--netflix-text);
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin-bottom: 20px;
}

/* Flash Messages */
.flash-message {
    position: fixed;
    top: 80px;
    right: 24px;
    z-index: 9999;
    padding: 14px 20px;
    border-radius: 4px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideIn 0.3s ease;
    background: var(--netflix-card);
    border-left: 3px solid;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.flash-success {
    border-left-color: var(--netflix-success);
    color: var(--netflix-success);
}

.flash-error {
    border-left-color: var(--netflix-red);
    color: var(--netflix-red);
}

.flash-message button {
    margin-left: 12px;
    background: none;
    border: none;
    cursor: pointer;
    color: inherit;
    font-size: 18px;
    transition: var(--transition-netflix);
}

.flash-message button:hover {
    transform: rotate(90deg);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(40px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Pagination - FIXED */
.pagination-wrapper {
    padding: 20px 24px;
    background: var(--netflix-dark);
    border-top: 1px solid var(--netflix-border);
}

.pagination {
    display: flex;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0 0 12px 0;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination li {
    display: inline-block;
}

.pagination a,
.pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    border-radius: 4px;
    text-decoration: none;
    transition: var(--transition-netflix);
    font-size: 13px;
    font-weight: 500;
}

.pagination a:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
    transform: scale(1.02);
}

.pagination .active span {
    background: var(--netflix-red);
    border-color: var(--netflix-red);
    color: white;
}

.pagination .disabled span {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination-info {
    text-align: center;
    font-size: 12px;
    color: var(--netflix-text-secondary);
    margin-top: 12px;
    padding-top: 8px;
    border-top: 1px solid var(--netflix-border);
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    max-width: 400px;
    width: 90%;
    padding: 24px;
    text-align: center;
    animation: fadeIn 0.3s ease;
}

.modal-icon {
    font-size: 56px;
    color: var(--netflix-red);
    margin-bottom: 16px;
}

.modal-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--netflix-text);
    margin-bottom: 12px;
}

.modal-text {
    color: var(--netflix-text-secondary);
    margin-bottom: 24px;
    line-height: 1.5;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-container {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .filters-grid {
        flex-direction: column;
    }
    
    .search-box {
        width: 100%;
        min-width: auto;
    }
    
    .filter-select {
        width: 100%;
    }
    
    .data-table th,
    .data-table td {
        padding: 10px 12px;
    }
    
    .action-group {
        flex-direction: column;
    }
    
    .modal-actions {
        flex-direction: column;
    }
    
    .modal-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .pagination a,
    .pagination span {
        min-width: 32px;
        height: 32px;
        font-size: 11px;
        padding: 0 8px;
    }
}

/* Animations */
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

.fade-in {
    animation: fadeIn 0.4s ease forwards;
}
</style>

<div class="admin-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1><i class="fas fa-users-cog"></i> Users Management</h1>
            <p>Manage user accounts, roles, and permissions</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flash-message flash-success" id="flashMsg">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button onclick="document.getElementById('flashMsg').remove()">×</button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="flash-message flash-error" id="flashMsg">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button onclick="document.getElementById('flashMsg').remove()">×</button>
    </div>
    @endif

    {{-- Filters Section --}}
    <div class="filters-section fade-in">
        <div class="filters-grid">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchUsers" placeholder="Search by name or email...">
            </div>
            <select class="filter-select" id="roleFilter">
                <option value="">All Roles</option>
                <option value="admin">Administrator</option>
                <option value="user">Regular User</option>
                <option value="moderator">Moderator</option>
            </select>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="table-card fade-in">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="50"><input type="checkbox" id="selectAll" onclick="toggleAll()"></th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    @php
                        $hasProfilePhoto = $user->profile_photo && file_exists(public_path('storage/' . $user->profile_photo));
                    @endphp
                    <tr>
                        <td><input type="checkbox" class="row-select" value="{{ $user->id }}" onchange="updateSelectedCount()"></td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if($hasProfilePhoto)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-id">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $user->role ?? 'user' }}">
                                {{ ucfirst($user->role ?? 'user') }}
                            </span>
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="action-btn view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn edit" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="action-btn delete" title="Delete User" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5>No Users Found</h5>
                                <p>Get started by adding your first user.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Add User
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- FIXED: Pagination --}}
        @if(isset($users) && $users->isNotEmpty() && method_exists($users, 'links'))
        <div class="pagination-wrapper">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($users->onFirstPage())
                    <li class="disabled"><span>&laquo; Previous</span></li>
                @else
                    <li><a href="{{ $users->previousPageUrl() }}" rel="prev">&laquo; Previous</a></li>
                @endif

                {{-- First page link if not near start --}}
                @if ($users->currentPage() > 3)
                    <li><a href="{{ $users->url(1) }}">1</a></li>
                    @if ($users->currentPage() > 4)
                        <li class="disabled"><span>...</span></li>
                    @endif
                @endif

                {{-- Page links around current page --}}
                @php
                    $start = max(1, $users->currentPage() - 2);
                    $end = min($users->lastPage(), $users->currentPage() + 2);
                @endphp

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $users->currentPage())
                        <li class="active"><span>{{ $i }}</span></li>
                    @else
                        <li><a href="{{ $users->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor

                {{-- Last page link if not near end --}}
                @if ($users->currentPage() < $users->lastPage() - 2)
                    @if ($users->currentPage() < $users->lastPage() - 3)
                        <li class="disabled"><span>...</span></li>
                    @endif
                    <li><a href="{{ $users->url($users->lastPage()) }}">{{ $users->lastPage() }}</a></li>
                @endif

                {{-- Next Page Link --}}
                @if ($users->hasMorePages())
                    <li><a href="{{ $users->nextPageUrl() }}" rel="next">Next &raquo;</a></li>
                @else
                    <li class="disabled"><span>Next &raquo;</span></li>
                @endif
            </div>
            <div class="pagination-info">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Delete User?</h3>
        <p class="modal-text" id="deleteUserNameText"></p>
        <div class="modal-actions">
            <button class="btn btn-outline" onclick="closeDeleteModal()">Cancel</button>
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger-outline">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-hide flash messages
setTimeout(() => { 
    const msg = document.getElementById('flashMsg'); 
    if(msg) msg.remove(); 
}, 4000);

// Delete confirmation
function confirmDelete(id, name) {
    document.getElementById('deleteUserNameText').innerHTML = `Delete <strong>${name}</strong>? This action cannot be undone.`;
    document.getElementById('deleteUserForm').action = "{{ url('admin/users') }}/" + id;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() { 
    document.getElementById('deleteModal').style.display = 'none'; 
}

// Select All functionality
function toggleAll() {
    const checked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const selected = document.querySelectorAll('.row-select:checked').length;
    // Optional: Show selected count in UI
    console.log('Selected users:', selected);
}

// Search filter
const searchInput = document.getElementById('searchUsers');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        const rows = document.querySelectorAll('.data-table tbody tr');
        let hasVisibleRows = false;
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(term);
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) hasVisibleRows = true;
        });
        
        // Show/hide empty state message for search
        const emptyState = document.querySelector('.data-table tbody tr td .empty-state');
        if (emptyState && !hasVisibleRows && term !== '') {
            // Create temporary no results message
            const noResults = document.getElementById('noSearchResults');
            if (!noResults) {
                const tbody = document.querySelector('.data-table tbody');
                const tr = document.createElement('tr');
                tr.id = 'noSearchResults';
                tr.innerHTML = '<td colspan="6"><div class="empty-state"><div class="empty-state-icon"><i class="fas fa-search"></i></div><h5>No matching users</h5><p>Try a different search term</p></div></td>';
                tbody.appendChild(tr);
            }
        } else {
            const noResults = document.getElementById('noSearchResults');
            if (noResults) noResults.remove();
        }
    });
}

// Role filter
const roleFilter = document.getElementById('roleFilter');
if (roleFilter) {
    roleFilter.addEventListener('change', function() {
        const role = this.value.toLowerCase();
        const rows = document.querySelectorAll('.data-table tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            const badge = row.querySelector('.role-badge');
            if(!badge) return;
            const badgeText = badge.textContent.trim().toLowerCase();
            const isVisible = (!role || badgeText.includes(role));
            row.style.display = isVisible ? '' : 'none';
        });
    });
}

// Stagger animations
document.querySelectorAll('.table-card, .filters-section, .page-header').forEach((el, i) => {
    el.style.animationDelay = `${i * 0.1}s`;
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// Close modal on background click
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection