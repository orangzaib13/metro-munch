<?php

namespace App\Livewire\Branch;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class Categories extends Component
{
    use WithFileUploads;

    public $categories;

    public $form = [
        'branch_id' => '',
        'name' => '',
        'image' => null,
        'is_active' => 1,
    ];

    public $editingId = null;
    public $isModalOpen = false;

    protected $rules = [
        'form.name' => 'required|string|min:3',
        'form.image' => 'nullable|image|max:2048',
        'form.is_active' => 'boolean',
    ];

    public function mount()
    {
        // Always force branch
        $this->form['branch_id'] = Auth::user()->branch_id;

        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::with('branch')
            ->where('branch_id', Auth::user()->branch_id)
            ->get();
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

        // FORCE branch every time
        $this->form['branch_id'] = Auth::user()->branch_id;
        $branchId = $this->form['branch_id'];

        if ($this->editingId) {

            $category = Category::findOrFail($this->editingId);

            // Block editing from other branches
            if ($category->branch_id != $branchId) {
                abort(403);
            }

            if ($this->form['image']) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

                $imagePath = $this->form['image']->store('categories', 'public');
            } else {
                $imagePath = $category->image;
            }

            $category->update([
                'branch_id' => $branchId,
                'name'      => $this->form['name'],
                'image'     => $imagePath,
                'is_active' => (int) $this->form['is_active'],
            ]);

            $this->dispatch('notify', message: 'Category updated 🎉', type: 'success');

        } else {

            $imagePath = $this->form['image']
                ? $this->form['image']->store('categories', 'public')
                : null;

            Category::create([
                'branch_id' => $branchId,
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
        $category = Category::findOrFail($id);

        if ($category->branch_id != Auth::user()->branch_id) {
            abort(403);
        }

        $this->editingId = $id;

        // Force branch 
        $this->form['branch_id'] = Auth::user()->branch_id;
        $this->form['name']      = $category->name;
        $this->form['image']     = null;
        $this->form['is_active'] = $category->is_active;

        $this->isModalOpen = true;
    }

    public function resetForm()
    {
        $this->editingId = null;

        // Force branch 
        $this->form = [
            'branch_id' => Auth::user()->branch_id,
            'name'      => '',
            'image'     => null,
            'is_active' => 1,
        ];
    }

    public function render()
    {
        return view('livewire.branch.categories');
    }
}
