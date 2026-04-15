@extends('layouts.app')

@section('title', 'My Matches - Foundify')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
@endphp

<style>
/* ── NETFLIX-STYLE MY MATCHES PAGE ───────────────── */
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
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title h1 i {
    color: var(--netflix-red);
    font-size: 28px;
}

.page-title p {
    font-size: 14px;
    color: var(--netflix-text-secondary);
    margin: 0;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
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
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
    transition: var(--transition-netflix);
    position: relative;
}

.stat-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-3px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.stat-value {
    font-size: 24px;
    font-weight: 800;
    color: var(--netflix-text);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    color: var(--netflix-text-secondary);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-arrow {
    color: var(--netflix-red);
    font-size: 14px;
    opacity: 0;
    transition: var(--transition-netflix);
    margin-left: auto;
}

.stat-card:hover .stat-arrow {
    opacity: 1;
    transform: translateX(4px);
}

/* Alert Card */
.alert-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.alert-card.info {
    border-left: 3px solid var(--netflix-info);
    background: rgba(33, 150, 243, 0.1);
}

.alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--netflix-info);
    color: white;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    font-weight: 700;
    color: var(--netflix-text);
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
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

.alert-action {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition-netflix);
    background: var(--netflix-info);
    color: white;
}

.alert-action:hover {
    background: #1976d2;
    transform: scale(1.02);
}

/* Filter Card */
.filter-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 32px;
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
    color: var(--netflix-text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 1px;
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
    padding: 10px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    color: var(--netflix-text);
    font-size: 14px;
    transition: var(--transition-netflix);
}

body.light .filter-select,
body.light .filter-input {
    background: rgba(0, 0, 0, 0.02);
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

.btn-secondary {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.02);
}

body.light .btn-secondary {
    background: rgba(0, 0, 0, 0.02);
}

body.light .btn-secondary:hover {
    background: rgba(0, 0, 0, 0.05);
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

.match-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition-netflix);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.match-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-4px);
}

.match-header {
    padding: 14px 20px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
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
    color: var(--netflix-text-secondary);
    margin: 0;
    letter-spacing: 1px;
}

