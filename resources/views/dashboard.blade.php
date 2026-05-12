@extends('layouts.app')

@section('title', 'Foundify - Dashboard')

@section('content')
@php
    $isAdmin = Auth::user()->isAdmin();
    $user = Auth::user();
@endphp

<style>
/* ── NETFLIX-STYLE DASHBOARD WITH LIGHT/DARK MODE ───────────────── */
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
    --netflix-danger: #e50914;
    --transition-netflix: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

body.light {
    --netflix-black: #f5f5f5;
    --netflix-dark: #ffffff;
    --netflix-card: #ffffff;
    --netflix-card-hover: #f8f8f8;
    --netflix-text: #1a1a1a;
    --netflix-text-secondary: #666666;
    --netflix-border: #e0e0e0;
}

body {
    background: var(--netflix-black);
    color: var(--netflix-text);
    transition: background-color 0.3s ease, color 0.3s ease;
}

.dashboard-container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px 40px;
    background: var(--netflix-black);
}

/* Welcome Header */
.welcome-header {
    margin-bottom: 32px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.welcome-content {
    min-width: 0;
}

.welcome-content h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--netflix-text);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 8px;
}

.welcome-content h1 i { color: var(--netflix-red); font-size: 1.8rem; }

.admin-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: var(--netflix-red);
    background: rgba(229, 9, 20, 0.15);
    border-radius: 4px;
    padding: 4px 12px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.welcome-content p { font-size: 1rem; color: var(--netflix-text-secondary); }
.welcome-content p span { color: var(--netflix-red); font-weight: 600; }

.header-actions { display: flex; gap: 12px; flex-wrap: wrap; }

/* Buttons */
.btn {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: var(--transition-netflix);
    cursor: pointer;
    border: none;
}

.btn-primary { background: var(--netflix-red); color: white; }
.btn-primary:hover { background: var(--netflix-red-dark); transform: scale(1.02); }
.btn-outline { background: rgba(255,255,255,0.1); border: 1px solid var(--netflix-border); color: var(--netflix-text); }
.btn-outline:hover { background: rgba(255,255,255,0.2); transform: scale(1.02); }
body.light .btn-outline { background: rgba(0,0,0,0.05); }
body.light .btn-outline:hover { background: rgba(0,0,0,0.1); }

/* ══════════════════════════════════════════════
   STATS GRID — 3 COLUMNS, 2 ROWS
   Top row: big cards | Bottom row: smaller cards
══════════════════════════════════════════════ */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 32px;
}

/* ── TOP ROW: Big Cards ── */
.stat-card-big {
    background: var(--netflix-card);
    border-radius: 14px;
    padding: 28px 24px 24px;
    text-decoration: none;
    border: 1px solid var(--netflix-border);
    border-top: 4px solid transparent;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: var(--transition-netflix);
    min-width: 0;
}

.stat-card-big:hover {
    transform: translateY(-4px);
    border-color: var(--netflix-red);
    border-top-color: var(--netflix-red) !important;
    background: var(--netflix-card-hover);
    box-shadow: 0 10px 28px rgba(229, 9, 20, 0.14);
}

/* ── BOTTOM ROW: Small Cards ── */
.stat-card-small {
    background: var(--netflix-card);
    border-radius: 10px;
    padding: 16px 18px;
    text-decoration: none;
    border: 1px solid var(--netflix-border);
    border-top: 3px solid transparent;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: var(--transition-netflix);
    min-width: 0;
}

.stat-card-small:hover {
    transform: translateY(-3px);
    border-color: var(--netflix-red);
    border-top-color: var(--netflix-red) !important;
    background: var(--netflix-card-hover);
    box-shadow: 0 6px 18px rgba(229, 9, 20, 0.12);
}

/* Accent top borders */
.accent-red    { border-top-color: var(--netflix-red) !important; }
.accent-green  { border-top-color: var(--netflix-success) !important; }
.accent-amber  { border-top-color: var(--netflix-warning) !important; }
.accent-blue   { border-top-color: var(--netflix-info) !important; }

/* Top row layout inside big card */
.stat-top-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Icons — big */
.stat-icon-big {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
}

