@extends('layouts.app')

@section('title', 'Found Items - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    // Filter out pending and rejected items for ALL users (including admins in the main grid)
    $visibleItems = $foundItems->filter(function($item) {
        return !in_array($item->status, ['pending', 'rejected']);
    });
@endphp

<style>
/* ── MODERN CLEAN FOUND ITEMS PAGE ───────────────── */
:root {
    --netflix-red: #e50914;
    --netflix-red-dark: #b20710;
    --netflix-black: #141414;
    --netflix-dark: #0a0a0a;
    --netflix-card: #1a1a1a;
    --netflix-card-hover: #242424;
    --netflix-text: #ffffff;
    --netflix-text-secondary: #a3a3a3;
    --netflix-border: #2a2a2a;
    --netflix-success: #2e7d32;
    --netflix-warning: #f5c518;
    --netflix-info: #2196f3;
    --netflix-error: #e50914;
    --netflix-rejected: #757575;
    --transition-netflix: all 0.25s ease;
}

/* Light Mode Overrides */
body.light {
    --netflix-black: #fafafa;
    --netflix-dark: #ffffff;
    --netflix-card: #ffffff;
    --netflix-card-hover: #f8f8f8;
    --netflix-text: #171717;
    --netflix-text-secondary: #737373;
    --netflix-border: #e5e5e5;
}

.dashboard-container {
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
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.3px;
}

.page-title h1 i {
    color: var(--netflix-red);
    font-size: 26px;
}

.page-title p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin: 0;
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 500;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition-netflix);
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: var(--netflix-red);
    color: white;
}

.btn-primary:hover {
    background: var(--netflix-red-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
}

.btn-outline:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.05);
}

/* Alert Cards */
.alert-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.alert-card.warning {
    border-left: 4px solid var(--netflix-warning);
    background: linear-gradient(135deg, rgba(245, 197, 24, 0.08), transparent);
}

.alert-card.info {
    border-left: 4px solid var(--netflix-info);
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.08), transparent);
}

.alert-card.rejected {
    border-left: 4px solid var(--netflix-rejected);
    background: linear-gradient(135deg, rgba(117, 117, 117, 0.08), transparent);
}

.alert-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-card.warning .alert-icon {
    background: rgba(245, 197, 24, 0.15);
    color: var(--netflix-warning);
}

.alert-card.info .alert-icon {
    background: rgba(33, 150, 243, 0.15);
    color: var(--netflix-info);
}

.alert-card.rejected .alert-icon {
    background: rgba(117, 117, 117, 0.15);
    color: var(--netflix-rejected);
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    font-weight: 600;
    color: var(--netflix-text);
    margin-bottom: 4px;
    font-size: 14px;
}

.alert-content p {
    color: var(--netflix-text-secondary);
    margin: 0;
    font-size: 13px;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.filter-tag {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.alert-action {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition-netflix);
    background: var(--netflix-red);
    color: white;
}

.alert-action:hover {
    background: var(--netflix-red-dark);
}

.alert-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    cursor: pointer;
    transition: var(--transition-netflix);
    display: flex;
    align-items: center;
    justify-content: center;
}

.alert-close:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
}

/* Filter Card */
.filter-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1.5fr 1.5fr 2fr auto;
    gap: 16px;
    align-items: end;
}

