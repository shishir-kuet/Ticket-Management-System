@extends('layouts.app')

@section('title', 'Manage Users - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <div class="header-content">
            <h1 class="heading-xl">User Management</h1>
            <p class="text-lead">Manage system users, roles, and permissions</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New User
            </a>
        </div>
    </div>

    <div class="admin-section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-id">#{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline">Edit</a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state-cell">
                                <div class="empty-state">
                                    <p>No users found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2rem;
}

.header-content h1 {
    margin-bottom: 0.5rem;
}

.table-container {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0.75rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: rgba(248, 250, 252, 0.8);
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    font-size: 0.875rem;
}

.data-table td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    font-size: 0.875rem;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table tr:hover {
    background: rgba(248, 250, 252, 0.5);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.user-name {
    font-weight: 600;
    color: #111827;
}

.user-id {
    font-size: 0.75rem;
    color: #6b7280;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-danger {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #b91c1c, #991b1b);
}

.empty-state-cell {
    text-align: center;
    padding: 3rem 1.5rem;
}

.pagination-wrapper {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(226, 232, 240, 0.5);
    background: rgba(248, 250, 252, 0.5);
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .data-table {
        font-size: 0.8125rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.75rem 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endsection