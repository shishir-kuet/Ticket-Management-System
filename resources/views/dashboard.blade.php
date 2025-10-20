<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dashboard - {{ config('app.name', 'Ticket System') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Instrument Sans', system-ui, sans-serif;
                background: #f9fafb;
                color: #111827;
                min-height: 100vh;
            }
            @media (prefers-color-scheme: dark) {
                body { background: #0f1117; color: #f3f4f6; }
            }
            .header {
                background: white;
                border-bottom: 1px solid #e5e7eb;
                padding: 1rem 0;
            }
            @media (prefers-color-scheme: dark) {
                .header { background: #1f2937; border-color: #374151; }
            }
            .header-content {
                max-width: 1024px;
                margin: 0 auto;
                padding: 0 1.5rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .header h1 { font-size: 1.25rem; font-weight: 600; }
            .header .user-menu {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            .header .user-info { font-size: 0.875rem; color: #6b7280; }
            @media (prefers-color-scheme: dark) {
                .header .user-info { color: #9ca3af; }
            }
            .btn {
                padding: 0.5rem 1rem;
                border-radius: 6px;
                text-decoration: none;
                font-size: 0.875rem;
                display: inline-block;
                border: none;
                cursor: pointer;
                font-family: inherit;
            }
            .btn-outline {
                border: 1px solid #d1d5db;
                color: #374151;
                background: transparent;
            }
            .btn-outline:hover { background: #f9fafb; }
            @media (prefers-color-scheme: dark) {
                .btn-outline { border-color: #4b5563; color: #d1d5db; }
                .btn-outline:hover { background: #1f2937; }
            }
            .btn-primary {
                background: #2563eb;
                color: white;
            }
            .btn-primary:hover { background: #1d4ed8; }
            .btn-danger {
                background: #dc2626;
                color: white;
            }
            .btn-danger:hover { background: #b91c1c; }
            .container {
                max-width: 1024px;
                margin: 0 auto;
                padding: 2rem 1.5rem;
            }
            .welcome-card {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 2rem;
                margin-bottom: 2rem;
            }
            @media (prefers-color-scheme: dark) {
                .welcome-card { background: #1f2937; border-color: #374151; }
            }
            .welcome-card h2 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }
            .welcome-card p {
                color: #6b7280;
                margin-bottom: 1.5rem;
            }
            @media (prefers-color-scheme: dark) {
                .welcome-card p { color: #9ca3af; }
            }
            .stats-grid {
                display: grid;
                gap: 1.5rem;
                margin-bottom: 2rem;
            }
            @media (min-width: 768px) {
                .stats-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (min-width: 1024px) {
                .stats-grid { grid-template-columns: repeat(4, 1fr); }
            }
            .stat-card {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 1.5rem;
                text-align: center;
            }
            @media (prefers-color-scheme: dark) {
                .stat-card { background: #1f2937; border-color: #374151; }
            }
            .stat-card .number {
                font-size: 2rem;
                font-weight: 700;
                color: #2563eb;
                margin-bottom: 0.5rem;
            }
            .stat-card .label {
                font-size: 0.875rem;
                color: #6b7280;
            }
            @media (prefers-color-scheme: dark) {
                .stat-card .label { color: #9ca3af; }
            }
            .actions-section {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 2rem;
            }
            @media (prefers-color-scheme: dark) {
                .actions-section { background: #1f2937; border-color: #374151; }
            }
            .actions-section h3 {
                font-size: 1.125rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            .actions-grid {
                display: grid;
                gap: 1rem;
            }
            @media (min-width: 768px) {
                .actions-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (min-width: 1024px) {
                .actions-grid { grid-template-columns: repeat(3, 1fr); }
            }
        </style>
    </head>
    <body>
        <header class="header">
            <div class="header-content">
                <div>
                    <a href="/" style="text-decoration: none; color: inherit;">
                        <h1>Ticket System</h1>
                    </a>
                </div>
                <div class="user-menu">
                    <span class="user-info">{{ Auth::user()->name }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Log out</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="container">
            @if(Auth::user()->isAdmin())
                {{-- Admin Dashboard --}}
                <div class="welcome-card">
                    <h2>Admin Dashboard</h2>
                    <p>Manage the entire ticket system, users, and view comprehensive reports.</p>
                    <div class="actions-grid">
                        <a href="#" class="btn btn-primary">Manage Users</a>
                        <a href="#" class="btn btn-primary">System Settings</a>
                        <a href="#" class="btn btn-outline">View All Tickets</a>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Total Users</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">All Tickets</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Active Agents</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Resolved Today</div>
                    </div>
                </div>

                <div class="actions-section">
                    <h3>Admin Tools</h3>
                    <div class="actions-grid">
                        <a href="#" class="btn btn-outline">User Management</a>
                        <a href="#" class="btn btn-outline">Ticket Categories</a>
                        <a href="#" class="btn btn-outline">System Reports</a>
                        <a href="#" class="btn btn-outline">Agent Performance</a>
                        <a href="#" class="btn btn-outline">System Settings</a>
                        <a href="#" class="btn btn-outline">Backup & Export</a>
                    </div>
                </div>

            @elseif(Auth::user()->isAgent())
                {{-- Agent Dashboard --}}
                <div class="welcome-card">
                    <h2>Agent Dashboard</h2>
                    <p>Handle customer tickets and collaborate with your team to solve issues.</p>
                    <div class="actions-grid">
                        <a href="#" class="btn btn-primary">My Assigned Tickets</a>
                        <a href="#" class="btn btn-outline">Browse Open Tickets</a>
                        <a href="#" class="btn btn-outline">Team Activity</a>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Assigned to Me</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">In Progress</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Resolved Today</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Open Tickets</div>
                    </div>
                </div>

                <div class="actions-section">
                    <h3>Agent Tools</h3>
                    <div class="actions-grid">
                        <a href="#" class="btn btn-outline">Take Unassigned Ticket</a>
                        <a href="#" class="btn btn-outline">My Performance</a>
                        <a href="#" class="btn btn-outline">Team Chat</a>
                        <a href="#" class="btn btn-outline">Knowledge Base</a>
                        <a href="#" class="btn btn-outline">Customer History</a>
                        <a href="#" class="btn btn-outline">Quick Responses</a>
                    </div>
                </div>

            @else
                {{-- Customer Dashboard --}}
                <div class="welcome-card">
                    <h2>Welcome back, {{ Auth::user()->name }}!</h2>
                    <p>Track your support tickets and get help when you need it.</p>
                    <div class="actions-grid">
                        <a href="#" class="btn btn-primary">Create New Ticket</a>
                        <a href="#" class="btn btn-outline">My Tickets</a>
                        <a href="#" class="btn btn-outline">Help Center</a>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">My Open Tickets</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">In Progress</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Resolved</div>
                    </div>
                    <div class="stat-card">
                        <div class="number">0</div>
                        <div class="label">Total Tickets</div>
                    </div>
                </div>

                <div class="actions-section">
                    <h3>Need Help?</h3>
                    <div class="actions-grid">
                        <a href="#" class="btn btn-outline">Browse FAQ</a>
                        <a href="#" class="btn btn-outline">Contact Support</a>
                        <a href="#" class="btn btn-outline">Ticket History</a>
                        <a href="#" class="btn btn-outline">Account Settings</a>
                        <a href="#" class="btn btn-outline">Download Reports</a>
                        <a href="#" class="btn btn-outline">Submit Feedback</a>
                    </div>
                </div>
            @endif
        </div>
    </body>
</html>
