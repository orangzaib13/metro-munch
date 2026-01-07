<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Branch;
use App\Models\DeliveryArea;
use App\Modles\FoodItem;

class AreaSelector extends Component
{
    public $branches = [];
    public $selectedBranch = null;
    public $deliveryAreas = [];
    public $selectedArea = null;
    public $showModal = true;
    
    // Store branch and area names for display
    public $selectedBranchName = null;
    public $selectedAreaName = null;

    public function mount()
    {
    $branchIds = DeliveryArea::pluck('branch_id');

    $this->branches = Branch::where('is_active', true)
        ->whereIn('id', $branchIds)
        ->whereHas('foodItems', function ($q) {
            $q->whereColumn('food_items.branch_id', 'branches.id');
        })
        ->get();

        // Check if session already has selections
        if (session()->has('selected_branch') && session()->has('selected_area')) {
            $this->selectedBranch = session('selected_branch');
            $this->selectedArea = session('selected_area');
            $this->selectedBranchName = session('branch_name');
            $this->selectedAreaName = session('area_name');
            $this->showModal = false;
        } else {
            $this->showModal = true;
        }
    }

    public function updatedSelectedBranch($branchId)
    {
        if ($branchId) {
            $branch = Branch::find($branchId);
            $this->selectedBranchName = $branch->name ?? null;
            
            $this->deliveryAreas = DeliveryArea::where('branch_id', $branchId)
                ->where('is_active', true)
                ->get();
                session(['selected_branch' => $branchId]);
            $this->dispatch('branch-changed', id: $branchId);
        } 
        else {
            $this->deliveryAreas = [];
            $this->selectedBranchName = null;
        }

        // Reset area when branch changes
        $this->selectedArea = null;
        $this->selectedAreaName = null;
    }

    public function updatedSelectedArea($areaId)
    {
        // Validate both branch and area are selected
        if (!$this->selectedBranch || !$areaId) {
            $this->dispatch('notify', message: 'Please select both branch and area', type: 'error');
            return;
        }

        // Get area details
        $area = DeliveryArea::find($areaId);
        
        if (!$area) {
            $this->dispatch('notify', message: 'Invalid area selected', type: 'error');
            return;
        }

        // Store in session
        session([
            'selected_branch' => $this->selectedBranch,
            'selected_area' => $areaId,
            'branch_name' => $this->selectedBranchName,
            'area_name' => $area->name,
        ]);

        $this->selectedAreaName = $area->name;
        $this->showModal = false;

        $this->dispatch('area-selected', 
            branchId: $this->selectedBranch,
            areaId: $areaId,
            branchName: $this->selectedBranchName,
            areaName: $area->name
        );

        // Emit JavaScript event to close modal
        $this->dispatch('close-modal');
    }

    public function resetSelection()
    {
        session()->forget(['selected_branch', 'selected_area', 'branch_name', 'area_name']);
        $this->selectedBranch = null;
        $this->selectedArea = null;
        $this->selectedBranchName = null;
        $this->selectedAreaName = null;
        $this->deliveryAreas = [];
        $this->showModal = true;
        $this->dispatch('open-modal');
    }

    public function render()
    {
        return view('livewire.customer.area-selector');
    }
}
