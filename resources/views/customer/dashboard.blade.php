@extends('layouts.app')

@section('title', 'My Dashboard - Resolve AI')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <h1 class="heading-xl">My Support Dashboard</h1>
        <p class="text-lead">Track your support tickets and get help when you need it</p>
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
                <p class="stat-label">Resolved</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-actions">
        <h2 class="heading-md">Quick Actions</h2>
        <div class="action-buttons-grid">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Ticket
            </a>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                View All My Tickets
            </a>
        </div>
    </div>

    <!-- Support Categories -->
    <div class="customer-help-section">
        <h2 class="heading-md">Need Help? Choose a Category</h2>
        <div class="categories-grid">
            @foreach($categories as $category)
                <div class="category-card">
                    <div class="category-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h3 class="category-title">{{ $category->name }}</h3>
                    @if($category->description)
                        <p class="category-description">{{ $category->description }}</p>
                    @endif
                    <a href="{{ route('help') }}" class="btn btn-outline btn-sm">Get Help</a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Tickets -->
    <div class="customer-tickets-section">
        <h2 class="heading-md">My Recent Tickets</h2>
        <div class="admin-section">
            <div class="tickets-list">
                @forelse($my_tickets as $ticket)
                    <div class="ticket-item">
                        <div class="ticket-content">
                            <h4 class="ticket-title">
                                <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a>
                            </h4>
                            <p class="ticket-meta">
                                <span class="priority-badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span> •
                                <span class="status-badge status-{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                @if($ticket->assignedAgent)
                                    • Assigned to {{ $ticket->assignedAgent->name }}
                                @else
                                    • Unassigned
                                @endif
                            </p>
                        </div>
                        <div class="ticket-date">
                            {{ $ticket->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-16 h-16 mx-auto mb-4 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="empty-title">No Support Tickets Yet</h3>
                        <p class="text-muted">You haven't created any support tickets. When you need help, create a ticket and our team will assist you.</p>
                        <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create Your First Ticket</a>
                    </div>
                @endforelse
            </div>
            @if($my_tickets->count() > 0)
                <div class="section-footer">
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline btn-sm">View All My Tickets</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.customer-help-section {
    margin-bottom: 3rem;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.category-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    text-align: center;
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.category-icon {
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.category-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.category-description {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.customer-tickets-section {
    margin-bottom: 2rem;
}

.empty-state {
    padding: 3rem 1.5rem;
    text-align: center;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin-bottom: 1.5rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    color: #000000 !important;
}

.empty-state .text-muted {
    color: #000000 !important;
}

@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Chatbot Integration -->
<x-chatbot context="customer" />
@endsection