<div class="checkout-page">
    @if($orderPlaced)
        <!-- Success Screen -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="card-body p-5 text-center">
                            <!-- Success Icon -->
                            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #c41e3a 0%, #a81830 100%); 
                                        border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check fa-3x text-white"></i>
                            </div>

                            <h2 class="fw-bold text-dark mb-2">Order Placed Successfully!</h2>
                            <p class="text-muted mb-4">Your order has been confirmed and sent to the restaurant</p>

                            <!-- Order Details -->
                            <div class="bg-light p-4 rounded-3 mb-4">
                                <div class="mb-3">
                                    <small class="text-muted">ORDER NUMBER</small>
                                    <h4 class="fw-bold text-danger mb-0">{{ $orderNumber }}</h4>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <small class="text-muted">TOTAL AMOUNT</small>
                                    <h4 class="fw-bold text-dark mb-0">Rs. {{ number_format($orderTotal, 2) }}</h4>
                                </div>
                                <hr>
                                <div>
                                    <small class="text-muted">PAYMENT METHOD</small>
                                    <p class="fw-bold mb-0">Cash on Delivery</p>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="alert alert-success border-0 mb-4">
                                <i class="fas fa-info-circle"></i> Your order will be delivered soon. You will receive updates via Email.
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <!-- Changed from route() to wire:navigate -->
                                <a href="#" wire:navigate href="/menu" class="btn btn-danger btn-lg">
                                    <span>
                                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                                    </span>
                                </a>
                                <a href="/order-tracker?order={{ $orderNumber }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-list"></i> View Order Status
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Checkout Form -->
        <div class="container py-5">
            <div class="row g-4">
                <!-- Left Column - Form -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold">Delivery Information</h4>
                                <a href="/" class="btn" wire:navigate.hover>
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </a>
                            </div>

                            <!-- Branch Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Select Branch</label>
                                <select wire:model.change="selectedBranch" class="form-select form-select-lg">
                                    <option value="">-- Select a Branch --</option>
                                    @if(session()->has('selected_branch'))
                                        <option value="{{\App\Models\Branch::find(session('selected_branch'))->id}}">
                                            {{ \App\Models\Branch::find(session('selected_branch'))->name }}
                                        </option>
                                    @endif
                                </select>
                                @error('selectedBranch')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Area Selection -->
                            @if(count($deliveryAreas) > 0)
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Select Delivery Area</label>
                                    <select wire:model.change="selectedArea" class="form-select form-select-lg" 
                                        >
                                        <option value="">-- Select an Area --</option>
                                        @foreach($deliveryAreas as $area)
                                            <option value="{{ $area->id }}">
                                                {{ $area->name }} (Rs. {{ number_format($area->delivery_fee, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedArea')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endif

                            <hr>

                            <h5 class="fw-bold mb-3">Customer Details</h5>

                            <!-- Customer Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name *</label>
                                <input type="text" wire:model="customerName" class="form-control form-control-lg" 
                                       placeholder="Enter your full name">
                                @if(isset($formErrors['customerName']))
                                    <small class="text-danger">{{ $formErrors['customerName'] }}</small>
                                @endif
                            </div>

                            <!-- Customer Phone -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Phone Number *</label>
                                <input type="tel" wire:model="customerPhone" class="form-control form-control-lg" 
                                       placeholder="03XXXXXXXXX">
                                @if(isset($formErrors['customerPhone']))
                                    <small class="text-danger">{{ $formErrors['customerPhone'] }}</small>
                                @endif
                            </div>

                            <!-- Customer Email -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email (Optional)</label>
                                <input type="email" wire:model="customerEmail" class="form-control form-control-lg" 
                                       placeholder="your@email.com">
                            </div>

                            <!-- Customer Address -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Delivery Address *</label>
                                <textarea wire:model="customerAddress" class="form-control form-control-lg" 
                                          placeholder="Enter complete delivery address" rows="3" 
                                        ></textarea>
                                @if(isset($formErrors['customerAddress']))
                                    <small class="text-danger">{{ $formErrors['customerAddress'] }}</small>
                                @endif
                            </div>

                            <!-- Special Instructions -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Special Instructions</label>
                                <textarea wire:model="specialInstructions" class="form-control form-control-lg" 
                                          placeholder="Any special requests? (optional)" rows="2" 
                                        ></textarea>
                            </div>

                            <!-- Promo Code Section -->
                            <hr>

                            <h5 class="fw-bold mb-3">Apply Promo Code</h5>
                            @if(!$promoCodeValid)
                                <div class="input-group input-group-lg mb-2" >
                                    <input type="text" wire:model="promoCode" class="form-control" 
                                           placeholder="Enter promo code">
                                    <button wire:click="validatePromoCode" class="btn btn-outline-danger" >
                                        <i class="fas fa-check"></i> Apply
                                    </button>
                                </div>
                                @if($promoCodeMessage && !$promoCodeValid)
                                    <small class="text-danger d-block mb-3">
                                         {{ $promoCodeMessage }}
                                    </small>
                                @endif
                            @else
                                <div class="alert alert-success border-0 mb-3">
                                    <i class="fas fa-check-circle"></i> 
                                    <strong>{{ $promoCodeMessage }}</strong>
                                    <button type="button" wire:click="removePromoCode" class="btn-close float-end"></button>
                                </div>
                            @endif

                            <hr>

                            <!-- Payment Method -->
                            <h5 class="fw-bold mb-4">Payment Method</h5>
                            <div class="mb-3 d-flex flex-row align-items-center justify-content-between">
                                <div>
                                    <input class="form-check-input" type="radio" wire:model="paymentMethod" 
                                       value="cash_on_delivery" id="cod" checked>
                                    <label class="form-check-label fw-bold" for="cod">
                                        Cash on Delivery
                                    </label>
                                </div>
                                <div>
                                    <small class="text-muted ms-4">Pay when your order arrives</small>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <button wire:click="placeOrder" class="btn btn-danger btn-lg w-100 py-3">
                                <span class="fs-6 text-uppercase fw-bold">Place Order</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-lg-5 discount-alerts">
                    <div class="card border-0 shadow-lg sticky-top" style="border-radius: 20px; top: 100px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Order Summary</h5>

                            <!-- Cart Items -->
                            <div class="mb-4" style="max-height: 400px; overflow-y: auto;">
                                @forelse($cart as $item)
                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                        <div>
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="mb-2 rounded" width="100px" />
                                            <p class="text-sm mb-1">{{ $item['name'] }}</p>
                                            @if(isset($item['variation_name']))
                                                <small class="text-muted">{{ $item['variation_name'] }}</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            @if(isset($item['discounted_price']) && $item['discounted_price'] < $item['price'])
                                                <!-- Show both original and discounted price -->
                                                <span class="text-muted text-decoration-line-through">
                                                    Rs. {{ number_format($item['price'] * $item['quantity'], 2) }}
                                                </span>
                                                <br>
                                                <span class="fw-bold text-danger">
                                                    Rs. {{ number_format($item['discounted_price'] * $item['quantity'], 2) }}
                                                </span>
                                            @else
                                                <!-- Show only regular price -->
                                                <span class="fw-bold">
                                                    Rs. {{ number_format($item['price'] * $item['quantity'], 2) }}
                                                </span>
                                            @endif
                                            <br>
                                            <small class="text-muted d-block">x {{ $item['quantity'] }}</small>
                                        </div>
                                    </div>

                                @empty
                                    <p class="text-muted text-start">Your cart is empty</p>
                                @endforelse
                            </div>

                            <!-- Updated totals section with discounts -->
                            <!-- Totals -->
                            <div class="bg-light p-4 rounded-3 mb-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold">Rs. {{ number_format($cartTotal, 2) }}</span>
                                </div>
                                
                                <!-- Enhanced Global Discount with prominent visual styling matching promo code -->
                                @if($globalDiscount > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 alert">
                                        <span class="fw-bold text-success">
                                            <i class="fas fa-star me-2"></i>Global Discount
                                        </span>
                                        <span class="fw-bold text-success fs-6">-Rs. {{ number_format($globalDiscount, 2) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Enhanced Promo Code Discount with matching prominent styling -->
                                @if($branchPromoDiscount > 0)
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 alert">
                                        <span class="fw-bold text-warning">
                                            <i class="fas fa-gift me-2"></i>Promo Code: {{ strtoupper($promoCode) }}
                                        </span>
                                        <span class="fw-bold text-warning fs-6">-Rs. {{ number_format($branchPromoDiscount, 2) }}</span>
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                    <span class="text-muted"><i class="fas fa-truck me-2"></i>Delivery Fee</span>
                                    <span class="fw-bold">Rs. {{ number_format($deliveryFee, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold fs-5">Total Amount</span>
                                    <span class="fw-bold fs-5 text-danger">Rs. {{ number_format($cartGrandTotal, 2) }}</span>
                                </div>
                            </div>

                            <!-- Savings Info Box -->
                            <!-- Added prominent savings display box -->
                            @if($totalDiscount > 0)
                                <div class="alert border-0 mb-4" >
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-success">Great Savings!</h6>
                                            <p class="mb-0 text-success">You are saving <strong>Rs. {{ number_format($totalDiscount, 2) }}</strong> on this order!</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning border-0 mb-4 text-center">
                                    <i class="fas fa-info-circle me-2"></i> 
                                    <strong>Have a promo code?</strong> Enter it above to save more on your order!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <x-notification-toast />
</div>
