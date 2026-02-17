@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-wrapper">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="profile-avatar-image">
                        @else
                            <div class="profile-avatar-large" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="profile-info">
                        <h2>{{ Auth::user()->name }}</h2>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                        <span class="badge" style="background: var(--primary);">Member since {{ Auth::user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="profile-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-edit">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
                
                <div class="profile-stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">{{ Auth::user()->lostItems()->count() }}</div>
                        <div class="stat-label">Lost Items</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ Auth::user()->foundItems()->count() }}</div>
                        <div class="stat-label">Found Items</div>
                    </div>
                    <div class="stat-item">
                        @php
                            $matchCount = \App\Models\ItemMatch::whereHas('lostItem', function($q) {
                                $q->where('user_id', Auth::id());
                            })->orWhereHas('foundItem', function($q) {
                                $q->where('user_id', Auth::id());
                            })->count();
                        @endphp
                        <div class="stat-value">{{ $matchCount }}</div>
                        <div class="stat-label">Matches</div>
                    </div>
                </div>
                
                <div class="profile-details">
                    <h5><i class="fas fa-info-circle" style="color: var(--primary);"></i> Profile Information</h5>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Name:</span>
                            <span class="detail-value">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value">{{ Auth::user()->email }}</span>
                        </div>
                        @if(Auth::user()->phone)
                        <div class="detail-item">
                            <span class="detail-label">Phone:</span>
                            <span class="detail-value">{{ Auth::user()->phone }}</span>
                        </div>
                        @endif
                        @if(Auth::user()->location)
                        <div class="detail-item">
                            <span class="detail-label">Location:</span>
                            <span class="detail-value">{{ Auth::user()->location }}</span>
                        </div>
                        @endif
                        <div class="detail-item">
                            <span class="detail-label">Member Since:</span>
                            <span class="detail-value">{{ Auth::user()->created_at->format('F d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
    padding: 2rem;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
    flex-wrap: wrap;
}

.profile-avatar-wrapper {
    position: relative;
}

.profile-avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 40px;
    box-shadow: 0 0 30px var(--primary-glow);
}

.profile-avatar-image {
    width: 100px;
    height: 100px;
    border-radius: 30px;
    object-fit: cover;
    border: 3px solid var(--primary);
    box-shadow: 0 0 30px var(--primary-glow);
    transition: all 0.3s ease;
}

.profile-avatar-image:hover {
    transform: scale(1.05);
    box-shadow: 0 0 40px var(--primary-glow);
}

.profile-info {
    flex: 1;
}

.profile-info h2 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-radius: 30px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 0 20px var(--primary-glow);
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--primary-glow);
}

.profile-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    padding: 2rem 0;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: var(--bg-header);
    border-radius: 16px;
    border: 1px solid var(--border-color);
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    color: var(--text-muted);
    font-size: 13px;
}

.profile-details {
    padding-top: 1rem;
}

.profile-details h5 {
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.detail-item {
    padding: 1rem;
    background: var(--bg-header);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.detail-label {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    margin-bottom: 4px;
}

.detail-value {
    color: var(--text-primary);
    font-size: 15px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection