<div>
    @if ($isHome)
        <div class="section-header">
            <h1>Daftar List Applicant</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Applicant List</h2>
            <p class="section-lead">In this section you can manage applicant data such as details and
                delete</p>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <input type="text" class="form-control" id="search" placeholder="Search User"
                                wire:model.live.debounce.250ms="search">
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
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicants as $index => $applicant)
                                    <tr>
                                        {{-- <td>{{ $applicant->phone_number }}</td> --}}
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $applicant->fullname }}</td>
                                        <td>{{ $applicant->email }}</td>
                                        <td>{{ $applicant->phone_number }}</td>


                                        <td>
                                            <div class="buttons">
                                                <a href="#" wire:click.prevent="show('{{ $applicant->id }}')"
                                                    class="btn btn-icon btn-primary"><i class="fas fa-eye"></i>
                                                    <a href="#"
                                                        wire:click.prevent="destroy('{{ $applicant->id }}')"
                                                        wire:confirm="Are you sure?" class="btn btn-icon btn-danger"><i
                                                            class="fas fa-times"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $applicants->links() }}


    @endif

    @if ($isShow)
        <div class="section-header">
            <h1>Detail Applicant</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Detail Applicant</h2>
            <p class="section-lead">In this section you can show details of the user.</p>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Name</strong></p>
                        </div>
                        <div class="col-6">
                            <p>{{ $fullname }}</p>
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
