@extends('layouts.app')

@section('title', 'All Tickets - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <div class="admin-title-section">
            <h1 class="heading-xl">All Tickets</h1>
            <p class="text-lead">Manage and monitor all support tickets in the system</p>
        </div>
        <div class="admin-actions">
            <div class="filter-controls">
                <select id="statusFilter" class="form-select-sm" onchange="filterTickets()">
                    <option value="">All Status</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                <select id="priorityFilter" class="form-select-sm" onchange="filterTickets()">
                    <option value="">All Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tickets Overview Stats -->
    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-icon open">📊</div>
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
            <div class="stat-icon urgent">🚨</div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $tickets->where('priority', 'urgent')->count() }}</h3>
                <p class="stat-label">Urgent Priority</p>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="tickets-container">
        <div class="table-wrapper">
            <table class="tickets-table" id="ticketsTable">
                <thead>
                    <tr>
                        <th>Ticket #</th>
                        <th>Title</th>
                        <th>Customer</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="ticket-row" 
                            data-status="{{ $ticket->status }}" 
                            data-priority="{{ $ticket->priority }}">
                            <td class="ticket-id">#{{ $ticket->id }}</td>
                            <td class="ticket-title">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="ticket-link">
                                    {{ Str::limit($ticket->title, 50) }}
                                </a>
                            </td>
                            <td class="ticket-customer">
                                <div class="user-info">
                                    <div class="user-avatar-sm">
                                        {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                    </div>
                                    <div class="user-details">
                                        <span class="user-name">{{ $ticket->user->name }}</span>
                                        <span class="user-email">{{ $ticket->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="ticket-category">
                                <span class="category-tag">
                                    {{ $ticket->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="ticket-priority">
                                <span class="priority-badge {{ $ticket->priority }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="ticket-status">
                                <span class="status-badge {{ str_replace(' ', '_', strtolower($ticket->status)) }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="ticket-agent">
                                @if($ticket->agent_id)
                                    <div class="agent-info">
                                        <div class="user-avatar-xs">
                                            {{ strtoupper(substr($ticket->agent->name, 0, 2)) }}
                                        </div>
                                        <span class="agent-name">{{ $ticket->agent->name }}</span>
                                    </div>
                                @else
                                    <span class="unassigned">Unassigned</span>
                                @endif
                            </td>
                            <td class="ticket-date">
                                <span class="date-primary">{{ $ticket->created_at->format('M d, Y') }}</span>
                                <span class="date-secondary">{{ $ticket->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="ticket-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                       class="btn btn-xs btn-outline" title="View Details">
                                        👁️
                                    </a>
                                    <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                                       class="btn btn-xs btn-primary" title="Edit Ticket">
                                        ✏️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-table">
                                <div class="empty-state">
                                    <div class="empty-icon">🎫</div>
                                    <h3 class="empty-title">No Tickets Found</h3>
                                    <p class="empty-description">No support tickets have been created yet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="pagination-wrapper">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
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

.stat-icon.urgent {
    background: rgba(239, 68, 68, 0.1);
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

.filter-controls {
    display: flex;
    gap: 1rem;
}

.form-select-sm {
    padding: 0.5rem 0.75rem;
    border: 1px solid rgba(209, 213, 219, 0.8);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.95);
    font-size: 0.875rem;
    min-width: 120px;
}

.tickets-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.table-wrapper {
    overflow-x: auto;
}

.tickets-table {
    width: 100%;
    border-collapse: collapse;
}

.tickets-table th {
    background: rgba(249, 250, 251, 0.95);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    white-space: nowrap;
}

.tickets-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(243, 244, 246, 0.8);
    vertical-align: top;
}

.ticket-row:hover {
    background: rgba(249, 250, 251, 0.5);
}

.ticket-id {
    font-weight: 600;
    color: #6366f1;
    font-family: 'Courier New', monospace;
}

.ticket-link {
    color: #111827;
    text-decoration: none;
    font-weight: 500;
}

.ticket-link:hover {
    color: #6366f1;
    text-decoration: underline;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar-sm {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    flex-shrink: 0;
}

.user-avatar-xs {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.625rem;
    font-weight: 600;
    color: white;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 500;
    color: #111827;
    font-size: 0.875rem;
}

.user-email {
    font-size: 0.75rem;
    color: #6b7280;
}

.category-tag {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
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

.agent-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.agent-name {
    font-size: 0.875rem;
    color: #111827;
}

.unassigned {
    color: #6b7280;
    font-style: italic;
    font-size: 0.875rem;
}

.date-primary {
    display: block;
    font-size: 0.875rem;
    color: #111827;
    font-weight: 500;
}

.date-secondary {
    display: block;
    font-size: 0.75rem;
    color: #6b7280;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.empty-table {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.empty-description {
    color: #6b7280;
    margin: 0;
}

.pagination-wrapper {
    padding: 1.5rem;
    background: rgba(249, 250, 251, 0.95);
    border-top: 1px solid rgba(226, 232, 240, 0.8);
}

@media (max-width: 768px) {
    .filter-controls {
        flex-direction: column;
        width: 100%;
    }
    
    .form-select-sm {
        width: 100%;
    }
    
    .stats-overview {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .tickets-table th,
    .tickets-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<script>
function filterTickets() {
    const statusFilter = document.getElementById('statusFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    const rows = document.querySelectorAll('.ticket-row');

    rows.forEach(row => {
        const status = row.dataset.status;
        const priority = row.dataset.priority;
        
        const statusMatch = !statusFilter || status === statusFilter;
        const priorityMatch = !priorityFilter || priority === priorityFilter;
        
        if (statusMatch && priorityMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection