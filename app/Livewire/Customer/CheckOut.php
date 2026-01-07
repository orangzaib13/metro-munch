<?php

namespace App\Livewire\Customer;


use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Branch;
use App\Models\DeliveryArea;
use App\Models\GlobalDiscount;
use App\Models\Discount;
use App\Models\Customer;
use App\Events\NewOrderPlaced;
use Livewire\Component;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $cart = [];
    public $cartTotal = 0;
    public $cartTax = 0;
    public $cartGrandTotal = 0;
    
    public $customerName = '';
    public $customerPhone = '';
    public $customerEmail = '';
    public $customerAddress = '';
    
    public $selectedBranch;
    public $selectedArea;
    public $selectedAreaName = '';
    public $deliveryFee = 0;
    
    public $globalDiscount = 0;
    public $branchPromoDiscount = 0;
    public $totalDiscount = 0;
    
    public $paymentMethod = 'cash_on_delivery';
    public $specialInstructions = '';
    
    public $orderPlaced = false;
    public $orderNumber = '';
    public $orderTotal = 0;
    
    public $branches = [];
    public $deliveryAreas = [];
    public $formErrors = [];
    public $promoCode = '';
    public $promoCodeMessage = '';
    public $promoCodeValid = false;
    
    public function mount()
    {
        $this->cart = session()->get('cart', []);
        
        $this->calculateTotals();
        
        if (session()->has('selected_branch_id')) {
            $this->selectedBranch = session()->get('selected_branch_id');
            $this->loadDeliveryAreas();
        }
        
        if (session()->has('selected_area_id')) {
            $this->selectedArea = session()->get('selected_area_id');
            $this->getDeliveryFee();
        }
        
        if(session()->has('area_name')) {
            $this->selectedAreaName = session()->get('area_name');
        }
        
        $this->branches = Branch::where('is_active', true)->get();

        if (count($this->cart) === 0) {
            return $this->redirectRoute('home', navigate: true);
    }
    }
    
    public function loadGlobalDiscount()
    {
        $globalDiscount = GlobalDiscount::first();
        
        if ($globalDiscount && $globalDiscount->count() > 0 && $globalDiscount->discount_percentage > 0 && $globalDiscount->is_active) {
            $discountAmount = ($this->cartTotal * $globalDiscount->discount_percentage) / 100;
            $this->globalDiscount = $discountAmount;
        } else {
            $this->globalDiscount = 0;
        }
        
        $this->calculateTotalDiscount();
    }
    
    public function validatePromoCode()
    {
        $this->promoCodeMessage = '';
        $this->branchPromoDiscount = 0;
        $this->promoCodeValid = false;
        
        if (empty($this->promoCode)) {
            $this->promoCodeMessage = 'Please enter a promo code';
            return;
        }
        
        if (!$this->selectedBranch) {
            $this->promoCodeMessage = 'Please select a branch first';
            return;
        }
        
        // Find discount by code for this branch
        $discount = Discount::where('branch_id', $this->selectedBranch)
            ->where('code', strtoupper($this->promoCode))
            ->where('is_active', true)
            ->first();
        
        if (!$discount) {
            $this->promoCodeMessage = 'Invalid promo code for this branch';
            return;
        }
        
        // Check if code is within valid date range
        if ($discount->valid_from && now() < $discount->valid_from) {
            $this->promoCodeMessage = 'This promo code is not yet active';
            return;
        }
        
        if ($discount->valid_to && now() > $discount->valid_to) {
            $this->promoCodeMessage = 'This promo code has expired';
            return;
        }
        
        // Check usage limit
        if ($discount->usage_limit && $discount->usage_count >= $discount->usage_limit) {
            $this->promoCodeMessage = 'This promo code has reached its usage limit';
            return;
        }
        
        // Check minimum order value
        if ($discount->min_order_value && $this->cartTotal < $discount->min_order_value) {
            $this->promoCodeMessage = 'Minimum order value of Rs.' . $discount->min_order_value . ' required';
            return;
        }
        
        $amountAfterGlobalDiscount = $this->cartTotal - $this->globalDiscount;
        
        if ($discount->type === 'percentage') {
            $discountAmount = ($amountAfterGlobalDiscount * $discount->value) / 100;
            // Cap discount at max_discount if set
            if ($discount->max_discount) {
                $discountAmount = min($discountAmount, $discount->max_discount);
            }
        } else {
            // Fixed amount discount
            $discountAmount = $discount->value;
        }
        
        $this->branchPromoDiscount = $discountAmount;
        $this->promoCodeValid = true;
        $this->promoCodeMessage = 'Promo code applied successfully!';
        
        $this->calculateTotalDiscount();
    }
    
    public function removePromoCode()
    {
        $this->promoCode = '';
        $this->promoCodeMessage = '';
        $this->promoCodeValid = false;
        $this->branchPromoDiscount = 0;
        $this->calculateTotalDiscount();
    }
    
    public function loadDeliveryAreas()
    {
        if ($this->selectedBranch) {
            $this->deliveryAreas = DeliveryArea::where('branch_id', $this->selectedBranch)
                ->where('is_active', true)
                ->get();
        }
    }

    
    public function updatedSelectedBranch()
    {
        session()->put('selected_branch_id', $this->selectedBranch);
        $this->selectedArea = null;
        $this->deliveryFee = 0;
        $this->removePromoCode();
        $this->loadDeliveryAreas();
        $this->calculateTotals();
    }
    
    public function updatedSelectedArea()
    {
        if ($this->selectedArea) {
            session()->put('selected_area_id', $this->selectedArea);
            $this->getDeliveryFee();
        }
    }
    
    public function getDeliveryFee()
    {
        if ($this->selectedArea) {
            $area = DeliveryArea::find($this->selectedArea);
            $this->deliveryFee = $area?->delivery_fee ?? 0;
            $this->calculateTotals();
        }
    }
    
    public function validateForm()
    {
        $this->formErrors = [];
        
        if (empty($this->customerName)) {
            $this->formErrors['customerName'] = 'Customer name is required';
        }
        
        if (empty($this->customerPhone)) {
            $this->formErrors['customerPhone'] = 'Phone number is required';
        } elseif (!preg_match('/^[0-9]{10,}$/', $this->customerPhone)) {
            $this->formErrors['customerPhone'] = 'Invalid phone number';
        }
        
        if (empty($this->customerAddress)) {
            $this->formErrors['customerAddress'] = 'Delivery address is required';
        }
        
        if (empty($this->selectedBranch)) {
            $this->formErrors['selectedBranch'] = 'Please select a branch';
        }
        
        if (empty($this->selectedArea)) {
            $this->formErrors['selectedArea'] = 'Please select a delivery area';
        }
        
        if (count($this->cart) === 0) {
            $this->formErrors['cart'] = 'Your cart is empty';
        }
        
        return count($this->formErrors) === 0;
    }
    
    public function placeOrder()
    {
        // Validate the checkout form
        if (!$this->validateForm()) {
            $this->dispatch('');
            $this->dispatch('notify', message: 'order-validation-failed', type: 'error');
            return;
        }

        try {
            $customer = Customer::firstOrCreate(
        ['phone' => $this->customerPhone],
        [
            'name' => $this->customerName,
            'email' => $this->customerEmail,
            'area' => $this->selectedAreaName,
            'total_orders' => 0,
            'total_spent' => 0,
            'last_order_at' => now(),
        ]
    );

    // Update totals if customer exists
    if (!$customer->wasRecentlyCreated) {
        $customer->increment('total_orders');
        $customer->increment('total_spent', $this->cartGrandTotal);
        $customer->update(['last_order_at' => now()]);
    } else {
        $customer->update([
            'total_orders' => 1,
            'total_spent' => $this->cartGrandTotal,
        ]);
    }

    // Create order using customer_id
    $order = Order::create([
        'order_number'     => 'ORD-' . strtoupper(Str::random(8)),
        'branch_id'        => $this->selectedBranch,
        'customer_id'      => $customer->id,
        'type'             => 'delivery',
        'area'             => $this->selectedAreaName,
        // Subtotal: sum of discounted prices per item
        'subtotal'         => collect($this->cart)->sum(function($item) {
                                return ($item['discounted_price'] ?? $item['price']) * $item['quantity'];
                            }),
        'tax'              => 0,
        // Total discount (global + promo)
        'discount'         => $this->totalDiscount,
        'delivery_fee'     => $this->deliveryFee,
        // Grand total = subtotal + delivery_fee - discount
        'total'            => collect($this->cart)->sum(function($item) {
                                return ($item['discounted_price'] ?? $item['price']) * $item['quantity'];
                            }) + $this->deliveryFee - $this->totalDiscount,
        'delivery_address' => $this->customerAddress,
        'payment_status'   => 'pending',
        'notes'            => $this->specialInstructions,
        'status'           => 'pending',
        'placed_at'        => now(),
    ]);


     // Create Order Items
    foreach ($this->cart as $item) {
        OrderItem::create([
        'order_id'       => $order->id,
        'food_item_id'   => $item['id'],
        'item_name'      => $item['name'],
        'quantity'       => $item['quantity'],
        'unit_price'     => $item['price'],
        'subtotal'       => $item['price'] * $item['quantity'],
        'variations'     => json_encode($item['variation'] ?? []),
        'extras'         => json_encode($item['extras'] ?? []),
        'side_orders'    => json_encode($item['side_orders'] ?? []),
         ]);
    }

    //Broadcast Order Event
    NewOrderPlaced::dispatch($order);

        // Handle Promo Code Usage

        if ($this->promoCodeValid) {
            $discount = Discount::where('branch_id', $this->selectedBranch)
            ->where('code', strtoupper($this->promoCode))
            ->first();

            if ($discount) {
                $discount->increment('usage_count');
            }
        }

        // Clear Session & Set Success State
        session()->forget(['cart', 'cart_total']);

        $this->orderPlaced   = true;
        $this->orderNumber   = $order->order_number;
        $this->orderTotal    = $this->cartGrandTotal;

        $this->dispatch('notify', message: 'Order placed successfully!', type: 'success');

        } catch (\Exception $e) {
            // Handle errors
            $this->formErrors['general'] = 'Error placing order: ' . $e->getMessage();
            $this->dispatch('order-error');
        }
    }

    public function calculateTotalDiscount()
    {
        $this->totalDiscount = $this->globalDiscount + $this->branchPromoDiscount;
    }
    
    public function calculateTotals()
    {
        $this->cartTotal = collect($this->cart)->sum(function($item) {
        return ($item['discounted_price'] ?? $item['price']) * $item['quantity'];
    });

        
        $this->cartTax = 0;
        
        $this->loadGlobalDiscount();
        
        $this->cartGrandTotal = $this->cartTotal + $this->deliveryFee - $this->totalDiscount;
    }
    
    public function render()
    {
        return view('livewire.customer.check-out');
    }
}