/* Icons — small */
.stat-icon-small {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.icon-red    { background: rgba(229,9,20,0.12);   color: var(--netflix-red); }
.icon-green  { background: rgba(46,125,50,0.12);   color: var(--netflix-success); }
.icon-amber  { background: rgba(245,197,24,0.12);  color: var(--netflix-warning); }
.icon-blue   { background: rgba(33,150,243,0.12);  color: var(--netflix-info); }

body.light .icon-red    { background: rgba(229,9,20,0.08); }
body.light .icon-green  { background: rgba(46,125,50,0.08); }
body.light .icon-amber  { background: rgba(245,197,24,0.08); }
body.light .icon-blue   { background: rgba(33,150,243,0.08); }

/* Divider inside big card */
.stat-sep {
    height: 1px;
    background: var(--netflix-border);
}

/* Big card value & label */
.stat-value-big {
    font-size: 3rem;
    font-weight: 800;
    color: var(--netflix-text);
    line-height: 1;
    letter-spacing: 0;
    margin-bottom: 4px;
    overflow-wrap: anywhere;
}

.stat-label-big {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

/* Small card value & label */
.stat-value-small {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--netflix-text);
    line-height: 1;
    letter-spacing: 0;
    overflow-wrap: anywhere;
}

.stat-label-small {
    font-size: 0.68rem;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-top: 3px;
}

/* Trend badge */
.stat-trend {
    font-size: 0.65rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 9px;
    border-radius: 5px;
}

.trend-positive { background: rgba(46,125,50,0.2);  color: var(--netflix-success); }
.trend-warning  { background: rgba(245,197,24,0.2); color: var(--netflix-warning); }

/* Responsive stats */
@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 14px; }
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .stat-value-big   { font-size: 2.2rem; }
    .stat-icon-big    { width: 46px; height: 46px; font-size: 1.3rem; }
    .stat-card-big    { padding: 20px 18px 18px; }
    .stat-value-small { font-size: 1.3rem; }
    .stat-icon-small  { width: 32px; height: 32px; font-size: 0.95rem; }
    .stat-card-small  { padding: 14px; gap: 10px; }
}

@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr; }
}
/* ══════════════════════════════════════════════
   END STATS GRID
══════════════════════════════════════════════ */

/* ── SIDE-BY-SIDE TABLES ────────────────────────────────────────── */
.tables-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

.tables-row-3 {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

.table-card {
    background: var(--netflix-card);
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--netflix-border);
    min-width: 0;
}

.table-header {
    padding: 16px 20px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.table-header h5 {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.table-header h5 i { color: var(--netflix-red); }

.view-link {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition-netflix);
    white-space: nowrap;
}

.view-link:hover { color: var(--netflix-red); }

.table-responsive { overflow-x: auto; }

.data-table { width: 100%; min-width: 620px; border-collapse: collapse; }

.data-table th {
    text-align: left;
    padding: 12px 16px;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: var(--netflix-dark);
    border-bottom: 1px solid var(--netflix-border);
}

.data-table td {
    padding: 12px 16px;
    font-size: 0.85rem;
    color: var(--netflix-text-secondary);
    border-bottom: 1px solid var(--netflix-border);
}

.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: rgba(229,9,20,0.05); }

.item-link {
    color: var(--netflix-text);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-netflix);
}

.item-link:hover { color: var(--netflix-red); }

/* User Avatar */
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--netflix-red);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 12px;
    margin-right: 8px;
}