@media (max-width: 992px) {
    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .filter-group.search-group {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    .filter-group.search-group {
        grid-column: span 1;
    }
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-label i {
    color: var(--netflix-red);
    font-size: 11px;
}

.select-wrapper {
    position: relative;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 11px 16px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 10px;
    color: var(--netflix-text);
    font-size: 14px;
    transition: var(--transition-netflix);
}

.filter-select {
    appearance: none;
    padding-right: 40px;
    cursor: pointer;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: var(--netflix-red);
    box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--netflix-red);
    font-size: 12px;
    pointer-events: none;
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.filter-actions .btn {
    flex: 1;
    justify-content: center;
}

/* Section Header */
.section-header {
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.section-header h5 {
    font-size: 16px;
    font-weight: 600;
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h5 i {
    color: var(--netflix-red);
    font-size: 18px;
}

/* Items Grid */
.items-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

@media (max-width: 992px) {
    .items-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .items-grid {
        grid-template-columns: 1fr;
    }
}

/* Item Card - Modern Clean Style */
.item-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 16px;
    overflow: hidden;
    transition: var(--transition-netflix);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.item-card:hover {
    transform: translateY(-4px);
    border-color: var(--netflix-red);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.item-card.returned {
    border-top: 3px solid var(--netflix-success);
}

.item-header {
    padding: 14px 16px;
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--netflix-card);
}

.item-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.badge {
    font-size: 10px;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.badge.approved {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
}

.badge.claimed {
    background: rgba(33, 150, 243, 0.15);
    color: var(--netflix-info);
}

.badge.returned {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
}

.badge.disposed {
    background: rgba(255, 255, 255, 0.08);
    color: var(--netflix-text-secondary);
}

.badge.category {
    background: rgba(255, 255, 255, 0.05);
    color: var(--netflix-text-secondary);
    border: 1px solid var(--netflix-border);
}

.item-actions {
    position: relative;
}

.action-toggle {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    cursor: pointer;
    transition: var(--transition-netflix);
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-toggle:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.08);
}

.action-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 12px;
    min-width: 160px;
    z-index: 100;
    display: none;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.action-menu.show {
    display: block;
    animation: fadeIn 0.2s ease;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    color: var(--netflix-text-secondary);
    text-decoration: none;
    transition: var(--transition-netflix);
    font-size: 13px;
    font-weight: 500;
    width: 100%;
    background: transparent;
    border: none;
    cursor: pointer;
}

.action-item:hover {
    background: rgba(229, 9, 20, 0.08);
    color: var(--netflix-red);
}

.action-item.danger:hover {
    background: rgba(229, 9, 20, 0.1);
    color: var(--netflix-red);
}

.action-item i {
    width: 18px;
    font-size: 14px;
}

/* Bigger Photos */
.item-image {
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.02) 0%, rgba(229, 9, 20, 0.03) 100%);
    border-bottom: 1px solid var(--netflix-border);
    overflow: hidden;
    position: relative;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.item-card:hover .item-image img {
    transform: scale(1.03);
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--netflix-text-secondary);
    font-size: 48px;
    opacity: 0.4;
}

.item-content {
    padding: 18px;
    flex: 1;
}

.item-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--netflix-text);
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.item-description {
    font-size: 13px;
    color: var(--netflix-text-secondary);
    line-height: 1.6;
    margin-bottom: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.item-meta {
    display: flex;
    gap: 14px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.item-meta span {
    font-size: 12px;
    color: var(--netflix-text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
}

.item-meta i {
    color: var(--netflix-red);
    font-size: 12px;
}

.item-location {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 8px 12px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--netflix-text-secondary);
    margin-bottom: 14px;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-location i {
    color: var(--netflix-red);
    font-size: 12px;
    flex-shrink: 0;
}

.view-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px;
    background: rgba(229, 9, 20, 0.08);
    border: 1px solid var(--netflix-border);
    border-radius: 10px;
    color: var(--netflix-red);
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: var(--transition-netflix);
    margin-top: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.view-link:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
}

.view-link:hover i:last-child {
    transform: translateX(4px);
}

.view-link i:last-child {
    transition: transform 0.2s;
}

.item-footer {
    padding: 12px 18px;
    border-top: 1px solid var(--netflix-border);
    background: var(--netflix-card);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11px;
    color: var(--netflix-text-secondary);
}

.item-footer i {
    color: var(--netflix-red);
    margin-right: 4px;
}

.returned-badge {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

.approved-badge {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

/* Responsive image heights */
@media (min-width: 1400px) {
    .item-image {
        height: 260px;
    }
}

@media (min-width: 1200px) and (max-width: 1399px) {
    .item-image {
        height: 240px;
    }
}

@media (max-width: 992px) {
    .item-image {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .item-image {
        height: 180px;
    }
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 64px 30px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 20px;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(229, 9, 20, 0.1) 0%, transparent 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--netflix-red);
    font-size: 36px;
}

.empty-state h5 {
    font-size: 18px;
    font-weight: 600;
    color: var(--netflix-text);
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin-bottom: 20px;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination {
    display: flex;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
    flex-wrap: wrap;
    justify-content: center;
}

.page-item {
    display: inline-block;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 14px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    border-radius: 10px;
    text-decoration: none;
    transition: var(--transition-netflix);
    font-size: 13px;
    font-weight: 500;
}

.page-link:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.05);
}

.page-item.active .page-link {
    background: var(--netflix-red);
    border-color: var(--netflix-red);
    color: white;
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

.fade-in {
    animation: fadeIn 0.4s ease forwards;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 16px;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-actions {
        width: 100%;
    }
    
    .page-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    .alert-card {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .alert-action {
        width: 100%;
        text-align: center;
    }
    
    .filter-actions {
        flex-direction: column;
    }
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-check-circle"></i>
                Found Items
            </h1>
            <p>{{ $isAdmin ? 'Manage and verify reported found items' : 'Browse items that have been found in your community' }}</p>
        </div>
        <div class="page-actions">
            @if(!$isAdmin)
            <a href="{{ route('found-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Report Found Item
            </a>
            @endif
        </div>
    </div>

    {{-- Admin Pending Alert --}}
    @if($isAdmin && isset($pendingCount) && $pendingCount > 0 && !request('status') && !request('category') && !request('search'))
    <div class="alert-card warning fade-in" id="pendingAlert">
        <div class="alert-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="alert-content">
            <strong>Pending Verification</strong>
            <p>There {{ $pendingCount > 1 ? 'are' : 'is' }} <strong>{{ $pendingCount }}</strong> found item{{ $pendingCount > 1 ? 's' : '' }} waiting for your approval.</p>
        </div>
        <a href="#pending-items" class="alert-action">Review Now</a>
        <button type="button" class="alert-close" onclick="this.closest('.alert-card').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Active Filters Alert --}}
    @if(request('status') || request('category') || request('search'))
    <div class="alert-card info fade-in">
        <div class="alert-icon">
            <i class="fas fa-filter"></i>
        </div>
        <div class="alert-content">
            <strong>Active Filters</strong>
            <div class="filter-tags">
                @if(request('status'))
                    <span class="filter-tag">Status: {{ strtoupper(request('status')) }}</span>
                @endif
                @if(request('category'))
                    <span class="filter-tag">Category: {{ strtoupper(request('category')) }}</span>
                @endif
                @if(request('search'))
                    <span class="filter-tag">Search: "{{ request('search') }}"</span>
                @endif
            </div>
        </div>
        <a href="{{ route('found-items.index') }}" class="alert-action">Clear All</a>
    </div>
    @endif

    {{-- Filter Section --}}
    <div class="filter-card fade-in">
        <form method="GET" action="{{ route('found-items.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-tag"></i> Category</label>
                    <div class="select-wrapper">
                        <select class="filter-select" name="category">
                            <option value="">All Categories</option>
                            <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>📱 Electronics</option>
                            <option value="Documents" {{ request('category') == 'Documents' ? 'selected' : '' }}>📄 Documents</option>
                            <option value="Jewelry" {{ request('category') == 'Jewelry' ? 'selected' : '' }}>💎 Jewelry</option>
                            <option value="Clothing" {{ request('category') == 'Clothing' ? 'selected' : '' }}>👕 Clothing</option>
                            <option value="Bags" {{ request('category') == 'Bags' ? 'selected' : '' }}>🎒 Bags</option>
                            <option value="Keys" {{ request('category') == 'Keys' ? 'selected' : '' }}>🔑 Keys</option>
                            <option value="Wallet" {{ request('category') == 'Wallet' ? 'selected' : '' }}>👛 Wallet</option>
                            <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>📦 Other</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-circle"></i> Status</label>
                    <div class="select-wrapper">
                        <select class="filter-select" name="status">
                            <option value="">All Status</option>
                            @if($isAdmin)
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            @endif
                            <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>Claimed</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                            <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="filter-group search-group">
                    <label class="filter-label"><i class="fas fa-search"></i> Search</label>
                    <input type="text" class="filter-input" name="search" value="{{ request('search') }}" placeholder="Item name, description, location...">
                </div>

                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
                        <a href="{{ route('found-items.index') }}" class="btn btn-outline"><i class="fas fa-redo-alt"></i> Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Pending Items Section for Admin (Only when no filters are applied) --}}
    @if($isAdmin && isset($pendingItems) && $pendingItems->count() > 0 && !request('status') && !request('category') && !request('search'))
    <div id="pending-items" class="fade-in">
        <div class="section-header">
            <h5><i class="fas fa-clock" style="color: var(--netflix-warning);"></i> Pending Approval ({{ $pendingItems->count() }})</h5>
            <small style="color: var(--netflix-text-secondary);">These items are hidden from users until approved</small>
        </div>
        <div class="items-grid">
            @foreach($pendingItems as $item)
            <div class="item-card">
                <div class="item-header">
                    <div class="item-badges">
                        <span class="badge pending"><i class="fas fa-clock"></i> Pending</span>
                        <span class="badge category">{{ strtoupper($item->category) }}</span>
                    </div>
                    <div class="item-actions">
                        <button class="action-toggle" onclick="toggleActions({{ $item->id }})"><i class="fas fa-ellipsis-h"></i></button>
                        <div class="action-menu" id="actions-{{ $item->id }}">
                            <a href="{{ route('found-items.show', $item) }}" class="action-item"><i class="fas fa-eye"></i> View</a>
                            <form action="{{ route('found-items.approve', $item) }}" method="POST">@csrf<button type="submit" class="action-item" onclick="return confirm('Approve this item?')"><i class="fas fa-check-circle"></i> Approve</button></form>
                            <form action="{{ route('found-items.reject', $item) }}" method="POST">@csrf<button type="submit" class="action-item danger" onclick="return confirm('Reject this item?')"><i class="fas fa-times-circle"></i> Reject</button></form>
                        </div>
                    </div>
                </div>
                <div class="item-image">
                    @if($item->photo)<img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->item_name }}">@else<div class="image-placeholder"><i class="fas fa-image"></i></div>@endif
                </div>
                <div class="item-content">
                    <h6 class="item-title">{{ $item->item_name }}</h6>
                    <p class="item-description">{{ Str::limit($item->description, 80) }}</p>
                    <div class="item-meta">
                        <span><i class="fas fa-calendar"></i> {{ $item->date_found->format('M d, Y') }}</span>
                        <span><i class="fas fa-user"></i> {{ $item->user->name }}</span>
                    </div>
                    @if($item->found_location)<div class="item-location"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($item->found_location, 25) }}</div>@endif
                </div>
                <div class="item-footer">
                    <span><i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}</span>
                    <span class="badge pending">Awaiting Review</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Main Items Grid (EXCLUDING PENDING & REJECTED) --}}
    <div class="fade-in">
        <div class="section-header">
            <h5>
                <i class="fas {{ $isAdmin ? 'fa-check-circle' : 'fa-box-open' }}"></i>
                {{ $isAdmin ? 'Approved & Active Items' : 'Found Items' }}
                <span style="font-size: 14px; color: var(--netflix-text-secondary);">
                    ({{ $visibleItems->count() }})
                </span>
            </h5>
            @if($isAdmin && isset($pendingCount) && $pendingCount > 0)
            <small style="color: var(--netflix-warning);">{{ $pendingCount }} pending approval</small>
            @endif
        </div>

        <div class="items-grid">
            @forelse($visibleItems as $item)
            <div class="item-card {{ $item->status == 'returned' ? 'returned' : '' }}">
                <div class="item-header">
                    <div class="item-badges">
                        <span class="badge {{ $item->status }}">
                            @if($item->status == 'approved')<i class="fas fa-check-circle"></i> Available
                            @elseif($item->status == 'claimed')<i class="fas fa-handshake"></i> Claimed
                            @elseif($item->status == 'returned')<i class="fas fa-gift"></i> Returned
                            @elseif($item->status == 'disposed')<i class="fas fa-archive"></i> Disposed
                            @endif
                        </span>
                        <span class="badge category">{{ strtoupper($item->category) }}</span>
                    </div>
                    <div class="item-actions">
                        <button class="action-toggle" onclick="toggleActions({{ $item->id }})"><i class="fas fa-ellipsis-h"></i></button>
                        <div class="action-menu" id="actions-{{ $item->id }}">
                            <a href="{{ route('found-items.show', $item) }}" class="action-item"><i class="fas fa-eye"></i> View</a>
                            @can('update', $item)<a href="{{ route('found-items.edit', $item) }}" class="action-item"><i class="fas fa-edit"></i> Edit</a>@endcan
                            @can('delete', $item)<form action="{{ route('found-items.destroy', $item) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="action-item danger" onclick="return confirm('Delete this item?')"><i class="fas fa-trash"></i> Delete</button></form>@endcan
                        </div>
                    </div>
                </div>
                <div class="item-image">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->item_name }}">
                    @else
                        <div class="image-placeholder"><i class="fas fa-image"></i></div>
                    @endif
                </div>
                <div class="item-content">
                    <h6 class="item-title">{{ $item->item_name }}</h6>
                    <p class="item-description">{{ Str::limit($item->description, 80) }}</p>
                    <div class="item-meta">
                        <span><i class="fas fa-calendar"></i> {{ $item->date_found->format('M d, Y') }}</span>
                        <span><i class="fas fa-user"></i> {{ $item->user->name }}</span>
                    </div>
                    @if($item->found_location)
                    <div class="item-location"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($item->found_location, 25) }}</div>
                    @endif
                    <a href="{{ route('found-items.show', $item) }}" class="view-link"><span>View Details</span><i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="item-footer">
                    <span><i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}</span>
                    @if($item->status == 'returned')
                        <span class="returned-badge"><i class="fas fa-gift"></i> Returned!</span>
                    @elseif($item->status == 'claimed')
                        <span class="approved-badge">✓ Claimed</span>
                    @elseif($item->approved_at)
                        <span class="approved-badge">✓ Available</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
                <h5>No Items Found</h5>
                <p>{{ $isAdmin ? 'No active items match your current filters.' : 'No found items have been reported yet.' }}</p>
                @if(!$isAdmin)<a href="{{ route('found-items.create') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Report Found Item</a>@endif
            </div>
            @endforelse
        </div>

        @if($foundItems->hasPages() && $visibleItems->count() > 0)
        <div class="pagination-wrapper">
            {{ $foundItems->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleActions(id) {
    const menu = document.getElementById(`actions-${id}`);
    if (menu) {
        menu.classList.toggle('show');
        document.querySelectorAll('.action-menu').forEach(m => {
            if (m !== menu) m.classList.remove('show');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.item-actions')) {
            document.querySelectorAll('.action-menu').forEach(m => m.classList.remove('show'));
        }
    });

    const categorySelect = document.querySelector('select[name="category"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const filterForm = document.getElementById('filterForm');

    if (categorySelect) categorySelect.addEventListener('change', () => filterForm.submit());
    if (statusSelect) statusSelect.addEventListener('change', () => filterForm.submit());

    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => filterForm.submit(), 500);
        });
    }

    setTimeout(() => {
        document.querySelectorAll('.alert-card').forEach(alert => {
            alert.style.transition = 'opacity 0.3s, transform 0.3s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            setTimeout(() => alert.remove(), 300);
        });
    }, 8000);

    document.querySelectorAll('.item-card').forEach((card, i) => {
        card.style.animation = `fadeIn 0.4s ease forwards ${i * 0.06}s`;
        card.style.opacity = '0';
    });
});
</script>
@endpush
@endsection