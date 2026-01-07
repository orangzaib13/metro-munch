<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DeliveryArea;
use App\Models\Branch;

class DeliveryAreasManagement extends Component
{
    public $deliveryAreas;
    public $branches;

    // Modal & form
    public $isModalOpen = false;
    public $editingId = null;

    public $branchId = '';
    public $name = '';
    public $deliveryFee = '';
    public $isActive = true;

    protected $rules = [
        'branchId' => 'required|exists:branches,id',
        'name' => 'required|string|min:2',
        'deliveryFee' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->branches = Branch::all();
        $this->deliveryAreas = DeliveryArea::with('branch')->get();
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        if ($id) {
            $this->editDeliveryArea($id);
        }
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->branchId = '';
        $this->name = '';
        $this->deliveryFee = '';
        $this->isActive = true;
    }

    public function saveDeliveryArea()
    {
        $this->validate();

        if ($this->editingId) {
            $area = DeliveryArea::findOrFail($this->editingId);
            $area->update([
                'branch_id' => $this->branchId,
                'name' => $this->name,
                'delivery_fee' => $this->deliveryFee,
                'is_active' => $this->isActive,
            ]);
            $msg = 'Delivery area updated successfully';
        } else {
            DeliveryArea::create([
                'branch_id' => $this->branchId,
                'name' => $this->name,
                'delivery_fee' => $this->deliveryFee,
                'is_active' => $this->isActive,
            ]);
            $msg = 'Delivery area added successfully';
        }

        $this->loadData();
        $this->closeModal();
        $this->dispatch('notify', message: $msg);
    }

    public function editDeliveryArea($id)
    {
        $area = DeliveryArea::findOrFail($id);
        $this->editingId = $id;
        $this->branchId = $area->branch_id;
        $this->name = $area->name;
        $this->deliveryFee = $area->delivery_fee;
        $this->isActive = $area->is_active;
    }

    public function toggleActive($id)
    {
        $area = DeliveryArea::find($id);
        if ($area) {
            $area->update(['is_active' => !$area->is_active]);
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.admin.delivery-areas-management');
    }
}