.user-info { display: flex; align-items: center; }
.user-details { display: flex; flex-direction: column; min-width: 0; }
.user-name { color: var(--netflix-text); font-weight: 500; font-size: 13px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.user-email { font-size: 11px; color: var(--netflix-text-secondary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.badge-high      { background: rgba(46,125,50,0.2);   color: var(--netflix-success); }
.badge-medium    { background: rgba(245,197,24,0.2);   color: var(--netflix-warning); }
.badge-pending   { background: rgba(245,197,24,0.2);   color: var(--netflix-warning); }
.badge-confirmed,
.badge-approved,
.badge-claimed,
.badge-returned,
.badge-recovered { background: rgba(46,125,50,0.2);   color: var(--netflix-success); }
.badge-lost      { background: rgba(229,9,20,0.15);   color: var(--netflix-red); }
.badge-found-type{ background: rgba(46,125,50,0.15);  color: var(--netflix-success); }
.badge-active    { background: rgba(33,150,243,0.15);  color: var(--netflix-info); }
.badge-admin     { background: rgba(229,9,20,0.2); color: var(--netflix-red); border: 1px solid rgba(229,9,20,0.3); }
.badge-user      { background: rgba(255,255,255,0.1); color: var(--netflix-text-secondary); }

/* Action Buttons */
.action-buttons { display: flex; gap: 8px; }

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.05);
    color: var(--netflix-text-secondary);
    cursor: pointer;
    transition: var(--transition-netflix);
    text-decoration: none;
    border: none;
}

.action-btn:hover { transform: scale(1.05); }
.action-btn.view:hover    { background: var(--netflix-red);    color: white; }
.action-btn.approve:hover { background: var(--netflix-success); color: white; }
.action-btn.reject:hover  { background: var(--netflix-red);    color: white; }
body.light .action-btn { background: rgba(0,0,0,0.05); }

/* Profile Card */
.profile-section {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    margin-bottom: 24px;
    align-items: start;
    min-width: 0;
}

.user-dashboard-overview {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 24px;
    margin-bottom: 24px;
    align-items: stretch;
    min-width: 0;
}

.user-dashboard-overview .profile-card {
    min-height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.user-dashboard-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.user-stat-card {
    min-height: 156px;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid var(--netflix-border);
    border-left: 4px solid var(--netflix-red);
    background: linear-gradient(135deg, var(--netflix-card) 0%, var(--netflix-dark) 100%);
    color: var(--netflix-text);
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    transition: var(--transition-netflix);
    min-width: 0;
}

.user-stat-card:hover {
    transform: translateY(-4px);
    border-color: var(--netflix-red);
    box-shadow: 0 10px 28px rgba(229, 9, 20, 0.14);
    background: var(--netflix-card-hover);
}

.user-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 96px;
    height: 100%;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.04) 100%);
    pointer-events: none;
}

.user-stat-lost { border-left-color: var(--netflix-red); }
.user-stat-found { border-left-color: var(--netflix-success); }
.user-stat-matches { border-left-color: var(--netflix-info); }
.user-stat-recovered { border-left-color: var(--netflix-warning); }

.user-stat-card:hover.user-stat-lost { border-color: var(--netflix-red); }
.user-stat-card:hover.user-stat-found { border-color: var(--netflix-success); }
.user-stat-card:hover.user-stat-matches { border-color: var(--netflix-info); }
.user-stat-card:hover.user-stat-recovered { border-color: var(--netflix-warning); }

.user-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    position: relative;
    z-index: 1;
}

.user-stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.user-stat-kicker {
    font-size: 0.66rem;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    text-align: right;
}

.user-stat-main {
    position: relative;
    z-index: 1;
}

.user-stat-value {
    font-size: 2.6rem;
    font-weight: 900;
    color: var(--netflix-text);
    line-height: 1;
    margin-bottom: 8px;
    overflow-wrap: anywhere;
}

.user-stat-label {
    font-size: 0.86rem;
    font-weight: 700;
    color: var(--netflix-text);
}

.user-stat-footer {
    font-size: 0.74rem;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 1;
    gap: 10px;
}

.user-stat-footer span {
    min-width: 0;
    overflow-wrap: anywhere;
}

.user-stat-footer i {
    color: currentColor;
    font-size: 0.68rem;
}

.user-matches-card {
    margin-bottom: 24px;
}

.profile-card {
    background: linear-gradient(135deg, var(--netflix-card) 0%, var(--netflix-dark) 100%);
    border-radius: 12px;
    padding: 28px;
    text-align: center;
    border: 1px solid var(--netflix-border);
    transition: var(--transition-netflix);
}

.profile-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-4px);
}

.profile-avatar-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 16px;
    border: 2px solid var(--netflix-red);
    display: block;
    transition: var(--transition-netflix);
}

.profile-card:hover .profile-avatar-image {
    border-color: var(--netflix-red-dark);
    transform: scale(1.05);
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--netflix-red);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 2rem;
    font-weight: 700;
    color: white;
    transition: var(--transition-netflix);
}

.profile-card:hover .profile-avatar {
    background: var(--netflix-red-dark);
    transform: scale(1.05);
}

.profile-card h5 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 6px;
    color: var(--netflix-text);
}

.member-badge {
    font-size: 0.7rem;
    color: var(--netflix-text-secondary);
    background: rgba(255,255,255,0.05);
    padding: 4px 12px;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 20px;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid var(--netflix-border);
}

.profile-stat-item { text-align: center; }

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--netflix-red);
    line-height: 1;
    margin-bottom: 4px;
}

.profile-stat-item .stat-label {
    font-size: 0.65rem;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Items List */
.items-list { padding: 8px; }

.item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    text-decoration: none;
    border-radius: 6px;
    transition: var(--transition-netflix);
    margin: 4px 0;
    cursor: pointer;
}

.item-row:hover { background: rgba(229,9,20,0.05); }

.item-info { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; }

.item-icon {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.item-icon.lost  { background: rgba(229,9,20,0.15);  color: var(--netflix-red); }
.item-icon.found { background: rgba(46,125,50,0.15);  color: var(--netflix-success); }

.item-details h6 {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--netflix-text);
    overflow-wrap: anywhere;
}

.item-date {
    display: block;
    font-size: 0.7rem;
    color: var(--netflix-text-secondary);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.empty-state-item { text-align: center; padding: 40px; color: var(--netflix-text-secondary); }
.empty-state-item i { font-size: 2rem; opacity: 0.3; margin-bottom: 12px; display: block; }

/* Quick Actions */
.quick-actions {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--netflix-border);
}

.quick-actions-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.quick-actions-label i { color: var(--netflix-red); }

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
}

.quick-action-card {
    background: var(--netflix-card);
    border: 1px solid var(--netflix-border);
    border-radius: 12px;
    padding: 24px 20px;
    text-align: center;
    text-decoration: none;
    transition: var(--transition-netflix);
}

.quick-action-card:hover {
    border-color: var(--netflix-red);
    transform: translateY(-4px);
    background: var(--netflix-card-hover);
}

.quick-action-card i {
    font-size: 1.8rem;
    margin-bottom: 12px;
    display: block;
}

.quick-action-card span {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--netflix-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px;
    color: var(--netflix-text-secondary);
}

.empty-state i { font-size: 2.5rem; opacity: 0.3; margin-bottom: 12px; display: block; }

.d-inline { display: inline; }

/* Responsive adjustments */
@media (max-width: 1024px) {
    .dashboard-container { padding: 20px; }
    .tables-row { grid-template-columns: 1fr; }
    .profile-section { grid-template-columns: 1fr; }
    .user-dashboard-overview { grid-template-columns: 1fr; }
    .user-dashboard-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .profile-card { padding: 24px; }
}

@media (max-width: 768px) {
    .dashboard-container { padding: 16px; }
    .actions-grid { grid-template-columns: 1fr 1fr; }
    .welcome-header { flex-direction: column; align-items: stretch; }
    .header-actions { width: 100%; }
    .header-actions .btn { flex: 1 1 170px; }
    .table-header { align-items: flex-start; flex-direction: column; }
    .data-table th, .data-table td { padding: 10px 12px; font-size: 0.75rem; }
    .user-dashboard-stats { gap: 12px; }
    .user-stat-card { min-height: 144px; padding: 18px; }
    .user-stat-value { font-size: 2.2rem; }
    .items-list { padding: 6px; }
}

@media (max-width: 576px) {
    .dashboard-container { padding: 12px; }
    .actions-grid { grid-template-columns: 1fr; }
    .user-dashboard-stats { grid-template-columns: 1fr; }
    .welcome-content h1 { font-size: 1.5rem; }
    .header-actions { flex-direction: column; }
    .header-actions .btn { width: 100%; }
    .profile-stats { gap: 8px; }
    .stat-number { font-size: 1.2rem; }
    .profile-card { padding: 20px 16px; }
    .profile-stats { grid-template-columns: 1fr; }
    .item-row { align-items: flex-start; flex-direction: column; gap: 10px; }
    .item-row > .badge { align-self: flex-start; }
    .item-info { width: 100%; align-items: flex-start; }
    .item-date { white-space: normal; }
    .empty-state { padding: 32px 16px; }
    .empty-state-item { padding: 32px 16px; }
    .quick-action-card { padding: 20px 16px; }
    .data-table { min-width: 560px; }
}

