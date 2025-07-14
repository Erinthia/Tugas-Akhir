<div>
    @if ($isHome)
        <div class="section-header">
            <h1>Category Management</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Category List</h2>
            <p class="section-lead">In this section you can manage category data such as adding, changing and deleting.
            </p>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">


                        </div>
                        <div class="col-4">
                            <input wire:model.live.debounce.250ms="search" type="text" class="form-control"
                                id="search" placeholder="Search Category">
                        </div>
                        <div class="col-4 text-right">
                            <button wire:click.prevent="create()" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                    <br>
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>×</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        </div>
                        <br>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $index => $category)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->description }}</td>
                                        <td>
                                            <div class="buttons">
                                                <a href="#" wire:click.prevent="update({{ $category->id }})"
                                                    class="btn btn-icon btn-warning"><i class="fas fa-edit"></i></a>
                                                <a href="#" wire:click.prevent="destroy({{ $category->id }})"
                                                    wire:confirm="Are you sure?" class="btn btn-icon btn-danger"><i
                                                        class="fas fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
    @endif
    @if ($isCreate)
        <div class="section-header">
            <h1>Create Category</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Create Category</h2>
            <p class="section-lead">In this section you can create new category to access the system.</p>
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
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" style="height: 121px;" wire:model="description"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="buttons">
                            <a href="#" wire:click="home()" class="btn btn-primary">Back</a>
                            <button class="submit btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if ($isUpdate)
        <div class="section-header">
            <h1>Update Category</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Create Category</h2>
            <p class="section-lead">In this section you can create new category to access the system.</p>
            <div class="card">
                <form wire:submit.prevent="setUpdate({{ $id }})">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" wire:model="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" style="height: 121px;" wire:model="description"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="buttons">
                            <a href="#" wire:click="home()" class="btn btn-primary">Back</a>
                            <button class="submit btn btn-success">Save</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
