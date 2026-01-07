<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Branch;

class BranchManagement extends Component
{
    public $branches;
    public $branchesStatus = [];
    public $isModalOpen = false;

    public $editingId = null;

    public $name = '';
    public $location = '';
    public $phone = '';
    public $email = '';
    public $address = '';


    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function mount()
    {
        // Pre-fill the array with existing statuses
        foreach (Branch::all() as $branch) {
            $this->branchesStatus[$branch->id] = $branch->is_active ? "1" : "0";
        }
        $this->loadBranches();
    }

    public function loadBranches()
    {
        $this->branches = Branch::all();
        info($this->branches);
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);

        $this->editingId = $id;
        $this->name = $branch->name;
        $this->location = $branch->location;
        $this->phone = $branch->phone;
        $this->email = $branch->email;
        $this->address = $branch->address;

        $this->openModal();
    }

    public function save()
    {
        $this->validate([
            'name'     => 'required|string|min:3',
            'location' => 'required|string',
            'phone'    => 'nullable|string',
            'email'    => 'nullable|email',
            'address'  => 'nullable|string',
        ]);

        Branch::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'     => $this->name,
                'location' => $this->location,
                'phone'    => $this->phone,
                'email'    => $this->email,
                'address'  => $this->address,
            ]
        );
        if($this->editingId) {
        $this->dispatch('notify', message: 'Branch updated 🎉', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Branch created 🎉', type: 'success');
        }

        $this->resetForm();
        $this->closeModal();
        $this->loadBranches();
    }

    public function updatedBranchesStatus($value, $id)
    {
        $branch = Branch::find($id);
        $branch->update(['is_active' => $value]);
        $this->dispatch('notify', message: 'Branch status updated 🎉', type: 'success');
    }


    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->location = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
    }

    public function render()
    {
        return view('livewire.admin.branch-management');
    }
}
