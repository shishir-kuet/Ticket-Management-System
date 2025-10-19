@extends('layouts.app')

@section('title', 'Edit Category - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <div class="admin-title-section">
            <h1 class="heading-xl">Edit Category</h1>
            <p class="text-lead">Update category information and description</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Back to Categories</a>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" 
                               class="form-input @error('name') error @enderror" required 
                               placeholder="e.g., Technical Support, Billing, General Inquiry">
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Choose a clear, descriptive name for this category</small>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-textarea @error('description') error @enderror"
                                  placeholder="Brief description of what tickets belong in this category">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Optional: Provide additional context about this category</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Usage Information -->
    <div class="usage-section">
        <div class="usage-card">
            <h3 class="usage-title">Category Usage</h3>
            <div class="usage-stats">
                <div class="usage-stat">
                    <span class="usage-number">{{ $category->tickets()->count() }}</span>
                    <span class="usage-label">Total Tickets</span>
                </div>
                <div class="usage-stat">
                    <span class="usage-number">{{ $category->tickets()->where('status', 'open')->count() }}</span>
                    <span class="usage-label">Open Tickets</span>
                </div>
                <div class="usage-stat">
                    <span class="usage-number">{{ $category->tickets()->where('status', 'resolved')->count() }}</span>
                    <span class="usage-label">Resolved Tickets</span>
                </div>
                <div class="usage-stat">
                    <span class="usage-number">{{ $category->created_at->format('M d, Y') }}</span>
                    <span class="usage-label">Created On</span>
                </div>
            </div>

            @if($category->tickets()->count() > 0)
                <div class="recent-tickets">
                    <h4 class="recent-title">Recent Tickets in this Category</h4>
                    <div class="ticket-list">
                        @foreach($category->tickets()->latest()->take(5)->get() as $ticket)
                            <div class="ticket-item">
                                <div class="ticket-info">
                                    <h5 class="ticket-title">{{ $ticket->title }}</h5>
                                    <p class="ticket-meta">
                                        By {{ $ticket->user->name }} • {{ $ticket->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="ticket-status">
                                    <span class="status-badge {{ str_replace(' ', '_', strtolower($ticket->status)) }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Danger Zone -->
    @if($category->tickets()->count() === 0)
        <div class="danger-zone">
            <h3 class="danger-title">Danger Zone</h3>
            <p class="danger-description">
                This category has no tickets assigned to it and can be safely deleted.
            </p>
            <div class="danger-actions">
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    @else
        <div class="warning-zone">
            <h3 class="warning-title">⚠️ Delete Warning</h3>
            <p class="warning-description">
                This category cannot be deleted because it has {{ $category->tickets()->count() }} ticket(s) assigned to it. 
                Please reassign or delete all tickets before removing this category.
            </p>
        </div>
    @endif
</div>

<style>
.form-container {
    max-width: 600px;
    margin: 2rem auto 3rem auto;
}

.form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.form-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-hint {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.usage-section {
    max-width: 600px;
    margin: 0 auto 2rem auto;
}

.usage-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.usage-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1.5rem 0;
}

.usage-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.usage-stat {
    text-align: center;
    padding: 1rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

.usage-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
}

.usage-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.recent-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1rem 0;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
}

.ticket-list {
    display: grid;
    gap: 1rem;
}

.ticket-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

.ticket-info {
    flex: 1;
}

.ticket-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.ticket-meta {
    font-size: 0.75rem;
    color: #6b7280;
    margin: 0;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.open {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.in_progress {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.resolved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.closed {
    background: #f3f4f6;
    color: #6b7280;
}

.danger-zone {
    max-width: 600px;
    margin: 0 auto;
    background: rgba(254, 242, 242, 0.95);
    border: 1px solid rgba(252, 165, 165, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
}

.danger-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #dc2626;
    margin: 0 0 0.5rem 0;
}

.danger-description {
    color: #7f1d1d;
    margin: 0 0 1.5rem 0;
    line-height: 1.5;
}

.danger-actions {
    display: flex;
    gap: 1rem;
}

.warning-zone {
    max-width: 600px;
    margin: 0 auto;
    background: rgba(254, 243, 199, 0.95);
    border: 1px solid rgba(251, 191, 36, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
}

.warning-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #d97706;
    margin: 0 0 0.5rem 0;
}

.warning-description {
    color: #92400e;
    margin: 0;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .form-container,
    .usage-section {
        margin-left: 1rem;
        margin-right: 1rem;
        max-width: none;
    }
    
    .usage-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .ticket-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .form-card,
    .usage-card {
        padding: 1.5rem;
    }
}
</style>
@endsection