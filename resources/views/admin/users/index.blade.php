@extends('layouts.app')

@section('title', 'Users Management - Foundify')

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

.btn-danger-outline {
    background: var(--error-soft);
    border: 1px solid var(--error);
    color: var(--error);
}

.btn-danger-outline:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}

@media (max-width: 992px) {
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
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
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
    color: white;
}

.stat-value {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
    margin-bottom: 6px;
}

.stat-trend {
    font-size: 11px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 20px;
}

.stat-trend.positive {
    background: var(--success-soft);
    color: var(--success);
}

.stat-trend.warning {
    background: var(--warning-soft);
    color: var(--warning);
}

.stat-trend.info {
    background: var(--info-soft);
    color: var(--info);
}

/* Filters Section */
.filters-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
}

.filters-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filters-header h5 i {
    color: var(--accent);
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

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
    z-index: 1;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 42px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 40px;
    color: var(--text-dark);
    font-size: 14px;
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.search-hint {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--bg-soft);
    color: var(--text-muted);
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 20px;
    border: 1px solid var(--border-light);
}

.filter-group {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-select-wrapper {
    position: relative;
    min-width: 150px;
}

.filter-select {
    width: 100%;
    padding: 11px 35px 11px 16px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 40px;
    color: var(--text-dark);
    font-size: 13px;
    appearance: none;
    cursor: pointer;
    transition: var(--transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 12px;
    pointer-events: none;
}

.btn-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.btn-icon:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: rotate(90deg);
}

/* Table Card */
.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.table-header {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.table-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-title h5 {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.badge-count {
    background: var(--accent-soft);
    color: var(--accent);
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.table-actions {
    display: flex;
    gap: 16px;
    align-items: center;
}

.selected-count {
    color: var(--text-muted);
    font-size: 13px;
}

/* Table */
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
    color: var(--text-muted);
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table td {
    padding: 14px 16px;
    font-size: 13px;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-light);
}

.data-table tr:hover td {
    background: var(--glass);
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent);
    color: white;
    font-weight: 600;
    font-size: 16px;
    overflow: hidden;
    flex-shrink: 0;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent);
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 2px;
}

.user-id {
    font-size: 10px;
    color: var(--text-muted);
}

/* Badges */
.role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.role-admin {
    background: var(--accent-soft);
    color: var(--accent);
}

.role-user {
    background: var(--success-soft);
    color: var(--success);
}

.role-moderator {
    background: var(--warning-soft);
    color: var(--warning);
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-active {
    background: var(--success-soft);
    color: var(--success);
}

.status-active .status-dot {
    background: var(--success);
}

.status-inactive {
    background: var(--glass);
    color: var(--text-muted);
}

.status-inactive .status-dot {
    background: var(--text-muted);
}

.status-suspended {
    background: var(--error-soft);
    color: var(--error);
}

.status-suspended .status-dot {
    background: var(--error);
}

/* Action Group */
.action-group {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    border: 1px solid;
    background: var(--bg-card);
    cursor: pointer;
}

.action-btn.view {
    border-color: var(--accent-soft);
    color: var(--accent);
}

.action-btn.view:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.action-btn.edit {
    border-color: var(--warning-soft);
    color: var(--warning);
}

.action-btn.edit:hover {
    background: var(--warning);
    color: white;
    border-color: var(--warning);
    transform: translateY(-2px);
}

.action-btn.delete {
    border-color: var(--error-soft);
    color: var(--error);
}

.action-btn.delete:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 30px;
}

.empty-state-content {
    max-width: 300px;
    margin: 0 auto;
}

.empty-state-content i {
    font-size: 48px;
    color: var(--border-light);
    margin-bottom: 16px;
}

.empty-state-content h5 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.empty-state-content p {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 20px;
}

/* Pagination */
.table-footer {
    padding: 16px 20px;
    background: var(--bg-soft);
    border-top: 1px solid var(--border-light);
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

/* Modals */
.modal-content {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
}

.modal-header {
    padding: 18px 24px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-title i {
    color: var(--accent);
}

.modal-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    cursor: pointer;
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

.modal-footer {
    padding: 16px 24px;
    background: var(--bg-soft);
    border-top: 1px solid var(--border-light);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

/* Form Elements */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

@media (max-width: 576px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-label i {
    color: var(--accent);
    font-size: 12px;
}

.required {
    color: var(--error);
    font-size: 12px;
}

.form-control,
.form-select {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    color: var(--text-dark);
    font-size: 14px;
    transition: var(--transition);
}

.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.select-wrapper {
    position: relative;
}

.form-select {
    appearance: none;
    padding-right: 40px;
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

/* Password Field */
.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
}

.password-toggle:hover {
    color: var(--accent);
}

.password-strength {
    margin-top: 8px;
    height: 4px;
    background: var(--border-light);
    border-radius: 2px;
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0;
    transition: width 0.3s ease;
    border-radius: 2px;
}

.password-match {
    font-size: 11px;
    margin-top: 6px;
    min-height: 18px;
}

/* Photo Upload */
.photo-upload-section {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-light);
    flex-wrap: wrap;
}

.photo-preview {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--bg-soft);
    border: 2px dashed var(--border-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    font-size: 32px;
    overflow: hidden;
    flex-shrink: 0;
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-actions {
    flex: 1;
}

.photo-input {
    display: none;
}

.btn-upload {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: var(--accent-soft);
    border: 1px solid var(--accent-soft);
    border-radius: 40px;
    color: var(--accent);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.btn-upload:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
}

.btn-clear-photo {
    margin-left: 8px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--error-soft);
    border: 1px solid var(--error);
    color: var(--error);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.btn-clear-photo:hover {
    background: var(--error);
    color: white;
    transform: rotate(90deg);
}

.upload-hint {
    margin-top: 8px;
    color: var(--text-muted);
    font-size: 11px;
}

/* Import Modal */
.import-icon {
    text-align: center;
    margin-bottom: 20px;
}

.import-icon i {
    font-size: 48px;
    color: var(--accent);
}

.template-download {
    text-align: center;
    margin: 20px 0;
}

.file-upload {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.file-input {
    display: none;
}

.file-label {
    padding: 10px 20px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 40px;
    color: var(--text-dark);
    font-size: 13px;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.file-label:hover {
    border-color: var(--accent);
    color: var(--accent);
    transform: translateY(-2px);
}

.file-name {
    color: var(--text-muted);
    font-size: 13px;
}

/* Animations */
.fade-in {
    animation: fadeIn 0.4s ease forwards;
}

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
    
    .filters-grid {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        width: 100%;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .filter-select-wrapper {
        flex: 1;
    }
    
    .photo-upload-section {
        flex-direction: column;
        text-align: center;
    }
    
    .file-upload {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-users-cog"></i>
                Users Management
            </h1>
            <p>Manage user accounts, roles, and permissions</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus"></i>
                Add User
            </button>
            <button class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                <i class="fas fa-upload"></i>
                Import
            </button>
            <button class="btn btn-outline" id="exportUsersBtn">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--accent), var(--accent-light));">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-trend positive"><i class="fas fa-arrow-up"></i> +12% from last month</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--success), #0d9668);">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <div class="stat-value">{{ $activeUsers ?? 0 }}</div>
                <div class="stat-label">Active Users</div>
                <div class="stat-trend positive"><i class="fas fa-check-circle"></i> Currently active</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning), #d97706);">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <div class="stat-value">{{ $pendingUsers ?? 0 }}</div>
                <div class="stat-label">Pending Approval</div>
                <div class="stat-trend warning"><i class="fas fa-clock"></i> Awaiting review</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, var(--info), #2563eb);">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <div class="stat-value">{{ $adminCount ?? 0 }}</div>
                <div class="stat-label">Administrators</div>
                <div class="stat-trend info"><i class="fas fa-shield-alt"></i> With full access</div>
            </div>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="filters-section fade-in">
        <div class="filters-header">
            <h5><i class="fas fa-filter"></i> Filter Users</h5>
        </div>
        <div class="filters-grid">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchUsers" placeholder="Search by name, email, or role...">
                <span class="search-hint">⌘K</span>
            </div>
            
            <div class="filter-group">
                <div class="filter-select-wrapper">
                    <select class="filter-select" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="admin">Administrator</option>
                        <option value="user">Regular User</option>
                        <option value="moderator">Moderator</option>
                    </select>
                    <i class="fas fa-chevron-down select-arrow"></i>
                </div>
                
                <div class="filter-select-wrapper">
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                    <i class="fas fa-chevron-down select-arrow"></i>
                </div>
                
                <button class="btn-icon" id="resetFilters" title="Reset filters">
                    <i class="fas fa-redo-alt"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="table-card fade-in">
        <div class="table-header">
            <div class="table-title">
                <h5>
                    <i class="fas fa-list"></i>
                    User List
                </h5>
                <span class="badge-count">{{ isset($users) && method_exists($users, 'total') ? $users->total() : count($users ?? []) }}</span>
            </div>
            <div class="table-actions">
                <span class="selected-count" id="selectedCount" style="display: none;">0 selected</span>
                <button class="btn-danger-outline" id="bulkDeleteBtn" style="display: none;" onclick="bulkDelete()">
                    <i class="fas fa-trash-alt"></i>
                    Delete Selected
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="50"><input type="checkbox" id="selectAll" onclick="toggleAll()"></th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Last Active</th>
                        <th width="120">Actions</th>
                    </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td><input type="checkbox" class="row-select" value="{{ $user->id }}" onchange="updateSelectedCount()"></td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <div class="user-details">
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-id">ID: {{ $user->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td><span class="email-text">{{ $user->email }}</span></td>
                        <td>
                            <span class="role-badge role-{{ $user->role ?? 'user' }}">
                                {{ ucfirst($user->role ?? 'user') }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $user->status ?? 'active' }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </td>
                        <td><span class="date-text">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span></td>
                        <td>
                            @if($user->last_active)
                                <span class="time-ago" title="{{ $user->last_active->format('M d, Y H:i') }}">
                                    {{ $user->last_active->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="action-btn view" onclick="viewUser({{ $user->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit" onclick="editUser({{ $user->id }})" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete" onclick="deleteUser({{ $user->id }})" title="Delete User">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-state-content">
                                <i class="fas fa-users"></i>
                                <h5>No Users Found</h5>
                                <p>Get started by adding your first user</p>
                                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-user-plus"></i>
                                    Add User
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($users) && method_exists($users, 'links'))
        <div class="table-footer">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i>
                    Add New User
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="addUserForm">
                @csrf
                
                <div class="modal-body">
                    <div class="photo-upload-section">
                        <div class="photo-preview" id="photoPreview">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="photo-actions">
                            <input type="file" class="photo-input" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(this)">
                            <label for="profile_photo" class="btn-upload">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Choose Photo
                            </label>
                            <button type="button" class="btn-clear-photo" id="clearPhotoBtn" onclick="clearPhoto()" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                            <p class="upload-hint">Max 2MB. JPG, PNG, GIF</p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> First Name <span class="required">*</span></label>
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required placeholder="Enter first name">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> Last Name <span class="required">*</span></label>
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required placeholder="Enter last name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="user@example.com">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-lock"></i> Password <span class="required">*</span></label>
                            <div class="password-field">
                                <input type="password" class="form-control" name="password" id="password" required placeholder="Minimum 8 characters">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength"><div class="strength-bar" id="strengthBar"></div></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-lock"></i> Confirm Password <span class="required">*</span></label>
                            <div class="password-field">
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required placeholder="Re-enter password">
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-match" id="passwordMatch"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-phone"></i> Phone Number</label>
                            <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="+63 XXX XXX XXXX">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar"></i> Date of Birth</label>
                            <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tag"></i> Role <span class="required">*</span></label>
                            <div class="select-wrapper">
                                <select class="form-select" name="role" required>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Regular User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-check-circle"></i> Status</label>
                            <div class="select-wrapper">
                                <select class="form-select" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Location</label>
                        <input type="text" class="form-control" name="location" value="{{ old('location') }}" placeholder="City, Country">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bulk Import Modal --}}
<div class="modal fade" id="bulkImportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-import"></i>
                    Bulk Import Users
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="import-icon"><i class="fas fa-file-csv"></i></div>
                <h6 style="text-align: center;">Upload CSV File</h6>
                <p class="text-muted text-center">Download the template first to ensure correct format</p>
                
                <div class="template-download">
                    <a href="#" class="btn btn-outline" id="downloadTemplateBtn">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>

                <div class="file-upload">
                    <input type="file" class="file-input" id="importFile" accept=".csv">
                    <label for="importFile" class="file-label"><i class="fas fa-cloud-upload-alt"></i> Choose File</label>
                    <span class="file-name" id="fileName">No file chosen</span>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="importBtn" disabled>
                    <i class="fas fa-upload"></i> Import Users
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview image
function previewImage(input) {
    const preview = document.getElementById('photoPreview');
    const clearBtn = document.getElementById('clearPhotoBtn');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            clearBtn.style.display = 'inline-flex';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function clearPhoto() {
    const preview = document.getElementById('photoPreview');
    const fileInput = document.getElementById('profile_photo');
    const clearBtn = document.getElementById('clearPhotoBtn');
    preview.innerHTML = '<i class="fas fa-user"></i>';
    fileInput.value = '';
    clearBtn.style.display = 'none';
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
    const toggleBtn = field.closest('.password-field').querySelector('.password-toggle');
    toggleBtn.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strengthBar');
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^A-Za-z0-9]/.test(password)) strength += 25;
    strength = Math.min(strength, 100);
    strengthBar.style.width = strength + '%';
    if (strength <= 25) strengthBar.style.background = '#ef4444';
    else if (strength <= 50) strengthBar.style.background = '#f59e0b';
    else if (strength <= 75) strengthBar.style.background = '#10b981';
    else strengthBar.style.background = '#7c3aed';
});

document.getElementById('password_confirmation')?.addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const matchDiv = document.getElementById('passwordMatch');
    if (confirm.length === 0) matchDiv.innerHTML = '';
    else if (password === confirm) matchDiv.innerHTML = '<span style="color: #10b981;"><i class="fas fa-check-circle me-1"></i>Passwords match</span>';
    else matchDiv.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-circle me-1"></i>Passwords do not match</span>';
});

