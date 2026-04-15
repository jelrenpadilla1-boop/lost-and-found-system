@extends('layouts.app')

@section('title', $lostItem->item_name . ' - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    $isOwner = Auth::id() === $lostItem->user_id;
@endphp

<style>
/* ── NETFLIX-STYLE LOST ITEM DETAIL PAGE ───────────────── */
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

.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px 32px;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 28px;
    gap: 20px;
    flex-wrap: wrap;
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--netflix-text);
    margin: 0 0 12px 0;
    letter-spacing: -0.02em;
}

.title-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Badges */
.badge {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge.status-pending {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.badge.status-approved {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.badge.status-found {
    background: rgba(33, 150, 243, 0.2);
    color: var(--netflix-info);
}

.badge.status-returned {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.status-rejected {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.time {
    background: rgba(255, 255, 255, 0.05);
    color: var(--netflix-text-secondary);
    border: 1px solid var(--netflix-border);
}

.badge.owner {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.badge.admin {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

/* Buttons */
.btn {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 4px;
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

.btn-success {
    background: var(--netflix-success);
    color: white;
}

.btn-success:hover {
    background: #1b5e20;
    transform: scale(1.02);
}

.btn-danger {
    background: var(--netflix-red);
    color: white;
}

.btn-danger:hover {
    background: var(--netflix-red-dark);
    transform: scale(1.02);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.02);
}

body.light .btn-secondary {
    background: rgba(0, 0, 0, 0.05);
}

body.light .btn-secondary:hover {
    background: rgba(0, 0, 0, 0.1);
}

/* Alerts */
.alerts-container {
    margin-bottom: 28px;
}

.alert-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    padding: 16px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 16px;
    border-left: 3px solid;
}

.alert-card.error {
    border-left-color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
}

.alert-card.warning {
    border-left-color: var(--netflix-warning);
    background: rgba(245, 197, 24, 0.1);
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-card.error .alert-icon {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.alert-card.warning .alert-icon {
    background: rgba(245, 197, 24, 0.15);
    color: var(--netflix-warning);
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--netflix-text);
    margin-bottom: 4px;
}

.alert-content p {
    font-size: 13px;
    color: var(--netflix-text-secondary);
    margin: 0;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 28px;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

/* Cards */
.card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 28px;
    transition: var(--transition-netflix);
}

.card-header {
    padding: 18px 24px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
}

.card-header h6 {
    font-size: 15px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h6 i {
    color: var(--netflix-red);
    font-size: 16px;
}

.card-body {
    padding: 24px;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
}

/* Image Section */
.image-wrapper {
    position: relative;
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 1;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
}

.item-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-wrapper:hover .item-image {
    transform: scale(1.05);
}

.image-expand {
    position: absolute;
    bottom: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 4px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition-netflix);
    opacity: 0;
}

.image-wrapper:hover .image-expand {
    opacity: 1;
}

.image-expand:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
}

.no-image {
    aspect-ratio: 1;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--netflix-border);
    color: var(--netflix-text-secondary);
}

.no-image i {
    font-size: 48px;
    color: var(--netflix-border);
    margin-bottom: 12px;
}

/* Info Section */
.info-group {
    margin-bottom: 20px;
}

.info-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-label i {
    color: var(--netflix-red);
    font-size: 11px;
}

.description {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    line-height: 1.6;
    background: rgba(255, 255, 255, 0.03);
    padding: 16px;
    border-radius: 8px;
    border: 1px solid var(--netflix-border);
    margin: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 16px;
}

.info-item {
    background: rgba(255, 255, 255, 0.03);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid var(--netflix-border);
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-item-value {
    font-size: 13px;
    font-weight: 500;
    color: var(--netflix-text);
    word-break: break-word;
}

.you-badge {
    color: var(--netflix-red);
    font-size: 11px;
    margin-left: 4px;
}

/* Actions Grid */
.actions-grid {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.action-btn {
    flex: 1;
    font-size: 12px;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
    transition: var(--transition-netflix);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    border: 1px solid;
}

.action-btn.success {
    border-color: var(--netflix-success);
    color: var(--netflix-success);
    background: rgba(46, 125, 50, 0.1);
}

.action-btn.success:hover {
    background: var(--netflix-success);
    color: white;
    transform: scale(1.02);
}

.action-btn.danger {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
}

.action-btn.danger:hover {
    background: var(--netflix-red);
    color: white;
    transform: scale(1.02);
}

/* Matches Card */
.matches-badge {
    background: var(--netflix-red);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 4px;
}

.match-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    transition: var(--transition-netflix);
}

.match-item:hover {
    border-color: var(--netflix-red);
    transform: translateX(4px);
}

.match-item:last-child {
    margin-bottom: 0;
}

.match-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.match-score {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 4px;
    white-space: nowrap;
}

.score-high {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.score-medium {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.score-low {
    background: rgba(33, 150, 243, 0.2);
    color: var(--netflix-info);
}

.match-info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.match-info strong {
    font-size: 14px;
    font-weight: 700;
    color: var(--netflix-text);
}

.your-item-badge {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
    font-size: 10px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
}

.match-view-link {
    color: var(--netflix-red);
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: var(--transition-netflix);
}

.match-view-link:hover {
    color: var(--netflix-red-dark);
    transform: translateX(4px);
}

.match-description {
    font-size: 12px;
    color: var(--netflix-text-secondary);
    margin-bottom: 10px;
    line-height: 1.5;
}

.match-footer {
    display: flex;
    gap: 16px;
    font-size: 11px;
    color: var(--netflix-text-secondary);
    flex-wrap: wrap;
}

.match-footer i {
    color: var(--netflix-red);
    margin-right: 4px;
}

/* Contact Card */
.contact-profile {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--netflix-border);
}

.contact-avatar {
    width: 56px;
    height: 56px;
    border-radius: 8px;
    background: var(--netflix-red);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 700;
    flex-shrink: 0;
}

.contact-details {
    flex: 1;
}

.contact-name {
    font-size: 16px;
    font-weight: 700;
    color: var(--netflix-text);
    margin: 0 0 4px 0;
}

.contact-role {
    font-size: 11px;
    color: var(--netflix-red);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.you-indicator {
    font-size: 10px;
    color: var(--netflix-text-secondary);
    margin-left: 6px;
}

.contact-info-list {
    margin-bottom: 20px;
}

.contact-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    font-size: 13px;
    color: var(--netflix-text-secondary);
    border-bottom: 1px solid var(--netflix-border);
    word-break: break-word;
}

.contact-info-item:last-child {
    border-bottom: none;
}

.contact-info-item i {
    color: var(--netflix-red);
    width: 18px;
    font-size: 14px;
}

.message-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px;
    background: var(--netflix-red);
    border: none;
    border-radius: 4px;
    color: white;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-netflix);
}

.message-btn:hover {
    background: var(--netflix-red-dark);
    transform: scale(1.02);
}

/* Map Card */
.map-container {
    width: 100%;
    position: relative;
    min-height: 250px;
    background: rgba(255, 255, 255, 0.03);
}

#map {
    height: 250px;
    width: 100%;
    z-index: 1;
    border-radius: 0;
}

#mapLoading, #mapError {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.map-footer {
    padding: 16px;
    background: rgba(255, 255, 255, 0.03);
    border-top: 1px solid var(--netflix-border);
}

.location-name {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--netflix-text-secondary);
    margin-bottom: 12px;
    word-break: break-word;
}

.location-name i {
    color: var(--netflix-red);
    font-size: 12px;
    flex-shrink: 0;
}

.map-actions {
    display: flex;
    gap: 12px;
}

.directions-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 12px;
    background: transparent;
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text-secondary);
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-netflix);
    cursor: pointer;
}

.directions-btn:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
    transform: scale(1.02);
}

/* Access Denied */
.access-denied {
    text-align: center;
    padding: 60px 30px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    max-width: 500px;
    margin: 40px auto;
}

.access-denied-icon {
    width: 80px;
    height: 80px;
    background: rgba(229, 9, 20, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--netflix-red);
    font-size: 32px;
}

.access-denied h4 {
    font-size: 20px;
    font-weight: 800;
    color: var(--netflix-text);
    margin-bottom: 10px;
}

.access-denied p {
    color: var(--netflix-text-secondary);
    margin-bottom: 24px;
}

/* Modals */
.modal-content {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
}

.modal-header {
    padding: 18px 24px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--netflix-text);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-title i {
    color: var(--netflix-red);
}

.modal-close {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    background: transparent;
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    cursor: pointer;
    transition: var(--transition-netflix);
}

.modal-close:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    background: var(--netflix-dark);
    border-top: 1px solid var(--netflix-border);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: var(--netflix-text);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-label i {
    color: var(--netflix-red);
}

.required {
    color: var(--netflix-red);
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text);
    font-size: 14px;
    transition: var(--transition-netflix);
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: var(--netflix-red);
}

body.light .form-control {
    background: rgba(0, 0, 0, 0.02);
}

.info-box {
    background: rgba(33, 150, 243, 0.1);
    border: 1px solid rgba(33, 150, 243, 0.2);
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
    color: var(--netflix-text-secondary);
}

