<?php

namespace App\Livewire\Branch;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\FoodItem;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\FoodItemVariation;
use App\Models\FoodItemExtra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class FoodItems extends Component
{
    use WithFileUploads;

    public $subcategories = [];
    public $selectedCategory = '';
    public $selectedSubcategory = '';

    public $foodItems;
    public $categories;

    public $editingId = null;

    public $categoryId = '';
    public $subcategoryId = null;
    public $name = '';
    public $description = '';
    public $price = '';
    public $image;
    public $isAvailable = true;
    public $allowDiscount = false;

    public $variations = [];
    public $extras = [];

    public $isModalOpen = false;

    protected $rules = [
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

    public function loadData()
    {
        $branchId = Auth::user()->branch_id;

        $query = FoodItem::with('category')
            ->where('branch_id', $branchId);

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $this->foodItems = $query->get();
        $this->categories = Category::all();
    }

    public function updatedSelectedCategory($categoryId)
    {
        $this->subcategories = $categoryId ? Subcategory::where('category_id', $categoryId)->get() : Subcategory::all();
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
        $this->categoryId = '';
        $this->subcategoryId = null;
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
        $branchId = Auth::user()->branch_id;

        // If editing, check authorization
        if ($this->editingId) {
            $item = FoodItem::findOrFail($this->editingId);
            if (Gate::denies('update', $item)) {
                $this->dispatch('notify', message: 'You are not authorized to update this item.', type: 'error');
                return;
            }
        }

        $imagePath = $this->image ? $this->image->store('food-items', 'public') : null;


        if ($this->editingId) {
            $item = FoodItem::find($this->editingId);
            if ($this->image && $item->image) \Storage::disk('public')->delete($item->image);
            $item->update([
                'branch_id' => $branchId,
                'category_id' => $this->categoryId,
                'subcategory_id' => $this->subcategoryId ?: null,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'image' => $imagePath ?? $item->image,
                'is_available' => $this->isAvailable,
                'allow_discount' => $this->allowDiscount,
            ]);
        } else {
            $item = FoodItem::create([
                'branch_id' => $branchId,
                'category_id' => $this->categoryId,
                'subcategory_id' => $this->subcategoryId ?: null,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'image' => $imagePath,
                'is_available' => $this->isAvailable,
                'allow_discount' => $this->allowDiscount,
            ]);
        }

        foreach ($this->variations as $v) {
            FoodItemVariation::updateOrCreate(
                ['food_item_id'=>$item->id, 'name'=>$v['name']],
                ['price'=>$v['price'], 'allow_discount'=>$v['allow_discount']]
            );
        }

        foreach ($this->extras as $e) {
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

        // Authorization check
        if (Gate::denies('update', $item)) {
            $this->dispatch('notify', message: 'You are not authorized to edit this item.', type: 'error');
            return;
        }

        $this->categoryId = $item->category_id;
        $this->subcategoryId = $item->subcategory_id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->price = $item->price;
        $this->isAvailable = $item->is_available;
        $this->allowDiscount = $item->allow_discount;
        $this->image = null;

        $this->variations = $item->variations->map(fn($v) => [
            'name' => $v->name,
            'price' => $v->price,
            'allow_discount' => $v->allow_discount,
        ])->toArray();

        $this->extras = $item->extras->map(fn($e) => [
            'name' => $e->name,
            'price' => $e->price,
            'description' => $e->description,
            'image' => null,
        ])->toArray();

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        $item = FoodItem::findOrFail($id);

        if (Gate::denies('delete', $item)) {
        $this->dispatch('notify', message: 'You are not authorized to delete this item.', type: 'error');
        return;
    }


        $branchId = Auth::user()->branch_id;

        // Find the item that belongs to this branch
        $item = FoodItem::where('id', $id)
                        ->where('branch_id', $branchId)
                        ->firstOrFail();

        // Delete main image if exists
        if ($item->image && \Storage::disk('public')->exists($item->image)) {
            \Storage::disk('public')->delete($item->image);
        }

        // Delete variations
        $item->variations()->delete();

        // Delete extras
        $item->extras()->delete();

        // Delete the food item
        $item->delete();

        $this->loadData();
        $this->dispatch('notify', message: 'Food item deleted successfully 🎉', type: 'success');
    }



        public function toggleStatus($id)
        {
            $item = FoodItem::findOrFail($id); 
            $item->is_available = !$item->is_available;
            $item->save();
            $this->loadData();
        }

        public function render()
        {
            return view('livewire.branch.food-items');
        }
    }
