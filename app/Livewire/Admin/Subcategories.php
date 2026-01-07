<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Subcategories extends Component
{
    use WithFileUploads;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $subcategories;
    public $categories;

    public $isCreateModalOpen = false;
    public $isEditModalOpen = false;

    // Form fields
    public $subcategoryId;
    public $name;
    public $category_id;
    public $branch_id;
    public $image;
    public $display_order;
    public $is_active = true;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->subcategories = Subcategory::with('category', 'branch')->get();
        $this->categories = Category::all();
    }

    // Open create modal
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isCreateModalOpen = true;
    }

    // Save new subcategory
    public function saveSubcategory()
    {
        $this->validate([
            'name' => 'required|string|min:2',
            'category_id' => 'required|exists:categories,id',
            'display_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $category = Category::findOrFail($this->category_id);
        $this->branch_id = $category->branch_id;

        $imagePath = $this->image ? $this->image->store('subcategories', 'public') : null;

        Subcategory::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'branch_id' => $this->branch_id,
            'image' => $imagePath,
            'display_order' => $this->display_order,
            'is_active' => (bool) $this->is_active,
        ]);

        $this->dispatch('notify', message: 'Subcategory added successfully.');
        $this->isCreateModalOpen = false;
        $this->loadData();
    }

    // Open edit modal
    public function editSubcategory($id)
    {
        $sub = Subcategory::findOrFail($id);

        $this->subcategoryId = $sub->id;
        $this->name = $sub->name;
        $this->category_id = $sub->category_id;
        $this->branch_id = $sub->branch_id;
        $this->display_order = $sub->display_order;
        $this->is_active = $sub->is_active;
        $this->image = null;

        $this->isEditModalOpen = true;
    }

    // Update existing subcategory
    public function updateSubcategory()
    {
        $this->validate([
            'name' => 'required|string|min:2',
            'category_id' => 'required|exists:categories,id',
            'display_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $subcategory = Subcategory::findOrFail($this->subcategoryId);

        $category = Category::findOrFail($this->category_id);
        $this->branch_id = $category->branch_id;

        if ($this->image) {
            if ($subcategory->image && Storage::disk('public')->exists($subcategory->image)) {
                Storage::disk('public')->delete($subcategory->image);
            }
            $imagePath = $this->image->store('subcategories', 'public');
        } else {
            $imagePath = $subcategory->image;
        }

        $subcategory->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'branch_id' => $this->branch_id,
            'image' => $imagePath,
            'display_order' => $this->display_order,
            'is_active' => (bool) $this->is_active,
        ]);

        $this->dispatch('notify', message: 'Subcategory updated successfully.');
        $this->isEditModalOpen = false;
        $this->loadData();
    }

    // Reset form
    public function resetForm()
    {
        $this->subcategoryId = null;
        $this->name = '';
        $this->category_id = '';
        $this->branch_id = '';
        $this->image = null;
        $this->display_order = null;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.subcategories');
    }
}