.info-box i {
    color: var(--netflix-info);
    font-size: 14px;
}

/* Leaflet Customization */
.leaflet-container {
    background: var(--netflix-dark);
    z-index: 1;
}

.leaflet-control-attribution {
    background: rgba(0, 0, 0, 0.5) !important;
    color: var(--netflix-text-secondary) !important;
    font-size: 9px !important;
}

.leaflet-popup-content-wrapper {
    background: var(--netflix-card);
    color: var(--netflix-text);
    border-radius: 8px;
    border: 1px solid var(--netflix-border);
}

.leaflet-popup-tip {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
}

/* Animations */
.fade-in {
    animation: fadeIn 0.4s ease forwards;
}

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
    
    .card-body {
        padding: 18px;
    }
    
    .actions-grid {
        flex-direction: column;
    }
    
    .match-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .contact-profile {
        flex-direction: column;
        text-align: center;
    }
    
    .contact-avatar {
        margin: 0 auto;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
    
    #map {
        height: 200px;
    }
}

.d-inline {
    display: inline;
}
</style>

<div class="dashboard-container">
    {{-- Authentication Check --}}
    @if(!$isAdmin && !$isOwner && $lostItem->status === 'pending')
        <div class="access-denied fade-in">
            <div class="access-denied-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h4>Access Denied</h4>
            <p>This item is pending approval and not yet visible to the public.</p>
            <a href="{{ route('lost-items.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Back to Lost Items
            </a>
        </div>
    @else
        {{-- Page Header --}}
        <div class="page-header fade-in">
            <div class="page-title">
                <h1>{{ $lostItem->item_name }}</h1>
                <div class="title-meta">
                    <span class="badge status-{{ $lostItem->status }}">
                        @if($lostItem->status == 'pending')
                            <i class="fas fa-clock"></i> Pending
                        @elseif($lostItem->status == 'approved')
                            <i class="fas fa-check-circle"></i> Active
                        @elseif($lostItem->status == 'found')
                            <i class="fas fa-check"></i> Found
                        @elseif($lostItem->status == 'returned')
                            <i class="fas fa-home"></i> Returned
                        @elseif($lostItem->status == 'rejected')
                            <i class="fas fa-times-circle"></i> Rejected
                        @elseif($lostItem->status == 'recovered')
                            <i class="fas fa-gift"></i> Recovered
                        @endif
                    </span>
                    
                    <span class="badge time">
                        <i class="fas fa-clock"></i>
                        Lost {{ $lostItem->created_at->diffForHumans() }}
                    </span>

                    @if($isOwner)
                        <span class="badge owner">
                            <i class="fas fa-star"></i> Your Item
                        </span>
                    @endif
                    
                    @if($isAdmin)
                        <span class="badge admin">
                            <i class="fas fa-crown"></i> Admin View
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="page-actions">
                <a href="{{ route('lost-items.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
                
                @if($isAdmin && $lostItem->status === 'pending')
                    <form action="{{ route('lost-items.approve', $lostItem) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Approve this item?')">
                            <i class="fas fa-check-circle"></i>
                            Approve
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times-circle"></i>
                        Reject
                    </button>
                @endif
                
                @can('update', $lostItem)
                    <a href="{{ route('lost-items.edit', $lostItem) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                @endcan
            </div>
        </div>

        {{-- Alerts --}}
        <div class="alerts-container fade-in">
            @if($lostItem->status === 'rejected' && $lostItem->rejection_reason && ($isAdmin || $isOwner))
                <div class="alert-card error">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Item Rejected</strong>
                        <p>{{ $lostItem->rejection_reason }}</p>
                    </div>
                </div>
            @endif

            @if($lostItem->status === 'pending' && $isOwner && !$isAdmin)
                <div class="alert-card warning">
                    <div class="alert-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Pending Approval</strong>
                        <p>Your item is awaiting admin review. It will be visible to others once approved.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Main Content Grid --}}
        <div class="content-grid">
            {{-- Left Column --}}
            <div class="left-column">
                {{-- Item Details Card --}}
                <div class="card details-card fade-in">
                    <div class="card-header">
                        <h6><i class="fas fa-info-circle"></i> Item Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="details-grid">
                            {{-- Image --}}
                            <div class="image-section">
                                @if($lostItem->photo)
                                    <div class="image-wrapper">
                                        <img src="{{ asset('storage/' . $lostItem->photo) }}" 
                                             class="item-image" 
                                             alt="{{ $lostItem->item_name }}">
                                        <button class="image-expand" onclick="openImageModal('{{ asset('storage/' . $lostItem->photo) }}')">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                        <span>No Photo</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="info-section">
                                <div class="info-group">
                                    <label class="info-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
                                    <p class="description">{{ $lostItem->description }}</p>
                                </div>

                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-item-label">Category</span>
                                        <span class="info-item-value">{{ strtoupper($lostItem->category) }}</span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <span class="info-item-label">Date Lost</span>
                                        <span class="info-item-value">{{ $lostItem->date_lost->format('M d, Y') }}</span>
                                    </div>
                                    
                                    @if($lostItem->lost_location)
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Location</span>
                                        <span class="info-item-value">{{ $lostItem->lost_location }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($lostItem->latitude && $lostItem->longitude && $lostItem->latitude != 0 && $lostItem->longitude != 0)
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Coordinates</span>
                                        <span class="info-item-value">{{ number_format($lostItem->latitude, 6) }}, {{ number_format($lostItem->longitude, 6) }}</span>
                                    </div>
                                    @endif
                                    
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Reported By</span>
                                        <span class="info-item-value">
                                            {{ $lostItem->user->name }}
                                            @if($lostItem->user_id === Auth::id())
                                                <span class="you-badge">(you)</span>
                                            @endif
                                        </span>
                                    </div>

                                    @if($isAdmin && $lostItem->approved_at)
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Approved</span>
                                        <span class="info-item-value">
                                            {{ $lostItem->approved_at->diffForHumans() }} 
                                            by {{ $lostItem->approver->name ?? 'Admin' }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Card --}}
                @if(($lostItem->status === 'pending' && ($isAdmin || $isOwner)) || 
                    ($lostItem->status === 'approved' && $isOwner) ||
                    ($isAdmin))
                    <div class="card actions-card fade-in">
                        <div class="card-header">
                            <h6><i class="fas fa-bolt"></i> Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="actions-grid">
                                @if($lostItem->status === 'approved' && $isOwner)
                                    <button class="action-btn success" data-bs-toggle="modal" data-bs-target="#foundModal">
                                        <i class="fas fa-check-circle"></i> 
                                        Mark as Found
                                    </button>
                                @endif

                                @can('delete', $lostItem)
                                    <form action="{{ route('lost-items.destroy', $lostItem) }}" method="POST" 
                                          onsubmit="return confirm('Delete this item? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn danger">
                                            <i class="fas fa-trash-alt"></i> 
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Matches Card --}}
                @if($matches->count() > 0 && ($lostItem->status === 'approved' || $isAdmin || $isOwner))
                    <div class="card matches-card fade-in">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <h6><i class="fas fa-exchange-alt"></i> Potential Matches</h6>
                                <span class="matches-badge">{{ $matches->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($matches as $match)
                                <div class="match-item">
                                    <div class="match-header">
                                        <div class="match-score score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                            {{ $match->match_score }}%
                                        </div>
                                        <div class="match-info">
                                            <strong>{{ $match->foundItem->item_name }}</strong>
                                            @if($match->foundItem->user_id === Auth::id())
                                                <span class="your-item-badge">Your Item</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('matches.show', $match) }}" class="match-view-link">
                                            View <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    
                                    <p class="match-description">{{ Str::limit($match->foundItem->description, 80) }}</p>
                                    
                                    <div class="match-footer">
                                        <span><i class="fas fa-user"></i> {{ $match->foundItem->user->name }}</span>
                                        <span><i class="fas fa-calendar"></i> {{ $match->foundItem->date_found->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column --}}
            <div class="right-column">
                {{-- Contact Card --}}
                @if($lostItem->status !== 'pending' || $isAdmin || $isOwner)
                <div class="card contact-card fade-in">
                    <div class="card-header">
                        <h6><i class="fas fa-user-circle"></i> Contact Reporter</h6>
                    </div>
                    <div class="card-body">
                        <div class="contact-profile">
                            <div class="contact-avatar">
                                {{ strtoupper(substr($lostItem->user->name, 0, 1)) }}
                            </div>
                            <div class="contact-details">
                                <p class="contact-name">{{ $lostItem->user->name }}</p>
                                <small class="contact-role">
                                    {{ $lostItem->user->isAdmin() ? 'Admin' : 'Member' }}
                                    @if($lostItem->user_id === Auth::id())
                                        <span class="you-indicator">(you)</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="contact-info-list">
                            <div class="contact-info-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $lostItem->user->email }}</span>
                            </div>

                            @if($lostItem->user->latitude && $lostItem->user->longitude)
                                <div class="contact-info-item">
                                    <i class="fas fa-map-pin"></i>
                                    <span>{{ number_format($lostItem->user->latitude, 4) }}, {{ number_format($lostItem->user->longitude, 4) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if(!$isOwner && !$isAdmin)
                        <a href="{{ route('messages.start', $lostItem->user) }}" class="message-btn">
                            <i class="fas fa-comment"></i> 
                            Send Message
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Map Card --}}
                @if(($lostItem->lost_location || ($lostItem->latitude && $lostItem->longitude)) && 
                    ($lostItem->status !== 'pending' || $isAdmin || $isOwner))
                <div class="card map-card fade-in">
                    <div class="card-header">
                        <h6><i class="fas fa-map"></i> Lost Location</h6>
                    </div>
                    <div class="map-container">
                        <div id="map" style="height: 250px; width: 100%;"></div>
                        <div id="mapLoading" style="display: none; height: 250px; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--netflix-red);"></i>
                            <p style="margin-top: 10px; color: var(--netflix-text-secondary);">Loading map...</p>
                        </div>
                        <div id="mapError" style="display: none; height: 250px; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-exclamation-triangle fa-2x" style="color: var(--netflix-warning);"></i>
                            <p style="margin-top: 10px; color: var(--netflix-text-secondary);">Could not load map location</p>
                        </div>
                    </div>
                    
                    <div class="map-footer">
                        @if($lostItem->lost_location)
                            <div class="location-name">
                                <i class="fas fa-map-marked-alt"></i>
                                <span id="locationDisplay">{{ Str::limit($lostItem->lost_location, 50) }}</span>
                            </div>
                        @endif
                        
                        <div class="map-actions">
                            <button onclick="openInGoogleMaps()" class="directions-btn" id="directionsBtn" style="display: none;">
                                <i class="fas fa-directions"></i> 
                                Get Directions
                            </button>
                            <button onclick="searchInGoogleMaps()" class="directions-btn" id="searchBtn">
                                <i class="fas fa-search"></i> 
                                Search on Maps
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- Modals --}}
@if($lostItem->status === 'approved' && $isOwner)
<div class="modal fade" id="foundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle" style="color: var(--netflix-success);"></i>
                    Mark as Found
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('lost-items.update', $lostItem) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="found">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-pencil-alt"></i>
                            Found Details <span class="optional">(Optional)</span>
                        </label>
                        <textarea class="form-control" name="found_details" rows="3" 
                                  placeholder="How was the item found? Any additional information?"></textarea>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <span>This will notify potential matches and update the item status to "Found".</span>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($isAdmin && $lostItem->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle" style="color: var(--netflix-red);"></i>
                    Reject Item
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('lost-items.reject', $lostItem) }}" method="POST">
                @csrf
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-exclamation-triangle"></i>
                            Rejection Reason <span class="required">*</span>
                        </label>
                        <textarea class="form-control" name="rejection_reason" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <span>The user will be notified of this rejection reason.</span>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif


