<div>
    <div class="page-header border-bottom mb-4">
        <h2 class="page-title">
            <i class="fas fa-history"></i> Order History
        </h2>
        <p class="page-subtitle">Manage your restaurant operations efficiently and effectively.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <select class="form-select" wire:model.change="dateFilter">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" wire:model.change="statusFilter">
                        <option value="all">All</option>
                        <option value="completed">Complete</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select class="form-select" wire:model.change="branchFilter">
                        <option value="all">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" placeholder="Order or Name" wire:model.live="search">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Order History</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $key => $order)
                            <tr>
                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $key + 1 }}</td>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>Rs. {{ number_format($order->subtotal, 0) }}</td>
                                <td>
                                    <span class="badge {{ $order->status === 'completed' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->note ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" wire:click="deleteOrder({{ $order->id }})">Delete</button>
                                    <a href="/show-order/{{ $order->id }}" wire:navigate.hover class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
