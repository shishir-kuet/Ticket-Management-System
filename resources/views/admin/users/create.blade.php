@extends('layouts.app')

@section('title', 'Create User - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <h1 class="heading-xl">Create New User</h1>
        <p class="text-lead">Add a new user to the system with appropriate role permissions</p>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="form-input @error('name') error @enderror" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                               class="form-input @error('email') error @enderror" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">User Role</label>
                        <select id="role" name="role" class="form-select @error('role') error @enderror" required>
                            <option value="">Select a role</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" 
                               class="form-input @error('password') error @enderror" required>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="form-input" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-container {
    max-width: 600px;
    margin: 0 auto;
}

.form-card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0.75rem;
    padding: 2rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.form-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-input, .form-select {
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    background: white;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input.error, .form-select.error {
    border-color: #dc2626;
}

.form-error {
    color: #dc2626;
    font-size: 0.8125rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection