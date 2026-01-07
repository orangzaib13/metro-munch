<style>
@media print {
    aside,
    .header,
    nav,
    .sidebar,
    .topbar,
    .navbar,
    .no-print {
        display: none !important;
        visibility: hidden !important;
    }

    /* Expand invoice to full width when printing */
    .content,
    .main-content,
    .page-wrapper,
    .col-md-6 {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
}

</style>


<div class="container-fluid py-4 px-lg-4">
    <div class="invoice-container">
            <nav class="navbar no-print mb-4">
            <div>
                <button class="btn btn-outline-secondary btn-sm me-2" onclick="window.history.back()">Back</button>
                <button class="btn btn-primary btn-sm" onclick="window.print()">Print Invoice</button>
            </div>
        </div>
    </nav>
    <div class="container border p-4 rounded">
        <!-- Header -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div>
                        <h4 class="fw-bold m-0">{{ $order->branch->name ?? 'Restaurant' }}</h4>
                        <small class="text-muted">{{ $order->branch->location ?? '—' }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-6 text-md-end">
                <h2 class="fw-bold mb-1">Order #{{ $order->order_number }}</h2>
                <p class="text-muted mb-0">Placed At: {{ $order->placed_at }}</p>
            </div>
        </div>

        <hr class="my-4">


        <!-- 3-Column Details -->
        <div class="row g-4 mb-4">

            <!-- Order Status -->
            <div class="col-md-4">
                <h6 class="fw-bold-custom text-uppercase text-muted small mb-3">Order Status</h6>

                <div class="mb-2">
                    <span class="text-muted small">Status:</span>
                    <span class="badge bg-success badge-status ms-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="mb-2">
                    <span class="text-muted small">Branch:</span>
                    <span class="fw-bold ms-2">{{ $order->branch->name ?? '—' }}</span>
                </div>

            </div>

            <!-- Customer -->
            <div class="col-md-4 border-start-md border-end-md border-secondary-subtle">
                <h6 class="fw-bold-custom text-uppercase text-muted small mb-3">Customer Details</h6>

                <p class="mb-1 fw-bold">
                    {{ $order->customer->name ?? $order->customer_name }}
                </p>

                <p class="mb-1 small text-muted">
                    {{ $order->customer->phone ?? $order->customer_phone }}
                </p>

                @if($order->customer?->email)
                    <p class="mb-0 small text-muted">{{ $order->customer->email }}</p>
                @endif
            </div>

            <!-- Delivery -->
            <div class="col-md-4">
                <h6 class="fw-bold-custom text-uppercase text-muted small mb-3">Delivery Details</h6>

                <p class="mb-1"><strong>Order Type:</strong> {{ ucfirst($order->type) }}</p>
                <p class="mb-1"><strong>Area:</strong> {{ $order->area ?? $order->customer->area ?? '—' }}</p>
                <p class="mb-0 small text-muted" style="max-width: 260px;">
                    <strong>Address:</strong> {{ $order->delivery_address ?? '—' }}
                </p>
            </div>
        </div>


        <!-- Items -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Item</th>
                                <th width="100" class="text-center">Qty</th>
                                <th width="120" class="text-end">Unit Price</th>
                                <th width="120" class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $item->foodItem->name ?? 'Item Removed' }}</div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end pe-4">
                                        {{ number_format($item->subtotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Totals -->
        <div class="row">
            <div class="col-md-6">
                @if(!empty($item->special_instructions))
                <div class="p-3">
                    <h6 class="fw-bold-custom mb-2">Order Notes:</h6>
                    <p class="mb-0 text-muted small">{{ $item->special_instructions ?? '—' }}</p>
                </div>
                @endif
            </div>

            <div class="col-md-6">
                <div class="d-flex flex-column align-items-end text-end">

                    <div class="d-flex justify-content-between w-100 mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-medium">{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between w-100 mb-2">
                        <span class="text-muted">Tax:</span>
                        <span class="fw-medium">{{ number_format($order->tax, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between w-100 mb-2">
                        <span class="text-muted">Discount:</span>
                        <span class="text-success">-{{ number_format($order->discount, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between w-100 mb-2">
                        <span class="text-muted">Delivery Fee:</span>
                        <span class="fw-medium">{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>

                    <div class="w-100 border-top my-2"></div>

                    <div class="d-flex justify-content-between w-100">
                        <span class="fw-bold fs-5">Total:</span>
                        <span class="fw-bold fs-4 text-danger">{{ number_format($order->total, 2) }}</span>
                    </div>

                </div>
            </div>
        </div>


        <div class="mt-5 pt-4 border-top text-center">
            <p class="text-muted small mb-0">Thank you for ordering!</p>
        </div>

    </div>
</div>