function toggleAll() {
    const selectAll = document.getElementById('selectAll');
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = selectAll.checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const selected = document.querySelectorAll('.row-select:checked').length;
    const selectedCount = document.getElementById('selectedCount');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    if (selected > 0) {
        selectedCount.style.display = 'inline';
        selectedCount.textContent = selected + ' selected';
        bulkDeleteBtn.style.display = 'inline-flex';
    } else {
        selectedCount.style.display = 'none';
        bulkDeleteBtn.style.display = 'none';
    }
}

function bulkDelete() { if (confirm('Delete selected users?')) alert('Bulk delete would be implemented'); }
function viewUser(id) { window.location.href = `/admin/users/${id}`; }
function editUser(id) { alert('Edit user: ' + id); }
function deleteUser(id) { if (confirm('Delete this user?')) alert('Delete user: ' + id); }

document.getElementById('searchUsers')?.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('.data-table tbody tr:not(.empty-state)').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});

document.getElementById('roleFilter')?.addEventListener('change', filterTable);
document.getElementById('statusFilter')?.addEventListener('change', filterTable);

function filterTable() {
    const role = document.getElementById('roleFilter').value.toLowerCase();
    const status = document.getElementById('statusFilter').value.toLowerCase();
    document.querySelectorAll('.data-table tbody tr:not(.empty-state)').forEach(row => {
        const roleCell = row.querySelector('td:nth-child(4) .role-badge');
        const statusCell = row.querySelector('td:nth-child(5) .status-badge');
        const roleText = roleCell ? roleCell.textContent.trim().toLowerCase() : '';
        const statusText = statusCell ? statusCell.textContent.trim().toLowerCase() : '';
        let show = true;
        if (role && !roleText.includes(role)) show = false;
        if (status && !statusText.includes(status)) show = false;
        row.style.display = show ? '' : 'none';
    });
}

