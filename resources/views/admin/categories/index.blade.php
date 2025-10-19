@extends('layouts.app')

@section('title', 'Ticket Categories - Admin Panel')

@section('content')
<div class="container-main">
    <div class="admin-header">
        <div class="admin-title-section">
            <h1 class="heading-xl">Ticket Categories</h1>
            <p class="text-lead">Manage support ticket categories and organization</p>
        </div>
        <div class="admin-actions">
            <button onclick="openCreateModal()" class="btn btn-primary">Add Category</button>
        </div>
    </div>

    <div class="categories-grid">
        @forelse($categories as $category)
            <div class="category-card" data-category-id="{{ $category->id }}">
                <div class="category-header">
                    <div class="category-info">
                        <h3 class="category-name">{{ $category->name }}</h3>
                        <p class="category-description">{{ $category->description ?: 'No description provided' }}</p>
                    </div>
                    <div class="category-actions">
                        <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')" 
                                class="btn btn-sm btn-outline">Edit</button>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;"
                              onsubmit="return confirm('Are you sure you want to delete this category? All tickets in this category will need to be reassigned.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
                
                <div class="category-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $category->tickets_count ?? 0 }}</span>
                        <span class="stat-label">Total Tickets</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">
                            {{ $category->tickets()->where('status', 'open')->count() }}
                        </span>
                        <span class="stat-label">Open Tickets</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">Created</span>
                        <span class="stat-label">{{ $category->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state-full">
                <div class="empty-icon">📂</div>
                <h3 class="empty-title">No Categories Found</h3>
                <p class="empty-description">Create your first ticket category to organize support requests</p>
                <button onclick="openCreateModal()" class="btn btn-primary">Create First Category</button>
            </div>
        @endforelse
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div id="categoryModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle" class="modal-title">Add New Category</h2>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        
        <form id="categoryForm" method="POST">
            @csrf
            <div id="methodField"></div>
            
            <div class="form-group">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" id="name" name="name" class="form-input" required 
                       placeholder="e.g., Technical Support, Billing, General Inquiry">
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-textarea" rows="3"
                          placeholder="Brief description of what tickets belong in this category"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" onclick="closeModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" id="submitBtn" class="btn btn-primary">Create Category</button>
            </div>
        </form>
    </div>
</div>

<style>
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.category-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.category-info {
    flex: 1;
}

.category-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.category-description {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

.category-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.category-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.empty-state-full {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 2px dashed rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    margin-top: 2rem;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.empty-description {
    color: #6b7280;
    margin: 0 0 2rem 0;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6b7280;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: background-color 0.2s ease;
}

.modal-close:hover {
    background: rgba(243, 244, 246, 0.8);
}

.modal form {
    padding: 2rem;
}

@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .category-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .category-actions {
        align-self: stretch;
        justify-content: flex-end;
    }
    
    .category-stats {
        grid-template-columns: 1fr;
        text-align: left;
    }
    
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-value {
        margin-bottom: 0;
    }
}
</style>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add New Category';
    document.getElementById('categoryForm').action = '{{ route("admin.categories.store") }}';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('submitBtn').textContent = 'Create Category';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('categoryModal').style.display = 'flex';
}

function editCategory(id, name, description) {
    document.getElementById('modalTitle').textContent = 'Edit Category';
    document.getElementById('categoryForm').action = `/admin/categories/${id}`;
    document.getElementById('methodField').innerHTML = '@method("PATCH")';
    document.getElementById('submitBtn').textContent = 'Update Category';
    document.getElementById('name').value = name;
    document.getElementById('description').value = description || '';
    document.getElementById('categoryModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection