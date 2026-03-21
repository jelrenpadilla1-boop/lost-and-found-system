@extends('layouts.app')

@section('title', 'Search Results - Foundify')

@section('content')
<div class="container py-4">
    <div class="search-results-page">
        <div class="results-header mb-4">
            <h1>
                <i class="fas fa-search" style="color: var(--primary);"></i>
                Search Results
            </h1>
            <p class="text-muted">Found {{ count($results) }} results for "{{ $query }}"</p>
        </div>

        @if(count($results) > 0)
            <div class="results-grid">
                @foreach($results as $result)
                    <a href="{{ $result['url'] }}" class="result-card">
                        <div class="result-icon {{ $result['type'] }}">
                            <i class="fas {{ $result['icon'] }}"></i>
                        </div>
                        <div class="result-content">
                            <h5>{{ $result['title'] }}</h5>
                            <p>{{ $result['subtitle'] }}</p>
                            <div class="result-meta">
                                <span class="badge status-{{ $result['status'] }}">{{ ucfirst($result['status']) }}</span>
                                <span class="date"><i class="far fa-calendar"></i> {{ $result['date'] }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-results">
                <i class="fas fa-search fa-3x mb-3" style="color: var(--text-muted);"></i>
                <h3>No results found</h3>
                <p class="text-muted">Try different keywords or filters</p>
                <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Go Back
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.search-results-page {
    max-width: 1200px;
    margin: 0 auto;
}

.results-header {
    text-align: center;
}

.results-header h1 {
    color: var(--text-primary);
    margin-bottom: 10px;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.result-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    gap: 15px;
    text-decoration: none;
    transition: var(--transition);
}

.result-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: 0 10px 30px var(--primary-glow);
}

.result-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.result-icon.lost {
    background: rgba(255, 20, 147, 0.1);
    color: var(--primary);
}

.result-icon.found {
    background: rgba(0, 250, 154, 0.1);
    color: var(--success);
}

.result-icon.user {
    background: rgba(255, 165, 0, 0.1);
    color: var(--warning);
}

.result-icon.match {
    background: rgba(139, 92, 246, 0.1);
    color: var(--info);
}

.result-content {
    flex: 1;
}

.result-content h5 {
    color: var(--text-primary);
    margin: 0 0 5px 0;
    font-size: 16px;
}

.result-content p {
    color: var(--text-muted);
    margin: 0 0 10px 0;
    font-size: 13px;
}

.result-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
}

.result-meta .badge {
    padding: 4px 10px;
    border-radius: 30px;
    font-weight: 500;
}

.result-meta .date {
    color: var(--text-muted);
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 20px;
}

.no-results h3 {
    color: var(--text-primary);
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .results-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection