@extends('layouts.app')

@section('title', 'Edit Lost Item')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>
            <i class="fas fa-edit text-primary"></i> Edit Lost Item
        </h1>
        <p>Update item details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('lost-items.show', $lostItem) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Item
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-pencil-alt"></i> Edit Item Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('lost-items.update', $lostItem) }}" method="POST" enctype="multipart/form-data" id="editItemForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <!-- Current Photo -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Current Photo</label>
                                <div class="text-center">
                                    @if($lostItem->photo)
                                        <img src="{{ asset('storage/' . $lostItem->photo) }}" 
                                             class="img-fluid rounded mb-3" 
                                             style="max-height: 200px;"
                                             alt="{{ $lostItem->item_name }}">
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="removePhoto" name="remove_photo">
                                                <label class="form-check-label text-danger" for="removePhoto">
                                                    <i class="fas fa-trash"></i> Remove photo
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center mb-3" 
                                             style="height: 150px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                        <small class="text-muted">No photo currently</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- New Photo -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="photo" class="form-label">New Photo (Optional)</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <small class="text-muted">Leave empty to keep current photo. Max size: 2MB</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="item_name" class="form-label">Item Name *</label>
                                <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                                       id="item_name" name="item_name" value="{{ old('item_name', $lostItem->item_name) }}" required>
                                @error('item_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Electronics" {{ (old('category', $lostItem->category) == 'Electronics') ? 'selected' : '' }}>Electronics</option>
                                    <option value="Documents" {{ (old('category', $lostItem->category) == 'Documents') ? 'selected' : '' }}>Documents</option>
                                    <option value="Jewelry" {{ (old('category', $lostItem->category) == 'Jewelry') ? 'selected' : '' }}>Jewelry</option>
                                    <option value="Clothing" {{ (old('category', $lostItem->category) == 'Clothing') ? 'selected' : '' }}>Clothing</option>
                                    <option value="Bags" {{ (old('category', $lostItem->category) == 'Bags') ? 'selected' : '' }}>Bags</option>
                                    <option value="Keys" {{ (old('category', $lostItem->category) == 'Keys') ? 'selected' : '' }}>Keys</option>
                                    <option value="Wallet" {{ (old('category', $lostItem->category) == 'Wallet') ? 'selected' :                                    <option value="Other" {{ (old('category', $lostItem->category) == 'Other') ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lost_date" class="form-label">Lost Date *</label>
                                <input type="date" class="form-control @error('lost_date') is-invalid @enderror" 
                                       id="lost_date" name="lost_date" value="{{ old('lost_date', $lostItem->lost_date->format('Y-m-d')) }}" required>
                                @error('lost_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lost_location" class="form-label">Lost Location *</label>
                                <input type="text" class="form-control @error('lost_location') is-invalid @enderror" 
                                       id="lost_location" name="lost_location" value="{{ old('lost_location', $lostItem->lost_location) }}" required>
                                @error('lost_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description', $lostItem->description) }}</textarea>
                                <small class="text-muted">Provide detailed description including color, size, brand, distinctive features, etc.</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_name" class="form-label">Your Name *</label>
                                <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                       id="contact_name" name="contact_name" value="{{ old('contact_name', $lostItem->contact_name) }}" required>
                                @error('contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_phone" class="form-label">Contact Phone *</label>
                                <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $lostItem->contact_phone) }}" required>
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="contact_email" class="form-label">Contact Email *</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                       id="contact_email" name="contact_email" value="{{ old('contact_email', $lostItem->contact_email) }}" required>
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="lost" {{ (old('status', $lostItem->status) == 'lost') ? 'selected' : '' }}>Still Lost</option>
                                    <option value="found" {{ (old('status', $lostItem->status) == 'found') ? 'selected' : '' }}>Found</option>
                                    <option value="claimed" {{ (old('status', $lostItem->status) == 'claimed') ? 'selected' : '' }}>Claimed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Reward Information -->
                        <div class="col-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="has_reward" name="has_reward" 
                                           {{ (old('has_reward', $lostItem->has_reward) ? 'checked' : '') }}>
                                    <label class="form-check-label" for="has_reward">
                                        <strong>Offer Reward</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reward_amount" class="form-label">Reward Amount (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('reward_amount') is-invalid @enderror" 
                                           id="reward_amount" name="reward_amount" 
                                           value="{{ old('reward_amount', $lostItem->reward_amount) }}" 
                                           min="0" step="0.01" placeholder="0.00">
                                </div>
                                <small class="text-muted">Leave empty if no reward offered</small>
                                @error('reward_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reward_currency" class="form-label">Currency</label>
                                <select class="form-select @error('reward_currency') is-invalid @enderror" 
                                        id="reward_currency" name="reward_currency">
                                    <option value="USD" {{ (old('reward_currency', $lostItem->reward_currency) == 'USD') ? 'selected' : '' }}>USD ($)</option>
                                    <option value="EUR" {{ (old('reward_currency', $lostItem->reward_currency) == 'EUR') ? 'selected' : '' }}>EUR (€)</option>
                                    <option value="GBP" {{ (old('reward_currency', $lostItem->reward_currency) == 'GBP') ? 'selected' : '' }}>GBP (£)</option>
                                    <option value="CAD" {{ (old('reward_currency', $lostItem->reward_currency) == 'CAD') ? 'selected' : '' }}>CAD ($)</option>
                                </select>
                                @error('reward_currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash"></i> Delete Item
                                </button>
                                
                                <div>
                                    <a href="{{ route('lost-items.show', $lostItem) }}" class="btn btn-outline-secondary me-2">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this lost item? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> All associated data will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('lost-items.destroy', $lostItem) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('editItemForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Custom validation can be added here
                const lostDate = document.getElementById('lost_date');
                const today = new Date().toISOString().split('T')[0];
                
                if (lostDate.value > today) {
                    e.preventDefault();
                    alert('Lost date cannot be in the future.');
                    lostDate.focus();
                }
            });
        }
        
        // Toggle reward fields
        const hasReward = document.getElementById('has_reward');
        const rewardAmount = document.getElementById('reward_amount');
        const rewardCurrency = document.getElementById('reward_currency');
        
        function toggleRewardFields() {
            if (hasReward.checked) {
                rewardAmount.removeAttribute('disabled');
                rewardCurrency.removeAttribute('disabled');
            } else {
                rewardAmount.setAttribute('disabled', 'disabled');
                rewardCurrency.setAttribute('disabled', 'disabled');
                rewardAmount.value = '';
            }
        }
        
        if (hasReward) {
            hasReward.addEventListener('change', toggleRewardFields);
            toggleRewardFields(); // Initialize on page load
        }
        
        // Image preview for new photo
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You could add a preview here if needed
                        console.log('New image selected');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Handle remove photo checkbox
        const removePhotoCheckbox = document.getElementById('removePhoto');
        if (removePhotoCheckbox) {
            removePhotoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    photoInput.disabled = true;
                    photoInput.value = '';
                } else {
                    photoInput.disabled = false;
                }
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .page-title h1 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .page-title p {
        margin: 0.25rem 0 0;
        color: #6c757d;
    }
    
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1.25rem 1.5rem;
        border-radius: 10px 10px 0 0 !important;
    }
    
    .card-header h5 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
        padding: 0.5rem 2rem;
    }
    
    .btn-primary:hover {
        background-color: #2779bd;
        border-color: #2779bd;
    }
    
    .btn-danger {
        background-color: #e3342f;
        border-color: #e3342f;
    }
    
    .btn-danger:hover {
        background-color: #cc1f1a;
        border-color: #cc1f1a;
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875em;
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-title {
        color: #2c3e50;
        font-weight: 600;
    }
</style>
@endsection