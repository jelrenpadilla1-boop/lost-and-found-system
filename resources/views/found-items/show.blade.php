@extends('layouts.app')

@section('title', $foundItem->item_name . ' - Foundify')

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
    align-items: flex-start;
    margin-bottom: 28px;
    gap: 20px;
    flex-wrap: wrap;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-light);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin: 0 0 12px 0;
    letter-spacing: -0.02em;
    word-break: break-word;
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
    border-radius: 30px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.badge.status-pending {
    background: var(--warning-soft);
    color: var(--warning);
}

.badge.status-approved {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-claimed {
    background: var(--success-soft);
    color: var(--success);
}

.badge.status-returned {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge.status-disposed {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

.badge.status-rejected {
    background: var(--error-soft);
    color: var(--error);
}

.badge.time {
    background: var(--glass);
    color: var(--text-muted);
    border: 1px solid var(--border-light);
}

.badge.owner {
    background: var(--accent-soft);
    color: var(--accent);
}

.badge.admin {
    background: var(--warning-soft);
    color: var(--warning);
}

/* Page Actions */
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

.btn-success {
    background: var(--success);
    color: white;
}

.btn-success:hover {
    background: #0d9668;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

/* Alerts */
.alerts-container {
    margin-bottom: 28px;
}

.alert-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 16px;
    box-shadow: var(--shadow-sm);
    border-left: 4px solid;
}

.alert-card.error {
    border-left-color: var(--error);
    background: var(--error-soft);
}

.alert-card.warning {
    border-left-color: var(--warning);
    background: var(--warning-soft);
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-card.error .alert-icon {
    background: rgba(239, 68, 68, 0.15);
    color: var(--error);
}

.alert-card.warning .alert-icon {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning);
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.alert-content p {
    font-size: 13px;
    color: var(--text-muted);
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
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.card-header {
    padding: 18px 24px;
    background: var(--bg-soft);
    border-bottom: 1px solid var(--border-light);
}

.card-header h6 {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h6 i {
    color: var(--accent);
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
    border-radius: var(--radius-sm);
    overflow: hidden;
    aspect-ratio: 1;
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
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
    border-radius: 8px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    opacity: 0;
}

.image-wrapper:hover .image-expand {
    opacity: 1;
}

.image-expand:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: scale(1.1);
}

.no-image {
    aspect-ratio: 1;
    background: var(--bg-soft);
    border-radius: var(--radius-sm);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--border-light);
    color: var(--text-muted);
}

.no-image i {
    font-size: 48px;
    color: var(--border-light);
    margin-bottom: 12px;
}

.no-image span {
    font-size: 13px;
}

/* Info Section */
.info-group {
    margin-bottom: 20px;
}

.info-label {
    display: block;
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

.info-label i {
    color: var(--accent);
    font-size: 11px;
}

.description {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.6;
    background: var(--bg-soft);
    padding: 16px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
    margin: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 16px;
}

.info-item {
    background: var(--bg-soft);
    padding: 12px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-item-value {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dark);
    word-break: break-word;
}

.you-badge {
    color: var(--accent);
    font-size: 11px;
    margin-left: 4px;
}

/* Actions Card */
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
    border-radius: 40px;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    border: 1px solid;
}

.action-btn.success {
    border-color: var(--success-soft);
    color: var(--success);
    background: var(--success-soft);
}

.action-btn.success:hover {
    background: var(--success);
    color: white;
    border-color: var(--success);
    transform: translateY(-2px);
}

.action-btn.danger {
    border-color: var(--error-soft);
    color: var(--error);
    background: var(--error-soft);
}

.action-btn.danger:hover {
    background: var(--error);
    color: white;
    border-color: var(--error);
    transform: translateY(-2px);
}

/* Matches Card */
.matches-badge {
    background: var(--accent);
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
}

.match-item {
    background: var(--bg-soft);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-sm);
    padding: 16px;
    margin-bottom: 12px;
    transition: var(--transition);
}

.match-item:hover {
    border-color: var(--accent);
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
    border-radius: 20px;
    white-space: nowrap;
}

.score-high {
    background: var(--success-soft);
    color: var(--success);
}

.score-medium {
    background: var(--warning-soft);
    color: var(--warning);
}

.score-low {
    background: var(--info-soft);
    color: var(--info);
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
    color: var(--text-dark);
}

.your-item-badge {
    background: var(--accent-soft);
    color: var(--accent);
    font-size: 10px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
}

.match-view-link {
    color: var(--accent);
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: var(--transition);
}

.match-view-link:hover {
    color: var(--accent-light);
    transform: translateX(4px);
}

.match-description {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 10px;
    line-height: 1.5;
}

.match-footer {
    display: flex;
    gap: 16px;
    font-size: 11px;
    color: var(--text-muted);
    flex-wrap: wrap;
}

.match-footer i {
    color: var(--accent);
    margin-right: 4px;
}

.match-status {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed var(--border-light);
}

/* Contact Card */
.contact-profile {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-light);
}

.contact-avatar {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: var(--accent);
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
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.contact-role {
    font-size: 11px;
    color: var(--accent);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.you-indicator {
    font-size: 10px;
    color: var(--text-muted);
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
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-light);
    word-break: break-word;
}

.contact-info-item:last-child {
    border-bottom: none;
}

.contact-info-item i {
    color: var(--accent);
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
    background: var(--accent);
    border: none;
    border-radius: 40px;
    color: white;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.message-btn:hover {
    background: var(--accent-light);
    transform: translateY(-2px);
}

/* Map Card */
.map-container {
    width: 100%;
    height: 200px;
    background: var(--bg-soft);
    position: relative;
}

#map {
    height: 100%;
    width: 100%;
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
}

.map-footer {
    padding: 16px;
    background: var(--bg-soft);
    border-top: 1px solid var(--border-light);
}

.location-name {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 12px;
    word-break: break-word;
}

.location-name i {
    color: var(--accent);
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
    border: 1px solid var(--border-light);
    border-radius: 40px;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.directions-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
    transform: translateY(-2px);
}

/* Debug Card (Admin) */
.debug-card {
    border-color: var(--warning-soft);
}

.debug-card .card-header {
    background: var(--warning-soft);
}

.debug-list {
    list-style: none;
    padding: 0;
    margin: 0 0 12px 0;
}

.debug-list li {
    padding: 8px 0;
    border-bottom: 1px dashed var(--border-light);
    font-size: 12px;
    color: var(--text-muted);
}

.debug-list li:last-child {
    border-bottom: none;
}

.debug-list strong {
    color: var(--warning);
    margin-left: 6px;
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

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-label i {
    color: var(--accent);
}

.required {
    color: var(--error);
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    color: var(--text-dark);
    font-size: 14px;
    transition: var(--transition);
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.info-box {
    background: var(--info-soft);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
    color: var(--text-muted);
}

.info-box i {
    color: var(--info);
    font-size: 14px;
}

/* Fullscreen Image */
.fullscreen-image {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
}

/* Leaflet Customization */
.leaflet-popup-content-wrapper {
    background: var(--bg-card);
    color: var(--text-dark);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
}

.leaflet-popup-tip {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
}

.custom-marker i {
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
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
}
</style>

<div class="dashboard-container">
    {{-- Authentication Check --}}
    @if(!$isAdmin && !$isOwner && $foundItem->status === 'pending')
        <div class="access-denied fade-in">
            <div class="access-denied-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h4>Access Denied</h4>
            <p>This item is pending approval and not yet visible to the public.</p>
            <a href="{{ route('found-items.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Back to Found Items
            </a>
        </div>
    @else
        {{-- Page Header --}}
        <div class="page-header fade-in">
            <div class="page-title">
                <h1>{{ $foundItem->item_name }}</h1>
                <div class="title-meta">
                    <span class="badge status-{{ $foundItem->status }}">
                        @if($foundItem->status == 'pending')
                            <i class="fas fa-clock"></i> Pending
                        @elseif($foundItem->status == 'approved')
                            <i class="fas fa-check-circle"></i> Active
                        @elseif($foundItem->status == 'claimed')
                            <i class="fas fa-handshake"></i> Claimed
                        @elseif($foundItem->status == 'returned')
                            <i class="fas fa-home"></i> Returned
                        @elseif($foundItem->status == 'disposed')
                            <i class="fas fa-times"></i> Disposed
                        @elseif($foundItem->status == 'rejected')
                            <i class="fas fa-times-circle"></i> Rejected
                        @endif
                    </span>
                    
                    <span class="badge time">
                        <i class="fas fa-clock"></i>
                        Found {{ $foundItem->created_at->diffForHumans() }}
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
                <a href="{{ route('found-items.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
                
                @if($isAdmin && $foundItem->status === 'pending')
                    <form action="{{ route('found-items.approve', $foundItem) }}" method="POST" class="d-inline">
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
                
                @can('update', $foundItem)
                    <a href="{{ route('found-items.edit', $foundItem) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                @endcan
            </div>
        </div>

        {{-- Alerts --}}
        <div class="alerts-container fade-in">
            @if($foundItem->status === 'rejected' && $foundItem->rejection_reason && ($isAdmin || $isOwner))
                <div class="alert-card error">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Item Rejected</strong>
                        <p>{{ $foundItem->rejection_reason }}</p>
                    </div>
                </div>
            @endif

            @if($foundItem->status === 'pending' && $isOwner && !$isAdmin)
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
                                @if($foundItem->photo)
                                    <div class="image-wrapper">
                                        <img src="{{ asset('storage/' . $foundItem->photo) }}" 
                                             class="item-image" 
                                             alt="{{ $foundItem->item_name }}">
                                        <button class="image-expand" onclick="openImageModal('{{ asset('storage/' . $foundItem->photo) }}')">
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
                                    <p class="description">{{ $foundItem->description }}</p>
                                </div>

                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-item-label">Category</span>
                                        <span class="info-item-value">{{ strtoupper($foundItem->category) }}</span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <span class="info-item-label">Date Found</span>
                                        <span class="info-item-value">{{ $foundItem->date_found->format('M d, Y') }}</span>
                                    </div>
                                    
                                    @if($foundItem->found_location)
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Location</span>
                                        <span class="info-item-value">{{ $foundItem->found_location }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0)
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Coordinates</span>
                                        <span class="info-item-value">{{ number_format($foundItem->latitude, 6) }}, {{ number_format($foundItem->longitude, 6) }}</span>
                                    </div>
                                    @endif
                                    
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Found By</span>
                                        <span class="info-item-value">
                                            {{ $foundItem->user->name }}
                                            @if($foundItem->user_id === Auth::id())
                                                <span class="you-badge">(you)</span>
                                            @endif
                                        </span>
                                    </div>

                                    @if($isAdmin && $foundItem->approved_at)
                                    <div class="info-item full-width">
                                        <span class="info-item-label">Approved</span>
                                        <span class="info-item-value">
                                            {{ $foundItem->approved_at->diffForHumans() }} 
                                            by {{ $foundItem->approver->name ?? 'Admin' }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Card --}}
                @if(($foundItem->status === 'pending' && ($isAdmin || $isOwner)) || 
                    ($foundItem->status === 'approved' && $isOwner) ||
                    ($isAdmin))
                    <div class="card actions-card fade-in">
                        <div class="card-header">
                            <h6><i class="fas fa-bolt"></i> Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="actions-grid">
                                @if($foundItem->status === 'approved' && $isOwner)
                                    <button class="action-btn success" data-bs-toggle="modal" data-bs-target="#claimModal">
                                        <i class="fas fa-handshake"></i> 
                                        Mark as Claimed
                                    </button>
                                @endif

                                @can('delete', $foundItem)
                                    <form action="{{ route('found-items.destroy', $foundItem) }}" method="POST" 
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
                @if($matches->count() > 0 && ($foundItem->status === 'approved' || $isAdmin || $isOwner))
                    <div class="card matches-card fade-in">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <h6><i class="fas fa-exchange-alt"></i> Potential Matches</h6>
                                <span class="matches-badge">{{ $matches->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($matches as $match)
                                @if($match->lostItem)
                                <div class="match-item">
                                    <div class="match-header">
                                        <div class="match-score score-{{ $match->match_score >= 80 ? 'high' : ($match->match_score >= 60 ? 'medium' : 'low') }}">
                                            {{ $match->match_score }}%
                                        </div>
                                        <div class="match-info">
                                            <strong>{{ $match->lostItem->item_name }}</strong>
                                            @if($match->lostItem->user_id === Auth::id())
                                                <span class="your-item-badge">Your Item</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('matches.show', $match) }}" class="match-view-link">
                                            View <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    
                                    <p class="match-description">{{ Str::limit($match->lostItem->description, 80) }}</p>
                                    
                                    <div class="match-footer">
                                        <span><i class="fas fa-user"></i> {{ $match->lostItem->user->name }}</span>
                                        <span><i class="fas fa-calendar"></i> {{ $match->lostItem->date_lost->format('M d, Y') }}</span>
                                    </div>

                                    @if($match->status !== 'pending')
                                    <div class="match-status">
                                        <span class="badge status-{{ $match->status }}">
                                            {{ strtoupper($match->status) }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Debug Info (Admin only) --}}
                @if($isAdmin)
                <div class="card debug-card fade-in">
                    <div class="card-header">
                        <h6><i class="fas fa-bug" style="color: var(--warning);"></i> Debug Info</h6>
                    </div>
                    <div class="card-body">
                        <ul class="debug-list">
                            <li>Item Status: <strong>{{ strtoupper($foundItem->status) }}</strong></li>
                            <li>Matches Count: <strong>{{ $matches->count() }}</strong></li>
                            <li>Item ID: <strong>{{ $foundItem->id }}</strong></li>
                            <li>Category: <strong>{{ strtoupper($foundItem->category) }}</strong></li>
                        </ul>
                        <small class="text-muted">Admin only - for debugging</small>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right Column --}}
            <div class="right-column">
                {{-- Contact Card --}}
                @if($foundItem->status !== 'pending' || $isAdmin || $isOwner)
                <div class="card contact-card fade-in">
                    <div class="card-header">
                        <h6><i class="fas fa-user-circle"></i> Contact Finder</h6>
                    </div>
                    <div class="card-body">
                        <div class="contact-profile">
                            <div class="contact-avatar">
                                {{ strtoupper(substr($foundItem->user->name, 0, 1)) }}
                            </div>
                            <div class="contact-details">
                                <p class="contact-name">{{ $foundItem->user->name }}</p>
                                <small class="contact-role">
                                    {{ $foundItem->user->isAdmin() ? 'Admin' : 'Member' }}
                                    @if($foundItem->user_id === Auth::id())
                                        <span class="you-indicator">(you)</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="contact-info-list">
                            <div class="contact-info-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $foundItem->user->email }}</span>
                            </div>

                            @if($foundItem->user->latitude && $foundItem->user->longitude)
                                <div class="contact-info-item">
                                    <i class="fas fa-map-pin"></i>
                                    <span>{{ number_format($foundItem->user->latitude, 4) }}, {{ number_format($foundItem->user->longitude, 4) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if(!$isOwner && !$isAdmin)
                        <a href="{{ route('messages.start', $foundItem->user) }}" class="message-btn">
                            <i class="fas fa-comment"></i> 
                            Send Message
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Map Card --}}
                @if(($foundItem->found_location || ($foundItem->latitude && $foundItem->longitude)) && 
                    ($foundItem->status !== 'pending' || $isAdmin || $isOwner))
                <div class="card map-card fade-in">
                    <div class="card-header">
                        <h6><i class="fas fa-map"></i> Found Location</h6>
                    </div>
                    <div class="map-container" id="mapContainer">
                        <div id="map" style="height: 200px; width: 100%;"></div>
                    </div>
                    
                    <div class="map-footer" id="mapFooter">
                        @if($foundItem->found_location)
                            <div class="location-name">
                                <i class="fas fa-map-marked-alt"></i>
                                <span>{{ Str::limit($foundItem->found_location, 50) }}</span>
                            </div>
                        @endif
                        
                        <div class="map-actions">
                            @if($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0)
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $foundItem->latitude }},{{ $foundItem->longitude }}" 
                                   target="_blank" class="directions-btn">
                                    <i class="fas fa-directions"></i> 
                                    Get Directions
                                </a>
                            @elseif($foundItem->found_location)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($foundItem->found_location) }}" 
                                   target="_blank" class="directions-btn">
                                    <i class="fas fa-search"></i> 
                                    Search on Maps
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- Claim Modal --}}
@if($foundItem->status === 'approved' && $isOwner)
<div class="modal fade" id="claimModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-handshake" style="color: var(--success);"></i>
                    Mark as Claimed
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('found-items.update', $foundItem) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="claimed">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-pencil-alt"></i>
                            Claim Details <span class="optional">(Optional)</span>
                        </label>
                        <textarea class="form-control" name="claim_details" rows="3" 
                                  placeholder="Add any details about the claim..."></textarea>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <span>This will notify the finder and update the item status to "Claimed".</span>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Reject Modal --}}
