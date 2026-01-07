<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\FoodItem;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\FoodItemVariation;
use App\Models\FoodItemExtra;

class FoodItemManagement extends Component
{
    use WithFileUploads;

    public $subcategories = [];
    public $selectedBranch = '';
    public $selectedCategory = '';
    public $selectedSubcategory = '';

    public $foodItems;
    public $branches;
    public $categories;

    public $editingId = null;

    public $branchId = '';
    public $categoryId = '';
    public $subcategoryId = null;
    public $name = '';
    public $description = '';
    public $price = '';
    public $image;
    public $isAvailable = true;
    public $allowDiscount = false;

    // Variations, Extras
    public $variations = [];
    public $extras = [];

    public $isModalOpen = false;

    protected $rules = [
        'branchId' => 'required|exists:branches,id',
        'categoryId' => 'required|exists:categories,id',
        'name' => 'required|string|min:3',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->loadData();
        $this->subcategories = Subcategory::all();
    }

    public function updatedSelectedBranch()
    {
        $this->loadData();
    }


    public function loadData()
    {
        $query = FoodItem::with('branch', 'category');

        if ($this->selectedBranch) $query->where('branch_id', $this->selectedBranch);
        if ($this->selectedCategory) $query->where('category_id', $this->selectedCategory);

        $this->foodItems = $query->get();
        $this->branches = Branch::all();
        $this->categories = Category::all();
    }

    public function updatedSelectedCategory($categoryId)
    {
        // update subcategories list
        $this->subcategories = $categoryId
            ? Subcategory::where('category_id', $categoryId)->get()
            : Subcategory::all();

        // refresh filtered food items
        $this->loadData();
    }


    public function openModal()
    {
        $this->resetForm();
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
        $this->categoryId = '';
        $this->subcategoryId = '';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->image = null;
        $this->isAvailable = true;
        $this->allowDiscount = false;
        $this->variations = [];
        $this->extras = [];
    }

    public function addVariation()
    {
        $this->variations[] = ['name'=>'', 'price'=>'', 'allow_discount'=>false];
    }

    public function removeVariation($index)
    {
        unset($this->variations[$index]);
        $this->variations = array_values($this->variations);
    }

    public function addExtra()
    {
        $this->extras[] = ['name'=>'', 'price'=>'', 'description'=>'', 'image'=>null];
    }

    public function removeExtra($index)
    {
        unset($this->extras[$index]);
        $this->extras = array_values($this->extras);
    }

    public function saveFoodItem()
    {
        $this->validate();

        $imagePath = $this->image ? $this->image->store('food-items', 'public') : null;

        if ($this->editingId) {
            $item = FoodItem::find($this->editingId);
            if ($this->image && $item->image) \Storage::disk('public')->delete($item->image);
            $item->update([
                'branch_id'=>$this->branchId,
                'category_id'=>$this->categoryId,
                'subcategory_id'=>$this->subcategoryId = $this->subcategoryId ?: null,
                'name'=>$this->name,
                'description'=>$this->description,
                'price'=>$this->price,
                'image'=>$imagePath ?? $item->image,
                'is_available'=>$this->isAvailable,
                'allow_discount'=>$this->allowDiscount,
            ]);
        } else {
            $item = FoodItem::create([
                'branch_id'=>$this->branchId,
                'category_id'=>$this->categoryId,
                'subcategory_id'=>$this->subcategoryId = $this->subcategoryId ?: null,
                'name'=>$this->name,
                'description'=>$this->description,
                'price'=>$this->price,
                'image'=>$imagePath,
                'is_available'=>$this->isAvailable,
                'allow_discount'=>$this->allowDiscount,
            ]);
        }

        // Save Variations
        foreach ($this->variations as $v) {
            FoodItemVariation::updateOrCreate(
                ['food_item_id'=>$item->id, 'name'=>$v['name']],
                ['price'=>$v['price'], 'allow_discount'=>$v['allow_discount']]
            );
        }

        // Save Extras
        foreach ($this->extras as $index => $e) {
            FoodItemExtra::updateOrCreate(
                ['food_item_id' => $item->id, 'name' => $e['name']],
                [
                    'price' => $e['price'],
                    'description' => $e['description'],
                    'image' => null
                ]
            );
        }

        $this->loadData();
        $this->closeModal();
        $this->dispatch('notify', message: 'Food item saved successfully 🎉', type: 'success');
    }

    public function edit($id)
    {
        $item = FoodItem::with('variations','extras')->findOrFail($id);
        $this->editingId = $id;

        $this->branchId = $item->branch_id;
        $this->categoryId = $item->category_id;
        $this->subcategoryId = $item->subcategory_id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->price = $item->price;
        $this->isAvailable = $item->is_available;
        $this->allowDiscount = $item->allow_discount;
        $this->image = null;

        $this->variations = $item->variations->map(function($v){
            return [
                'name' => $v->name,
                'price' => $v->price,
                'allow_discount' => $v->allow_discount,
            ];
        })->toArray();

        $this->extras = $item->extras->map(function($e){
            return [
                'name' => $e->name,
                'price' => $e->price,
                'description' => $e->description,
                'image' => null,
            ];
        })->toArray();

        $this->isModalOpen = true;
    }

    //  Delete Method 
    public function delete($id)
    {
        $item = FoodItem::findOrFail($id);

        // Delete main image
        if ($item->image) {
            \Storage::disk('public')->delete($item->image);
        }

        // Delete variations
        foreach ($item->variations as $v) {
            $v->delete();
        }

        // Delete extras and their images
        foreach ($item->extras as $e) {
            if ($e->image) {
                \Storage::disk('public')->delete($e->image);
            }
            $e->delete();
        }

        // Delete the food item
        $item->delete();

        $this->loadData();
        $this->dispatch('notify', message: 'Food item deleted successfully 🎉', type: 'success');
    }

        // Toggle status
    public function toggleStatus($id)
    {
        $item = FoodItem::findOrFail($id); 

        $item->is_available = !$item->is_available; // For food item active/inactive

        $item->save();

        $this->loadData();

    }


    public function render()
    {
        return view('livewire.admin.food-item-management');
    }
}
