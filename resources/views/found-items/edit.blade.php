@extends('layouts.app')

@section('title', 'Edit Found Item - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    $isOwner = Auth::id() === $foundItem->user_id;
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
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px 32px;
}

/* Access Denied */
.access-denied {
    text-align: center;
    padding: 60px 30px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    max-width: 500px;
    margin: 40px auto;
}

.access-denied-icon {
    width: 80px;
    height: 80px;
    background: var(--error-soft);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--error);
    font-size: 32px;
}

.access-denied h4 {
    font-size: 20px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.access-denied p {
    color: var(--text-muted);
    margin-bottom: 24px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
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

.btn-danger {
    background: var(--error);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Form Card */
.form-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.form-card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 20px 28px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.card-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h5 i {
    color: var(--accent);
    font-size: 18px;
}

.card-body {
    padding: 28px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
}

@media (max-width: 992px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}

/* Form Sections */
.form-section {
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 24px;
    margin-bottom: 24px;
}

.form-section:last-child {
    margin-bottom: 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-light);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.section-title i {
    color: var(--accent);
    font-size: 16px;
}

/* Form Groups */
.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 576px) {
    .form-row {
        grid-template-columns: 1fr;
    }
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
    margin-left: 2px;
}

.optional {
    font-size: 10px;
    color: var(--text-muted);
    font-weight: 400;
    margin-left: 6px;
}

/* Input Styles */
.input-wrapper,
.select-wrapper,
.textarea-wrapper,
.date-wrapper {
    position: relative;
    width: 100%;
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

.form-control::placeholder {
    color: var(--text-soft);
}

/* Select */
.select-wrapper {
    position: relative;
}

.form-select {
    appearance: none;
    padding-right: 40px;
    cursor: pointer;
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

/* Textarea */
textarea.form-control {
    resize: vertical;
    min-height: 120px;
    line-height: 1.5;
}

/* Date Input */
input[type="date"] {
    cursor: pointer;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(0.4);
}

body.dark input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(0.7);
}

/* Form Hints */
.form-hint {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-top: 8px;
    color: var(--text-muted);
    font-size: 11px;
}

.form-hint i {
    color: var(--accent);
    font-size: 11px;
    margin-top: 2px;
}

/* Error States */
.is-invalid {
    border-color: var(--error) !important;
}

.invalid-feedback {
    display: block;
    color: var(--error);
    font-size: 11px;
    margin-top: 6px;
}

/* Photo Section */
.photo-section {
    text-align: center;
}

.current-photo-container {
    position: relative;
    display: inline-block;
    max-width: 100%;
}

.current-photo {
    max-height: 200px;
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    border: 1px solid var(--border-light);
}

.current-photo:hover {
    transform: scale(1.02);
    box-shadow: var(--shadow-md);
}

.photo-actions {
    margin-top: 16px;
}

.photo-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    background: var(--error-soft);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 40px;
    color: var(--error);
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.photo-checkbox:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: translateY(-2px);
}

.photo-checkbox input {
    margin-right: 6px;
    cursor: pointer;
    accent-color: var(--error);
}

.no-photo-container {
    padding: 40px;
    background: var(--bg-soft);
    border-radius: var(--radius-sm);
    border: 2px dashed var(--border-light);
}

.no-photo-container i {
    font-size: 48px;
    color: var(--border-light);
    margin-bottom: 12px;
}

.no-photo-container p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

/* File Upload */
.file-upload-wrapper {
    position: relative;
    cursor: pointer;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.file-upload-content {
    border: 2px dashed var(--border-light);
    border-radius: var(--radius-sm);
    padding: 24px 20px;
    text-align: center;
    transition: var(--transition);
    background: var(--bg-soft);
}

.file-upload-wrapper:hover .file-upload-content {
    border-color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

.upload-icon {
    font-size: 32px;
    color: var(--accent);
    margin-bottom: 12px;
    display: inline-block;
}

.upload-text {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 6px;
}

.upload-hint {
    display: block;
    font-size: 10px;
    color: var(--text-muted);
}

/* Photo Preview */
.photo-preview {
    min-height: 160px;
    border: 2px dashed var(--border-light);
    border-radius: var(--radius-sm);
    overflow: hidden;
    transition: var(--transition);
    background: var(--bg-soft);
}

.photo-preview:hover {
    border-color: var(--accent);
}

.preview-placeholder {
    height: 160px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.preview-placeholder i {
    font-size: 40px;
    color: var(--accent);
    margin-bottom: 8px;
    opacity: 0.6;
}

.preview-placeholder p {
    font-size: 12px;
    margin: 0;
}

/* Info Box */
.info-box {
    background: var(--info-soft);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.info-box i {
    color: var(--info);
    font-size: 16px;
}

.info-box span {
    color: var(--text-muted);
    font-size: 13px;
}

/* Location Actions */
.location-actions {
    display: flex;
    gap: 12px;
    margin-top: 16px;
    flex-wrap: wrap;
}

.location-status {
    margin-top: 12px;
    font-size: 12px;
    padding: 8px 12px;
    border-radius: 8px;
    background: var(--bg-soft);
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid var(--border-light);
    flex-wrap: wrap;
    gap: 16px;
}

.action-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

@media (max-width: 576px) {
    .form-actions {
        flex-direction: column;
    }
    
    .action-group {
        width: 100%;
    }
    
    .action-group .btn {
        flex: 1;
        justify-content: center;
    }
    
    .btn-danger {
        width: 100%;
    }
}

/* Modal */
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
    color: var(--error);
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

.delete-warning {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: var(--error-soft);
    border-radius: var(--radius-sm);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.delete-warning i {
    color: var(--error);
    font-size: 24px;
}

.warning-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--error);
    margin-bottom: 4px;
}

.warning-text {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}

/* Toast Notifications */
#notificationsContainer {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    max-width: 350px;
}

.toast {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    margin-bottom: 12px;
    box-shadow: var(--shadow-md);
    animation: slideInRight 0.3s ease;
}

.toast-body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    font-size: 13px;
    color: var(--text-dark);
}

.toast-body i {
    margin-right: 12px;
    font-size: 16px;
}

.toast-body .toast-message {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
}

.toast-close {
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
    transition: var(--transition);
}

.toast-close:hover {
    color: var(--error);
    transform: rotate(90deg);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
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
    
    .card-body {
        padding: 20px;
    }
    
    .form-section {
        padding: 18px;
    }
}
</style>

<div class="dashboard-container">
    {{-- Access Check --}}
    @if(!$isAdmin && !$isOwner)
        <div class="access-denied fade-in">
            <div class="access-denied-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h4>Access Denied</h4>
            <p>You don't have permission to edit this item.</p>
            <a href="{{ route('found-items.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Back to Found Items
            </a>
        </div>
    @else
        {{-- Page Header --}}
        <div class="page-header fade-in">
            <div class="page-title">
                <h1>
                    <i class="fas fa-edit"></i>
                    Edit Found Item
                </h1>
                <p>Update the details of "{{ $foundItem->item_name }}"</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('found-items.show', $foundItem) }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </a>
            </div>
        </div>

        {{-- Main Edit Form Card --}}
        <div class="form-card fade-in">
            <div class="card-header">
                <h5>
                    <i class="fas fa-pencil-alt"></i>
                    Edit Item Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('found-items.update', $foundItem) }}" method="POST" enctype="multipart/form-data" id="editItemForm">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        {{-- Left Column --}}
                        <div class="left-column">
                            {{-- Basic Information --}}
                            <div class="form-section">
                                <h6 class="section-title">
                                    <i class="fas fa-info-circle"></i>
                                    Basic Information
                                </h6>

                                <div class="form-group">
                                    <label for="item_name" class="form-label">
                                        <i class="fas fa-tag"></i>
                                        Item Name <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('item_name') is-invalid @enderror" 
                                               id="item_name" 
                                               name="item_name" 
                                               value="{{ old('item_name', $foundItem->item_name) }}" 
                                               required>
                                    </div>
                                    @error('item_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="category" class="form-label">
                                            <i class="fas fa-list"></i>
                                            Category <span class="required">*</span>
                                        </label>
                                        <div class="select-wrapper">
                                            <select class="form-select @error('category') is-invalid @enderror" 
                                                    id="category" 
                                                    name="category" 
                                                    required>
                                                <option value="">Select Category</option>
                                                <option value="Electronics" {{ (old('category', $foundItem->category) == 'Electronics') ? 'selected' : '' }}>📱 Electronics</option>
                                                <option value="Documents" {{ (old('category', $foundItem->category) == 'Documents') ? 'selected' : '' }}>📄 Documents</option>
                                                <option value="Jewelry" {{ (old('category', $foundItem->category) == 'Jewelry') ? 'selected' : '' }}>💎 Jewelry</option>
                                                <option value="Clothing" {{ (old('category', $foundItem->category) == 'Clothing') ? 'selected' : '' }}>👕 Clothing</option>
                                                <option value="Bags" {{ (old('category', $foundItem->category) == 'Bags') ? 'selected' : '' }}>🎒 Bags</option>
                                                <option value="Keys" {{ (old('category', $foundItem->category) == 'Keys') ? 'selected' : '' }}>🔑 Keys</option>
                                                <option value="Wallet" {{ (old('category', $foundItem->category) == 'Wallet') ? 'selected' : '' }}>👛 Wallet</option>
                                                <option value="Books" {{ (old('category', $foundItem->category) == 'Books') ? 'selected' : '' }}>📚 Books</option>
                                                <option value="Other" {{ (old('category', $foundItem->category) == 'Other') ? 'selected' : '' }}>📦 Other</option>
                                            </select>
                                            <i class="fas fa-chevron-down select-arrow"></i>
                                        </div>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="date_found" class="form-label">
                                            <i class="fas fa-calendar-alt"></i>
                                            Date Found <span class="required">*</span>
                                        </label>
                                        <div class="date-wrapper">
                                            <input type="date" 
                                                   class="form-control @error('date_found') is-invalid @enderror" 
                                                   id="date_found" 
                                                   name="date_found" 
                                                   value="{{ old('date_found', $foundItem->date_found ? \Carbon\Carbon::parse($foundItem->date_found)->format('Y-m-d') : '') }}" 
                                                   required>
                                        </div>
                                        @error('date_found')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-circle"></i>
                                        Status <span class="required">*</span>
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status" 
                                                required>
                                            <option value="pending" {{ (old('status', $foundItem->status) == 'pending') ? 'selected' : '' }}>⏳ Pending / Active</option>
                                            <option value="claimed" {{ (old('status', $foundItem->status) == 'claimed') ? 'selected' : '' }}>✅ Claimed</option>
                                            <option value="disposed" {{ (old('status', $foundItem->status) == 'disposed') ? 'selected' : '' }}>🗑️ Disposed</option>
                                        </select>
                                        <i class="fas fa-chevron-down select-arrow"></i>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left"></i>
                                        Description <span class="required">*</span>
                                    </label>
                                    <div class="textarea-wrapper">
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="5" 
                                                  required>{{ old('description', $foundItem->description) }}</textarea>
                                    </div>
                                    <div class="form-hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Include color, size, brand, distinctive features, and any other identifying details.</span>
                                    </div>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="right-column">
                            {{-- Photo Management --}}
                            <div class="form-section">
                                <h6 class="section-title">
                                    <i class="fas fa-image"></i>
                                    Photo
                                </h6>
                                
                                <div class="photo-section">
                                    @if($foundItem->photo)
                                        <div class="current-photo-container">
                                            <img src="{{ asset('storage/' . $foundItem->photo) }}" 
                                                 class="current-photo" 
                                                 alt="{{ $foundItem->item_name }}">
                                            <div class="photo-actions">
                                                <label class="photo-checkbox">
                                                    <input type="checkbox" id="removePhoto" name="remove_photo" value="1">
                                                    <i class="fas fa-trash-alt"></i>
                                                    Remove Current Photo
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="no-photo-container">
                                            <i class="fas fa-image"></i>
                                            <p>No photo currently uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Upload New Photo --}}
                            <div class="form-section">
                                <h6 class="section-title">
                                    <i class="fas fa-camera"></i>
                                    Upload New Photo
                                </h6>

                                <div class="form-group">
                                    <label for="photo" class="form-label">
                                        <i class="fas fa-upload"></i>
                                        Choose File <span class="optional">(Optional)</span>
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" 
                                               class="file-input @error('photo') is-invalid @enderror" 
                                               id="photo" 
                                               name="photo" 
                                               accept="image/jpeg,image/png,image/gif,image/webp">
                                        <div class="file-upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <span class="upload-text">Click to upload or drag & drop</span>
                                            <span class="upload-hint">JPG, PNG, GIF, WEBP up to 2MB</span>
                                        </div>
                                    </div>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- New Photo Preview --}}
                                <div class="photo-preview" id="photoPreview">
                                    <div class="preview-placeholder">
                                        <i class="fas fa-image"></i>
                                        <p>New photo preview</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Location Information --}}
                            <div class="form-section">
                                <h6 class="section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Location
                                </h6>

                                <div class="info-box">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Update the location where you found the item.</span>
                                </div>

                                <div class="form-group">
                                    <label for="found_location" class="form-label">
                                        <i class="fas fa-map-marked-alt"></i>
                                        Found Location <span class="optional">(Optional)</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('found_location') is-invalid @enderror" 
                                               id="found_location" 
                                               name="found_location" 
                                               value="{{ old('found_location', $foundItem->found_location) }}" 
                                               placeholder="e.g., Central Park, Starbucks on 5th Ave">
                                    </div>
                                    @error('found_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="latitude" class="form-label">
                                            <i class="fas fa-map-pin"></i>
                                            Latitude
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="number" 
                                                   step="any" 
                                                   class="form-control @error('latitude') is-invalid @enderror" 
                                                   id="latitude" 
                                                   name="latitude" 
                                                   value="{{ old('latitude', $foundItem->latitude) }}" 
                                                   placeholder="40.7128" 
                                                   min="-90" 
                                                   max="90">
                                        </div>
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="longitude" class="form-label">
                                            <i class="fas fa-map-pin"></i>
                                            Longitude
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="number" 
                                                   step="any" 
                                                   class="form-control @error('longitude') is-invalid @enderror" 
                                                   id="longitude" 
                                                   name="longitude" 
                                                   value="{{ old('longitude', $foundItem->longitude) }}" 
                                                   placeholder="-74.0060" 
                                                   min="-180" 
                                                   max="180">
                                        </div>
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="location-actions">
                                    <button type="button" class="btn btn-primary" onclick="getCurrentLocation()">
                                        <i class="fas fa-location-arrow"></i>
                                        Use My Current Location
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="clearLocation()">
                                        <i class="fas fa-times"></i>
                                        Clear
                                    </button>
                                </div>
                                <div id="locationStatus" class="location-status"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="form-actions">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt"></i>
                            Delete Item
                        </button>
                        
                        <div class="action-group">
                            <a href="{{ route('found-items.show', $foundItem) }}" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i>
                                Update Item
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Delete Item
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="delete-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <p class="warning-title">Delete "{{ $foundItem->item_name }}"?</p>
                        <p class="warning-text">This action cannot be undone. All associated data, matches, and records will be permanently removed.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('found-items.destroy', $foundItem) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i>
                        Permanently Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Notifications Container --}}
