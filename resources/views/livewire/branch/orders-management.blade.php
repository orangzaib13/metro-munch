<div>
    <div class="page-header border-bottom mb-4">
        <h2 class="page-title">
            <i class="fas fa-clipboard-list"></i> Orders Management
        </h2>
        <p class="page-subtitle">Manage your restaurant operations efficiently and effectively.</p>
    </div>
    
    <div class="card mb-4">
    <div class="card-body">

        <div class="row mb-3">

            <div class="col-md-4">
                <label class="form-label">Date</label>
                <select class="form-select" wire:model.change="dateFilter">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select class="form-select" wire:model.change="statusFilter">
                    <option value="all">Active</option>
                    <option value="pending">Pending</option>
                    <option value="in-process">In-Process</option>
                    <option value="dispatched">Dispatched</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

        </div>

    </div>
</div>

    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">

            <span>Order List<br>
                <small class="text-muted">
                    Showing 1 to {{ count($orders) }} of {{ $totalOrders }} orders
                </small>
            </span>

            <!-- Search Input -->
            <div class="w-50">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search by order number, name, or phone..."
                    wire:model.live="search">
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order #</th>
                            <th>Name</th>
                            <th>Date & Time</th>
                            <th>Area</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $key => $order)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->area ?? 'N/A' }}</td>
                                <td>Rs. {{ number_format($order->subtotal, 0) }}</td>

                                <td>
                                    <select class="form-select form-select-sm"
                                    wire:change="updateStatus({{ $order->id }}, $event.target.value)">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in-process" {{ $order->status === 'in-process' ? 'selected' : '' }}>In-Process</option>
                                    <option value="dispatched" {{ $order->status === 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>

                                </td>

                                <td>
                                    <a href="/show-order/{{ $order->id }}" wire:navigate.hover class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-primary" title="Print">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <small class="text-muted">Page 1 of {{ ceil($totalOrders / 10) }}</small>
        </div>
    </div>
</div>
