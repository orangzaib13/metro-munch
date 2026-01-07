<?php

namespace App\Livewire\Branch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Component
{
    use WithPagination;

    public $dateFilter = 'today';
    public $statusFilter = 'all';
    public $search = '';
    public $branches = [];

    protected $queryString = [
        'dateFilter' => ['except' => 'today'],
        'statusFilter' => ['except' => 'all'],
        'branchFilter' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->branches = Branch::where('is_active', true)
        ->where('id', Auth::user()->branch_id)
        ->get();

    }

    public function updatedDateFilter() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    public function getOrdersProperty()
    {
        $query = Order::query();

        if ($this->dateFilter === 'today') {
            $query->whereDate('placed_at', now());
        } elseif ($this->dateFilter === 'yesterday') {
            $query->whereDate('placed_at', now()->subDay());
        } elseif ($this->dateFilter === 'week') {
            $query->whereBetween('placed_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->dateFilter === 'month') {
            $query->whereMonth('placed_at', now()->month);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where(function($q){
                $q->where('order_number', 'like', '%' . $this->search . '%')
                ->orWhereHas('customer', function($q2){
                    $q2->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        return $query->latest('placed_at')->paginate(12);
    }

        public function deleteOrder($orderId)
        {
            Order::find($orderId)?->delete();
            session()->flash('message', 'Order deleted successfully.');
        }

        public function render()
    {
        return view('livewire.branch.order-history', [
            'orders' => $this->orders, 
        ]);
    }

 }
