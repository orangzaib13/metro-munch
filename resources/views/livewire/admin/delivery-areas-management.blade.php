<div>
    <div class="page-header border-bottom mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title"><i class="fas fa-map-marker-alt"></i> Delivery Areas</h2>
             <p class="page-subtitle">Manage your restaurant delivery areas.</p>
    </div>
    <button class="btn btn-primary mb-3" wire:click="openModal">
                <i class="fas fa-plus"></i>
            </button>
</div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Name</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveryAreas as $area)
                            <tr>
                                <td>{{ $area->branch->name ?? '-' }}</td>
                                <td>{{ $area->name }}</td>
                                <td>Rs. {{ number_format($area->delivery_fee, 2) }}</td>
                                <td>
                                    <span class="badge {{ $area->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $area->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" wire:click="openModal({{ $area->id }})">Edit</button>
                                    <button class="btn btn-sm btn-warning" wire:click="toggleActive({{ $area->id }})">
                                    @if($area->is_active)
                                        <i class="fas fa-eye-slash"></i>
                                    @else
                                        <i class="fas fa-eye"></i>
                                    @endif
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No delivery areas found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Delivery Area' : 'Add Delivery Area' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Branch <span class="required">*</span></label>
                        <select class="form-control" wire:model="branchId">
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branchId') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Area Name <span class="required">*</span></label>
                        <input type="text" class="form-control" wire:model="name" placeholder="Enter area name">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label>Delivery Fee (Rs.) <span class="required">*</span></label>
                        <input type="number" class="form-control" wire:model="deliveryFee" step="0.01" placeholder="Enter delivery fee">
                        @error('deliveryFee') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" wire:model="isActive" id="isActive">
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                    <button class="btn btn-primary" wire:click="saveDeliveryArea">{{ $editingId ? 'Update' : 'Add' }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
