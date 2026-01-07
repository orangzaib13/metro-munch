<?php

namespace App\Livewire\Branch;

use Livewire\Component;

class OrderNotifications extends Component
{
    public $orders = [];

    protected $listeners = ['newOrder'];

    public function newOrder($order)
    {
        $this->orders[] = $order;
        $this->dispatch('orderAdded', ['order' => $order]); // internal dispatch
    }

    public function render()
    {
        return view('livewire.branch.order-notifications');
    }
}
