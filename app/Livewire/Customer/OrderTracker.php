<?php
namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use App\Models\Customer;

class OrderTracker extends Component
{
    public $orderNumber = '';
    public $order;
    public $customer;
    public $errorMessage = '';

    public function mount()
    {
        $this->orderNumber = request()->query('order');

        if ($this->orderNumber) {
            $this->trackOrder();
        }
    }

    public function trackOrder()
    {
        $this->reset(['order', 'customer', 'errorMessage']);

        if (!$this->orderNumber) {
            $this->errorMessage = 'Please enter a tracking ID.';
            return;
        }

        // Fetch order with items and delivery area
        $this->order = Order::with(['items', 'deliveryArea', 'customer'])
            ->where('order_number', $this->orderNumber)
            ->first();

        if (!$this->order) {
            $this->errorMessage = 'Order not found. Please check the Order ID.';
            return;
        }

        // Fetch the customer manually using customer_id
        if ($this->order->customer_id) {
            $this->customer = Customer::find($this->order->customer_id);

            if (!$this->customer) {
                $this->errorMessage = 'Customer not found for this order.';
            }
        }
    }

    public function render()
    {
        return view('livewire.customer.order-tracker');
    }
}