.match-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Badges */
.badge {
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge.score-high {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.badge.score-medium {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.badge.score-low {
    background: rgba(33, 150, 243, 0.2);
    color: var(--netflix-info);
}

.badge.status-pending {
    background: rgba(245, 197, 24, 0.2);
    color: var(--netflix-warning);
}

.badge.status-confirmed {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
}

.badge.status-rejected {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.match-body {
    padding: 20px;
    flex: 1;
}

/* Items Comparison */
.items-comparison {
    display: flex;
    align-items: stretch;
    gap: 16px;
    margin-bottom: 20px;
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
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition-netflix);
}

.item-side.lost:hover {
    border-color: var(--netflix-red);
}

.item-side.found:hover {
    border-color: var(--netflix-success);
}

.item-header {
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.item-side.lost .item-header {
    background: rgba(229, 9, 20, 0.15);
    color: var(--netflix-red);
}

.item-side.found .item-header {
    background: rgba(46, 125, 50, 0.15);
    color: var(--netflix-success);
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
    color: var(--netflix-text);
    margin: 0 0 8px 0;
}

.item-desc {
    font-size: 12px;
    color: var(--netflix-text-secondary);
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
    color: var(--netflix-text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
}

.item-meta i {
    font-size: 10px;
    width: 14px;
}

.item-side.lost .item-meta i {
    color: var(--netflix-red);
}

.item-side.found .item-meta i {
    color: var(--netflix-success);
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
    background: var(--netflix-card);
    border: 2px solid var(--netflix-red);
    color: var(--netflix-red);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
}

/* Contact Preview */
.contact-preview {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    overflow: hidden;
    margin-top: 16px;
}

.contact-preview-header {
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    color: var(--netflix-red);
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px solid var(--netflix-border);
    text-transform: uppercase;
    letter-spacing: 1px;
    background: rgba(229, 9, 20, 0.1);
}

.contact-preview-body {
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: var(--netflix-text-secondary);
}

.contact-item i {
    width: 16px;
    color: var(--netflix-red);
}

/* Match Footer */
.match-footer {
    padding: 14px 20px;
    background: var(--netflix-dark);
    border-top: 1px solid var(--netflix-border);
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
    color: var(--netflix-text-secondary);
}

.match-time i {
    color: var(--netflix-red);
}

.match-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-view,
.btn-contact {
    font-size: 11px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    transition: var(--transition-netflix);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid;
    cursor: pointer;
    background: transparent;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-view {
    border-color: rgba(229, 9, 20, 0.3);
    color: var(--netflix-red);
}

.btn-view:hover {
    background: var(--netflix-red);
    color: white;
    border-color: var(--netflix-red);
    transform: scale(1.02);
}

.btn-contact {
    border-color: rgba(33, 150, 243, 0.3);
    color: var(--netflix-info);
}

.btn-contact:hover {
    background: var(--netflix-info);
    color: white;
    border-color: var(--netflix-info);
    transform: scale(1.02);
}

.success-badge {
    background: rgba(46, 125, 50, 0.2);
    color: var(--netflix-success);
    font-size: 11px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(46, 125, 50, 0.2);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 30px;
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
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
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    color: var(--netflix-text-secondary);
    border-radius: 4px;
    text-decoration: none;
    transition: var(--transition-netflix);
    font-size: 13px;
}

.page-link:hover {
    border-color: var(--netflix-red);
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
}

.page-item.active .page-link {
    background: var(--netflix-red);
    border-color: var(--netflix-red);
    color: white;
}

/* Modal Styles */
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

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 24px;
}

.contact-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
}

.contact-detail-item i {
    font-size: 18px;
    margin-top: 2px;
    color: var(--netflix-red);
}

.contact-detail-item strong {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: var(--netflix-text);
    margin-bottom: 4px;
}

.contact-detail-item p {
    font-size: 13px;
    color: var(--netflix-text-secondary);
    margin: 0;
}

.divider {
    border: none;
    border-top: 1px solid var(--netflix-border);
    margin: 24px 0;
}

.message-suggestion h6 {
    font-size: 13px;
    font-weight: 700;
    color: var(--netflix-text);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.message-suggestion h6 i {
    color: var(--netflix-red);
}

.suggestion-box {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
    font-size: 13px;
    color: var(--netflix-text-secondary);
    line-height: 1.6;
}

.btn-copy-suggestion {
    width: 100%;
    padding: 10px;
    background: transparent;
    border: 1px solid var(--netflix-border);
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    color: var(--netflix-red);
    cursor: pointer;
    transition: var(--transition-netflix);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-copy-suggestion:hover {
    border-color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.1);
    transform: scale(1.02);
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
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 8px;
    margin-bottom: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: slideInRight 0.3s ease;
}

.toast-body {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    font-size: 13px;
    color: var(--netflix-text);
}

.toast-body i {
    font-size: 16px;
}

.toast-close {
    background: transparent;
    border: none;
    color: var(--netflix-text-secondary);
    cursor: pointer;
    padding: 4px;
    font-size: 18px;
    transition: var(--transition-netflix);
}

.toast-close:hover {
    color: var(--netflix-red);
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
        padding: 16px;
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
    .btn-contact {
        width: 100%;
        justify-content: center;
    }
    
    .empty-actions {
        flex-direction: column;
    }
    
    .empty-actions .btn {
        width: 100%;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-footer button,
    .modal-footer a {
        width: 100%;
    }
}

.d-inline {
    display: inline;
}
</style>

<div class="dashboard-container">
    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1>
                <i class="fas fa-handshake"></i>
                My Matches
            </h1>
            <p>View all matches related to your lost and found items</p>
        </div>
    </div>

    {{-- Active Filter Indicators --}}
    @if(request('status') || request('type') || request('min_score') || request('recovered'))
    <div class="alert-card info fade-in">
        <div class="alert-icon"><i class="fas fa-filter"></i></div>
        <div class="alert-content">
            <strong>Active Filters</strong>
            <div class="filter-tags">
                @if(request('status'))<span class="filter-tag">Status: {{ strtoupper(request('status')) }}</span>@endif
                @if(request('type'))<span class="filter-tag">Type: {{ request('type') == 'lost' ? 'My Lost' : 'My Found' }}</span>@endif
                @if(request('min_score'))<span class="filter-tag">Min Score: {{ request('min_score') }}%</span>@endif
                @if(request('recovered'))<span class="filter-tag">Recovered Items</span>@endif
            </div>
        </div>
        <a href="{{ route('matches.my-matches') }}" class="alert-action"><i class="fas fa-times"></i> Clear All</a>
    </div>
    @endif

    {{-- Filter Section --}}
    <div class="filter-card fade-in">
        <form method="GET" action="{{ route('matches.my-matches') }}" id="filterForm">
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
                    <label class="filter-label"><i class="fas fa-tag"></i> Item Type</label>
                    <div class="select-wrapper">
                        <select class="filter-select" name="type">
                            <option value="">All Types</option>
                            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>My Lost Items</option>
                            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>My Found Items</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label"><i class="fas fa-chart-line"></i> Min Score (%)</label>
                    <input type="number" class="filter-input" name="min_score" value="{{ request('min_score') }}" min="0" max="100" placeholder="0">
                </div>

                <div class="filter-group actions-group">
                    <label class="filter-label">&nbsp;</label>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
                        <a href="{{ route('matches.my-matches') }}" class="btn btn-outline"><i class="fas fa-redo-alt"></i> Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Matches List --}}
    <div class="matches-grid fade-in">
        @forelse($matches as $match)
        @php
            $isMyLostItem = $match->lostItem && $match->lostItem->user_id == Auth::id();
            $isMyFoundItem = $match->foundItem && $match->foundItem->user_id == Auth::id();
            $otherPartyName = $isMyLostItem ? ($match->foundItem->user->name ?? 'User') : ($match->lostItem->user->name ?? 'User');
            $otherPartyEmail = $isMyLostItem ? ($match->foundItem->user->email ?? '') : ($match->lostItem->user->email ?? '');
        @endphp
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

            <div class="match-body">
                <div class="items-comparison">
                    {{-- Your Item --}}
                    <div class="item-side {{ $isMyLostItem ? 'lost' : 'found' }}">
                        <div class="item-header">
                            @if($isMyLostItem)
                                <i class="fas fa-search"></i> Your Lost
                            @else
                                <i class="fas fa-check-circle"></i> Your Found
                            @endif
                        </div>
                        <div class="item-content">
                            @if($isMyLostItem)
                                <h6>{{ $match->lostItem->item_name }}</h6>
                                <p class="item-desc">{{ Str::limit($match->lostItem->description, 60) }}</p>
                                <div class="item-meta">
                                    <span><i class="fas fa-calendar"></i> Lost: {{ $match->lostItem->date_lost->format('M d, Y') }}</span>
                                    @if($match->lostItem->lost_location)
                                    <span class="location"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->lostItem->lost_location, 20) }}</span>
                                    @endif
                                </div>
                            @else
                                <h6>{{ $match->foundItem->item_name }}</h6>
                                <p class="item-desc">{{ Str::limit($match->foundItem->description, 60) }}</p>
                                <div class="item-meta">
                                    <span><i class="fas fa-calendar"></i> Found: {{ $match->foundItem->date_found->format('M d, Y') }}</span>
                                    @if($match->foundItem->found_location)
                                    <span class="location"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($match->foundItem->found_location, 20) }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- VS Divider --}}
                    <div class="vs-divider"><span>VS</span></div>

                    {{-- Other Party's Item --}}
                    <div class="item-side {{ $isMyLostItem ? 'found' : 'lost' }}">
                        <div class="item-header">
                            @if($isMyLostItem)
                                <i class="fas fa-check-circle"></i> Found
                            @else
                                <i class="fas fa-search"></i> Lost
                            @endif
                        </div>
                        <div class="item-content">
                            @if($isMyLostItem && $match->foundItem)
                                <h6>{{ $match->foundItem->item_name }}</h6>
                                <p class="item-desc">{{ Str::limit($match->foundItem->description, 60) }}</p>
                                <div class="item-meta">
                                    <span><i class="fas fa-user"></i> {{ $match->foundItem->user->name }}</span>
                                    <span><i class="fas fa-calendar"></i> Found: {{ $match->foundItem->date_found->format('M d, Y') }}</span>
                                </div>
                            @elseif($isMyFoundItem && $match->lostItem)
                                <h6>{{ $match->lostItem->item_name }}</h6>
                                <p class="item-desc">{{ Str::limit($match->lostItem->description, 60) }}</p>
                                <div class="item-meta">
                                    <span><i class="fas fa-user"></i> {{ $match->lostItem->user->name }}</span>
                                    <span><i class="fas fa-calendar"></i> Lost: {{ $match->lostItem->date_lost->format('M d, Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact Preview for Pending Matches --}}
                @if($match->status === 'pending')
                <div class="contact-preview">
                    <div class="contact-preview-header">
                        <i class="fas fa-envelope"></i> Contact Information
                    </div>
                    <div class="contact-preview-body">
                        <div class="contact-item">
                            <i class="fas fa-user"></i>
                            <span>{{ $otherPartyName }}</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $otherPartyEmail }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="match-footer">
                <div class="match-time"><i class="fas fa-clock"></i> Matched {{ $match->created_at->diffForHumans() }}</div>
                <div class="match-actions">
                    <a href="{{ route('matches.show', $match) }}" class="btn-view"><i class="fas fa-eye"></i> Details</a>
                    @if($match->status === 'pending')
                        <button class="btn-contact" onclick="openContactModal({{ $match->id }})"><i class="fas fa-envelope"></i> Contact</button>
                    @endif
                    @if($match->status === 'confirmed')
                        <span class="success-badge"><i class="fas fa-check-circle"></i> Successfully Matched</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contact Modal --}}
        <div class="modal fade" id="contactModal{{ $match->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-envelope"></i>
                            Contact {{ $otherPartyName }}
                        </h5>
                        <button type="button" class="modal-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="contact-details">
                            <div class="contact-detail-item">
                                <i class="fas fa-user"></i>
                                <div><strong>Name</strong><p>{{ $otherPartyName }}</p></div>
                            </div>
                            <div class="contact-detail-item">
                                <i class="fas fa-envelope"></i>
                                <div><strong>Email</strong><p>{{ $otherPartyEmail }}</p></div>
                            </div>
                        </div>

                        <hr class="divider">

                        <div class="message-suggestion">
                            <h6><i class="fas fa-lightbulb"></i> Suggested Message</h6>
                            <div class="suggestion-box">
                                @if($isMyLostItem)
                                    <p>Hi {{ $match->foundItem->user->name }},</p>
                                    <p>I saw that you found a {{ $match->foundItem->item_name }} that matches my lost {{ $match->lostItem->item_name }}. Could we arrange a time to verify and potentially claim it?</p>
                                @else
                                    <p>Hi {{ $match->lostItem->user->name }},</p>
                                    <p>I found a {{ $match->foundItem->item_name }} that matches your lost {{ $match->lostItem->item_name }}. Please contact me to verify and arrange pickup.</p>
                                @endif
                            </div>
                            <button class="btn-copy-suggestion" onclick="copySuggestion(this)">
                                <i class="fas fa-copy"></i> Copy Message
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="mailto:{{ $otherPartyEmail }}" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-handshake"></i></div>
            <h5>No Matches Found</h5>
            <p>You don't have any matches for your items yet.</p>
            <p class="text-muted">Report more items to increase matching possibilities.</p>
            <div class="empty-actions">
                <a href="{{ route('lost-items.create') }}" class="btn btn-outline"><i class="fas fa-search"></i> Report Lost</a>
                <a href="{{ route('found-items.create') }}" class="btn btn-outline"><i class="fas fa-check-circle"></i> Report Found</a>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($matches->hasPages())
    <div class="pagination-wrapper fade-in">
        {{ $matches->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Notifications Container --}}
<div id="notificationsContainer"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-submit filter form on select change
const statusSelect = document.querySelector('select[name="status"]');
const typeSelect = document.querySelector('select[name="type"]');
const filterForm = document.getElementById('filterForm');

if (statusSelect && filterForm) {
    statusSelect.addEventListener('change', () => filterForm.submit());
}

if (typeSelect && filterForm) {
    typeSelect.addEventListener('change', () => filterForm.submit());
}

// Debounced input for score filter
const minScoreInput = document.querySelector('input[name="min_score"]');
let scoreTimeout;

if (minScoreInput && filterForm) {
    minScoreInput.addEventListener('input', function() {
        clearTimeout(scoreTimeout);
        scoreTimeout = setTimeout(() => filterForm.submit(), 500);
    });
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

// Open contact modal
function openContactModal(matchId) {
    const modal = new bootstrap.Modal(document.getElementById('contactModal' + matchId));
    modal.show();
}

// Copy suggestion text
function copySuggestion(button) {
    const suggestionBox = button.closest('.message-suggestion').querySelector('.suggestion-box');
    const text = suggestionBox.innerText;
    
    navigator.clipboard.writeText(text).then(() => {
        showToast('Message copied to clipboard', 'success');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    }).catch(() => {
        showToast('Failed to copy message', 'error');
    });
}

// Show toast notification
function showToast(message, type = 'info') {
    const container = document.getElementById('notificationsContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = 'toast';
    
    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
    const iconColor = type === 'success' ? 'var(--netflix-success)' : type === 'error' ? 'var(--netflix-red)' : 'var(--netflix-info)';
    
    toast.innerHTML = `
        <div class="toast-body">
            <i class="fas fa-${icon}" style="color: ${iconColor};"></i>
            <span>${message}</span>
            <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
        </div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        if (toast && toast.parentNode) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(20px)';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
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
</script>
@endpush
@endsection