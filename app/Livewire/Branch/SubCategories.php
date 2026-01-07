<?php

namespace App\Livewire\Branch;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SubCategories extends Component
{
    use WithFileUploads;

    public $subcategories;
    public $categories;

    public $isCreateModalOpen = false;
    public $isEditModalOpen = false;

    public $subcategoryId;
    public $name;
    public $category_id;
    public $branch_id;
    public $image;
    public $display_order;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|min:2',
        'category_id' => 'required|exists:categories,id',
        'display_order' => 'nullable|integer',
        'image' => 'nullable|image|max:2048',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $branchId = Auth::user()->branch_id;

        // Only show subcategories from user's branch
        $this->subcategories = Subcategory::with('category')
            ->where('branch_id', $branchId)
            ->get();

        // Only allow selecting categories from user's branch
        $this->categories = Category::where('branch_id', $branchId)->get();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isCreateModalOpen = true;
    }

    public function saveSubcategory()
    {
        $this->validate();

        $category = Category::findOrFail($this->category_id);

        // SECURITY CHECK — parent category must belong to user's branch
        if ($category->branch_id != Auth::user()->branch_id) {
            abort(403, 'Unauthorized action.');
        }

        // Force branch from parent category
        $this->branch_id = $category->branch_id;

        $imagePath = $this->image
            ? $this->image->store('subcategories', 'public')
            : null;

        Subcategory::create([
            'name'          => $this->name,
            'category_id'   => $this->category_id,
            'branch_id'     => $this->branch_id,
            'image'         => $imagePath,
            'display_order' => $this->display_order,
            'is_active'     => (bool) $this->is_active,
        ]);

        $this->dispatch('notify', message: 'Subcategory added successfully.');
        $this->isCreateModalOpen = false;
        $this->loadData();
    }

    public function editSubcategory($id)
    {
        $sub = Subcategory::findOrFail($id);

        // SECURITY CHECK — cannot edit other branch subcategory
        if ($sub->branch_id != Auth::user()->branch_id) {
            abort(403);
        }

        $this->subcategoryId = $sub->id;
        $this->name = $sub->name;
        $this->category_id = $sub->category_id;
        $this->branch_id = $sub->branch_id;
        $this->display_order = $sub->display_order;
        $this->is_active = $sub->is_active;
        $this->image = null;

        $this->isEditModalOpen = true;
    }

    public function updateSubcategory()
    {
        $this->validate();

        $subcategory = Subcategory::findOrFail($this->subcategoryId);

        // SECURITY CHECK — subcategory must belong to user's branch
        if ($subcategory->branch_id != Auth::user()->branch_id) {
            abort(403);
        }

        $category = Category::findOrFail($this->category_id);

        // SECURITY CHECK — parent category must also be same branch
        if ($category->branch_id != Auth::user()->branch_id) {
            abort(403);
        }

        // Force branch from parent category
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
            'name'          => $this->name,
            'category_id'   => $this->category_id,
            'branch_id'     => $this->branch_id,
            'image'         => $imagePath,
            'display_order' => $this->display_order,
            'is_active'     => (bool) $this->is_active,
        ]);

        $this->dispatch('notify', message: 'Subcategory updated successfully.');
        $this->isEditModalOpen = false;
        $this->loadData();
    }

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
        return view('livewire.branch.sub-categories');
    }
}
