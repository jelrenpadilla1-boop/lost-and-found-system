@extends('layouts.app')

@section('title', 'Lost Items - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<style>
/* ── MODERN DESIGN SYSTEM (matches dashboard) ───────────────── */
:root {
    --bg-white: #ffffff;
    --bg-soft: #faf9fe;
    --bg-card: #ffffff;
    --border-light: #edeef5;
    --border-soft: #e6e8f0;
    --accent: #7c3aed;
    --accent-light: #8b5cf6;
    --accent-soft: #ede9fe;
    --text-dark: #1e1b2f;
    --text-muted: #5b5b7a;
    --text-soft: #7e7b9a;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
    --radius-card: 20px;
    --radius-sm: 12px;
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    --success: #10b981;
    --success-soft: #d1fae5;
    --warning: #f59e0b;
    --warning-soft: #fef3c7;
    --error: #ef4444;
    --error-soft: #fee2e2;
    --info: #3b82f6;
    --info-soft: #dbeafe;
    --glass: rgba(0, 0, 0, 0.02);
    --glass-b: rgba(0, 0, 0, 0.04);
    --glass-hover: rgba(0, 0, 0, 0.06);
}

/* DARK MODE */
body.dark {
    --bg-white: #0f0c1a;
    --bg-soft: #12101c;
    --bg-card: #191624;
    --border-light: #2a2438;
    --border-soft: #2d2740;
    --accent: #a78bfa;
    --accent-light: #c4b5fd;
    --accent-soft: #2d2648;
    --text-dark: #f0edfc;
    --text-muted: #b4adcf;
    --text-soft: #938bb0;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
    --shadow-md: 0 12px 30px rgba(0, 0, 0, 0.4), 0 4px 8px rgba(0, 0, 0, 0.2);
    --shadow-lg: 0 20px 35px -12px rgba(0, 0, 0, 0.5);
    --success-soft: rgba(16, 185, 129, 0.15);
    --warning-soft: rgba(245, 158, 11, 0.15);
    --error-soft: rgba(239, 68, 68, 0.15);
    --info-soft: rgba(59, 130, 246, 0.15);
    --glass: rgba(255, 255, 255, 0.03);
    --glass-b: rgba(255, 255, 255, 0.06);
    --glass-hover: rgba(255, 255, 255, 0.08);
}

/* Dashboard Container */
.dashboard-container {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 28px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-light);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.02em;
}

.page-title h1 i {
    color: var(--accent);
    font-size: 26px;
}

.page-title p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.page-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 40px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid transparent;
}

.btn-primary {
    background: var(--accent);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
}

.btn-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    position: relative;
}

.stat-card:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.icon-primary {
    background: var(--accent-soft);
    color: var(--accent);
}

.icon-warning {
    background: var(--warning-soft);
    color: var(--warning);
}

.icon-success {
    background: var(--success-soft);
    color: var(--success);
}

.icon-info {
    background: var(--info-soft);
    color: var(--info);
}

.stat-value {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
    letter-spacing: -0.02em;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}

.stat-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--accent);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}

/* Alert Card */
.alert-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-sm);
    animation: slideIn 0.3s ease;
}

.alert-card.warning {
    background: var(--warning-soft);
    border-left: 4px solid var(--warning);
}

