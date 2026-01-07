<div class="order-tracker-page" wire:poll.live>
    <link rel="stylesheet" href="{{ asset('css/customer/order-track.css') }}">
    <!-- Tracking Input -->
    <section class="tracking-hero" >
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-3">Track Your Order</h1>
                    <p class="text-muted mb-4">Enter your tracking ID to get real-time updates on your shipment.</p>
                    
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" class="form-control" placeholder="Enter order number" wire:model="orderNumber"/>
                        <button class="btn btn-danger px-4" wire:click="trackOrder">
                            <span>Track</span>
                        </button>
                    </div>

                    @if($errorMessage)
                        <div class="text-danger mt-2">{{ $errorMessage }}</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($order)
        <main class="container py-5">
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Status Card -->
                    <div class="card mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold fs-7 ls-1">Status</span>
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="status-badge me-3">
                                            <i class="fa-solid fa-truck-fast me-2"></i>{{ $order->status }}
                                        </div>
                                        <span class="text-muted small">Expected: <strong>{{ $order->completed_at ? $order->completed_at->format('M d, Y') : 'Pending' }}</strong></span>
                                    </div>
                                </div>
                                <div class="text-end mt-3 mt-md-0">
                                    <h5 class="fw-bold mb-0">{{ $order->order_number }}</h5>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            @php
                                $progress = match($order->status) {
                                    'pending' => 25,
                                    'in-process' => 50,
                                    'dispatched' => 75,
                                    'completed' => 100,
                                    default => 0
                                };
                            @endphp

                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Order Placed</span>
                                <span>Delivered</span>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fa-solid fa-list-ul me-2 text-danger"></i> Shipment History
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline">
                                @php
                                    $steps = ['Order Placed', 'Processed', 'Out for Delivery', 'Delivered'];
                                @endphp
                                @foreach($steps as $step)
                                    @php
                                        $stepClass = match($order->status) {
                                            'Pending' => $step == 'Order Placed' ? 'active' : '',
                                            'in-process' => $step == 'Processed' || $step == 'Order Placed' ? 'completed' : '',
                                            'dispatched' => $step == 'Out for Delivery' ? 'active' : ($step == 'Order Placed' || $step == 'Processed' ? 'completed' : ''),
                                            'completed' => 'completed',
                                            default => ''
                                        };
                                    @endphp
                                    <div class="timeline-item {{ $stepClass }}">
                                        <div class="timeline-marker">
                                            @if($stepClass == 'completed')
                                                <i class="fa-solid fa-check"></i>
                                            @elseif($stepClass == 'active')
                                                <i class="fa-solid fa-truck"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <h6>{{ $step }}</h6>
                                            <div class="timeline-date">{{ $order->placed_at->format('M d, Y - H:i') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Order Items -->
                    <div class="card mb-3">
                        <div class="card-header">
                            Order Items ({{ $order->items->count() }})
                        </div>
                        <div class="card-body p-3">
                            @foreach($order->items as $item)
                                @php
                                    $variations = json_decode($item->variations, true) ?: [];
                                    $extras = json_decode($item->extras, true) ?: [];
                                    $sideOrders = json_decode($item->side_orders, true) ?: [];
                                @endphp

                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $item->foodItem?->image ? asset('storage/' . $item->foodItem->image) : '' . $item->id . '/100/100' }}" 
                                        class="product-img me-3" 
                                        alt="{{ $item->item_name }}">

                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fs-6">{{ $item->item_name }}</h6>

                                        @if(count($variations))
                                            <small class="text-muted d-block">Variations: {{ implode(', ', array_map(fn($v) => $v['name'] ?? '', $variations)) }}</small>
                                        @endif

                                        @if(count($extras))
                                            <small class="text-muted d-block">Extras: {{ implode(', ', array_map(fn($e) => $e['name'] ?? '', $extras)) }}</small>
                                        @endif

                                        @if(count($sideOrders))
                                            <small class="text-muted d-block">Side Orders: {{ implode(', ', array_map(fn($s) => $s['name'] ?? '', $sideOrders)) }}</small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">Rs{{ number_format($item->unit_price) }}</div>
                                        <small class="text-muted">x{{ $item->quantity }}</small>
                                    </div>
                                </div>
                                <hr class="my-2">
                            @endforeach
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total Amount</span>
                                <span>Rs{{ number_format($order->total) }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Shipping Address -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fa-solid fa-map-pin me-2 text-danger"></i> Shipping Address
                        </div>
                        <div class="card-body">
                            <h6 class="mb-1">{{ $order->area }}</h6>
                            <p class="text-muted mb-0 small">
                                {{ json_decode($order->deliveryArea)->name ?? '' }}<br>
                                {{ $order->delivery_address }}<br>
                                Phone: {{ $order->customer->phone ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    @endif
    <x-notification-toast />
</div>
