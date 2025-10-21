@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="page-title-section">
            <h1 class="heading-xl">My Tickets</h1>
            <p class="text-lead">Track and manage your support requests</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create New Ticket</a>
        </div>
    </div>

    <!-- Tickets Overview Stats -->
    @if(auth()->user()->role === 'customer')
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon open">📋</div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $tickets->where('status', 'open')->count() }}</h3>
                    <p class="stat-label">Open Tickets</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon progress">⚡</div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $tickets->where('status', 'in_progress')->count() }}</h3>
                    <p class="stat-label">In Progress</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon resolved">✅</div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $tickets->where('status', 'resolved')->count() }}</h3>
                    <p class="stat-label">Resolved</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon total">🎫</div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $tickets->total() }}</h3>
                    <p class="stat-label">Total Tickets</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Controls -->
    <div class="filter-section">
        <div class="filter-card">
            <form method="GET" action="{{ route('tickets.index') }}" class="filter-form">
                <div class="filter-group">
                    <label for="status" class="filter-label">Status</label>
                    <select name="status" id="status" class="filter-select">
                        <option value="">All Statuses</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="priority" class="filter-label">Priority</label>
                    <select name="priority" id="priority" class="filter-select">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="tickets-container">
        @forelse($tickets as $ticket)
            <div class="ticket-card">
                <div class="ticket-header">
                    <div class="ticket-meta">
                        <span class="ticket-number">#{{ $ticket->id }}</span>
                        <span class="ticket-date">{{ $ticket->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="ticket-status">
                        <span class="status-badge {{ str_replace(' ', '_', strtolower($ticket->status)) }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="priority-badge {{ $ticket->priority }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                </div>

                <div class="ticket-content">
                    <h3 class="ticket-title">
                        <a href="{{ route('tickets.show', $ticket) }}" class="ticket-link">
                            {{ $ticket->title }}
                        </a>
                    </h3>
                    <p class="ticket-description">{{ Str::limit($ticket->description, 150) }}</p>
                </div>

                <div class="ticket-footer">
                    <div class="ticket-info">
                        <span class="ticket-category">
                            📂 {{ $ticket->category->name ?? 'Uncategorized' }}
                        </span>
                        @if($ticket->agent)
                            <span class="ticket-agent">
                                👤 Assigned to {{ $ticket->agent->name }}
                            </span>
                        @else
                            <span class="ticket-unassigned">
                                ⏳ Awaiting assignment
                            </span>
                        @endif
                    </div>
                    <div class="ticket-actions">
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline">View Details</a>
                        @if($ticket->status !== 'closed' && auth()->user()->role === 'customer')
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-primary">Edit</a>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">🎫</div>
                <h3 class="empty-title">No Tickets Found</h3>
                <p class="empty-description">
                    @if(request()->hasAny(['status', 'priority']))
                        No tickets match your current filters. Try adjusting your search criteria.
                    @else
                        You haven't created any support tickets yet. Create your first ticket to get started.
                    @endif
                </p>
                <div class="empty-actions">
                    @if(request()->hasAny(['status', 'priority']))
                        <a href="{{ route('tickets.index') }}" class="btn btn-outline">Clear Filters</a>
                    @endif
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create First Ticket</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
        <div class="pagination-wrapper">
            {{ $tickets->withQueryString()->links() }}
        </div>
    @endif
</div>

<style>
.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin: 2rem 0;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-icon.open {
    background: rgba(59, 130, 246, 0.1);
}

.stat-icon.progress {
    background: rgba(251, 191, 36, 0.1);
}

.stat-icon.resolved {
    background: rgba(34, 197, 94, 0.1);
}

.stat-icon.total {
    background: rgba(139, 92, 246, 0.1);
}

.stat-number {
    font-size: 1.875rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.filter-section {
    margin: 2rem 0;
}

.filter-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.filter-form {
    display: flex;
    align-items: end;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.filter-select {
    padding: 0.5rem 0.75rem;
    border: 1px solid rgba(209, 213, 219, 0.8);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.95);
    font-size: 0.875rem;
    min-width: 140px;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.tickets-container {
    display: grid;
    gap: 1.5rem;
    margin: 2rem 0;
}

.ticket-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.ticket-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.ticket-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.ticket-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.ticket-number {
    font-weight: 600;
    color: #6366f1;
    font-family: 'Courier New', monospace;
}

.ticket-date {
    font-size: 0.875rem;
    color: #6b7280;
}

.ticket-status {
    display: flex;
    gap: 0.5rem;
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

.priority-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
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

.ticket-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.ticket-link {
    color: #111827;
    text-decoration: none;
}

.ticket-link:hover {
    color: #6366f1;
    text-decoration: underline;
}

.ticket-description {
    color: #6b7280;
    margin: 0 0 1rem 0;
    line-height: 1.5;
}

.ticket-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
}

.ticket-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.ticket-category,
.ticket-agent,
.ticket-unassigned {
    font-size: 0.875rem;
    color: #6b7280;
}

.ticket-actions {
    display: flex;
    gap: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 2px dashed rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    margin: 2rem 0;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.empty-description {
    color: #000000 !important;
    margin: 0 0 2rem 0;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.5;
}

.empty-state p {
    color: #000000 !important;
}

.empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.pagination-wrapper {
    margin: 2rem 0;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .stats-overview {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-actions {
        justify-content: center;
    }
    
    .ticket-header,
    .ticket-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .ticket-status {
        align-self: flex-end;
    }
    
    .ticket-actions {
        align-self: stretch;
        justify-content: center;
    }
    
    .empty-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endsection