<div id="notificationsContainer"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('editItemForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const foundDate = document.getElementById('date_found');
            const today = new Date().toISOString().split('T')[0];
            
            if (foundDate.value > today) {
                e.preventDefault();
                showToast('Found date cannot be in the future', 'error');
                foundDate.focus();
            }
        });
    }
    
    // Photo preview for new upload
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const removePhotoCheckbox = document.getElementById('removePhoto');
    
    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showToast('File size must be less than 2MB', 'error');
                    this.value = '';
                    return;
                }
                
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showToast('Please upload a valid image (JPG, PNG, GIF, WEBP)', 'error');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.innerHTML = `
                        <div style="position: relative;">
                            <img src="${e.target.result}" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 8px;">
                            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 12px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); border-radius: 0 0 8px 8px;">
                                <small style="color: white; font-size: 11px;">${file.name} (${(file.size / 1024).toFixed(2)} KB)</small>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.innerHTML = `
                    <div class="preview-placeholder">
                        <i class="fas fa-image"></i>
                        <p>New photo preview</p>
                    </div>
                `;
            }
        });
    }
    
    if (removePhotoCheckbox && photoInput) {
        removePhotoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                photoInput.disabled = true;
                photoInput.value = '';
                showToast('Current photo will be removed upon saving', 'warning');
                
                if (photoPreview) {
                    photoPreview.innerHTML = `
                        <div class="preview-placeholder">
                            <i class="fas fa-image"></i>
                            <p>Current photo will be removed</p>
                        </div>
                    `;
                }
            } else {
                photoInput.disabled = false;
                photoPreview.innerHTML = `
                    <div class="preview-placeholder">
                        <i class="fas fa-image"></i>
                        <p>New photo preview</p>
                    </div>
                `;
            }
        });
    }
});

// Get current location
function getCurrentLocation() {
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const foundLocationInput = document.getElementById('found_location');
    const statusDiv = document.getElementById('locationStatus');
    
    statusDiv.innerHTML = '<span style="color: var(--text-muted);"><i class="fas fa-spinner fa-spin"></i> Getting location...</span>';
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);
                
                latitudeInput.value = lat;
                longitudeInput.value = lng;
                
                statusDiv.innerHTML = `<span style="color: var(--success);"><i class="fas fa-check-circle"></i> Location acquired: ${lat}, ${lng}</span>`;
                
                reverseGeocode(lat, lng, foundLocationInput);
                
                showToast('Location retrieved successfully', 'success');
            },
            function(error) {
                let errorMessage = 'Unable to get location. ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Please enable location access.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Location information is unavailable.';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Location request timed out.';
                        break;
                }
                
                statusDiv.innerHTML = `<span style="color: var(--error);"><i class="fas fa-exclamation-circle"></i> ${errorMessage}</span>`;
                showToast(errorMessage, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        statusDiv.innerHTML = '<span style="color: var(--error);"><i class="fas fa-exclamation-circle"></i> Geolocation is not supported by your browser</span>';
        showToast('Geolocation not supported', 'error');
    }
}

function reverseGeocode(lat, lng, inputElement) {
    if (!inputElement) return;
    
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                inputElement.value = data.display_name;
            }
        })
        .catch(error => console.error('Reverse geocoding failed:', error));
}

function clearLocation() {
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';
    document.getElementById('found_location').value = '';
    document.getElementById('locationStatus').innerHTML = '';
    showToast('Location fields cleared', 'info');
}

function showToast(message, type = 'info') {
    const container = document.getElementById('notificationsContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = 'toast';
    
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    const iconColor = type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--error)' : 'var(--accent)';
    
    toast.innerHTML = `
        <div class="toast-body">
            <div class="toast-message">
                <i class="fas fa-${icon}" style="color: ${iconColor};"></i>
                <span>${message}</span>
            </div>
            <button class="toast-close" onclick="this.closest('.toast').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        if (toast && toast.parentNode) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
}

// Remove invalid feedback on input
document.querySelectorAll('.form-control, .form-select').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        const feedback = this.parentElement.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
    });
});

// Submit button loading state
const submitBtn = document.getElementById('submitBtn');
if (submitBtn) {
    submitBtn.addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        setTimeout(() => {
            if (this.disabled) {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-save"></i> Update Item';
            }
        }, 10000);
    });
}
</script>
@endpush
@endsection