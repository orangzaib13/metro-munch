<div>
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-percentage"></i> Discount Management</h2>
        <p class="page-subtitle">Global discount + promo codes</p>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'global' ? 'active' : '' }}" href="#" wire:click.prevent="setTab('global')">
                        Global Discount
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'promo' ? 'active' : '' }}" href="#" wire:click.prevent="setTab('promo')">
                        Promo Codes
                    </a>
                </li>
            </ul>
        </div>

         <!-- GLOBAL TAB  -->
        @if($activeTab === 'global')
        <div class="card-body">
            <h5>Global Discount Setting</h5>

            <div class="mb-3">
                <label>Discount Percentage (%)</label>
                <input type="number" class="form-control" wire:model="global.discount_percentage" min="0" max="100" step="0.01">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" wire:model="global.is_active" id="enableGlobal">
                <label class="form-check-label" for="enableGlobal">Enable Global Discount</label>
            </div>

            <button class="btn btn-primary" wire:click="saveGlobalDiscount">
                Save Global Discount
            </button>
        </div>
        @endif

         <!-- PROMO TAB  -->
        @if($activeTab === 'promo')
        <div class="card-body">
            <button class="btn btn-success mb-3" wire:click="openModal">
                <i class="fas fa-plus"></i> Add Promo Code
            </button>

            <h5>Existing Promo Codes</h5>
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Branch</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Valid</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $promo)
                    <tr>
                        <td>{{ $promo->code }}</td>
                        <td>{{ $promo->branch->name ?? '-' }}</td>
                        <td>{{ ucfirst($promo->type) }}</td>
                        <td>{{ $promo->value }}</td>
                        <td>{{ $promo->valid_from }} — {{ $promo->valid_to }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" wire:click="editPromo({{ $promo->id }})">Edit</button>
                            <button class="btn btn-sm btn-danger" wire:click="deletePromo({{ $promo->id }})">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No promo codes found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    </div>

     <!-- Promo Modal  -->
    @if($editingId || $isModalOpen)
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Promo Code' : 'Add Promo Code' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                        <label>Branch</label>
                        <select class="form-control" wire:model="promo.branch_id">
                            <option value="">-- Select Branch --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('promo.branch_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Code</label>
                        <input type="text" class="form-control" wire:model="promo.code">
                        @error('promo.code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-md-6">
                        <label>Type</label>
                        <select class="form-control" wire:model="promo.type">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Value</label>
                        <input type="number" class="form-control" wire:model="promo.value" step="0.01">
                    </div>

                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                        <label>Max Discount</label>
                        <input type="number" class="form-control" wire:model="promo.max_discount" step="0.01">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Min Order Value</label>
                        <input type="number" class="form-control" wire:model="promo.min_order_value" step="0.01">
                    </div>

                    </div>

                    <div class="mb-3">
                        <label>Usage Limit</label>
                        <input type="number" class="form-control" wire:model="promo.usage_limit">
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                        <label>Valid From</label>
                        <input type="datetime-local" class="form-control" wire:model="promo.valid_from">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Valid To</label>
                        <input type="datetime-local" class="form-control" wire:model="promo.valid_to">
                    </div>

                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" wire:model="promo.is_active" id="promoActive">
                        <label class="form-check-label" for="promoActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                    <button class="btn btn-success" wire:click="savePromo">
                        {{ $editingId ? 'Update' : 'Add' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
