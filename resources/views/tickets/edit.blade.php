@extends('layouts.app')

@section('title', 'Edit Ticket - #' . $ticket->id)

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="page-title-section">
            <h1 class="heading-xl">Edit Ticket #{{ $ticket->id }}</h1>
            <p class="text-lead">Update your ticket information</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline">Cancel</a>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="title" class="form-label">Ticket Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $ticket->title) }}" 
                               class="form-input @error('title') error @enderror" required 
                               placeholder="Brief description of your issue">
                        @error('title')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Provide a clear, concise title that describes your issue</small>
                    </div>

                    <div class="form-group">
                        <label for="category_id" class="form-label">Category</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') error @enderror" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Choose the category that best matches your issue</small>
                    </div>

                    <div class="form-group">
                        <label for="priority" class="form-label">Priority Level</label>
                        <select id="priority" name="priority" class="form-select @error('priority') error @enderror" required>
                            <option value="">Select priority</option>
                            <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low - General inquiry</option>
                            <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium - Standard issue</option>
                            <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High - Important issue</option>
                            <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent - Critical issue</option>
                        </select>
                        @error('priority')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Select the urgency level of your issue</small>
                    </div>

                    @if(auth()->user()->role !== 'customer')
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select @error('status') error @enderror">
                                <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <small class="form-hint">Update the current status of this ticket</small>
                        </div>

                        <div class="form-group">
                            <label for="agent_id" class="form-label">Assigned Agent</label>
                            <select id="agent_id" name="agent_id" class="form-select @error('agent_id') error @enderror">
                                <option value="">Unassigned</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('agent_id', $ticket->agent_id) == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agent_id')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <small class="form-hint">Assign this ticket to a support agent</small>
                        </div>
                    @endif

                    <div class="form-group full-width">
                        <label for="description" class="form-label">Detailed Description</label>
                        <textarea id="description" name="description" rows="6"
                                  class="form-textarea @error('description') error @enderror" required
                                  placeholder="Please provide a detailed description of your issue...">{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">The more details you provide, the faster we can help resolve your issue</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Ticket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Ticket Info -->
    <div class="ticket-info-section">
        <div class="info-card">
            <h3 class="info-title">Current Ticket Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <h4 class="info-label">Current Status</h4>
                    <span class="status-badge {{ str_replace(' ', '_', strtolower($ticket->status)) }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>
                <div class="info-item">
                    <h4 class="info-label">Current Priority</h4>
                    <span class="priority-badge {{ $ticket->priority }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
                <div class="info-item">
                    <h4 class="info-label">Created</h4>
                    <p class="info-value">{{ $ticket->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                <div class="info-item">
                    <h4 class="info-label">Last Updated</h4>
                    <p class="info-value">{{ $ticket->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-container {
    max-width: 800px;
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
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-hint {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.ticket-info-section {
    max-width: 800px;
    margin: 0 auto;
}

.info-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.info-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1.5rem 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item {
    text-align: center;
    padding: 1rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

.info-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    margin: 0 0 0.5rem 0;
}

.info-value {
    font-size: 0.875rem;
    color: #111827;
    margin: 0;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
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

.priority-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.priority-badge.low {
    background: #f3f4f6;
    color: #6b7280;
}

.priority-badge.medium {
    background: #dbeafe;
    color: #1e40af;
}

.priority-badge.high {
    background: #fef3c7;
    color: #d97706;
}

.priority-badge.urgent {
    background: #fee2e2;
    color: #dc2626;
}

@media (max-width: 768px) {
    .form-container,
    .ticket-info-section {
        margin-left: 1rem;
        margin-right: 1rem;
        max-width: none;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-card,
    .info-card {
        padding: 1.5rem;
    }
}
</style>
@endsection