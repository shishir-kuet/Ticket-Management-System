@extends('layouts.app')

@section('title', 'Admin Dashboard - Resolve AI')

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

    <div class="admin-header">
        <h1 class="heading-xl">Admin Dashboard</h1>
        <p class="text-lead">System overview and management controls</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total_tickets'] }}</h3>
                <p class="stat-label">Total Tickets</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['open_tickets'] }}</h3>
                <p class="stat-label">Open Tickets</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['in_progress_tickets'] }}</h3>
                <p class="stat-label">In Progress</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['closed_tickets'] }}</h3>
                <p class="stat-label">Closed Tickets</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total_users'] }}</h3>
                <p class="stat-label">Total Users</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-agent">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['agents'] }}</h3>
                <p class="stat-label">Agents</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['unassigned_tickets'] }}</h3>
                <p class="stat-label">Unassigned Tickets</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-actions">
        <h2 class="heading-md">Quick Actions</h2>
        <div class="action-buttons-grid">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add User
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Add Category
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Manage Users
            </a>
            <a href="{{ route('admin.reports') }}" class="btn btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Reports
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="admin-content-grid">
        <!-- Unassigned Tickets - Assignment Section -->
        <div class="admin-section">
            <h2 class="heading-md">Unassigned Tickets 
                <span class="section-badge">{{ $unassigned_tickets->count() }}</span>
            </h2>
            <div class="tickets-list">
                @forelse($unassigned_tickets as $ticket)
                    <div class="ticket-item assignment-item">
                        <div class="ticket-content">
                            <h4 class="ticket-title">
                                <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a>
                            </h4>
                            <p class="ticket-meta">
                                By {{ $ticket->customer->name }} • 
                                <span class="priority-badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span> •
                                <span class="status-badge status-{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                            </p>
                            <p class="ticket-description">{{ Str::limit($ticket->description, 80) }}</p>
                        </div>
                        <div class="assignment-section">
                            <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="assignment-form">
                                @csrf
                                @method('PATCH')
                                <div class="assignment-controls">
                                    <select name="agent_id" class="assignment-select" required>
                                        <option value="">Select Agent</option>
                                        @foreach($available_agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                                </div>
                            </form>
                            <div class="ticket-date">
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3>All Tickets Assigned!</h3>
                        <p>Great job! All open tickets have been assigned to agents.</p>
                    </div>
                @endforelse
            </div>
            @if($unassigned_tickets->count() > 0)
                <div class="section-footer">
                    <a href="{{ route('tickets.index') }}?filter=unassigned" class="btn btn-outline btn-sm">View All Unassigned</a>
                </div>
            @endif
        </div>

        <!-- Recent Tickets -->
        <div class="admin-section">
            <h2 class="heading-md">Recent Tickets</h2>
            <div class="tickets-list">
                @forelse($recent_tickets as $ticket)
                    <div class="ticket-item">
                        <div class="ticket-content">
                            <h4 class="ticket-title">
                                <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a>
                            </h4>
                            <p class="ticket-meta">
                                By {{ $ticket->customer->name }} • 
                                <span class="priority-badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span> •
                                <span class="status-badge status-{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                @if($ticket->agent)
                                    • Assigned to {{ $ticket->agent->name }}
                                @endif
                            </p>
                        </div>
                        <div class="ticket-date">
                            {{ $ticket->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No tickets found.</p>
                @endforelse
            </div>
            <div class="section-footer">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline btn-sm">View All Tickets</a>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="admin-section">
            <h2 class="heading-md">Recent Users</h2>
            <div class="users-list">
                @forelse($recent_users as $user)
                    <div class="user-item">
                        <div class="user-avatar">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div class="user-content">
                            <h4 class="user-name">{{ $user->name }}</h4>
                            <p class="user-meta">{{ $user->email }} • <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></p>
                        </div>
                        <div class="user-date">
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No users found.</p>
                @endforelse
            </div>
            <div class="section-footer">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">View All Users</a>
            </div>
        </div>
    </div>
</div>

<!-- Chatbot Integration -->
<x-chatbot context="admin" />

<style>
/* Assignment Interface Styles */
.section-badge {
    background: #fef3c7;
    color: #d97706;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.assignment-item {
    flex-direction: column;
    align-items: stretch;
    gap: 1rem;
}

.assignment-item .ticket-content {
    flex: 1;
}

.ticket-description {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0.5rem 0 0 0;
    line-height: 1.4;
}

.assignment-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(226, 232, 240, 0.5);
}

.assignment-form {
    flex: 1;
    margin-right: 1rem;
}

.assignment-controls {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.assignment-select {
    padding: 0.5rem 0.75rem;
    border: 1.5px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    background: white;
    min-width: 160px;
    transition: all 0.2s ease;
}

.assignment-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.assignment-select:hover {
    border-color: #9ca3af;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6b7280;
}

.empty-icon {
    margin: 0 auto 1rem auto;
    width: 3rem;
    height: 3rem;
    color: #10b981;
}

.empty-state h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}

/* Enhanced grid layout for better spacing */
.admin-content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

@media (max-width: 1024px) {
    .admin-content-grid {
        grid-template-columns: 1fr;
    }
    
    .assignment-controls {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .assignment-section {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .assignment-form {
        margin-right: 0;
    }
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
</style>
@endsection