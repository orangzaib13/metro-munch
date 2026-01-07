<div>
    <div class="page-header d-flex justify-content-between align-items-center border-bottom mb-4 pb-3">
        <h2 class="page-title"><i class="fas fa-building"></i> Branch Management</h2>
        <button class="btn btn-primary" wire:click="openModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>


    <!-- All Branches Table -->
    <div class="card mt-4">
        <div class="card-header d">
            <h6>All Branches</h6>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th colspan="5">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->location }}</td>
                            <td>{{ $branch->address }}</td>
                            <td>{{ $branch->phone }}</td>
                            <td colspan="5">
                                <button class="btn btn-sm btn-primary" wire:click="edit({{ $branch->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <select class="form-select d-inline w-25 p-2" wire:model.change="branchesStatus.{{ $branch->id }}">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

        <!-- Modal -->
    <div class="modal fade @if($isModalOpen) show d-block @endif" tabindex="-1" role="dialog" @if($isModalOpen) style="background: rgba(0,0,0,0.5);" @endif>
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingId ? 'Edit Branch' : 'Add Branch' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                            <label>Branch Name *</label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label>Location *</label>
                            <input type="text" class="form-control" wire:model="location">
                            @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                        <div class="mb-3">
                            <label>Address</label>
                            <input type="text" class="form-control" wire:model="address">
                        </div>

                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" class="form-control" wire:model="phone">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" wire:model="email">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $editingId ? 'Update Branch' : 'Add Branch' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
