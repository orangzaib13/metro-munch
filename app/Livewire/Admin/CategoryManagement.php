<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Branch;

class CategoryManagement extends Component
{
    use WithFileUploads;

    public $categories;
    public $branches;

    public $form = [
        'branch_id' => '',
        'name' => '',
        'image' => null,
        'is_active' => 1, // default active
    ];

    public $editingId = null;
    public $isModalOpen = false;

    protected $rules = [
        'form.branch_id' => 'required|exists:branches,id',
        'form.name' => 'required|string|min:3',
        'form.image' => 'nullable|image|max:2048',
        'form.is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadCategories();
        $this->branches = Branch::all();
    }

    public function loadCategories()
    {
        $this->categories = Category::with('branch')->get();
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

    public function saveCategory()
    {
        $this->validate();

        if ($this->editingId) {
            $category = Category::find($this->editingId);

            // If new image uploaded → delete old one
            if ($this->form['image']) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                $imagePath = $this->form['image']->store('categories', 'public');
            } else {
                $imagePath = $category->image;
            }

            $category->update([
                'branch_id'  => $this->form['branch_id'],
                'name'       => $this->form['name'],
                'image'      => $imagePath,
                'is_active'  => (int) $this->form['is_active'],
            ]);

            $this->dispatch('notify', message: 'Category updated 🎉', type: 'success');
        } else {
            $imagePath = $this->form['image']
                ? $this->form['image']->store('categories', 'public')
                : null;

            Category::create([
                'branch_id' => $this->form['branch_id'],
                'name'      => $this->form['name'],
                'image'     => $imagePath,
                'is_active' => (int) $this->form['is_active'],
            ]);

            $this->dispatch('notify', message: 'Category added 🎉', type: 'success');
        }

        $this->loadCategories();
        $this->closeModal();
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $this->editingId = $id;

        $this->form['branch_id'] = $category->branch_id;
        $this->form['name'] = $category->name;
        $this->form['image'] = null;
        $this->form['is_active'] = $category->is_active;

        $this->isModalOpen = true;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->form = [
            'branch_id' => '',
            'name' => '',
            'image' => null,
            'is_active' => 1, // default Active
        ];
    }

    public function render()
    {
        return view('livewire.admin.category-management');
    }
}