@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let map = null;
let mapInitialized = false;

document.addEventListener('DOMContentLoaded', function() {
    @php
        $canShowMap = ($lostItem->lost_location || ($lostItem->latitude && $lostItem->longitude)) && 
                     ($lostItem->status !== 'pending' || $isAdmin || $isOwner);
    @endphp
    
    @if($canShowMap)
        setTimeout(initMap, 100);
    @endif
});

function initMap() {
    const hasCoordinates = @json(($lostItem->latitude && $lostItem->longitude && $lostItem->latitude != 0 && $lostItem->longitude != 0));
    const locationName = @json($lostItem->lost_location);
    const lat = @json($lostItem->latitude);
    const lng = @json($lostItem->longitude);
    
    if (hasCoordinates) {
        displayMap(lat, lng, locationName, true);
        document.getElementById('directionsBtn').style.display = 'flex';
        document.getElementById('searchBtn').style.display = 'none';
    } else if (locationName) {
        geocodeLocation(locationName);
    } else {
        showMapError('No location data available');
    }
}

function geocodeLocation(location) {
    showMapLoading();
    
    const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}&limit=1`;
    
    fetch(geocodeUrl, {
        headers: { 
            'User-Agent': 'Foundify-App/1.0',
            'Accept-Language': 'en'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            const geocodedLat = parseFloat(data[0].lat);
            const geocodedLng = parseFloat(data[0].lon);
            displayMap(geocodedLat, geocodedLng, location, false);
            document.getElementById('directionsBtn').style.display = 'flex';
            document.getElementById('searchBtn').style.display = 'none';
            hideMapLoading();
        } else {
            showMapError('Could not find location on map');
            document.getElementById('searchBtn').style.display = 'flex';
        }
    })
    .catch(error => {
        console.error('Geocoding failed:', error);
        showMapError('Unable to load map location');
        document.getElementById('searchBtn').style.display = 'flex';
    });
}

function displayMap(lat, lng, locationName, isExact = true) {
    if (map) {
        map.remove();
        map = null;
    }
    
    try {
        map = L.map('map').setView([lat, lng], 14);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        const markerIcon = L.divIcon({
            className: 'custom-marker',
            html: '<i class="fas fa-map-marker-alt" style="color: #e50914; font-size: 28px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>',
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -28]
        });
        
        const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
        
        let popupContent = `<strong style="color: #e50914;">${escapeHtml(locationName || 'Lost Item Location')}</strong>`;
        if (!isExact) {
            popupContent += '<br><small style="color: #b3b3b3;">Approximate location based on address</small>';
        }
        
        marker.bindPopup(popupContent).openPopup();
        
        setTimeout(() => {
            if (map) {
                map.invalidateSize();
            }
        }, 100);
        
        hideMapLoading();
        mapInitialized = true;
        
    } catch (error) {
        console.error('Map initialization error:', error);
        showMapError('Error loading map');
    }
}

function showMapLoading() {
    const mapElement = document.getElementById('map');
    const loadingElement = document.getElementById('mapLoading');
    const errorElement = document.getElementById('mapError');
    
    if (mapElement) mapElement.style.display = 'none';
    if (loadingElement) loadingElement.style.display = 'flex';
    if (errorElement) errorElement.style.display = 'none';
}

function hideMapLoading() {
    const mapElement = document.getElementById('map');
    const loadingElement = document.getElementById('mapLoading');
    const errorElement = document.getElementById('mapError');
    
    if (mapElement) mapElement.style.display = 'block';
    if (loadingElement) loadingElement.style.display = 'none';
    if (errorElement) errorElement.style.display = 'none';
}

function showMapError(message) {
    const mapElement = document.getElementById('map');
    const loadingElement = document.getElementById('mapLoading');
    const errorElement = document.getElementById('mapError');
    
    if (mapElement) mapElement.style.display = 'none';
    if (loadingElement) loadingElement.style.display = 'none';
    if (errorElement) {
        errorElement.style.display = 'flex';
        const errorText = errorElement.querySelector('p');
        if (errorText && message) {
            errorText.textContent = message;
        }
    }
}

function openInGoogleMaps() {
    const hasCoordinates = @json(($lostItem->latitude && $lostItem->longitude && $lostItem->latitude != 0 && $lostItem->longitude != 0));
    const lat = @json($lostItem->latitude);
    const lng = @json($lostItem->longitude);
    const locationName = @json($lostItem->lost_location);
    
    if (hasCoordinates && lat && lng) {
        const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=driving`;
        window.open(url, '_blank');
    } else if (locationName) {
        searchInGoogleMaps();
    }
}

function searchInGoogleMaps() {
    const locationName = @json($lostItem->lost_location);
    const lat = @json($lostItem->latitude);
    const lng = @json($lostItem->longitude);
    
    let searchQuery = locationName;
    if (lat && lng && lat != 0 && lng != 0) {
        searchQuery = `${lat},${lng}`;
    }
    
    if (searchQuery) {
        const url = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(searchQuery)}`;
        window.open(url, '_blank');
    } else {
        alert('No location information available');
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function openImageModal(imageSrc) {
    const modalImage = document.getElementById('modalImage');
    if (modalImage) {
        modalImage.src = imageSrc;
    }
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

window.addEventListener('resize', function() {
    if (map && mapInitialized) {
        setTimeout(() => {
            map.invalidateSize();
        }, 200);
    }
});
</script>
@endpush
@endsection