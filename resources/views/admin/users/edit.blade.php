@extends('layouts.app')

@section('title', 'Edit User - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <h1 class="heading-xl">Edit User</h1>
        <p class="text-lead">Update user information and role permissions</p>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                               class="form-input @error('name') error @enderror" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="form-input @error('email') error @enderror" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">User Role</label>
                        <select id="role" name="role" class="form-select @error('role') error @enderror" required>
                            <option value="">Select a role</option>
                            <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-divider">
                        <h3 class="form-section-title">Change Password (Optional)</h3>
                        <p class="form-section-desc">Leave blank to keep current password</p>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" 
                               class="form-input @error('password') error @enderror">
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="form-input">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-divider {
    grid-column: 1 / -1;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
    padding-top: 1.5rem;
    margin-top: 1rem;
}

.form-section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
}

.form-section-desc {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}
</style>
@endsection