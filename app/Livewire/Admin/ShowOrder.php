<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;

class ShowOrder extends Component
{
    public $orderId;
    public $order;

    public function mount($orderId)
    {
        $this->orderId = $orderId;

        $this->order = Order::with([
            'customer',
            'branch',
            'items.foodItem' 
        ])->findOrFail($orderId);
    }

    public function render()
    {
        return view('livewire.admin.show-order');
    }
}
