<div>
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-shopping-cart"></i> Order Options</h2>
        <p class="page-subtitle">Manage your restaurant operations efficiently and effectively.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-4">Delivery & Pickup Settings</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="p-3 mb-3" style="background:#f8f9fa; border-radius:8px;">
                        <div class="d-flex align-items-center mb-3">
                            <div style="width:50px;height:50px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;margin-right:15px;">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Enable Delivery</h6>
                                <small class="text-muted">Allow customers to order delivery</small>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" wire:model="settings.delivery_enabled" id="deliveryEnabled">
                            <label class="form-check-label" for="deliveryEnabled">Enabled</label>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Delivery Message</label>
                            <input type="text" class="form-control" wire:model="settings.delivery_message">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 mb-3" style="background:#f8f9fa; border-radius:8px;">
                        <div class="d-flex align-items-center mb-3">
                            <div style="width:50px;height:50px;background:#95a5a6;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;margin-right:15px;">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Enable Pickup</h6>
                                <small class="text-muted">Allow customers to pickup orders</small>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" wire:model="settings.pickup_enabled" id="pickupEnabled">
                            <label class="form-check-label" for="pickupEnabled">Enabled</label>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Pickup Message</label>
                            <input type="text" class="form-control" wire:model="settings.pickup_message">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Select Branch (Optional)</label>
                <select class="form-select" wire:model="branch_id">
                <option value="">Global (No branch)</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>

            </div>

            <div class="form-check mb-4">
                <input type="radio" class="form-check-input" wire:model="settings.default_option" value="none" id="noDefault">
                <label class="form-check-label" for="noDefault">No default option (users must select)</label>
            </div>

            <button class="btn btn-primary btn-lg" wire:click="saveSettings">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </div>
</div>
