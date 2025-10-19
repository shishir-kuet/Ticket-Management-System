@extends('layouts.app')

@section('title', 'User Details - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <div class="admin-title-section">
            <h1 class="heading-xl">User Details</h1>
            <div class="admin-actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit User</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Back to Users</a>
            </div>
        </div>
    </div>

    <div class="user-profile-container">
        <div class="user-profile-card">
            <!-- User Basic Info -->
            <div class="user-profile-header">
                <div class="user-avatar-large">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="user-profile-info">
                    <h2 class="user-profile-name">{{ $user->name }}</h2>
                    <p class="user-profile-email">{{ $user->email }}</p>
                    <div class="user-profile-badges">
                        @if($user->role === 'admin')
                            <span class="role-badge admin">Administrator</span>
                        @elseif($user->role === 'agent')
                            <span class="role-badge agent">Support Agent</span>
                        @else
                            <span class="role-badge customer">Customer</span>
                        @endif
                        <span class="status-badge active">Active</span>
                    </div>
                </div>
            </div>

            <!-- User Details Grid -->
            <div class="user-details-grid">
                <div class="detail-item">
                    <h3 class="detail-label">Member Since</h3>
                    <p class="detail-value">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div class="detail-item">
                    <h3 class="detail-label">Last Activity</h3>
                    <p class="detail-value">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
                <div class="detail-item">
                    <h3 class="detail-label">User ID</h3>
                    <p class="detail-value">#{{ $user->id }}</p>
                </div>
                <div class="detail-item">
                    <h3 class="detail-label">Email Verified</h3>
                    <p class="detail-value">
                        @if($user->email_verified_at)
                            <span class="status-success">Verified</span>
                        @else
                            <span class="status-warning">Not Verified</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- User Activity/Tickets Section -->
        <div class="user-activity-section">
            <h3 class="section-title">Recent Activity</h3>
            
            @if($user->role === 'customer')
                <!-- Customer Tickets -->
                <div class="activity-card">
                    <h4 class="activity-title">Submitted Tickets</h4>
                    @if($user->tickets && $user->tickets->count() > 0)
                        <div class="ticket-list">
                            @foreach($user->tickets->take(5) as $ticket)
                                <div class="ticket-item">
                                    <div class="ticket-info">
                                        <h5 class="ticket-title">{{ $ticket->title }}</h5>
                                        <p class="ticket-meta">{{ $ticket->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="ticket-status">
                                        <span class="status-badge {{ strtolower($ticket->status) }}">{{ ucfirst($ticket->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="empty-state">No tickets submitted yet</p>
                    @endif
                </div>
            @elseif($user->role === 'agent')
                <!-- Agent Assigned Tickets -->
                <div class="activity-card">
                    <h4 class="activity-title">Assigned Tickets</h4>
                    @if($user->assignedTickets && $user->assignedTickets->count() > 0)
                        <div class="ticket-list">
                            @foreach($user->assignedTickets->take(5) as $ticket)
                                <div class="ticket-item">
                                    <div class="ticket-info">
                                        <h5 class="ticket-title">{{ $ticket->title }}</h5>
                                        <p class="ticket-meta">Assigned {{ $ticket->updated_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="ticket-status">
                                        <span class="status-badge {{ strtolower($ticket->status) }}">{{ ucfirst($ticket->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="empty-state">No tickets assigned yet</p>
                    @endif
                </div>
            @endif

            <!-- Recent Comments -->
            @if($user->comments && $user->comments->count() > 0)
                <div class="activity-card">
                    <h4 class="activity-title">Recent Comments</h4>
                    <div class="comment-list">
                        @foreach($user->comments->take(3) as $comment)
                            <div class="comment-item">
                                <p class="comment-content">{{ Str::limit($comment->content, 120) }}</p>
                                <p class="comment-meta">
                                    On ticket: <strong>{{ $comment->ticket->title }}</strong> • 
                                    {{ $comment->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Danger Zone (for admins) -->
    @if(auth()->user()->role === 'admin' && $user->id !== auth()->id())
        <div class="danger-zone">
            <h3 class="danger-title">Danger Zone</h3>
            <div class="danger-actions">
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    @endif
</div>

<style>
.user-profile-container {
    display: grid;
    gap: 2rem;
    margin-bottom: 2rem;
}

.user-profile-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.user-profile-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    margin-bottom: 2rem;
}

.user-avatar-large {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
}

.user-profile-info {
    flex: 1;
}

.user-profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.user-profile-email {
    color: #6b7280;
    margin: 0 0 1rem 0;
}

.user-profile-badges {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.user-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    text-align: center;
    padding: 1rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    margin: 0 0 0.5rem 0;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.user-activity-section {
    display: grid;
    gap: 1.5rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.activity-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.activity-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1rem 0;
}

.ticket-list, .comment-list {
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

.comment-item {
    padding: 1rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

.comment-content {
    font-size: 0.875rem;
    color: #374151;
    margin: 0 0 0.5rem 0;
    line-height: 1.5;
}

.comment-meta {
    font-size: 0.75rem;
    color: #6b7280;
    margin: 0;
}

.empty-state {
    text-align: center;
    color: #6b7280;
    font-style: italic;
    margin: 2rem 0;
}

.status-success {
    color: #059669;
    font-weight: 500;
}

.status-warning {
    color: #d97706;
    font-weight: 500;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.open {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.in_progress {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.resolved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.closed {
    background: #f3f4f6;
    color: #374151;
}

.danger-zone {
    background: rgba(254, 242, 242, 0.95);
    border: 1px solid rgba(252, 165, 165, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.danger-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #dc2626;
    margin: 0 0 1rem 0;
}

.danger-actions {
    display: flex;
    gap: 1rem;
}

@media (max-width: 768px) {
    .user-profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .user-details-grid {
        grid-template-columns: 1fr;
    }
    
    .ticket-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>
@endsection