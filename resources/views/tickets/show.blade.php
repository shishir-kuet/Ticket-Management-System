@extends('layouts.app')

@section('title', 'Ticket Details - #' . $ticket->id)

@section('content')
<div class="container-main">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header">
        <div class="page-title-section">
            <h1 class="heading-xl">Ticket #{{ $ticket->id }}</h1>
            <p class="text-lead">{{ $ticket->title }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('tickets.index') }}" class="btn btn-outline">Back to Tickets</a>
            @if($ticket->status !== 'closed' && (auth()->user()->role === 'customer' && $ticket->customer_id === auth()->id()) || auth()->user()->role !== 'customer')
                <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-primary">Edit Ticket</a>
            @endif
        </div>
    </div>

    <div class="ticket-details-container">
        <!-- Ticket Information Card -->
        <div class="ticket-info-card">
            <div class="ticket-header-info">
                <div class="ticket-status-section">
                    <span class="status-badge {{ str_replace(' ', '_', strtolower($ticket->status)) }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    <span class="priority-badge {{ $ticket->priority }}">
                        {{ ucfirst($ticket->priority) }} Priority
                    </span>
                </div>
                <div class="ticket-meta-grid">
                    <div class="meta-item">
                        <h4 class="meta-label">Created</h4>
                        <p class="meta-value">{{ $ticket->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="meta-item">
                        <h4 class="meta-label">Last Updated</h4>
                        <p class="meta-value">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="meta-item">
                        <h4 class="meta-label">Category</h4>
                        <p class="meta-value">{{ $ticket->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div class="meta-item">
                        <h4 class="meta-label">Assigned Agent</h4>
                        <p class="meta-value">
                            @if($ticket->agent)
                                <span class="agent-info">
                                    <span class="agent-avatar">{{ strtoupper(substr($ticket->agent->name, 0, 2)) }}</span>
                                    {{ $ticket->agent->name }}
                                </span>
                            @else
                                <em class="unassigned-text">Not assigned yet</em>
                            @endif
                        </p>
                        
                        @if(in_array(auth()->user()->role, ['admin', 'agent']) && $ticket->status !== 'closed')
                            <div class="assignment-controls-inline">
                                <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="assignment-form-inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="assignment-input-group">
                                        <select name="agent_id" class="assignment-select-inline" required>
                                            <option value="">{{ $ticket->agent ? 'Reassign to...' : 'Assign to agent...' }}</option>
                                            @foreach(App\Models\User::where('role', 'agent')->orderBy('name')->get() as $agent)
                                                <option value="{{ $agent->id }}" 
                                                    {{ $ticket->agent_id == $agent->id ? 'selected' : '' }}>
                                                    {{ $agent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-assign">
                                            {{ $ticket->agent ? 'Reassign' : 'Assign' }}
                                        </button>
                                    </div>
                                </form>
                                
                                @if($ticket->agent)
                                    <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="unassign-form" style="margin-top: 0.5rem;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="agent_id" value="">
                                        <button type="submit" class="btn btn-unassign" onclick="return confirm('Are you sure you want to unassign this ticket?')">
                                            Unassign Ticket
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="ticket-description">
                <h3 class="section-title">Description</h3>
                <div class="description-content">
                    {{ $ticket->description }}
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <div class="comments-header">
                <h3 class="section-title">Comments & Updates</h3>
                <span class="comments-count">{{ $ticket->comments->count() }} comments</span>
            </div>

            <div class="comments-list">
                @forelse($ticket->comments as $comment)
                    <div class="comment-item">
                        <div class="comment-header">
                            <div class="comment-author">
                                <div class="author-avatar">
                                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                </div>
                                <div class="author-info">
                                    <h4 class="author-name">{{ $comment->user->name }}</h4>
                                    <p class="author-role">{{ ucfirst($comment->user->role) }}</p>
                                </div>
                            </div>
                            <div class="comment-meta">
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                @if($comment->user_id === auth()->id() || auth()->user()->role === 'admin')
                                    <div class="comment-actions">
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Delete comment">🗑️</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="comment-content">
                            {{ $comment->comment }}
                        </div>
                    </div>
                @empty
                    <div class="no-comments">
                        <p>No comments yet. Be the first to add a comment!</p>
                    </div>
                @endforelse
            </div>

            <!-- Add Comment Form -->
            @if($ticket->status !== 'closed')
                <div class="add-comment-section">
                    <h4 class="add-comment-title">Add a Comment</h4>
                    <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST" class="comment-form">
                        @csrf
                        <div class="form-group">
                            <textarea name="comment" rows="4" class="form-textarea @error('comment') error @enderror" 
                                      placeholder="Type your comment here..." required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Add Comment</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="closed-notice">
                    <p>🔒 This ticket is closed. No new comments can be added.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.ticket-details-container {
    display: grid;
    gap: 2rem;
    margin: 2rem 0;
}

.ticket-info-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.ticket-header-info {
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    margin-bottom: 2rem;
}

.ticket-status-section {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
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

.priority-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

.ticket-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.meta-item {
    text-align: center;
    padding: 1rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

.meta-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    margin: 0 0 0.5rem 0;
}

.meta-value {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1rem 0;
}

.description-content {
    background: rgba(249, 250, 251, 0.8);
    border: 1px solid rgba(226, 232, 240, 0.6);
    border-radius: 12px;
    padding: 1.5rem;
    color: #374151;
    line-height: 1.6;
    white-space: pre-wrap;
}

.comments-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.comments-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.comments-count {
    font-size: 0.875rem;
    color: #6b7280;
    background: rgba(249, 250, 251, 0.8);
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

.comments-list {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.comment-item {
    background: rgba(249, 250, 251, 0.8);
    border: 1px solid rgba(226, 232, 240, 0.6);
    border-radius: 16px;
    padding: 1.5rem;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

.author-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.author-role {
    font-size: 0.75rem;
    color: #6b7280;
    margin: 0;
    text-transform: capitalize;
}

.comment-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.comment-time {
    font-size: 0.75rem;
    color: #6b7280;
}

.comment-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-delete {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    font-size: 0.875rem;
    opacity: 0.6;
    transition: opacity 0.2s ease;
}

.btn-delete:hover {
    opacity: 1;
    background: rgba(239, 68, 68, 0.1);
}

.comment-content {
    color: #374151;
    line-height: 1.5;
    white-space: pre-wrap;
}

.no-comments {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
    font-style: italic;
}

.add-comment-section {
    border-top: 1px solid rgba(226, 232, 240, 0.8);
    padding-top: 2rem;
}

.add-comment-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1rem 0;
}

.comment-form {
    display: grid;
    gap: 1rem;
}

.closed-notice {
    text-align: center;
    padding: 1.5rem;
    background: rgba(249, 250, 251, 0.8);
    border: 1px solid rgba(226, 232, 240, 0.6);
    border-radius: 12px;
    color: #6b7280;
    font-style: italic;
}

/* Alert message styles for assignment feedback */
.alert {
    padding: 1rem 1.5rem;
    margin: 1rem 0;
    border-radius: 0.5rem;
    font-weight: 500;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* Assignment Controls */
.agent-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.agent-avatar {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.unassigned-text {
    color: #9ca3af;
    font-style: italic;
}

.assignment-controls-inline {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
}

.assignment-form-inline {
    margin-bottom: 0;
}

.assignment-input-group {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.assignment-select-inline {
    padding: 0.5rem 0.75rem;
    border: 1.5px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    background: white;
    min-width: 180px;
    transition: all 0.2s ease;
}

.assignment-select-inline:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.assignment-select-inline:hover {
    border-color: #9ca3af;
}

.btn-assign {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-assign:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-unassign {
    background: transparent;
    color: #dc2626;
    border: 1px solid #dc2626;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-unassign:hover {
    background: #dc2626;
    color: white;
}

@media (max-width: 768px) {
    .assignment-input-group {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .assignment-select-inline {
        min-width: unset;
        width: 100%;
    }
}

@media (max-width: 768px) {
    .ticket-meta-grid {
        grid-template-columns: 1fr;
    }
    
    .comments-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .comment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .comment-meta {
        align-self: flex-end;
    }
    
    .ticket-status-section {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection