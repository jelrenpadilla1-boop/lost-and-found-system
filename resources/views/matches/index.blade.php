@extends('layouts.app')

@section('title', 'All Matches - Foundify')

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

/* Stats Grid */
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
    background: var(--accent-soft);
    color: var(--accent);
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

.stat-arrow {
    color: var(--accent);
    font-size: 14px;
    opacity: 0;
    transition: var(--transition);
}

.stat-card:hover .stat-arrow {
    opacity: 1;
    transform: translateX(4px);
}

/* Alert Card */
.alert-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    box-shadow: var(--shadow-sm);
}

.alert-card.info {
    background: var(--info-soft);
    border-left: 4px solid var(--info);
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--info);
    color: white;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
    font-size: 13px;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
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
    background: var(--info);
    color: white;
}

.alert-action:hover {
    background: #2563eb;
    transform: translateY(-1px);
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
    grid-template-columns: 1fr 1fr 1fr 1.5fr;
    gap: 16px;
    align-items: end;
}

@media (max-width: 992px) {
    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-group.actions-group {
        grid-column: span 2;
    }
}

@media (max-width: 576px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-group.actions-group {
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

/* Matches Grid */
.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(560px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

@media (max-width: 640px) {
    .matches-grid {
        grid-template-columns: 1fr;
    }
}

/* Match Card */
.match-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.match-card:hover {
    border-color: var(--accent);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.match-header {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.match-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.match-title h5 {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    margin: 0;
    letter-spacing: 0.05em;
}

.match-badges {
    display: flex;
    gap: 8px;
}

/* Badges */
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

.badge.score-high {
    background: var(--success-soft);
    color: var(--success);
}

.badge.score-medium {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.score-low {
    background: var(--info-soft);
    color: var(--info);
}

.badge.status-pending {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.status-confirmed {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-rejected {
    background: var(--error-soft);
    color: var(--error);
}

/* Items Comparison */
.items-comparison {
    display: flex;
    align-items: stretch;
    gap: 16px;
    padding: 20px;
    flex: 1;
}

@media (max-width: 640px) {
    .items-comparison {
        flex-direction: column;
    }
    
    .vs-divider {
        transform: rotate(90deg);
        margin: 10px auto;
    }
}

.item-side {
    flex: 1;
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    overflow: hidden;
    transition: var(--transition);
}

.item-side.lost:hover {
    border-color: var(--error);
    box-shadow: 0 0 15px var(--error-soft);
}

.item-side.found:hover {
    border-color: var(--success);
    box-shadow: 0 0 15px var(--success-soft);
}

.item-header {
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.item-side.lost .item-header {
    background: var(--error-soft);
    color: var(--error);
}

.item-side.found .item-header {
    background: var(--success-soft);
    color: var(--success);
}

.item-header i {
    font-size: 12px;
}

.item-content {
    padding: 14px;
}

.item-content h6 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 8px 0;
}

.item-desc {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 12px;
    line-height: 1.5;
}

.item-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.item-meta span {
    font-size: 10px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.item-meta i {
    font-size: 10px;
    width: 14px;
}

.item-side.lost .item-meta i {
    color: var(--error);
}

.item-side.found .item-meta i {
    color: var(--success);
}

.location {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* VS Divider */
.vs-divider {
    display: flex;
    align-items: center;
    justify-content: center;
}

.vs-divider span {
    background: var(--bg-card);
    border: 2px solid var(--accent);
    color: var(--accent);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 0 20px rgba(124, 58, 237, 0.3);
}

/* Match Footer */
.match-footer {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.match-time {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: var(--text-muted);
}

.match-time i {
    color: var(--accent);
    font-size: 11px;
}

.match-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-view,
.btn-confirm,
.btn-reject {
    font-size: 11px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 40px;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid;
    cursor: pointer;
    background: transparent;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.btn-view {
    border-color: var(--accent-soft);
    color: var(--accent);
}

.btn-view:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.btn-confirm {
    border-color: var(--success-soft);
    color: var(--success);
}

.btn-confirm:hover {
    background: var(--success);
    color: white;
    border-color: var(--success);
    transform: translateY(-2px);
}

.btn-reject {
    border-color: var(--error-soft);
    color: var(--error);
}

.btn-reject:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
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
    background: var(--bg-soft);
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
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 20px;
}

.empty-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
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

/* Statistics Card */
.statistics-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-top: 40px;
    box-shadow: var(--shadow-sm);
}

.statistics-header {
    padding: 18px 24px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 10px;
}

.statistics-header i {
    color: var(--accent);
    font-size: 18px;
}

.statistics-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.statistics-body {
    padding: 24px;
}

.statistics-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    align-items: center;
}

@media (max-width: 768px) {
    .statistics-grid {
        grid-template-columns: 1fr;
    }
}

.chart-col {
    text-align: center;
}

.chart-col h6 {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 16px;
    font-weight: 500;
    letter-spacing: 0.05em;
}

.chart-container {
    width: 200px;
    height: 200px;
    margin: 0 auto;
}

.stats-col {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.stats-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.stat-item {
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
}

.stat-item:hover {
    border-color: var(--accent);
    transform: translateX(4px);
}

.stat-item .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    background: var(--accent-soft);
    color: var(--accent);
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
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
    
    .filter-actions {
        flex-direction: column;
    }
    
    .match-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .btn-view,
    .btn-confirm,
    .btn-reject {
        width: 100%;
        justify-content: center;
    }
    
    .empty-actions {
        flex-direction: column;
    }
    
    .empty-actions .btn {
        width: 100%;
    }
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-exchange-alt"></i>
                All Matches
            </h1>
            <p>Potential matches between lost and found items</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid fade-in">
        <a href="{{ route('matches.index') }}" class="stat-card">
            <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Matches</div></div>
            <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('matches.index', ['status' => 'pending']) }}" class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div>
            @if($isAdmin && $stats['pending'] > 0)<span class="stat-badge">{{ $stats['pending'] }}</span>@endif
            <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('matches.index', ['status' => 'confirmed']) }}" class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div><div class="stat-value">{{ $stats['confirmed'] }}</div><div class="stat-label">Confirmed</div></div>
            <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('matches.index', ['status' => 'rejected']) }}" class="stat-card">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div><div class="stat-value">{{ $stats['rejected'] }}</div><div class="stat-label">Rejected</div></div>
            <div class="stat-arrow"><i class="fas fa-arrow-right"></i></div>
        </a>
    </div>

    {{-- Active Filter Indicators --}}
    @if(request('status') || request('min_score') || request('max_score'))
    <div class="alert-card info fade-in">
        <div class="alert-icon"><i class="fas fa-filter"></i></div>
        <div class="alert-content">
            <strong>Active Filters</strong>
            <div class="filter-tags">
                @if(request('status'))<span class="filter-tag">Status: {{ strtoupper(request('status')) }}</span>@endif
                @if(request('min_score'))<span class="filter-tag">Min Score: {{ request('min_score') }}%</span>@endif
                @if(request('max_score'))<span class="filter-tag">Max Score: {{ request('max_score') }}%</span>@endif
            </div>
        </div>
        <a href="{{ route('matches.index') }}" class="alert-action"><i class="fas fa-times"></i> Clear All</a>
    </div>
    @endif

    {{-- Filter Section --}}
    <div class="filter-card fade-in">
        <form method="GET" action="{{ route('matches.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-circle"></i> Status</label>
                    <div class="select-wrapper">
                        <select class="filter-select" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-chart-line"></i> Min Score (%)</label>
                    <input type="number" class="filter-input" name="min_score" value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                </div>

                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-chart-line"></i> Max Score (%)</label>
                    <input type="number" class="filter-input" name="max_score" value="{{ request('max_score') }}" min="0" max="100" placeholder="100">
                </div>

                <div class="filter-group actions-group">
                    <label class="filter-label">&nbsp;</label>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
                        <a href="{{ route('matches.index') }}" class="btn btn-outline"><i class="fas fa-redo-alt"></i> Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Matches List --}}
    <div class="matches-grid fade-in">
        @forelse($matches as $match)
        <div class="match-card">
            <div class="match-header">
                <div class="match-title">
                    <h5>Match #{{ $match->id }}</h5>
                    <div class="match-badges">
                        <span class="badge score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">{{ $match->match_score }}%</span>
                        <span class="badge status-{{ $match->status }}">{{ strtoupper($match->status) }}</span>
                    </div>
                </div>
            </div>

            <div class="items-comparison">
                {{-- Lost Item --}}
                <div class="item-side lost">
                    <div class="item-header"><i class="fas fa-search"></i> LOST</div>
                    <div class="item-content">
                        <h6>{{ $match->lostItem->item_name }}</h6>
                        <p class="item-desc">{{ Str::limit($match->lostItem->description, 60) }}</p>
                        <div class="item-meta">
                            <span><i class="fas fa-user"></i> {{ $match->lostItem->user->name }}</span>
                            <span><i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}</span>
                            @if($match->lostItem->lost_location)
                            <span class="location" title="{{ $match->lostItem->lost_location }}"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->lostItem->lost_location, 20) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- VS Divider --}}
                <div class="vs-divider"><span>VS</span></div>

                {{-- Found Item --}}
                <div class="item-side found">
                    <div class="item-header"><i class="fas fa-check-circle"></i> FOUND</div>
                    <div class="item-content">
                        <h6>{{ $match->foundItem->item_name }}</h6>
                        <p class="item-desc">{{ Str::limit($match->foundItem->description, 60) }}</p>
                        <div class="item-meta">
                            <span><i class="fas fa-user"></i> {{ $match->foundItem->user->name }}</span>
                            <span><i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}</span>
                            @if($match->foundItem->found_location)
                            <span class="location" title="{{ $match->foundItem->found_location }}"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->foundItem->found_location, 20) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="match-footer">
                <div class="match-time"><i class="fas fa-clock"></i> {{ $match->created_at->diffForHumans() }}</div>
                <div class="match-actions">
                    <a href="{{ route('matches.show', $match) }}" class="btn-view"><i class="fas fa-eye"></i> Details</a>
                    @if($match->status === 'pending')
                        @can('confirm', $match)
                        <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-confirm" onclick="return confirm('Confirm this match?')"><i class="fas fa-check"></i> Confirm</button>
                        </form>
                        @endcan
                        @can('reject', $match)
                        <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-reject" onclick="return confirm('Reject this match?')"><i class="fas fa-times"></i> Reject</button>
                        </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-exchange-alt"></i></div>
            <h5>No Matches Found</h5>
            <p>No potential matches have been identified yet.</p>
            <p class="text-muted">Report more items to increase matching possibilities.</p>
            @if(!$isAdmin)
            <div class="empty-actions">
                <a href="{{ route('lost-items.create') }}" class="btn btn-outline"><i class="fas fa-search"></i> Report Lost</a>
                <a href="{{ route('found-items.create') }}" class="btn btn-outline"><i class="fas fa-check-circle"></i> Report Found</a>
            </div>
            @endif
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($matches->hasPages())
    <div class="pagination-wrapper fade-in">
        {{ $matches->withQueryString()->links() }}
    </div>
    @endif

    {{-- Match Statistics --}}
    <div class="statistics-card fade-in">
        <div class="statistics-header">
            <i class="fas fa-chart-pie"></i>
            <h5>Match Statistics</h5>
        </div>
        <div class="statistics-body">
            <div class="statistics-grid">
                <div class="chart-col">
                    <div class="chart-container">
                        <canvas id="matchStatusChart"></canvas>
                    </div>
                    <h6>Status Distribution</h6>
                </div>

                <div class="stats-col">
                    <div class="stats-list">
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-bullseye"></i></div>
                            <div class="stat-info">
                                @php $highMatches = $matches->where('match_score', '>=', 80)->count(); @endphp
                                <div class="stat-number">{{ $highMatches }}</div>
                                <div class="stat-label">High Confidence (80%+)</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="stat-info">
                                @php $avgScore = $matches->count() > 0 ? round($matches->avg('match_score'), 1) : 0; @endphp
                                <div class="stat-number">{{ $avgScore }}%</div>
                                <div class="stat-label">Average Score</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                            <div class="stat-info">
                                <div class="stat-number">{{ $stats['confirmed'] }}</div>
                                <div class="stat-label">Recovered Items</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize match status chart
    const ctx = document.getElementById('matchStatusChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Rejected'],
                datasets: [{
                    data: [{{ $stats['pending'] }}, {{ $stats['confirmed'] }}, {{ $stats['rejected'] }}],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: ['#f59e0b', '#10b981', '#ef4444'],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e1b2f',
                        bodyColor: '#5b5b7a',
                        borderColor: '#edeef5',
                        borderWidth: 1
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Auto-submit filter form on select change
    const statusSelect = document.querySelector('select[name="status"]');
    const filterForm = document.getElementById('filterForm');
    if (statusSelect && filterForm) {
        statusSelect.addEventListener('change', () => filterForm.submit());
    }

    // Form loading state
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';
                submitBtn.disabled = true;
            }
        });
    }

    // Stagger animations
    document.querySelectorAll('.match-card').forEach((card, i) => {
        card.style.animation = `fadeIn 0.4s ease forwards ${i * 0.08}s`;
        card.style.opacity = '0';
    });

    // Auto-hide alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-card').forEach(alert => {
            alert.style.transition = 'opacity 0.3s, transform 0.3s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            setTimeout(() => alert.remove(), 300);
        });
    }, 8000);
});
</script>
@endpush
@endsection