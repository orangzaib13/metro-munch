<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class CreateUser extends Component
{
    public $name, $email, $user_role = 'User', $branch_id;
    public $showModal = false;
    public $confirmDeleteId = null;

   public function render()
{
    return view('livewire.admin.create-user', [
        // Only active branches that are assigned to users
        'branches' => Branch::where('is_active', true)
            ->get(),

        // Get all users with their branch
        'users' => User::where('user_role', '!=', 'Admin')->with('branch')->latest()->get(),
    ]);
}


    public function openModal()
    {
        $this->reset(['name','email','user_role','branch_id']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_role' => ['required', Rule::in(['User','Manager'])],
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'user_role' => $this->user_role,
            'branch_id' => $this->branch_id,
            'password' => Hash::make(uniqid()),
        ]);

        Password::sendResetLink(['email' => $user->email]);

        $this->closeModal();

        $this->dispatch('notify', message: 'User created successfully! Password setup email sent. 🎉', type: 'success');
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteUser()
    {
        User::findOrFail($this->confirmDeleteId)->delete();
        $this->confirmDeleteId = null;

        $this->dispatch('notify', message: 'User deleted successfully.', type: 'success');
    }
}
