<div>
    <div class="page-header">
        <h2 class="page-title">
            <i class="fas fa-users"></i> Customers
        </h2>
        <p class="page-subtitle">Manage your restaurant operations efficiently and effectively.</p>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Order Count</label>
                   <select class="form-select" wire:model.change="orderCountFilter">
                    <option value="any">Any orders</option>
                    <option value="1-5">1-5 orders</option>
                    <option value="6-15">6-15 orders</option>
                    <option value="16+">16+ orders</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Search by name, email or phone">
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">Customer List</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>PHONE</th>
                            <th>ALT. PHONE</th>
                            <th>EMAIL</th>
                            <th>AREA</th>
                            <th>ORDERS</th>
                            <th>TOTAL SPENT</th>
                            <th>LAST ORDER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td><strong>{{ $customer->name }}</strong></td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->alt_phone ?? '-' }}</td>
                                <td>{{ $customer->email ?? '-' }}</td>
                                <td>{{ $customer->area ?? '-' }}</td>
                                <td><span class="badge badge-info">{{ $customer->total_orders }}</span></td>
                                <td>Rs. {{ number_format($customer->total_spent, 0) }}</td>
                                <td>{{ $customer->last_order_at ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No customers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
