<?php

namespace App\Livewire\Branch;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class Analytics extends Component
{
    public $timeFilter = 'last-7-days';

    public $branchId;

    public $totalOrders = 0;
    public $completedOrders = 0;
    public $cancelledOrders = 0;
    public $pendingOrders = 0;
    public $dispatchedOrders = 0;
    public $inProcessOrders = 0;

    public $subtotal = 0;
    public $tax = 0;
    public $discounts = 0;
    public $totalRevenue = 0;
    public $deliveryFees = 0;
    public $avgOrderValue = 0;
    public $avgDeliveryFee = 0;

    public $orderStatuses = [];

    public function mount()
    {
        // Force single branch
        $this->branchId = Auth::user()->branch_id;

        $this->loadAnalytics();
    }

    public function updatedTimeFilter()
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics()
    {
        $query = Order::where('branch_id', $this->branchId);

        // Time filter
        match ($this->timeFilter) {
            'last-7-days' => $query->whereBetween('placed_at', [now()->subDays(7), now()]),
            'last-30-days' => $query->whereBetween('placed_at', [now()->subDays(30), now()]),
            'this-month'  => $query->whereMonth('placed_at', now()->month),
            default       => null,
        };

        $orders = $query->get();

        // Order counts
        $this->totalOrders      = $orders->count();
        $this->completedOrders  = $orders->where('status', 'completed')->count();
        $this->cancelledOrders  = $orders->where('status', 'cancelled')->count();
        $this->pendingOrders    = $orders->where('status', 'pending')->count();
        $this->dispatchedOrders = $orders->where('status', 'dispatched')->count();
        $this->inProcessOrders  = $orders->where('status', 'in-process')->count();

        // Financial metrics
        $this->subtotal      = $orders->sum('subtotal');
        $this->tax           = $orders->sum('tax');
        $this->discounts     = $orders->sum('discount');
        $this->totalRevenue  = $orders->sum('total');
        $this->deliveryFees  = $orders->sum('delivery_fee');

        $this->avgOrderValue  = $this->totalOrders ? round($this->totalRevenue / $this->totalOrders, 2) : 0;
        $this->avgDeliveryFee = $this->totalOrders ? round($this->deliveryFees / $this->totalOrders, 2) : 0;

        // Status distribution
        $this->orderStatuses = [
            'pending'    => $this->pendingOrders,
            'in-process' => $this->inProcessOrders,
            'dispatched' => $this->dispatchedOrders,
            'completed'  => $this->completedOrders,
            'cancelled'  => $this->cancelledOrders,
        ];
    }



    public function render()
    {
        return view('livewire.branch.analytics');
    }
}
