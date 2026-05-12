@extends('layouts.app')

@section('title', 'Admin Dashboard - Foundify')

@push('styles')
<style>
/* Admin Dashboard — Foundify Theme */
:root {
    --bg-soft: var(--bg-secondary);
    --border-light: var(--border-color);
    --text-dark: var(--text-primary);
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    --shadow-sm: 0 4px 12px rgba(0,0,0,0.3);
    --shadow-md: 0 12px 30px rgba(0,0,0,0.5);
}

.admin-container {
    width: 100%;
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
    border-bottom: 1px solid var(--border-color);
}

.page-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    letter-spacing: 0;
}

.page-title h1 i { color: var(--accent); }

.page-title p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

.page-actions { display: flex; gap: 12px; flex-wrap: wrap; }

/* Buttons */
.btn-admin {
    font-size: 13px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid transparent;
}

.btn-admin-primary {
    background: var(--accent);
    color: white;
}

.btn-admin-primary:hover {
    background: var(--accent-light);
    transform: scale(1.04);
    color: white;
}

.btn-admin-outline {
    background: transparent;
    border-color: var(--border-color);
    color: var(--text-muted);
}

.btn-admin-outline:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: var(--accent-soft);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 24px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    min-width: 0;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--accent);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent);
    transform: scaleX(0);
    transition: transform 0.3s ease;
    transform-origin: left;
}

.stat-card:hover::before { transform: scaleX(1); }

.stat-card.success::before { background: var(--success); }
.stat-card.warning::before { background: var(--warning); }

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 16px;
}

