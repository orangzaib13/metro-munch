<div>
    <div class="page-header d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="page-title"><i class="fas fa-utensils"></i> Food Items</h2>
            <p class="page-subtitle">Manage all food items efficiently.</p>
        </div>
        <button class="btn btn-primary" wire:click="openModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div class="row mt-3">
        <div class="row mb-3 border-bottom pb-3">
            <div class="col-md-6">
                <label>Filter by Branch</label>
                <select class="form-select" wire:model.change="selectedBranch">
                    <option value="">-- All Branches --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Filter by Category</label>
                <select class="form-select" wire:model.change="selectedCategory">
                    <option value="">-- All Categories --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @forelse($foodItems as $item)
            <div class="col-md-3 mb-4">
                <div class="card p-0 shadow-sm">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" style="height:200px; object-fit:cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">
                            <strong>Branch:</strong> {{ $item->branch?->name ?? 'N/A' }}<br>
                            <strong>Category:</strong> {{ $item->category?->name ?? 'N/A' }}<br>
                            <strong>Subcategory:</strong> {{ $item->subcategory?->name ?? 'N/A' }}<br>
                            <strong>Price:</strong> RS {{ number_format($item->price) }}<br>
                            <span class="badge {{ $item->is_available ? 'bg-success' : 'bg-danger' }}">
                                {{ $item->is_available ? 'Active' : 'Inactive' }}
                            </span>
                            @if($item->allow_discount)
                                <span class="badge bg-info">Discount</span>
                            @endif
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $item->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <div>
                            <button class="btn btn-sm btn-primary" wire:click="delete({{ $item->id }})" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-sm {{ $item->is_available ? 'btn-danger' : 'btn-success' }}" 
                                    wire:click="toggleStatus({{ $item->id }})">
                                <i class="fas {{ $item->is_available ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">No food items found.</div>
            </div>
        @endforelse
    </div>

    <!-- Modal for Add/Edit -->
@if($isModalOpen)
<div class="modal fade show d-block" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $editingId ? 'Edit Food Item' : 'Add Food Item' }}</h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>
            
            <div class="modal-body">
                <!-- Branch / Category / Subcategory -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Select Branch <span class="required">*</span></label>
                        <select class="form-select" wire:model="branchId">
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branchId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Select Category <span class="required">*</span></label>
                        <select class="form-select" wire:model="categoryId">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categoryId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Select Subcategory (Optional)</label>
                        <select class="form-select" wire:model="subcategoryId">
                            <option value="">-- Select Subcategory --</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('subcategoryId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Name / Description -->
                <div class="mb-3">
                    <label>Item Title <span class="required">*</span></label>
                    <input type="text" class="form-control" wire:model="name" placeholder="Enter item title">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" wire:model="description" placeholder="Enter description (optional)"></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Availability / Discount / Price / Image -->
                <div class="row align-items-end mb-3 px-2">
                    <div class="col-md-4">
                        <label>Price <span class="required">*</span></label>
                        <input type="number" class="form-control" wire:model="price" step="0.01" placeholder="Enter price">
                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" wire:model="isAvailable" id="isAvailable">
                        <label class="form-check-label" for="isAvailable">Available</label>
                        @error('isAvailable') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" wire:model="allowDiscount" id="allowDiscount">
                        <label class="form-check-label" for="allowDiscount">Apply Discount</label>
                        @error('allowDiscount') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <label>Upload Item Image</label>
                    <input type="file" class="form-control" wire:model="image" accept="image/*">
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Variations -->
                <div class="mb-3 border-bottom pb-2 {{ count($variations) === 0 ? 'd-flex align-items-center justify-content-between flex-row' : 'd-flex flex-column' }}">
                    <h5>Variations (Optional)</h5>
                    @foreach($variations as $index => $v)
                        <div class="row align-items-center mb-2">
                            <div class="col-md-5">
                                <input type="text" class="form-control" wire:model="variations.{{ $index }}.name" placeholder="Variation Name (e.g., Small)">
                                @error('variations.'.$index.'.name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-5">
                                <input type="number" class="form-control" wire:model="variations.{{ $index }}.price" placeholder="Price" step="0.01">
                                @error('variations.'.$index.'.price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-2">
                                <button class="btn text-danger btn-sm" wire:click.prevent="removeVariation({{ $index }})"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                    <button class="btn btn-primary btn-sm" wire:click.prevent="addVariation"><i class="fas fa-plus"></i></button>
                </div>

                <!-- Extras -->
                <div class="mb-3 border-bottom pb-2 {{ count($extras) === 0 ? 'd-flex align-items-center justify-content-between flex-row' : 'd-flex flex-column' }}">
                    <h5>Extras / Toppings (Optional)</h5>
                    @foreach($extras as $index => $e)
                        <div class="row align-items-center mb-2">
                            <div class="col-md-4">
                                <input type="text" class="form-control" wire:model="extras.{{ $index }}.name" placeholder="Extra Name">
                                @error('extras.'.$index.'.name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" wire:model="extras.{{ $index }}.price" placeholder="Price" step="0.01">
                                @error('extras.'.$index.'.price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" wire:model="extras.{{ $index }}.description" placeholder="Description (optional)">
                                @error('extras.'.$index.'.description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-1">
                                <button class="btn text-danger btn-sm" wire:click.prevent="removeExtra({{ $index }})"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                    <button class="btn btn-primary btn-sm" wire:click.prevent="addExtra"><i class="fas fa-plus"></i></button>
                </div>

                <button class="btn btn-primary mt-3 w-100" wire:click="saveFoodItem">
                      @if($editingId)
                        <i class="fas fa-edit"></i> Update Food Item
                    @else
                        <i class="fas fa-plus"></i> Add Food Item
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endif
</div>