@media (max-width: 380px) {
    .welcome-content h1 { font-size: 1.35rem; }
    .stat-card-big,
    .stat-card-small,
    .user-stat-card { padding: 16px; }
    .stat-value-big { font-size: 2rem; }
    .user-stat-value { font-size: 2rem; }
    .profile-avatar,
    .profile-avatar-image { width: 68px; height: 68px; }
}

/* Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: var(--netflix-dark); }
::-webkit-scrollbar-thumb { background: var(--netflix-red); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--netflix-red-dark); }
</style>

<div class="dashboard-container">

    {{-- ── WELCOME HEADER ─────────────────────────────────────────── --}}
    <div class="welcome-header">
        <div class="welcome-content">
            <h1>
                <i class="fas fa-{{ $isAdmin ? 'crown' : 'home' }}"></i>
                {{ $isAdmin ? 'Admin Dashboard' : 'My Dashboard' }}
                @if($isAdmin)
                    <span class="admin-tag"><i class="fas fa-shield-alt"></i> ADMIN</span>
                @endif
            </h1>
            <p>Welcome back, <span>{{ $user->name }}</span></p>
        </div>
        @if(!$isAdmin)
        <div class="header-actions">
            <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Report Lost
            </a>
            <a href="{{ route('found-items.create') }}" class="btn btn-outline">
                <i class="fas fa-check"></i> Report Found
            </a>
        </div>
        @endif
    </div>

    @if($isAdmin)
    {{-- ══════════════════════════════════════════════════════════════
         ADMIN VIEW
    ══════════════════════════════════════════════════════════════ --}}

    {{-- Stats Grid: top 3 big, bottom 3 small --}}
    <div class="stats-grid">

        {{-- ── TOP ROW: Big Cards ── --}}

        {{-- Total Users --}}
        <a href="{{ route('admin.users.index') }}" class="stat-card-big accent-red">
            <div class="stat-top-row">
                <div class="stat-icon-big icon-red"><i class="fas fa-users"></i></div>
                @if(isset($newUsersCount) && $newUsersCount > 0)
                    <div class="stat-trend trend-positive">
                        <i class="fas fa-arrow-up"></i> +{{ $newUsersCount }} this week
                    </div>
                @endif
            </div>
            <div class="stat-sep"></div>
            <div>
                <div class="stat-value-big">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label-big">Total Users</div>
            </div>
        </a>

        {{-- Total Lost Items --}}
        <a href="{{ route('lost-items.index') }}" class="stat-card-big accent-amber">
            <div class="stat-top-row">
                <div class="stat-icon-big icon-amber"><i class="fas fa-search"></i></div>
            </div>
            <div class="stat-sep"></div>
            <div>
                <div class="stat-value-big">{{ $totalLostItems ?? 0 }}</div>
                <div class="stat-label-big">Total Lost</div>
            </div>
        </a>

        {{-- Total Found Items --}}
        <a href="{{ route('found-items.index') }}" class="stat-card-big accent-green">
            <div class="stat-top-row">
                <div class="stat-icon-big icon-green"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-sep"></div>
            <div>
                <div class="stat-value-big">{{ $totalFoundItems ?? 0 }}</div>
                <div class="stat-label-big">Total Found</div>
            </div>
        </a>

        {{-- ── BOTTOM ROW: Small Cards ── --}}

        {{-- Total Matches --}}
        <a href="{{ route('matches.index') }}" class="stat-card-small accent-blue">
            <div class="stat-icon-small icon-blue"><i class="fas fa-exchange-alt"></i></div>
            <div>
                <div class="stat-value-small">{{ $stats['total_matches'] ?? 0 }}</div>
                <div class="stat-label-small">Total Matches</div>
            </div>
        </a>

        {{-- Successful Matches --}}
        <a href="{{ route('matches.index', ['status' => 'confirmed']) }}" class="stat-card-small accent-green">
            <div class="stat-icon-small icon-green"><i class="fas fa-handshake"></i></div>
            <div>
                <div class="stat-value-small">{{ $stats['confirmed_matches'] ?? 0 }}</div>
                <div class="stat-label-small">Successful</div>
            </div>
        </a>

        {{-- Pending Matches --}}
        <a href="{{ route('matches.index', ['status' => 'pending']) }}" class="stat-card-small accent-amber">
            <div class="stat-icon-small icon-amber"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-value-small">{{ $detailedStats['matches']['pending'] ?? 0 }}</div>
                <div class="stat-label-small">Pending</div>
            </div>
        </a>

    </div>

    {{-- Pending Lost & Found Items — Side by Side --}}
    <div class="tables-row">
        <div class="table-card">
            <div class="table-header">
                <h5><i class="fas fa-search"></i> Lost Items — Pending</h5>
                <a href="{{ route('lost-items.index', ['status' => 'pending']) }}" class="view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Reported By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingLost ?? [] as $item)
                        <tr>
                            <td><a href="{{ route('lost-items.show', $item) }}" class="item-link">{{ $item->item_name }}</a></td>
                            <td>{{ $item->user->name ?? 'Unknown' }}</td>
                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('lost-items.show', $item) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('lost-items.approve', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn approve" onclick="return confirm('Approve this item?')"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form action="{{ route('lost-items.reject', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn reject" onclick="return confirm('Reject this item?')"><i class="fas fa-times"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="empty-state"><i class="fas fa-inbox"></i> No pending lost items</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h5><i class="fas fa-check-circle"></i> Found Items — Pending</h5>
                <a href="{{ route('found-items.index', ['status' => 'pending']) }}" class="view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Reported By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingFound ?? [] as $item)
                        <tr>
                            <td><a href="{{ route('found-items.show', $item) }}" class="item-link">{{ $item->item_name }}</a></td>
                            <td>{{ $item->user->name ?? 'Unknown' }}</td>
                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('found-items.show', $item) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('found-items.approve', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn approve" onclick="return confirm('Approve this item?')"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form action="{{ route('found-items.reject', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn reject" onclick="return confirm('Reject this item?')"><i class="fas fa-times"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="empty-state"><i class="fas fa-inbox"></i> No pending found items</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Users & Pending Matches — Side by Side --}}
    <div class="tables-row">
        <div class="table-card">
            <div class="table-header">
                <h5><i class="fas fa-user-plus"></i> Recent Users</h5>
                <a href="{{ route('admin.users.index') }}" class="view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $recentUser)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($recentUser->name, 0, 1)) }}
                                    </div>
                                    <div class="user-details">
                                        <span class="user-name">{{ $recentUser->name }}</span>
                                        <span class="user-email">{{ $recentUser->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $recentUser->isAdmin() ? 'badge-admin' : 'badge-user' }}">
                                    {{ $recentUser->isAdmin() ? 'ADMIN' : 'USER' }}
                                </span>
                            </td>
                            <td>{{ $recentUser->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.show', $recentUser) }}" class="action-btn view" title="View User">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $recentUser) }}" class="action-btn approve" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-users"></i> No recent users
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h5><i class="fas fa-exchange-alt"></i> Matches — Pending</h5>
                <a href="{{ route('matches.index', ['status' => 'pending']) }}" class="view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Lost Item</th>
                            <th>Found Item</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingMatches ?? [] as $match)
                        <tr>
                            <td><a href="{{ route('lost-items.show', $match->lostItem) }}" class="item-link">{{ $match->lostItem->item_name ?? '—' }}</a></td>
                            <td><a href="{{ route('found-items.show', $match->foundItem) }}" class="item-link">{{ $match->foundItem->item_name ?? '—' }}</a></td>
                            <td><span class="badge {{ $match->match_score >= 80 ? 'badge-high' : 'badge-medium' }}">{{ $match->match_score }}%</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('matches.show', $match) }}" class="action-btn view"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('matches.confirm', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn approve" onclick="return confirm('Confirm this match?')"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form action="{{ route('matches.reject', $match) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn reject" onclick="return confirm('Reject this match?')"><i class="fas fa-times"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-state"><i class="fas fa-inbox"></i> No pending matches</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @else
    {{-- ══════════════════════════════════════════════════════════════
         USER VIEW
    ══════════════════════════════════════════════════════════════ --}}

    {{-- Stats Grid for regular user --}}
    <div class="stats-grid user-dashboard-stats">

        {{-- ── TOP ROW: Big Cards ── --}}
        <a href="{{ route('lost-items.my-items') }}" class="user-stat-card user-stat-lost">
            <div class="user-stat-top">
                <div class="user-stat-icon icon-red"><i class="fas fa-search"></i></div>
                <div class="user-stat-kicker">Reported</div>
            </div>
            <div class="user-stat-main">
                <div class="user-stat-value">{{ $userStats['lost_items'] }}</div>
                <div class="user-stat-label">Lost Items</div>
            </div>
            <div class="user-stat-footer">
                <span>View my lost reports</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('found-items.my-items') }}" class="user-stat-card user-stat-found">
            <div class="user-stat-top">
                <div class="user-stat-icon icon-green"><i class="fas fa-check-circle"></i></div>
                <div class="user-stat-kicker">Submitted</div>
            </div>
            <div class="user-stat-main">
                <div class="user-stat-value">{{ $userStats['found_items'] }}</div>
                <div class="user-stat-label">Found Items</div>
            </div>
            <div class="user-stat-footer">
                <span>View my found reports</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        {{-- ── BOTTOM ROW: Small Cards ── --}}
        <a href="{{ route('matches.my-matches', ['min_score' => 60]) }}" class="user-stat-card user-stat-matches">
            <div class="user-stat-top">
                <div class="user-stat-icon icon-blue"><i class="fas fa-exchange-alt"></i></div>
                <div class="user-stat-kicker">60%+</div>
            </div>
            <div class="user-stat-main">
                <div class="user-stat-value">{{ $userStats['potential_matches'] }}</div>
                <div class="user-stat-label">Potential Matches</div>
            </div>
            <div class="user-stat-footer">
                <span>Review possible matches</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('matches.my-matches', ['recovered' => 'true']) }}" class="user-stat-card user-stat-recovered">
            <div class="user-stat-top">
                <div class="user-stat-icon icon-amber"><i class="fas fa-trophy"></i></div>
                <div class="user-stat-kicker">Completed</div>
            </div>
            <div class="user-stat-main">
                <div class="user-stat-value">{{ $userStats['recovered_items'] }}</div>
                <div class="user-stat-label">Recovered</div>
            </div>
            <div class="user-stat-footer">
                <span>View recovered items</span>
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

    </div>

    {{-- Profile + Matches Overview --}}
    <div class="profile-section user-dashboard-overview">
        <div class="profile-card">
            @if($user->profile_photo && file_exists(public_path('storage/' . $user->profile_photo)))
                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                     alt="{{ $user->name }}"
                     class="profile-avatar-image">
            @else
                <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <h5>{{ $user->name }}</h5>
            <span class="member-badge">Member since {{ $user->created_at->format('M Y') }}</span>
            <div class="profile-stats">
                <div class="profile-stat-item">
                    <div class="stat-number">{{ $userStats['total_items'] }}</div>
                    <div class="stat-label">Total Items</div>
                </div>
                <div class="profile-stat-item">
                    <div class="stat-number">{{ $userStats['matches'] }}</div>
                    <div class="stat-label">Matches</div>
                </div>
                <div class="profile-stat-item">
                    <div class="stat-number">{{ $userStats['recovered_items'] }}</div>
                    <div class="stat-label">Recovered</div>
                </div>
            </div>
        </div>

        <div class="table-card user-matches-card">
            <div class="table-header">
                <h5><i class="fas fa-microchip"></i> Potential Matches</h5>
                <a href="{{ route('matches.my-matches', ['min_score' => 60]) }}" class="view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($highMatches as $match)
                        <tr>
                            <td>
                                @if($match->lostItem && $match->lostItem->user_id == $user->id)
                                    <a href="{{ route('lost-items.show', $match->lostItem) }}" class="item-link">{{ $match->lostItem->item_name }}</a>
                                @elseif($match->foundItem && $match->foundItem->user_id == $user->id)
                                    <a href="{{ route('found-items.show', $match->foundItem) }}" class="item-link">{{ $match->foundItem->item_name }}</a>
                                @endif
                            </td>
                            <td>
                                @if($match->lostItem && $match->lostItem->user_id == $user->id)
                                    <span class="badge badge-lost">LOST</span>
                                @else
                                    <span class="badge badge-found-type">FOUND</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $match->match_score >= 80 ? 'badge-high' : 'badge-medium' }}">{{ $match->match_score }}%</span></td>
                            <td><span class="badge badge-{{ $match->status }}">{{ strtoupper($match->status) }}</span></td>
                            <td><a href="{{ route('matches.show', $match) }}" class="action-btn view"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fas fa-inbox"></i>
                                No matches yet — keep reporting items!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Lost & Found Items — Side by Side --}}
    <div class="tables-row">
        <div class="table-card">
            <div class="table-header">
                <h5><i class="fas fa-search"></i> Recent Lost Items</h5>
                <a href="{{ route('lost-items.my-items') }}" class="view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="items-list">
                @forelse($recentLost as $item)
                <a href="{{ route('lost-items.show', $item) }}" class="item-row">
                    <div class="item-info">
                        <div class="item-icon lost"><i class="fas fa-search"></i></div>
                        <div class="item-details">
                            <h6>{{ $item->item_name }}</h6>
                            <span class="item-date">{{ $item->created_at->format('M d, Y') }} • {{ $item->lost_location ?? 'No location' }}</span>
                        </div>
                    </div>
                    <span class="badge badge-{{ $item->status == 'pending' ? 'pending' : $item->status }}">{{ strtoupper($item->status) }}</span>
                </a>
                @empty
                <div class="empty-state-item">
                    <i class="fas fa-inbox"></i>
                    <p>No lost items reported yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h5><i class="fas fa-check-circle"></i> Recent Found Items</h5>
                <a href="{{ route('found-items.my-items') }}" class="view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <div class="items-list">
                @forelse($recentFound as $item)
                <a href="{{ route('found-items.show', $item) }}" class="item-row">
                    <div class="item-info">
                        <div class="item-icon found"><i class="fas fa-check-circle"></i></div>
                        <div class="item-details">
                            <h6>{{ $item->item_name }}</h6>
                            <span class="item-date">{{ $item->created_at->format('M d, Y') }} • {{ $item->found_location ?? 'No location' }}</span>
                        </div>
                    </div>
                    <span class="badge badge-{{ $item->status }}">{{ strtoupper($item->status) }}</span>
                </a>
                @empty
                <div class="empty-state-item">
                    <i class="fas fa-inbox"></i>
                    <p>No found items reported yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="quick-actions">
        <div class="quick-actions-label">
            <i class="fas fa-bolt"></i> Quick Actions
        </div>
        <div class="actions-grid">
            @if(!$isAdmin)
            <a href="{{ route('lost-items.create') }}" class="quick-action-card">
                <i class="fas fa-plus-circle" style="color: var(--netflix-red);"></i>
                <span>Report Lost Item</span>
            </a>
            <a href="{{ route('found-items.create') }}" class="quick-action-card">
                <i class="fas fa-check-circle" style="color: var(--netflix-success);"></i>
                <span>Report Found Item</span>
            </a>
            @endif
            <a href="{{ route('map.index') }}" class="quick-action-card">
                <i class="fas fa-map-marked-alt" style="color: var(--netflix-info);"></i>
                <span>View Map</span>
            </a>
            <a href="{{ route('matches.index') }}" class="quick-action-card">
                <i class="fas fa-exchange-alt" style="color: var(--netflix-warning);"></i>
                <span>All Matches</span>
            </a>
            @if($isAdmin)
            <a href="{{ route('admin.users.index') }}" class="quick-action-card">
                <i class="fas fa-users-cog" style="color: var(--netflix-red);"></i>
                <span>Manage Users</span>
            </a>
            @endif
            <a href="{{ route('profile.show') }}" class="quick-action-card">
                <i class="fas fa-user-circle" style="color: var(--netflix-success);"></i>
                <span>My Profile</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            this.querySelectorAll('button').forEach(btn => btn.disabled = true);
        });
    });

    const cards = document.querySelectorAll('.stat-card-big, .stat-card-small, .user-stat-card, .table-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.4s ease ${index * 0.05}s, transform 0.4s ease ${index * 0.05}s`;
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });
});
</script>
@endsection
