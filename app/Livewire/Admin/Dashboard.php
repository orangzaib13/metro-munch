<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Customer;
use App\Models\FoodItem;
use App\Models\Branch;

class Dashboard extends Component
{
    public $totalOrders = 0;
    public $completedOrders = 0;
    public $cancelledOrders = 0;
    public $pendingOrders = 0;

    public $subtotal = 0;
    public $tax = 0;
    public $discounts = 0;
    public $totalRevenue = 0;
    public $deliveryFees = 0;

    public $avgOrderValue = 0;
    public $avgDeliveryFee = 0;

    public $totalCustomers = 0;
    public $totalFoodItems = 0;
    public $totalBranches = 0;
    public $recentOrders = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $last7Days = now()->subDays(7);

        $orders = Order::whereBetween('placed_at', [$last7Days, now()])->get();

        $this->totalOrders = $orders->count();
        $this->completedOrders = $orders->where('status', 'completed')->count();
        $this->cancelledOrders = $orders->where('status', 'cancelled')->count();
        $this->pendingOrders   = $orders->whereIn('status', ['pending','in-process','dispatched'])->count();

        $this->subtotal      = $orders->sum('subtotal');
        $this->tax           = $orders->sum('tax');
        $this->discounts     = $orders->sum('discount');
        $this->totalRevenue  = $orders->sum('total');
        $this->deliveryFees  = $orders->sum('delivery_fee');

        if ($this->totalOrders > 0) {
            $this->avgOrderValue = round($this->totalRevenue / $this->totalOrders, 2);
            $this->avgDeliveryFee = round($this->deliveryFees / $this->totalOrders, 2);
        }

        // 👍 Load missing stats
        $this->totalCustomers = Customer::count();
        $this->totalFoodItems = FoodItem::count();
        $this->totalBranches  = Branch::count();

        $this->recentOrders = Order::with('customer')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
