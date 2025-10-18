@extends('layouts.app')

@section('title', 'Agent Dashboard - Resolve AI')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <h1 class="heading-xl">Agent Dashboard</h1>
        <p class="text-lead">Manage your assigned tickets and help customers efficiently</p>
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
                <h3 class="stat-number">{{ $stats['assigned_tickets'] }}</h3>
                <p class="stat-label">Assigned Tickets</p>
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
                <h3 class="stat-number">{{ $stats['completed_today'] }}</h3>
                <p class="stat-label">Completed Today</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-actions">
        <h2 class="heading-md">Quick Actions</h2>
        <div class="action-buttons-grid">
            <a href="{{ route('tickets.index') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                View All Tickets
            </a>
            <a href="{{ route('tickets.create') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Ticket
            </a>
        </div>
    </div>

    <!-- Ticket Management -->
    <div class="admin-content-grid">
        <!-- My Tickets -->
        <div class="admin-section">
            <h2 class="heading-md">My Active Tickets ({{ $my_tickets->count() }})</h2>
            <div class="tickets-list">
                @forelse($my_tickets as $ticket)
                    <div class="ticket-item">
                        <div class="ticket-content">
                            <h4 class="ticket-title">
                                <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a>
                            </h4>
                            <p class="ticket-meta">
                                By {{ $ticket->customer->name }} • 
                                <span class="priority-badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span> •
                                <span class="status-badge status-{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                            </p>
                        </div>
                        <div class="ticket-actions">
                            <div class="ticket-date">{{ $ticket->created_at->diffForHumans() }}</div>
                            <div class="ticket-action-buttons">
                                @if($ticket->status === 'open')
                                    <form action="{{ route('tickets.status', $ticket) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button type="submit" class="btn btn-sm btn-primary">Start Work</button>
                                    </form>
                                @elseif($ticket->status === 'in_progress')
                                    <form action="{{ route('tickets.status', $ticket) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="closed">
                                        <button type="submit" class="btn btn-sm btn-success">Mark Complete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="stat-content" style="text-align: center; margin-bottom: 1rem;">
                            <h3 style="font-size: 2rem; color: #6b7280; margin: 0;">0</h3>
                            <p class="stat-label">Active Tickets</p>
                        </div>
                        <p class="text-muted">No active tickets assigned to you.</p>
                        <a href="{{ route('tickets.index') }}" class="btn btn-outline btn-sm">Browse Available Tickets</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Unassigned Tickets -->
        <div class="admin-section">
            <h2 class="heading-md">Available Tickets ({{ $unassigned_tickets->count() }})</h2>
            <div class="tickets-list">
                @forelse($unassigned_tickets as $ticket)
                    <div class="ticket-item">
                        <div class="ticket-content">
                            <h4 class="ticket-title">
                                <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a>
                            </h4>
                            <p class="ticket-meta">
                                By {{ $ticket->customer->name }} • 
                                <span class="priority-badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span>
                            </p>
                        </div>
                        <div class="ticket-actions">
                            <div class="ticket-date">{{ $ticket->created_at->diffForHumans() }}</div>
                            <form action="{{ route('tickets.assign', $ticket) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="agent_id" value="{{ auth()->id() }}">
                                <button type="submit" class="btn btn-sm btn-outline">Take Ticket</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="stat-content" style="text-align: center; margin-bottom: 1rem;">
                            <h3 style="font-size: 2rem; color: #6b7280; margin: 0;">0</h3>
                            <p class="stat-label">Available Tickets</p>
                        </div>
                        <p class="text-muted">No unassigned tickets available at the moment.</p>
                    </div>
                @endforelse
            </div>
            @if($unassigned_tickets->count() > 0)
                <div class="section-footer">
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline btn-sm">View All Available Tickets</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.ticket-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

.ticket-action-buttons {
    display: flex;
    gap: 0.5rem;
}

.empty-state {
    padding: 2rem 1.5rem;
    text-align: center;
}

.empty-state p {
    margin-bottom: 1rem;
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
}

@media (max-width: 768px) {
    .ticket-actions {
        align-items: flex-start;
        margin-top: 0.5rem;
    }
    
    .ticket-action-buttons {
        flex-wrap: wrap;
    }
}
</style>
@endsection