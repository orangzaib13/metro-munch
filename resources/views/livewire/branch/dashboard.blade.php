<div>
    <div class="page-header border-bottom mb-4">
        <h2 class="page-title">
            <i class="fas fa-chart-line"></i> Dashboard
        </h2>
        <p class="page-subtitle">Manage your restaurant operations efficiently and effectively.</p>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-card-label">Total Orders</div>
                <div class="stat-card-value">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card success">
                <div class="stat-card-label">Completed Orders</div>
                <div class="stat-card-value success">{{ $completedOrders }}</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card warning">
                <div class="stat-card-label">Pending Orders</div>
                <div class="stat-card-value warning">{{ $pendingOrders }}</div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card info">
                <div class="stat-card-label">Total Revenue</div>
                <div class="stat-card-value info">Rs. {{ number_format($totalRevenue, 0) }}</div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Total Customers</div>
                <div class="card-body">
                    <h3 style="color: var(--primary); font-weight: 700;">{{ $totalCustomers }}</h3>
                    <small class="text-muted">Active customers in system</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">Total Food Items</div>
                <div class="card-body">
                    <h3 style="color: var(--success); font-weight: 700;">{{ $totalFoodItems }}</h3>
                    <small class="text-muted">Available in menu</small>
                </div>
            </div>
        </div>
   
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">Recent Orders</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                        <td>Rs. {{ number_format($order->total, 0) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('show-order', $order->id) }}" wire:navigate.hover class="btn btn-sm btn-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


