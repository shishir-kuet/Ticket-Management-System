@extends('layouts.app')

@section('title', 'Reports & Analytics - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <div class="admin-title-section">
            <h1 class="heading-xl">Reports & Analytics</h1>
            <p class="text-lead">Comprehensive insights into your ticket management system</p>
        </div>
        <div class="admin-actions">
            <button onclick="exportReport()" class="btn btn-outline">Export Report</button>
            <button onclick="refreshData()" class="btn btn-primary">Refresh Data</button>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">🎫</div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total_tickets'] ?? 0 }}</h3>
                <p class="stat-label">Total Tickets</p>
                <span class="stat-trend positive">+{{ $stats['new_tickets_this_month'] ?? 0 }} this month</span>
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['resolved_tickets'] ?? 0 }}</h3>
                <p class="stat-label">Resolved Tickets</p>
                <span class="stat-trend positive">{{ number_format(($stats['resolved_tickets'] ?? 0) / max(($stats['total_tickets'] ?? 1), 1) * 100, 1) }}% resolution rate</span>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['pending_tickets'] ?? 0 }}</h3>
                <p class="stat-label">Pending Tickets</p>
                <span class="stat-trend neutral">{{ $stats['open_tickets'] ?? 0 }} open, {{ $stats['in_progress_tickets'] ?? 0 }} in progress</span>
            </div>
        </div>
        
        <div class="stat-card info">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['active_users'] ?? 0 }}</h3>
                <p class="stat-label">Active Users</p>
                <span class="stat-trend positive">{{ $stats['new_users_this_month'] ?? 0 }} new this month</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Tickets by Priority Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Tickets by Priority</h3>
                <p class="chart-subtitle">Distribution of ticket priorities across the system</p>
            </div>
            <div class="chart-content">
                <div class="priority-chart">
                    @if(isset($ticketsByPriority) && $ticketsByPriority->count() > 0)
                        @foreach($ticketsByPriority as $priority => $count)
                            @php
                                $percentage = ($count / $ticketsByPriority->sum()) * 100;
                                $priorityClass = match($priority) {
                                    'urgent' => 'urgent',
                                    'high' => 'high',
                                    'medium' => 'medium',
                                    'low' => 'low',
                                    default => 'low'
                                };
                            @endphp
                            <div class="priority-item">
                                <div class="priority-info">
                                    <span class="priority-label {{ $priorityClass }}">{{ ucfirst($priority) }}</span>
                                    <span class="priority-count">{{ $count }} tickets</span>
                                </div>
                                <div class="priority-bar">
                                    <div class="priority-fill {{ $priorityClass }}" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="priority-percentage">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-chart">
                            <p>No priority data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tickets by Category Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Tickets by Category</h3>
                <p class="chart-subtitle">Ticket distribution across different categories</p>
            </div>
            <div class="chart-content">
                <div class="category-chart">
                    @if(isset($ticketsByCategory) && $ticketsByCategory->count() > 0)
                        @foreach($ticketsByCategory as $category => $count)
                            @php
                                $percentage = ($count / $ticketsByCategory->sum()) * 100;
                            @endphp
                            <div class="category-item">
                                <div class="category-info">
                                    <span class="category-label">{{ $category ?: 'Uncategorized' }}</span>
                                    <span class="category-count">{{ $count }} tickets</span>
                                </div>
                                <div class="category-bar">
                                    <div class="category-fill" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="category-percentage">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-chart">
                            <p>No category data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="performance-section">
        <div class="performance-card">
            <div class="performance-header">
                <h3 class="performance-title">System Performance</h3>
                <p class="performance-subtitle">Key performance indicators for the support system</p>
            </div>
            <div class="performance-grid">
                <div class="metric-item">
                    <div class="metric-value">{{ $stats['avg_response_time'] ?? '2.5' }}</div>
                    <div class="metric-label">Avg Response Time (hours)</div>
                    <div class="metric-trend good">↓ 15% from last month</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $stats['avg_resolution_time'] ?? '4.2' }}</div>
                    <div class="metric-label">Avg Resolution Time (days)</div>
                    <div class="metric-trend good">↓ 8% from last month</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $stats['customer_satisfaction'] ?? '4.6' }}</div>
                    <div class="metric-label">Customer Satisfaction</div>
                    <div class="metric-trend excellent">↑ 3% from last month</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ $stats['first_contact_resolution'] ?? '78' }}%</div>
                    <div class="metric-label">First Contact Resolution</div>
                    <div class="metric-trend good">↑ 5% from last month</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="activity-section">
        <div class="activity-card">
            <div class="activity-header">
                <h3 class="activity-title">Recent System Activity</h3>
                <p class="activity-subtitle">Latest updates and changes in the system</p>
            </div>
            <div class="activity-timeline">
                <div class="timeline-item">
                    <div class="timeline-marker created"></div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">New ticket created</h4>
                        <p class="timeline-desc">High priority ticket #{{ rand(1000, 9999) }} reported by John Doe</p>
                        <span class="timeline-time">{{ now()->subMinutes(15)->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker resolved"></div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Ticket resolved</h4>
                        <p class="timeline-desc">Support agent resolved ticket #{{ rand(1000, 9999) }}</p>
                        <span class="timeline-time">{{ now()->subHour()->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker user"></div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">New user registered</h4>
                        <p class="timeline-desc">Jane Smith joined as a customer</p>
                        <span class="timeline-time">{{ now()->subHours(3)->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker category"></div>
                    <div class="timeline-content">
                        <h4 class="timeline-title">Category updated</h4>
                        <p class="timeline-desc">Technical Support category description updated</p>
                        <span class="timeline-time">{{ now()->subHours(5)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

.stat-card.primary {
    border-left: 4px solid #6366f1;
}

.stat-card.success {
    border-left: 4px solid #10b981;
}

.stat-card.warning {
    border-left: 4px solid #f59e0b;
}

.stat-card.info {
    border-left: 4px solid #3b82f6;
}

.stat-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0.25rem 0 0 0;
}

.stat-trend {
    font-size: 0.75rem;
    font-weight: 500;
    margin-top: 0.5rem;
}

.stat-trend.positive {
    color: #059669;
}

.stat-trend.neutral {
    color: #6b7280;
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.chart-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.chart-header {
    margin-bottom: 2rem;
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.chart-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.priority-chart,
.category-chart {
    display: grid;
    gap: 1rem;
}

.priority-item,
.category-item {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 1rem;
}

.priority-info,
.category-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.priority-label {
    font-weight: 500;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.priority-label.urgent {
    background: #fee2e2;
    color: #dc2626;
}

.priority-label.high {
    background: #fef3c7;
    color: #d97706;
}

.priority-label.medium {
    background: #dbeafe;
    color: #1e40af;
}

.priority-label.low {
    background: #f3f4f6;
    color: #6b7280;
}

.category-label {
    font-weight: 500;
    color: #111827;
}

.priority-count,
.category-count {
    font-size: 0.875rem;
    color: #6b7280;
}

.priority-bar,
.category-bar {
    height: 8px;
    background: rgba(243, 244, 246, 0.8);
    border-radius: 4px;
    overflow: hidden;
    margin: 0 1rem;
    flex: 1;
}

.priority-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.priority-fill.urgent {
    background: #dc2626;
}

.priority-fill.high {
    background: #d97706;
}

.priority-fill.medium {
    background: #1e40af;
}

.priority-fill.low {
    background: #6b7280;
}

.category-fill {
    height: 100%;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.priority-percentage,
.category-percentage {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    min-width: 50px;
    text-align: right;
}

.performance-section {
    margin: 2rem 0;
}

.performance-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.performance-header {
    margin-bottom: 2rem;
}

.performance-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.performance-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.performance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.metric-item {
    text-align: center;
    padding: 1.5rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.metric-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.75rem;
}

.metric-trend {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
}

.metric-trend.good {
    background: #d1fae5;
    color: #065f46;
}

.metric-trend.excellent {
    background: #dbeafe;
    color: #1e40af;
}

.activity-section {
    margin: 2rem 0;
}

.activity-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.activity-header {
    margin-bottom: 2rem;
}

.activity-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.activity-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

.activity-timeline {
    display: grid;
    gap: 1.5rem;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.timeline-marker.created {
    background: #3b82f6;
}

.timeline-marker.resolved {
    background: #10b981;
}

.timeline-marker.user {
    background: #8b5cf6;
}

.timeline-marker.category {
    background: #f59e0b;
}

.timeline-content {
    flex: 1;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.timeline-desc {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 0.5rem 0;
}

.timeline-time {
    font-size: 0.75rem;
    color: #9ca3af;
}

.empty-chart {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
    font-style: italic;
}

@media (max-width: 768px) {
    .stats-grid,
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .performance-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .priority-item,
    .category-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .priority-info,
    .category-info {
        margin-bottom: 0.5rem;
    }
    
    .priority-bar,
    .category-bar {
        margin: 0;
    }
    
    .priority-percentage,
    .category-percentage {
        text-align: left;
        margin-top: 0.5rem;
    }
}
</style>

<script>
function exportReport() {
    alert('Export functionality would be implemented here');
}

function refreshData() {
    location.reload();
}
</script>
@endsection