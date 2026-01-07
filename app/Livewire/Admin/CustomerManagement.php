<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class CustomerManagement extends Component
{
    use WithPagination;

    public $orderCountFilter = 'any';
    public $search = '';

    protected $queryString = [
        'orderCountFilter' => ['except' => 'any'],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getCustomersProperty()
    {
        $query = Customer::query();

        if ($this->orderCountFilter !== 'any') {
            if ($this->orderCountFilter === '1-5') {
                $query->whereBetween('total_orders', [1, 5]);
            } elseif ($this->orderCountFilter === '6-15') {
                $query->whereBetween('total_orders', [6, 15]);
            } elseif ($this->orderCountFilter === '16+') {
                $query->where('total_orders', '>=', 16);
            }
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        return $query->latest('last_order_at')->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.customer-management', [
            'customers' => $this->customers,
        ]);
    }
}