@if($isAdmin && $foundItem->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle" style="color: var(--error);"></i>
                    Reject Item
                </h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('found-items.reject', $foundItem) }}" method="POST">
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
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="fullscreen-image" alt="">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @php
        $canShowMap = ($foundItem->found_location || ($foundItem->latitude && $foundItem->longitude)) && 
                     ($foundItem->status !== 'pending' || $isAdmin || $isOwner);
    @endphp
    
    @if($canShowMap)
        initMap();
    @endif
});

function initMap() {
    const hasCoordinates = @json(($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0));
    const locationName = @json($foundItem->found_location);
    
    if (hasCoordinates) {
        const lat = @json($foundItem->latitude);
        const lng = @json($foundItem->longitude);
        displayMap(lat, lng, locationName);
    } else if (locationName) {
        geocodeLocation(locationName);
    }
}

function geocodeLocation(location) {
    const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}&limit=1`;
    
    fetch(geocodeUrl, {
        headers: { 'User-Agent': 'Foundify-App/1.0' }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.length > 0) {
            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            displayMap(lat, lng, location);
        } else {
            showGeocodingFallback();
        }
    })
    .catch(error => {
        console.error('Geocoding failed:', error);
        showGeocodingFallback();
    });
}

function displayMap(lat, lng, locationName) {
    const map = L.map('map').setView([lat, lng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    const markerIcon = L.divIcon({
        className: 'custom-marker',
        html: '<i class="fas fa-map-marker-alt" style="color: var(--accent); font-size: 28px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>',
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -28]
    });
    
    const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
    
    let popupContent = `<strong style="color: var(--accent);">${locationName || 'Found Item Location'}</strong>`;
    
    const hasExactCoordinates = @json(($foundItem->latitude && $foundItem->longitude && $foundItem->latitude != 0 && $foundItem->longitude != 0));
    if (!hasExactCoordinates) {
        popupContent += '<br><small style="color: var(--text-muted);">Approximate location</small>';
    }
    
    marker.bindPopup(popupContent).openPopup();
}

function showGeocodingFallback() {
    const mapContainer = document.getElementById('mapContainer');
    const footer = document.getElementById('mapFooter');
    
    if (mapContainer) {
        mapContainer.innerHTML = `
            <div style="height: 200px; display: flex; align-items: center; justify-content: center; background: var(--bg-soft); flex-direction: column; padding: 20px;">
                <i class="fas fa-map-marked-alt fa-3x mb-3" style="color: var(--border-light);"></i>
                <p style="color: var(--text-muted); text-align: center; margin-bottom: 5px;">{{ $foundItem->found_location ?? 'Location provided' }}</p>
                <p class="text-muted small text-center">Could not pinpoint exact location</p>
            </div>
        `;
    }
}

function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}
</script>
@endpush
@endsection