<div>
     <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center border-bottom mb-4">
        <div>
            <h2 class="page-title"><i class="fas fa-shapes"></i> Subcategories</h2>
            <p class="page-subtitle">Manage your restaurant operations efficiently and effectively.</p>
        </div>
        <button class="btn btn-primary" wire:click="openCreateModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>

     <!-- Subcategory Cards  -->
    <div class="row mt-3">
        @forelse($subcategories as $sub)
            <div class="col-md-2 mb-4">
                <div class="card p-0 position-relative">
                    @if($sub->image)
                        <img src="{{ asset('storage/' . $sub->image) }}" class="card-img-top" alt="{{ $sub->name }}">       
                    @else
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="No Image Available">
                    @endif
                    <div class="card-body p-2">
                        <h6 class="card-title">{{ $sub->name }}</h6>
                        <small class="text-muted">Category: {{ $sub->category->name }}</small>
                    </div>
                    <div class="card-footer bg-white border-top d-flex justify-content-between p-2">
                        <small class="badge py-2 {{ $sub->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $sub->is_active ? 'Active' : 'Inactive' }}
                    </small>
                        <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-sm btn-primary" wire:click="editSubcategory({{ $sub->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info text-center">No subcategories found</div>
            </div>
        @endforelse
    </div>

     <!-- CREATE MODAL  -->
    <div class="modal fade @if($isCreateModalOpen) show d-block @endif" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="saveSubcategory">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subcategory</h5>
                        <button type="button" class="btn-close" wire:click="$set('isCreateModalOpen', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name *</label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Category *</label>
                            <select class="form-control" wire:model="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label>Display Order</label>
                            <input type="number" class="form-control" wire:model="display_order">
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" class="form-control" wire:model="image">
                            @if ($image) <img src="{{ $image->temporaryUrl() }}" class="mt-2" style="height:80px;"> @endif
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" wire:model="is_active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('isCreateModalOpen', false)">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- EDIT MODAL  -->
    <div class="modal fade @if($isEditModalOpen) show d-block @endif" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="updateSubcategory">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Subcategory</h5>
                        <button type="button" class="btn-close" wire:click="$set('isEditModalOpen', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                            <label>Name *</label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Category *</label>
                            <select class="form-control" wire:model="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        </div>

                        <div class="mb-3">
                            <label>Display Order</label>
                            <input type="number" class="form-control" wire:model="display_order">
                        </div>

                        <div class="row">
                        <div class="mb-3 col-md-4">
                             @if ($image) <img src="{{ $image->temporaryUrl() }}" class="mt-2 img rounded" style="height:80px;"> @elseif($subcategoryId && $subcategories->find($subcategoryId)?->image)
                                <img src="{{ asset('storage/' . $subcategories->find($subcategoryId)->image) }}" class="mt-2 rounded" style="height:80px;">
                            @endif
                        </div>
                        <div class="mb-3 col-md-8">
                            <label>Image</label>
                            <input type="file" class="form-control" wire:model="image">
                        </div>

                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" wire:model="is_active">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('isEditModalOpen', false)">Cancel</button>
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($isCreateModalOpen || $isEditModalOpen)
        <div class="modal-backdrop fade show"></div>
    @endif
    
</div>
