<div>
        <!-- Page Header  -->
    <div class="page-header d-flex justify-content-between align-items-center border-bottom mb-4">
        <div>
            <h2 class="page-title"><i class="fas fa-shapes"></i>Categories </h2>
            <p class="page-subtitle">Manage your restaurant operations efficiently and effectively.</p>
        </div>
        <button class="btn btn-primary" wire:click="openModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-2 mb-3">
                <div class="card p-0 shadow-sm">
                    <img src="{{ $category->image ? asset('storage/'.$category->image) : 'https://via.placeholder.com/150' }}" class="card-img-top" alt="">
                    <div class="card-body p-2">
                        <h6>{{ $category->name }}</h6>
                        <small>Branch: {{ $category->branch->name }}</small>
                    </div>
                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center p-2">
                            <small class="badge fs-sm {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </small>
                            <button class="btn btn-sm btn-primary" wire:click="edit({{ $category->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    <div class="modal fade @if($isModalOpen) show d-block @endif" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form wire:submit.prevent="saveCategory">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editingId ? 'Edit Category' : 'Add Category' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Branch *</label>
                            <select class="form-select" wire:model="form.branch_id">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('form.branch_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Category Name *</label>
                            <input type="text" class="form-control" wire:model="form.name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Category Image</label>
                            <input type="file" class="form-control" wire:model="form.image">
                            @error('form.image') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" wire:model="form.is_active">
                                <option value="">Select</option>    
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            Cancel
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Save
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    @if($isModalOpen)
        <div class="modal-backdrop fade show"></div>
    @endif

</div>
