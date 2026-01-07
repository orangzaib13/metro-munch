<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Discount;
use App\Models\GlobalDiscount;
use App\Models\Branch;

class DiscountManagement extends Component
{
    public $activeTab = 'global';
    public $isModalOpen = false;


    // Global Discount Fields
    public $global = [
        'discount_percentage' => 0,
        'is_active' => false,
    ];

    // Promo Code Form
    public $promo = [
        'branch_id' => '',
        'code' => '',
        'description' => '',
        'type' => 'percentage',
        'value' => '',
        'max_discount' => '',
        'min_order_value' => '',
        'usage_limit' => '',
        'valid_from' => '',
        'valid_to' => '',
        'is_active' => true,
    ];

    public $promoCodes = [];
    public $branches;
    public $editingId = null;
    public $globalId = null;

    protected $rules = [
        // Promo rules
        'promo.branch_id' => 'required|exists:branches,id',
        'promo.code' => 'required|string|unique:discounts,code',
        'promo.type' => 'required|in:percentage,fixed',
        'promo.value' => 'required|numeric|min:0',
        'promo.max_discount' => 'nullable|numeric|min:0',
        'promo.min_order_value' => 'nullable|numeric|min:0',
        'promo.usage_limit' => 'nullable|integer|min:0',
        'promo.valid_from' => 'nullable|date',
        'promo.valid_to' => 'nullable|date|after_or_equal:promo.valid_from',

        // Global discount rules
        'global.discount_percentage' => 'required|numeric|min:0|max:100',
        'global.is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openModal($id = null)
    {
        $this->resetPromo();
        if ($id) {
            $this->editPromo($id);
        }
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetPromo();
    }

    public function loadData()
    {
        $this->branches = Branch::all();

        $this->promoCodes = Discount::with('branch')
            ->where('is_active', true)
            ->get();

        // Load existing global discount (only one row)
        $global = GlobalDiscount::first();
        if ($global) {
            $this->globalId = $global->id;
            $this->global = $global->only(['discount_percentage','is_active']);
        }
    }

    public function saveGlobalDiscount()
    {
        $this->validate([
            'global.discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $record = GlobalDiscount::updateOrCreate(
            ['id' => $this->globalId],
            $this->global
        );

        $this->globalId = $record->id;

        $this->dispatch('notify', message: 'Global discount updated successfully');
    }

    public function savePromo()
    {
        $this->validate();

        if ($this->editingId) {
            Discount::find($this->editingId)->update($this->promo);
            $msg = 'Promo code updated successfully';
        } else {
            Discount::create($this->promo);
            $msg = 'Promo code created successfully';
        }

        $this->resetPromo();
        $this->loadData();
        $this->isModalOpen = false;
        $this->dispatch('notify', message: $msg);
    }

    public function editPromo($id)
    {
        $discount = Discount::findOrFail($id);

        $this->promo = $discount->only([
            'branch_id','code','description','type','value',
            'max_discount','min_order_value','usage_limit',
            'valid_from','valid_to','is_active'
        ]);

        $this->editingId = $id;
    }

    public function deletePromo($id)
    {
        Discount::find($id)?->delete();
        $this->loadData();

        $this->dispatch('notify', message: 'Promo code deleted successfully');

    }

    public function resetPromo()
    {
        $this->editingId = null;

        $this->promo = [
            'branch_id' => '',
            'code' => '',
            'description' => '',
            'type' => 'percentage',
            'value' => '',
            'max_discount' => '',
            'min_order_value' => '',
            'usage_limit' => '',
            'valid_from' => '',
            'valid_to' => '',
            'is_active' => true,
        ];
    }

    public function render()
    {
        return view('livewire.admin.discount-management');
    }
}
