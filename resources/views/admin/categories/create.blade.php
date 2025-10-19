@extends('layouts.app')

@section('title', 'Create Category - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <div class="admin-title-section">
            <h1 class="heading-xl">Create New Category</h1>
            <p class="text-lead">Add a new ticket category to organize support requests</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Back to Categories</a>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="form-input @error('name') error @enderror" required 
                               placeholder="e.g., Technical Support, Billing, General Inquiry">
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Choose a clear, descriptive name for this category</small>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-textarea @error('description') error @enderror"
                                  placeholder="Brief description of what tickets belong in this category">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Optional: Provide additional context about this category</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Examples Section -->
    <div class="examples-section">
        <div class="examples-card">
            <h3 class="examples-title">Category Examples</h3>
            <p class="examples-subtitle">Here are some common ticket categories you might want to create:</p>
            
            <div class="examples-grid">
                <div class="example-item">
                    <h4 class="example-name">Technical Support</h4>
                    <p class="example-desc">Software bugs, performance issues, and technical troubleshooting</p>
                </div>
                <div class="example-item">
                    <h4 class="example-name">Billing & Payments</h4>
                    <p class="example-desc">Payment issues, billing inquiries, and subscription management</p>
                </div>
                <div class="example-item">
                    <h4 class="example-name">General Inquiry</h4>
                    <p class="example-desc">Questions about features, services, and general information</p>
                </div>
                <div class="example-item">
                    <h4 class="example-name">Feature Request</h4>
                    <p class="example-desc">Suggestions for new features or improvements</p>
                </div>
                <div class="example-item">
                    <h4 class="example-name">Account Management</h4>
                    <p class="example-desc">Profile updates, password resets, and account settings</p>
                </div>
                <div class="example-item">
                    <h4 class="example-name">Bug Report</h4>
                    <p class="example-desc">Software defects and unexpected behavior reports</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-container {
    max-width: 600px;
    margin: 2rem auto 3rem auto;
}

.form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.form-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-hint {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.examples-section {
    max-width: 800px;
    margin: 0 auto;
}

.examples-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.examples-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.examples-subtitle {
    color: #6b7280;
    margin: 0 0 2rem 0;
    line-height: 1.5;
}

.examples-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.example-item {
    padding: 1.5rem;
    background: rgba(249, 250, 251, 0.8);
    border: 1px solid rgba(226, 232, 240, 0.6);
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.example-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.example-name {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.example-desc {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .form-container {
        margin: 1rem;
        max-width: none;
    }
    
    .examples-section {
        margin: 0 1rem;
        max-width: none;
    }
    
    .examples-grid {
        grid-template-columns: 1fr;
    }
    
    .form-card,
    .examples-card {
        padding: 1.5rem;
    }
}
</style>
@endsection