<?php

namespace App\Livewire\Branch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrdersManagement extends Component
{
    use WithPagination;

    public $dateFilter = 'today';
    public $statusFilter = 'all';
    public $search = '';

    public $branchId;

    protected $queryString = [
        'dateFilter' => ['except' => 'today'],
        'statusFilter' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->branchId = Auth::user()->branch_id;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        $query = Order::query()
            ->where('branch_id', $this->branchId);

        if ($this->dateFilter === 'today') 
        {
            $query->whereDate('placed_at', now());
        } 
        elseif ($this->dateFilter === 'yesterday')
        {
            $query->whereDate('placed_at', now()->subDay());
        }
         elseif ($this->dateFilter === 'week') 
        {
            $query->whereBetween('placed_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }
         elseif ($this->dateFilter === 'month') 
        {
            $query->whereMonth('placed_at', now()->month);
        }

        // Status filter
        if ($this->statusFilter !== 'all' && $this->statusFilter !== '') 
        {
            $query->where('status', $this->statusFilter);
        }


        // Search
        if ($this->search) {
            $query->where(function (Builder $q) {
                $q->where('order_number', 'like', "%{$this->search}%")
                ->orWhereHas('customer', function ($c) {
                    $c->where('name', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            });
        }


        return $query->latest('placed_at')->paginate(10);
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::where('branch_id', $this->branchId)
                      ->find($orderId);

        if ($order) {
            $updateData = ['status' => $status];

            if ($status === 'completed') {
                $updateData['completed_at'] = Carbon::now();
            }

            $order->update($updateData);

            $this->dispatch('notify', message: 'Order status updated successfully');
        }
    }

    public function render()
    {
        return view('livewire.branch.orders-management', [
            'orders' => $this->orders,
            'totalOrders' => $this->orders->total(),
        ]);
    }
}
