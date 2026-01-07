<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h4>User Management</h4>

        <button class="btn btn-primary" wire:click="openModal">
            Add User
        </button>
    </div>

    <!-- Users Table -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Branch</th>
                <th width="80">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->user_role }}</td>
                    <td>{{ $user->branch?->name ?? '-' }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm"
                                wire:click="confirmDelete({{ $user->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>


    <!-- Create User Modal -->
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5)">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="createUser">

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" wire:model="name" class="form-control">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" wire:model="email" class="form-control">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Role</label>
                            <select wire:model="user_role" class="form-control">
                                <option value="">Select Role</option>
                                <option value="Manager">Manager</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Branch (optional)</label>
                            <select wire:model="branch_id" class="form-control">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-primary w-100">
                            Create User
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>
    @endif


    <!-- Delete Confirmation -->
    @if($confirmDeleteId)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5)">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <div class="modal-body text-center">
                        <p>Are you sure you want to delete this user?</p>
                        <button class="btn btn-secondary btn-sm" wire:click="$set('confirmDeleteId', null)">Cancel</button>
                        <button class="btn btn-danger btn-sm" wire:click="deleteUser">Delete</button>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