.stat-icon.red   { background: var(--accent-soft); color: var(--accent); }
.stat-icon.green { background: var(--success-soft); color: var(--success); }
.stat-icon.yellow { background: var(--warning-soft); color: var(--warning); }
.stat-icon.blue  { background: rgba(59,130,246,0.15); color: #60a5fa; }

.stat-number {
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 6px;
    letter-spacing: 0;
    overflow-wrap: anywhere;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.stat-week {
    margin-top: 12px;
    font-size: 11px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-week i { color: var(--success); font-size: 10px; }

/* Section Cards */
.section-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 28px;
}

.section-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.section-card:hover { box-shadow: var(--shadow-md); }

.section-header {
    padding: 16px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.section-header h5 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-header h5 i { color: var(--accent); }

.section-link {
    font-size: 12px;
    color: var(--text-muted);
    text-decoration: none;
    transition: color 0.2s;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

.section-link:hover { color: var(--accent); }

/* User List */
.user-list { padding: 8px 0; }

.user-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    transition: var(--transition);
    border-bottom: 1px solid var(--border-color);
}

.user-row:last-child { border-bottom: none; }

.user-row:hover { background: var(--bg-secondary); }

.user-avatar {
    width: 38px; height: 38px;
    border-radius: 8px;
    background: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 13px;
    flex-shrink: 0;
    text-decoration: none;
}

.user-info { flex: 1; min-width: 0; }

.user-name {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-email {
    font-size: 11px;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-meta {
    font-size: 11px;
    color: var(--text-muted);
    text-align: right;
    flex-shrink: 0;
}

/* Role Badge */
.role-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin { background: var(--accent-soft); color: var(--accent); }
.role-user  { background: var(--success-soft); color: var(--success); }

/* Match List */
.match-list { padding: 8px 0; }

.match-row {
    padding: 12px 20px;
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
}

.match-row:last-child { border-bottom: none; }
.match-row:hover { background: var(--bg-secondary); }

.match-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 6px;
    font-size: 13px;
}

.match-item-name {
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: min(180px, 42vw);
}

.match-arrow { color: var(--text-muted); font-size: 11px; }

.match-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.match-score {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.match-score i { color: var(--warning); }

/* Status Badges */
.status-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending  { background: var(--warning-soft); color: var(--warning); }
.status-confirmed { background: var(--success-soft); color: var(--success); }
.status-rejected { background: var(--error-soft); color: var(--error); }

/* Quick Stats Row */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.quick-stat {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: var(--transition);
    min-width: 0;
}

.quick-stat:hover {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: var(--shadow-sm);
}

.quick-stat-icon {
    width: 44px; height: 44px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.quick-stat-info { flex: 1; min-width: 0; }
.quick-stat-number { font-size: 22px; font-weight: 800; color: var(--text-primary); line-height: 1; overflow-wrap: anywhere; }
.quick-stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; overflow-wrap: anywhere; }

/* Statistics Panel */
.statistics-panel {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 28px;
}

.statistics-header {
    padding: 18px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.statistics-title h5 {
    font-size: 14px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.statistics-title h5 i { color: var(--accent); }

.statistics-title p {
    margin: 0;
    font-size: 12px;
    color: var(--text-muted);
}

.statistics-period {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    padding: 6px 10px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
}

.statistics-metric {
    padding: 20px;
    border-right: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
}

.statistics-metric:nth-child(4n) { border-right: none; }
.statistics-metric:nth-last-child(-n + 4) { border-bottom: none; }

.metric-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.metric-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.metric-icon.red { background: var(--accent-soft); color: var(--accent); }
.metric-icon.green { background: var(--success-soft); color: var(--success); }
.metric-icon.yellow { background: var(--warning-soft); color: var(--warning); }
.metric-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }

.metric-label {
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-weight: 700;
    text-align: right;
}

.metric-value {
    font-size: 30px;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1;
    letter-spacing: 0;
}

.metric-note {
    font-size: 12px;
    color: var(--text-muted);
    line-height: 1.4;
}

.metric-progress {
    height: 6px;
    border-radius: 999px;
    background: var(--bg-secondary);
    overflow: hidden;
}

.metric-progress-bar {
    height: 100%;
    border-radius: inherit;
    background: var(--accent);
}

.metric-progress-bar.green { background: var(--success); }
.metric-progress-bar.yellow { background: var(--warning); }
.metric-progress-bar.blue { background: #60a5fa; }

/* Analytics */
.analytics-panel {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 28px;
}

.analytics-header {
    padding: 18px 20px;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.analytics-title h5 {
    font-size: 14px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.analytics-title h5 i { color: var(--accent); }

.analytics-title p {
    margin: 0;
    font-size: 12px;
    color: var(--text-muted);
}

.analytics-layout {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
    border-bottom: 1px solid var(--border-color);
}

.analytics-chart-area {
    padding: 20px;
    border-right: 1px solid var(--border-color);
    overflow-x: auto;
}

.analytics-section-title {
    font-size: 12px;
    font-weight: 800;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.analytics-section-title i { color: var(--accent); }

.activity-chart {
    height: 250px;
    display: grid;
    grid-template-columns: repeat(30, minmax(4px, 1fr));
    gap: 6px;
    align-items: stretch;
    min-width: 620px;
}

.activity-day {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.activity-track {
    height: 210px;
    border-radius: 4px;
    background: var(--bg-secondary);
    display: flex;
    align-items: flex-end;
    overflow: hidden;
}

.activity-stack {
    width: 100%;
    min-height: 4px;
    border-radius: inherit;
    overflow: hidden;
    display: flex;
    flex-direction: column-reverse;
}

.activity-segment { width: 100%; display: block; }
.activity-segment.lost { background: var(--accent); }
.activity-segment.found { background: var(--success); }
.activity-segment.matches { background: #60a5fa; }

.activity-label {
    height: 14px;
    font-size: 9px;
    line-height: 1;
    color: var(--text-muted);
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
}

.analytics-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 14px;
}

.legend-item {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.legend-swatch {
    width: 9px;
    height: 9px;
    border-radius: 2px;
    display: inline-block;
}

.legend-swatch.lost { background: var(--accent); }
.legend-swatch.found { background: var(--success); }
.legend-swatch.matches { background: #60a5fa; }

.analytics-summary {
    display: grid;
    grid-template-columns: 1fr;
}

.analytics-kpi {
    min-height: 112px;
    padding: 18px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 10px;
}

.analytics-kpi:last-child { border-bottom: none; }

.analytics-kpi-label {
    font-size: 11px;
    font-weight: 800;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.analytics-kpi-value {
    font-size: 30px;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1;
    letter-spacing: 0;
}

.analytics-kpi-note {
    font-size: 12px;
    color: var(--text-muted);
    line-height: 1.4;
}

.category-analytics {
    display: grid;
    grid-template-columns: 1fr 1fr;
}

.category-block {
    padding: 20px;
    border-right: 1px solid var(--border-color);
}

.category-block:last-child { border-right: none; }

.category-list {
    display: grid;
    gap: 14px;
}

.category-row {
    display: grid;
    gap: 8px;
}

.category-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.category-name {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
}

.category-count {
    font-size: 11px;
    font-weight: 800;
    color: var(--text-muted);
    flex-shrink: 0;
}

.category-track {
    height: 7px;
    border-radius: 999px;
    background: var(--bg-secondary);
    overflow: hidden;
}

.category-bar {
    height: 100%;
    border-radius: inherit;
    background: var(--accent);
    display: block;
}

.category-bar.found { background: var(--success); }

/* Empty state */
.empty-row {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-muted);
    font-size: 13px;
}

.empty-row i { display: block; font-size: 28px; margin-bottom: 8px; color: var(--border-color); }

/* Admin Quick Links */
.quick-links {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}

.quick-link-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 10px;
    transition: var(--transition);
}

.quick-link-card:hover {
    border-color: var(--accent);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.quick-link-card:hover .quick-link-icon { background: var(--accent); color: white; }

.quick-link-icon {
    width: 48px; height: 48px;
    border-radius: 8px;
    background: var(--accent-soft);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: var(--transition);
}

.quick-link-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Fade-in */
.fade-in { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

.fade-in:nth-child(1) { animation-delay: 0.05s; }
.fade-in:nth-child(2) { animation-delay: 0.10s; }
.fade-in:nth-child(3) { animation-delay: 0.15s; }
.fade-in:nth-child(4) { animation-delay: 0.20s; }

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .statistics-grid { grid-template-columns: repeat(2, 1fr); }
    .statistics-metric:nth-child(4n) { border-right: 1px solid var(--border-color); }
    .statistics-metric:nth-child(2n) { border-right: none; }
    .statistics-metric:nth-last-child(-n + 4) { border-bottom: 1px solid var(--border-color); }
    .statistics-metric:nth-last-child(-n + 2) { border-bottom: none; }
    .analytics-layout { grid-template-columns: 1fr; }
    .analytics-chart-area { border-right: none; border-bottom: 1px solid var(--border-color); }
    .analytics-summary { grid-template-columns: repeat(4, 1fr); }
    .analytics-kpi { border-bottom: none; border-right: 1px solid var(--border-color); }
    .analytics-kpi:last-child { border-right: none; }
    .quick-links { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .admin-container { padding: 16px; }
    .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .section-grid { grid-template-columns: 1fr; }
    .quick-stats { grid-template-columns: 1fr; }
    .quick-links { grid-template-columns: repeat(2, 1fr); }
    .page-header { flex-direction: column; align-items: flex-start; }
    .page-actions { width: 100%; }
    .page-actions .btn-admin { flex: 1 1 180px; }
    .statistics-header { align-items: flex-start; flex-direction: column; }
    .analytics-header { align-items: flex-start; flex-direction: column; }
    .activity-chart { gap: 4px; }
    .analytics-summary { grid-template-columns: 1fr; }
    .analytics-kpi { border-right: none; border-bottom: 1px solid var(--border-color); }
    .analytics-kpi:last-child { border-bottom: none; }
    .category-analytics { grid-template-columns: 1fr; }
    .category-block { border-right: none; border-bottom: 1px solid var(--border-color); }
    .category-block:last-child { border-bottom: none; }
}

@media (max-width: 480px) {
    .admin-container { padding: 12px; }
    .stats-grid { grid-template-columns: 1fr; }
    .stat-card { padding: 18px; }
    .stat-number { font-size: 1.8rem; }
    .statistics-grid { grid-template-columns: 1fr; }
    .statistics-metric { border-right: none !important; border-bottom: 1px solid var(--border-color) !important; }
    .statistics-metric:last-child { border-bottom: none !important; }
    .quick-links { grid-template-columns: 1fr; }
    .analytics-chart-area,
    .category-block,
    .analytics-kpi,
    .statistics-metric { padding: 16px; }
    .activity-chart { min-width: 540px; height: 220px; }
    .activity-track { height: 180px; }
    .user-row { align-items: flex-start; }
    .user-meta { text-align: left; }
    .match-footer { align-items: flex-start; flex-direction: column; gap: 8px; }
}
</style>
@endpush

@section('content')
<div class="admin-container">

    {{-- Page Header --}}
    <div class="page-header fade-in">
        <div class="page-title">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <p>Platform overview and recent activity</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-outline">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="{{ route('lost-items.index') }}" class="btn-admin btn-admin-primary">
                <i class="fas fa-search"></i> View Items
            </a>
        </div>
    </div>

    {{-- Main Stats --}}
    <div class="stats-grid">
        <div class="stat-card fade-in">
            <div class="stat-icon red"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-week">
                <i class="fas fa-arrow-up"></i>
                +{{ $stats['users_this_week'] }} this week
            </div>
        </div>

        <div class="stat-card fade-in">
            <div class="stat-icon red"><i class="fas fa-search"></i></div>
            <div class="stat-number">{{ number_format($stats['total_lost_items']) }}</div>
            <div class="stat-label">Lost Items</div>
            <div class="stat-week">
                <i class="fas fa-clock"></i>
                +{{ $stats['items_this_week'] }} items this week
            </div>
        </div>

        <div class="stat-card success fade-in">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ number_format($stats['total_found_items']) }}</div>
            <div class="stat-label">Found Items</div>
            <div class="stat-week">
                <i class="fas fa-arrow-up"></i>
                Actively reported
            </div>
        </div>

        <div class="stat-card warning fade-in">
            <div class="stat-icon yellow"><i class="fas fa-link"></i></div>
            <div class="stat-number">{{ number_format($stats['total_matches']) }}</div>
            <div class="stat-label">Total Matches</div>
            <div class="stat-week">
                <i class="fas fa-check"></i>
                {{ $stats['confirmed_matches'] }} confirmed
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="quick-stats">
        <div class="quick-stat fade-in">
            <div class="quick-stat-icon" style="background: var(--warning-soft); color: var(--warning);">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-number">{{ $stats['pending_lost_items'] }}</div>
                <div class="quick-stat-label">Pending Lost Items</div>
            </div>
        </div>

        <div class="quick-stat fade-in">
            <div class="quick-stat-icon" style="background: var(--success-soft); color: var(--success);">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-number">{{ $stats['pending_found_items'] }}</div>
                <div class="quick-stat-label">Pending Found Items</div>
            </div>
        </div>

        <div class="quick-stat fade-in">
            <div class="quick-stat-icon" style="background: var(--accent-soft); color: var(--accent);">
                <i class="fas fa-link"></i>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-number">{{ $stats['pending_matches'] }}</div>
                <div class="quick-stat-label">Pending Matches</div>
            </div>
        </div>
    </div>

    {{-- Analytics Overview --}}
    <div class="analytics-panel fade-in" id="analytics">
        <div class="analytics-header">
            <div class="analytics-title">
                <h5><i class="fas fa-chart-line"></i> Analytics Overview</h5>
                <p>Last 30 days of platform activity, category demand, and match outcomes.</p>
            </div>
            <span class="statistics-period">Last 30 Days</span>
        </div>

        <div class="analytics-layout">
            <div class="analytics-chart-area">
                <div class="analytics-section-title">
                    <i class="fas fa-signal"></i> Daily Activity
                </div>
                <div class="activity-chart">
                    @foreach($analytics['activity'] as $day)
                        @php
                            $stackHeight = $day['total'] > 0
                                ? max(6, round(($day['total'] / $analytics['max_activity']) * 100))
                                : 0;
                        @endphp
                        <div class="activity-day" title="{{ $day['label'] }}: {{ $day['lost'] }} lost, {{ $day['found'] }} found, {{ $day['matches'] }} matches">
                            <div class="activity-track">
                                @if($day['total'] > 0)
                                    <div class="activity-stack" style="height: {{ $stackHeight }}%;">
                                        @if($day['lost'] > 0)
                                            <span class="activity-segment lost" style="height: {{ round(($day['lost'] / $day['total']) * 100) }}%;"></span>
                                        @endif
                                        @if($day['found'] > 0)
                                            <span class="activity-segment found" style="height: {{ round(($day['found'] / $day['total']) * 100) }}%;"></span>
                                        @endif
                                        @if($day['matches'] > 0)
                                            <span class="activity-segment matches" style="height: {{ round(($day['matches'] / $day['total']) * 100) }}%;"></span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <span class="activity-label">
                                @if($loop->first || $loop->last || $loop->iteration % 7 === 0)
                                    {{ $day['label'] }}
                                @else
                                    &nbsp;
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="analytics-legend">
                    <span class="legend-item"><span class="legend-swatch lost"></span> Lost Reports</span>
                    <span class="legend-item"><span class="legend-swatch found"></span> Found Reports</span>
                    <span class="legend-item"><span class="legend-swatch matches"></span> Matches</span>
                </div>
            </div>

            <div class="analytics-summary">
                <div class="analytics-kpi">
                    <div class="analytics-kpi-label">New Users</div>
                    <div class="analytics-kpi-value">{{ number_format($analytics['summary']['new_users_30_days']) }}</div>
                    <div class="analytics-kpi-note">Registered during the last 30 days</div>
                </div>
                <div class="analytics-kpi">
                    <div class="analytics-kpi-label">New Item Reports</div>
                    <div class="analytics-kpi-value">{{ number_format($analytics['summary']['items_30_days']) }}</div>
                    <div class="analytics-kpi-note">Lost and found reports submitted</div>
                </div>
                <div class="analytics-kpi">
                    <div class="analytics-kpi-label">30-Day Match Success</div>
                    <div class="analytics-kpi-value">{{ $analytics['summary']['match_success_30_days'] }}%</div>
                    <div class="analytics-kpi-note">
                        {{ number_format($analytics['summary']['confirmed_matches_30_days']) }} of {{ number_format($analytics['summary']['matches_30_days']) }} matches confirmed
                    </div>
                </div>
                <div class="analytics-kpi">
                    <div class="analytics-kpi-label">Recovery Rate</div>
                    <div class="analytics-kpi-value">{{ $analytics['summary']['recovery_rate'] }}%</div>
                    <div class="analytics-kpi-note">Lost items marked found or returned</div>
                </div>
            </div>
        </div>

        <div class="category-analytics">
            <div class="category-block">
                <div class="analytics-section-title">
                    <i class="fas fa-search-location"></i> Top Lost Categories
                </div>
                <div class="category-list">
                    @forelse($analytics['lost_categories'] as $category)
                        @php
                            $categoryLabel = $category->category ?: 'Uncategorized';
                            $categoryWidth = max(6, round(($category->count / $analytics['max_lost_category']) * 100));
                        @endphp
                        <div class="category-row">
                            <div class="category-meta">
                                <span class="category-name">{{ $categoryLabel }}</span>
                                <span class="category-count">{{ number_format($category->count) }}</span>
                            </div>
                            <div class="category-track">
                                <span class="category-bar" style="width: {{ $categoryWidth }}%;"></span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-row">
                            <i class="fas fa-search"></i>
                            No lost category data
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="category-block">
                <div class="analytics-section-title">
                    <i class="fas fa-box-open"></i> Top Found Categories
                </div>
                <div class="category-list">
                    @forelse($analytics['found_categories'] as $category)
                        @php
                            $categoryLabel = $category->category ?: 'Uncategorized';
                            $categoryWidth = max(6, round(($category->count / $analytics['max_found_category']) * 100));
                        @endphp
                        <div class="category-row">
                            <div class="category-meta">
                                <span class="category-name">{{ $categoryLabel }}</span>
                                <span class="category-count">{{ number_format($category->count) }}</span>
                            </div>
                            <div class="category-track">
                                <span class="category-bar found" style="width: {{ $categoryWidth }}%;"></span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-row">
                            <i class="fas fa-box-open"></i>
                            No found category data
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="statistics-panel fade-in">
        <div class="statistics-header">
            <div class="statistics-title">
                <h5><i class="fas fa-chart-pie"></i> Statistics</h5>
                <p>Admin snapshot for approvals, matches, recovery, and monthly activity.</p>
            </div>
            <span class="statistics-period">Live Overview</span>
        </div>
        <div class="statistics-grid">
            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon yellow"><i class="fas fa-clipboard-check"></i></div>
                    <div class="metric-label">Pending Reviews</div>
                </div>
                <div>
                    <div class="metric-value">{{ number_format($stats['pending_reviews']) }}</div>
                    <div class="metric-note">
                        {{ number_format($stats['pending_lost_items'] + $stats['pending_found_items']) }} item reports and
                        {{ number_format($stats['pending_matches']) }} matches
                    </div>
                </div>
            </div>

            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon green"><i class="fas fa-thumbs-up"></i></div>
                    <div class="metric-label">Approval Rate</div>
                </div>
                <div>
                    <div class="metric-value">{{ $stats['approval_rate'] }}%</div>
                    <div class="metric-note">Approved or resolved item reports</div>
                </div>
                <div class="metric-progress">
                    <div class="metric-progress-bar green" style="width: {{ min(100, $stats['approval_rate']) }}%;"></div>
                </div>
            </div>

            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon blue"><i class="fas fa-handshake"></i></div>
                    <div class="metric-label">Match Success</div>
                </div>
                <div>
                    <div class="metric-value">{{ $stats['match_success_rate'] }}%</div>
                    <div class="metric-note">
                        {{ number_format($stats['confirmed_matches']) }} of {{ number_format($stats['total_matches']) }} matches confirmed
                    </div>
                </div>
                <div class="metric-progress">
                    <div class="metric-progress-bar blue" style="width: {{ min(100, $stats['match_success_rate']) }}%;"></div>
                </div>
            </div>

            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon green"><i class="fas fa-trophy"></i></div>
                    <div class="metric-label">Recovered</div>
                </div>
                <div>
                    <div class="metric-value">{{ number_format($stats['recovered_items']) }}</div>
                    <div class="metric-note">Items marked found, returned, or claimed</div>
                </div>
            </div>

            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon red"><i class="fas fa-eye"></i></div>
                    <div class="metric-label">Active Items</div>
                </div>
                <div>
                    <div class="metric-value">{{ number_format($stats['active_items']) }}</div>
                    <div class="metric-note">Approved reports visible to users</div>
                </div>
            </div>

            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon red"><i class="fas fa-ban"></i></div>
                    <div class="metric-label">Rejected Reports</div>
                </div>
                <div>
                    <div class="metric-value">{{ number_format($stats['rejected_items']) }}</div>
                    <div class="metric-note">Lost and found reports rejected by admins</div>
                </div>
            </div>

            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon yellow"><i class="fas fa-star"></i></div>
                    <div class="metric-label">High Confidence</div>
                </div>
                <div>
                    <div class="metric-value">{{ number_format($stats['high_confidence_matches']) }}</div>
                    <div class="metric-note">Matches with an 80% score or higher</div>
                </div>
            </div>

            <div class="statistics-metric">
                <div class="metric-top">
                    <div class="metric-icon blue"><i class="fas fa-calendar-alt"></i></div>
                    <div class="metric-label">This Month</div>
                </div>
                <div>
                    <div class="metric-value">{{ number_format($stats['items_this_month']) }}</div>
                    <div class="metric-note">
                        New item reports, plus {{ number_format($stats['users_this_month']) }} new users
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Users & Recent Matches --}}
    <div class="section-grid">

        {{-- Recent Users --}}
        <div class="section-card fade-in">
            <div class="section-header">
                <h5><i class="fas fa-user-clock"></i> Recent Users</h5>
                <a href="{{ route('admin.users.index') }}" class="section-link">
                    View all <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="user-list">
                @forelse($recentUsers as $user)
                <div class="user-row">
                    <a href="{{ route('admin.users.show', $user) }}" class="user-avatar">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </a>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>
                    <div class="user-meta">
                        <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                            {{ $user->role ?? 'user' }}
                        </span>
                        <div style="margin-top: 4px; font-size: 10px;">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-row">
                    <i class="fas fa-users"></i>
                    No users yet
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Matches --}}
        <div class="section-card fade-in">
            <div class="section-header">
                <h5><i class="fas fa-link"></i> Recent Matches</h5>
                <a href="{{ route('matches.index') }}" class="section-link">
                    View all <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            <div class="match-list">
                @forelse($recentMatches as $match)
                <div class="match-row">
                    <div class="match-items">
                        <span class="match-item-name">{{ $match->lostItem?->item_name ?? 'Deleted item' }}</span>
                        <span class="match-arrow"><i class="fas fa-arrows-alt-h"></i></span>
                        <span class="match-item-name">{{ $match->foundItem?->item_name ?? 'Deleted item' }}</span>
                    </div>
                    <div class="match-footer">
                        <span class="match-score">
                            <i class="fas fa-star"></i>
                            {{ number_format($match->match_score, 0) }}% match
                        </span>
                        <span class="status-badge status-{{ $match->status }}">{{ $match->status }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-row">
                    <i class="fas fa-link"></i>
                    No matches yet
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
