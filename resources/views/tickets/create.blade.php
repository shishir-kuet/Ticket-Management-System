@extends('layouts.app')

@section('title', 'Create New Ticket')

@section('content')
<div class="container-main">
    <div class="page-header">
        <div class="page-title-section">
            <h1 class="heading-xl">Create New Ticket</h1>
            <p class="text-lead">
                @if(auth()->user()->role === 'customer')
                    Submit a support request and we'll help you resolve it
                @else
                    Create a new support ticket for a customer or yourself
                @endif
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('tickets.index') }}" class="btn btn-outline">View All Tickets</a>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    @if(auth()->user()->role !== 'customer' && !empty($customers))
                        <div class="form-group">
                            <label for="customer_id" class="form-label">Create Ticket For</label>
                            <select id="customer_id" name="customer_id" class="form-select @error('customer_id') error @enderror">
                                <option value="">Select customer (leave empty for yourself)</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                            <small class="form-hint">As an agent, you can create tickets on behalf of customers</small>
                        </div>
                    @endif

                    <div class="form-group {{ auth()->user()->role === 'customer' ? 'full-width' : '' }}">
                        <label for="title" class="form-label">Ticket Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" 
                               class="form-input @error('title') error @enderror" required 
                               placeholder="Brief description of your issue">
                        @error('title')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Provide a clear, concise title that describes the issue</small>
                    </div>

                    <div class="form-group">
                        <label for="category_id" class="form-label">Category</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') error @enderror" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Choose the category that best matches your issue</small>
                    </div>

                    <div class="form-group">
                        <label for="priority" class="form-label">Priority Level</label>
                        <select id="priority" name="priority" class="form-select @error('priority') error @enderror" required>
                            <option value="">Select priority</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - General inquiry</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium - Standard issue</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Important issue</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent - Critical issue</option>
                        </select>
                        @error('priority')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">Select the urgency level of your issue</small>
                    </div>

                    <div class="form-group full-width">
                        <label for="description" class="form-label">Detailed Description</label>
                        <textarea id="description" name="description" rows="6"
                                  class="form-textarea @error('description') error @enderror" required
                                  placeholder="Please provide a detailed description of the issue. Include any relevant information such as error messages, steps to reproduce the problem, or what was being attempted when the issue occurred.">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <small class="form-hint">The more details provided, the faster the issue can be resolved</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="help-section">
        <div class="help-card">
            <h3 class="help-title">💡 Tips for Better Support</h3>
            <div class="help-tips">
                <div class="tip-item">
                    <h4 class="tip-title">Be Specific</h4>
                    <p class="tip-desc">Include exact error messages, steps you took, and what you expected to happen.</p>
                </div>
                <div class="tip-item">
                    <h4 class="tip-title">Add Context</h4>
                    <p class="tip-desc">Mention your operating system, browser, or app version if relevant.</p>
                </div>
                <div class="tip-item">
                    <h4 class="tip-title">Choose Right Priority</h4>
                    <p class="tip-desc">Urgent is for critical issues that stop you from working. Use appropriately.</p>
                </div>
                <div class="tip-item">
                    <h4 class="tip-title">Select Correct Category</h4>
                    <p class="tip-desc">This helps us route your ticket to the right support specialist.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-container {
    max-width: 800px;
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
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-hint {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.5rem;
    line-height: 1.4;
}

.help-section {
    max-width: 800px;
    margin: 0 auto;
}

.help-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.help-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1.5rem 0;
    text-align: center;
}

.help-tips {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.tip-item {
    text-align: center;
    padding: 1.5rem;
    background: rgba(249, 250, 251, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(226, 232, 240, 0.6);
}

.tip-title {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.tip-desc {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .form-container,
    .help-section {
        margin-left: 1rem;
        margin-right: 1rem;
        max-width: none;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .help-tips {
        grid-template-columns: 1fr;
    }
    
    .form-card,
    .help-card {
        padding: 1.5rem;
    }
}
</style>
@endsection