.alert-card.info {
    background: var(--info-soft);
    border-left: 4px solid var(--info);
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-card.warning .alert-icon {
    background: var(--warning);
    color: white;
}

.alert-card.info .alert-icon {
    background: var(--info);
    color: white;
}

.alert-content {
    flex: 1;
    min-width: 200px;
}

.alert-content strong {
    display: block;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 14px;
}

.alert-content p {
    color: var(--text-muted);
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
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.alert-action {
    padding: 8px 16px;
    border-radius: 40px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    background: var(--accent);
    color: white;
}

.alert-action:hover {
    background: var(--accent-light);
    transform: translateY(-1px);
}

.alert-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.alert-close:hover {
    border-color: var(--error);
    color: var(--error);
    transform: rotate(90deg);
}

/* Filter Card */
.filter-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 24px;
    margin-bottom: 32px;
    box-shadow: var(--shadow-sm);
}

.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr auto;
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
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-label i {
    color: var(--accent);
    font-size: 12px;
}

.select-wrapper {
    position: relative;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    color: var(--text-dark);
    font-size: 14px;
    transition: var(--transition);
}

.filter-select {
    appearance: none;
    padding-right: 40px;
    cursor: pointer;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--accent);
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
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.section-header h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h5 i {
    color: var(--accent);
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

/* Item Card */
.item-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.item-card.pending {
    border-left: 4px solid var(--warning);
    border-left-width: 4px;
}

.item-card:hover {
    border-color: var(--accent);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.item-header {
    padding: 16px 18px;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bg-soft);
}

.item-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.badge {
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.badge.pending {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.approved {
    background: var(--success-soft);
    color: var(--success);
}

.badge.found {
    background: var(--info-soft);
    color: var(--info);
}

.badge.returned {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge.category {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

.item-actions {
    position: relative;
}

.action-toggle {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-toggle:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
}

.action-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    min-width: 160px;
    z-index: 100;
    display: none;
    overflow: hidden;
}

.action-menu.show {
    display: block;
    animation: fadeIn 0.2s ease;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    color: var(--text-muted);
    text-decoration: none;
    transition: var(--transition);
    font-size: 12px;
    font-weight: 500;
    width: 100%;
    background: transparent;
    border: none;
    cursor: pointer;
}

.action-item:hover {
    background: var(--accent-soft);
    color: var(--accent);
}

.action-item.success:hover {
    background: var(--success-soft);
    color: var(--success);
}

.action-item.danger:hover {
    background: var(--error-soft);
    color: var(--error);
}

.action-item i {
    width: 16px;
    font-size: 13px;
}

.item-image {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    overflow: hidden;
}

.item-image img {
    max-height: 140px;
    max-width: 100%;
    object-fit: contain;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: 48px;
    background: var(--bg-soft);
}

.item-content {
    padding: 18px;
    flex: 1;
}

.item-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 10px 0;
}

.item-description {
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 14px;
}

.item-meta {
    display: flex;
    gap: 16px;
    margin-bottom: 12px;
    flex-wrap: wrap;
}

.item-meta span {
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

.item-meta i {
    color: var(--accent);
    font-size: 11px;
}

.item-location {
    background: var(--glass);
    border: 1px solid var(--border-light);
    border-radius: 20px;
    padding: 6px 12px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: var(--text-muted);
    margin-bottom: 16px;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-location i {
    color: var(--accent);
    font-size: 11px;
    flex-shrink: 0;
}

.view-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    background: var(--glass);
    border: 1px solid var(--border-light);
    border-radius: 10px;
    color: var(--accent);
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
    transition: var(--transition);
    margin-top: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.view-link:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.view-link:hover i:last-child {
    transform: translateX(4px);
}

.view-link i:last-child {
    transition: transform 0.2s;
}

.item-footer {
    padding: 12px 18px;
    border-top: 1px solid var(--border-light);
    background: var(--bg-soft);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 10px;
    color: var(--text-muted);
}

.item-footer i {
    color: var(--accent);
    margin-right: 4px;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 30px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: var(--glass);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    border: 2px dashed var(--border-light);
    color: var(--accent);
    font-size: 32px;
}

.empty-state h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 20px;
}

.mt-3 {
    margin-top: 20px;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 32px;
    display: flex;
    justify-content: center;
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
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    border-radius: 10px;
    text-decoration: none;
    transition: var(--transition);
    font-size: 13px;
}

.page-link:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.page-item.disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
}

/* Quick Stats */
.quick-stats {
    margin-top: 32px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 24px;
    box-shadow: var(--shadow-sm);
}

.quick-stats-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-light);
}

.quick-stats-header i {
    color: var(--accent);
    font-size: 20px;
}

.quick-stats-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    letter-spacing: 0.05em;
}

.quick-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

@media (max-width: 576px) {
    .quick-stats-grid {
        grid-template-columns: 1fr;
    }
}

.quick-stat-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    background: var(--bg-soft);
    border-radius: var(--radius-sm);
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid var(--border-light);
}

.quick-stat-item:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.quick-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.quick-stat-icon.warning {
    background: var(--warning-soft);
    color: var(--warning);
}

.quick-stat-icon.success {
    background: var(--success-soft);
    color: var(--success);
}

.quick-stat-icon.primary {
    background: var(--accent-soft);
    color: var(--accent);
}

.quick-stat-value {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
}

.quick-stat-label {
    font-size: 10px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
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

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.fade-in {
    animation: fadeIn 0.4s ease forwards;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 20px;
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
}
</style>

<div class="dashboard-container">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-search"></i>
                Lost Items
            </h1>
            <p>{{ $isAdmin ? 'Manage and verify reported lost items' : 'Browse items reported as lost in your community' }}</p>
        </div>
        <div class="page-actions">
            @if(!$isAdmin)
            <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Report Lost
            </a>
            @endif
        </div>
    </div>

    <!-- Admin Pending Alert -->
    @if($isAdmin && $pendingCount > 0)
    <div class="alert-card warning fade-in" id="pendingAlert">
        <div class="alert-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="alert-content">
            <strong>Pending Verification</strong>
            <p>There {{ $pendingCount > 1 ? 'are' : 'is' }} <strong>{{ $pendingCount }}</strong> lost item{{ $pendingCount > 1 ? 's' : '' }} waiting for your approval.</p>
        </div>
        <a href="#pending-items" class="alert-action">Review Now</a>
        <button type="button" class="alert-close" onclick="this.closest('.alert-card').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Active Filters Alert -->
    @if(request('status') || request('category') || request('search'))
    <div class="alert-card info fade-in">
        <div class="alert-icon">
            <i class="fas fa-filter"></i>
        </div>
        <div class="alert-content">
            <strong>Active Filters</strong>
            <div class="filter-tags">
                @if(request('status'))
                    <span class="filter-tag">
                        Status: {{ request('status') == 'pending' ? ($isAdmin ? 'PENDING' : 'MISSING') : strtoupper(request('status')) }}
                    </span>
                @endif
                @if(request('category'))
                    <span class="filter-tag">
                        Category: {{ strtoupper(request('category')) }}
                    </span>
                @endif
                @if(request('search'))
                    <span class="filter-tag">
                        Search: "{{ request('search') }}"
                    </span>
                @endif
            </div>
        </div>
        <a href="{{ route('lost-items.index') }}" class="alert-action">Clear All</a>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid fade-in">
        <a href="{{ route('lost-items.index') }}" class="stat-card">
            <div class="stat-icon icon-primary">
                <i class="fas fa-box-open"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalItems }}</div>
                <div class="stat-label">Total Items</div>
            </div>
        </a>

        <a href="{{ route('lost-items.index', ['status' => 'pending']) }}" class="stat-card">
            <div class="stat-icon icon-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="stat-value">{{ $pendingCount }}</div>
                <div class="stat-label">{{ $isAdmin ? 'Pending' : 'Missing' }}</div>
            </div>
            @if($isAdmin && $pendingCount > 0)
            <span class="stat-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('lost-items.index', ['status' => 'approved']) }}" class="stat-card">
            <div class="stat-icon icon-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="stat-value">{{ $approvedCount ?? 0 }}</div>
                <div class="stat-label">Approved</div>
            </div>
        </a>

        <a href="{{ route('lost-items.index', ['status' => 'returned']) }}" class="stat-card">
            <div class="stat-icon icon-info">
                <i class="fas fa-home"></i>
            </div>
            <div>
                <div class="stat-value">{{ $returnedCount }}</div>
                <div class="stat-label">Returned</div>
            </div>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="filter-card fade-in">
        <form method="GET" action="{{ route('lost-items.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i>
                        Category
                    </label>
                    <div class="select-wrapper">
                        <select class="filter-select" id="category" name="category">
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
                    <label class="filter-label">
                        <i class="fas fa-circle"></i>
                        Status
                    </label>
                    <div class="select-wrapper">
                        <select class="filter-select" id="status" name="status">
                            <option value="">All Status</option>
                            @if($isAdmin)
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            @else
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Missing</option>
                            @endif
                            <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="filter-group search-group">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Search
                    </label>
                    <input type="text" class="filter-input" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Item name, description, location...">
                </div>

                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                            Apply
                        </button>
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline">
                            <i class="fas fa-redo-alt"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Pending Items Section for Admin -->
    @if($isAdmin && !in_array(request('status'), ['approved', 'found', 'returned']) && isset($pendingItems) && $pendingItems->count() > 0)
    <div id="pending-items" class="fade-in">
        <div class="section-header">
            <h5>
                <i class="fas fa-clock" style="color: var(--warning);"></i>
                Pending Approval ({{ $pendingItems->count() }})
            </h5>
        </div>

        <div class="items-grid">
            @foreach($pendingItems as $item)
            <div class="item-card pending">
                <div class="item-header">
                    <div class="item-badges">
                        <span class="badge pending">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                        <span class="badge category">{{ strtoupper($item->category) }}</span>
                    </div>
                    <div class="item-actions">
                        <button class="action-toggle" onclick="toggleActions({{ $item->id }})">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="action-menu" id="actions-{{ $item->id }}">
                            <a href="{{ route('lost-items.show', $item) }}" class="action-item">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <form action="{{ route('lost-items.approve', $item) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-item success" onclick="return confirm('Approve this item?')">
                                    <i class="fas fa-check-circle"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('lost-items.reject', $item) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-item danger" onclick="return confirm('Reject this item?')">
                                    <i class="fas fa-times-circle"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="item-image">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->item_name }}">
                    @else
                        <div class="image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>

                <div class="item-content">
                    <h6 class="item-title">{{ $item->item_name }}</h6>
                    <p class="item-description">{{ Str::limit($item->description, 80) }}</p>

                    <div class="item-meta">
                        <span><i class="fas fa-calendar"></i> {{ $item->date_lost->format('M d, Y') }}</span>
                        <span><i class="fas fa-user"></i> {{ $item->user->name }}</span>
                    </div>

                    @if($item->lost_location)
                    <div class="item-location" title="{{ $item->lost_location }}">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ Str::limit($item->lost_location, 30) }}
                    </div>
                    @endif
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

    <!-- Main Items Grid -->
    <div class="fade-in">
        <div class="section-header">
            <h5>
                <i class="fas fa-check-circle"></i>
                {{ $isAdmin ? 'Approved Items' : 'Lost Items' }}
                <span style="font-size: 14px; color: var(--text-muted);">({{ $lostItems->total() }})</span>
            </h5>
        </div>

        <div class="items-grid">
            @forelse($lostItems as $item)
            <div class="item-card">
                <div class="item-header">
                    <div class="item-badges">
                        <span class="badge {{ $item->status }}">
                            @if($item->status == 'pending')
                                <i class="fas fa-clock"></i> Missing
                            @elseif($item->status == 'approved')
                                <i class="fas fa-check-circle"></i> Active
                            @elseif($item->status == 'found')
                                <i class="fas fa-check-circle"></i> Found
                            @elseif($item->status == 'returned')
                                <i class="fas fa-home"></i> Returned
                            @endif
                        </span>
                        <span class="badge category">{{ strtoupper($item->category) }}</span>
                    </div>
                    <div class="item-actions">
                        <button class="action-toggle" onclick="toggleActions({{ $item->id }})">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="action-menu" id="actions-{{ $item->id }}">
                            <a href="{{ route('lost-items.show', $item) }}" class="action-item">
                                <i class="fas fa-eye"></i> View
                            </a>
                            
                            @if($isAdmin && $item->status == 'pending')
                            <form action="{{ route('lost-items.approve', $item) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-item success">
                                    <i class="fas fa-check-circle"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('lost-items.reject', $item) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-item danger">
                                    <i class="fas fa-times-circle"></i> Reject
                                </button>
                            </form>
                            @endif
                            
                            @can('update', $item)
                            <a href="{{ route('lost-items.edit', $item) }}" class="action-item">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @endcan
                            
                            @can('delete', $item)
                            <form action="{{ route('lost-items.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-item danger" onclick="return confirm('Delete this item? This action cannot be undone.')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="item-image">
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->item_name }}">
                    @else
                        <div class="image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>

                <div class="item-content">
                    <h6 class="item-title">{{ $item->item_name }}</h6>
                    <p class="item-description">{{ Str::limit($item->description, 80) }}</p>

                    <div class="item-meta">
                        <span><i class="fas fa-calendar"></i> {{ $item->date_lost->format('M d, Y') }}</span>
                        <span><i class="fas fa-user"></i> {{ $item->user->name }}</span>
                    </div>

                    @if($item->lost_location)
                    <div class="item-location" title="{{ $item->lost_location }}">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ Str::limit($item->lost_location, 30) }}
                    </div>
                    @endif

                    <a href="{{ route('lost-items.show', $item) }}" class="view-link">
                        <span>View Details</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="item-footer">
                    <span><i class="fas fa-clock"></i> {{ $item->created_at->diffForHumans() }}</span>
                    @if($item->approved_at)
                    <span><i class="fas fa-check-circle" style="color: var(--success);"></i> Verified</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h5>No Items Found</h5>
                <p>{{ $isAdmin ? 'No items match your current filters.' : 'No lost items have been reported yet.' }}</p>
                @if(!$isAdmin)
                <a href="{{ route('lost-items.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i>
                    Report Lost Item
                </a>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($lostItems->hasPages())
        <div class="pagination-wrapper">
            {{ $lostItems->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats fade-in">
        <div class="quick-stats-header">
            <i class="fas fa-chart-pie"></i>
            <h5>Quick Insights</h5>
        </div>
        <div class="quick-stats-grid">
            <a href="{{ route('lost-items.index', ['status' => 'pending']) }}" class="quick-stat-item">
                <div class="quick-stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="quick-stat-value">{{ $pendingCount }}</div>
                    <div class="quick-stat-label">{{ $isAdmin ? 'Pending Review' : 'Missing Items' }}</div>
                </div>
            </a>
            
            <a href="{{ route('lost-items.index', ['status' => 'found']) }}" class="quick-stat-item">
                <div class="quick-stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="quick-stat-value">{{ $foundCount ?? 0 }}</div>
                    <div class="quick-stat-label">Found & Matched</div>
                </div>
            </a>
            
            <div class="quick-stat-item">
                <div class="quick-stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="quick-stat-value">{{ $activeReporters }}</div>
                    <div class="quick-stat-label">Active Reporters</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle action menus
    window.toggleActions = function(id) {
        const menu = document.getElementById(`actions-${id}`);
        if (menu) {
            menu.classList.toggle('show');
            
            // Close other menus
            document.querySelectorAll('.action-menu').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
        }
    };

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.item-actions')) {
            document.querySelectorAll('.action-menu').forEach(m => {
                m.classList.remove('show');
            });
        }
    });

    // Filter form auto-submit for selects
    const categorySelect = document.getElementById('category');
    const statusSelect = document.getElementById('status');
    const filterForm = document.getElementById('filterForm');

    if (categorySelect) {
        categorySelect.addEventListener('change', () => filterForm.submit());
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', () => filterForm.submit());
    }

    // Debounced search
    const searchInput = document.getElementById('search');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    }

    // Auto-hide alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-card').forEach(alert => {
            alert.style.transition = 'opacity 0.3s, transform 0.3s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            setTimeout(() => alert.remove(), 300);
        });
    }, 8000);

    // Stagger card animations
    const cards = document.querySelectorAll('.item-card');
    cards.forEach((card, index) => {
        card.style.animation = `fadeIn 0.4s ease forwards ${index * 0.05}s`;
        card.style.opacity = '0';
    });
});
</script>
@endpush
@endsection