document.getElementById('resetFilters')?.addEventListener('click', function() {
    document.getElementById('searchUsers').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.querySelectorAll('.data-table tbody tr').forEach(row => { row.style.display = ''; });
});

document.getElementById('importFile')?.addEventListener('change', function(e) {
    document.getElementById('fileName').textContent = e.target.files.length ? e.target.files[0].name : 'No file chosen';
    document.getElementById('importBtn').disabled = !e.target.files.length;
});

document.getElementById('importBtn')?.addEventListener('click', () => alert('Import would be implemented'));
document.getElementById('downloadTemplateBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    const blob = new Blob(['name,email,password,role,phone\nJohn Doe,john@example.com,password123,user,1234567890'], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'user_import_template.csv'; a.click(); window.URL.revokeObjectURL(url);
});
document.getElementById('exportUsersBtn')?.addEventListener('click', () => alert('Export would be implemented'));

document.getElementById('addUserForm')?.addEventListener('submit', function(e) {
    if (document.getElementById('password').value !== document.getElementById('password_confirmation').value) {
        e.preventDefault(); alert('Passwords do not match!'); return;
    }
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...'; btn.disabled = true;
});

document.addEventListener('keydown', (e) => { if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); document.getElementById('searchUsers')?.focus(); } });
</script>
@endpush
@endsection