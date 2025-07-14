<div>
    @if ($isHome)
        <div class="section-header">
            <h1>User Management</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">User List</h2>
            <p class="section-lead">In this section you can manage system user data such as adding, changing and
                deleting</p>
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <br>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <input type="text" class="form-control" id="search" placeholder="Search User"
                                wire:model.live.debounce.250ms="search">
                        </div>
                        <div class="col-4 text-right">
                            <button wire:click.prevent="newuser()" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="#" wire:click.prevent="show({{ $user->id }})"
                                                    class="btn btn-icon btn-primary"><i class="fas fa-eye"></i></a>
                                                <a href="#" wire:click.prevent="edit({{ $user->id }})"
                                                    class="btn btn-icon btn-warning"><i class="fas fa-edit"></i></a>
                                                <a href="#" wire:click.prevent="destroy({{ $user->id }})"
                                                    wire:confirm="Are you sure?" class="btn btn-icon btn-danger"><i
                                                        class="fas fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
    @endif

    @if ($isCreate)
        <div class="section-header">
            <h1>Create User</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Create User</h2>
            <p class="section-lead">In this section you can create new user to access the system</p>
            <div class="card">
                <form wire:submit.prevent="save">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" wire:model="email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="roles_id">Role</label>
                            <select class="form-control" id="roles_id" wire:model="roles_id">
                                <option value="">-- Pilih Role --</option>
                                @foreach ($allRoles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('roles_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="phone_number" class="form-control" id="phone_number" wire:model="phone_number">
                            @error('phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" wire:model="password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="buttons">
                            <a href="#" wire:click="back()" class="btn btn-primary">Back</a>
                            <button class="submit btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($isEdit)
        <div class="section-header">
            <h1>Update User</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Update User</h2>
            <p class="section-lead">In this section you can update user data.</p>
            <div class="card">
                <form wire:submit.prevent="setUpdate({{ $id }})">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" wire:model="email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="roles_id">Role</label>
                            <select class="form-control" id="roles_id" wire:model="roles_id">
                                <option value="">-- Pilih Role --</option>
                                @foreach ($allRoles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('roles_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" wire:model="password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" wire:model="phone_number">
                            <!-- Changed to 'tel' -->
                            @error('phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="buttons">
                            <a href="#" wire:click="back()" class="btn btn-primary">Back</a>
                            <button class="submit btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($isShow)
        <div class="section-header">
            <h1>Detail User</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Detail User</h2>
            <p class="section-lead">In this section you can show details of the user.</p>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Name</strong></p>
                        </div>
                        <div class="col-6">
                            <p>{{ $name }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Email</strong></p>
                        </div>
                        <div class="col-6">
                            <p>{{ $email }}</p>
                        </div>
                    </div>
                    <div class="buttons">
                        <a href="#" wire:click="back()" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
