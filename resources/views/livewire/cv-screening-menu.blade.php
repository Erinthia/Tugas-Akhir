<div>
    @if ($isHome)
        <div class="section-header">
            <h1>Daftar List CV</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">CV Screening List</h2>
            <p class="section-lead">In this section you can manage system cv data such as editing, detail and
                deleting</p>
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
                    @endif<div>
    @if ($isHome)
        <div class="section-header">
            <h1>Daftar List CV</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">CV Screening List</h2>
            <p class="section-lead">In this section you can manage system cv data such as editing, detail and
                deleting</p>
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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Action</th>
                                <th scope="col">Decision</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applicants as $index => $applicant)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $applicant->fullname }}</td>

                                    <td>
                                        <div class="buttons">
                                            <a href="#" wire:click.prevent="show({{ $applicant->id }})"
                                                class="btn btn-icon btn-primary"><i
                                                    class="fas fa-exclamation-triangle"></i></a>
                                            <a href="#" wire:click.prevent="edit({{ $applicant->id }})"
                                                class="btn btn-icon btn-warning"><i
                                                    class="fas fa-exclamation-triangle"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="buttons">
                                            @if ($applicant->status === 'recommended')
                                                <span class="badge badge-success">Disarankan</span>
                                            @elseif ($applicant->status === 'neutral')
                                                <span class="badge badge-warning">Netral</span>
                                            @elseif ($applicant->status === 'not_recommended')
                                                <span class="badge badge-danger">Tidak Disarankan</span>
                                            @else
                                                <span class="badge badge-secondary">Belum Dinilai</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
    @endif

    @if ($isEdit)
        <div class="section-header">
            <h1>CV Screening</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Screening CV</h2>
            <p class="section-lead">In this section you can show details of the CV.</p>

            <div class="card" style="min-height: 900px;">
                <div class="card-body">
                    <div class="section-header">
                        <h1>Detail CV</h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if ($selectedApplicant->cv_file)
                                <div
                                    style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
                                    @php
                                        $filePath = Storage::url($selectedApplicant->cv_file);
                                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    @endphp

                                    @if ($fileExtension === 'pdf')
                                        <iframe src="{{ $filePath }}" width="100%" height="1000px"
                                            style="border: none;"></iframe>
                                    @elseif (in_array($fileExtension, ['doc', 'docx']))
                                        <iframe
                                            src="https://docs.google.com/viewer?url={{ urlencode($filePath) }}&embedded=true"
                                            width="100%" height="700px" style="border: none;"></iframe>
                                    @else
                                        <p>Pratinjau tidak tersedia untuk format file ini.</p>
                                        <a href="{{ url($filePath) }}" class="btn btn-primary" download>Download CV</a>
                                    @endif
                                </div>
                            @else
                                <p class="text-center">Tidak ada CV yang diunggah.</p>
                            @endif
                        </div>
                    </div>
                    <div class="section-header">
                        <h1>Penilaian CV Screening</h1>
                    </div>
                    <div class="section-body">
                        <div class="card">
                            <form wire:submit.prevent="createCv({{ $selectedApplicant->id }})">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="skor">Skor</label>
                                        <input type="text" class="form-control" id="skor" wire:model="skor">
                                        @error('skor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Decision</label>
                                        <select wire:model="decision" class="form-control">
                                            <option value="">Pilih Decision</option>
                                            @foreach ($decisions as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('decision')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea wire:model="notes" style="height: 150px" class="form-control" id="notes"></textarea>
                                        @error('notes')
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
                </div>
            </div>
        </div>
    @endif
    @if ($isShow)
        <div class="section-header">
            <h1>CV Screening</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Screening CV</h2>
            <p class="section-lead">In this section you can show details of the CV.</p>

            <div class="card" style="min-height: 900px;">
                <div class="card-body">
                    <div class="section-header">
                        <h1>Detail CV</h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if ($selectedApplicant->cv_file)
                                <div
                                    style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
                                    @php
                                        $filePath = Storage::url($selectedApplicant->cv_file);
                                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    @endphp

                                    @if ($fileExtension === 'pdf')
                                        <iframe src="{{ $filePath }}" width="100%" height="1000px"
                                            style="border: none;"></iframe>
                                    @elseif (in_array($fileExtension, ['doc', 'docx']))
                                        <iframe
                                            src="https://docs.google.com/viewer?url={{ urlencode($filePath) }}&embedded=true"
                                            width="100%" height="700px" style="border: none;"></iframe>
                                    @else
                                        <p>Pratinjau tidak tersedia untuk format file ini.</p>
                                        <a href="{{ url($filePath) }}" class="btn btn-primary" download>Download
                                            CV</a>
                                    @endif
                                </div>
                            @else
                                <p class="text-center">Tidak ada CV yang diunggah.</p>
                            @endif
                        </div>
                    </div>
                    <div class="section-header">
                        <h1>Detail Penilaian CV Screening</h1>
                    </div>
                    <div class="section-body">
                        <div class="card">
                            <form wire:submit.prevent="store">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="skor">Skor</label>
                                        <input type="text" class="form-control" id="skor" wire:model="skor"
                                            @if ($isShow) disabled @endif>
                                        @error('skor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Decision</label>
                                        <select wire:model="decision" class="form-control"
                                            @if ($isShow) disabled @endif>
                                            <option value="">Pilih Decision</option>
                                            @foreach ($decisions as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('decision')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea wire:model="notes" style="height: 150px" class="form-control" id="notes"
                                            @if ($isShow) disabled @endif></textarea>
                                        @error('notes')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
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

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Action</th>
                                <th scope="col">Decision</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applicants as $index => $applicant)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $applicant->fullname }}</td>

                                    <td>
                                        <div class="buttons">
                                            <a href="#" wire:click.prevent="show({{ $applicant->id }})"
                                                class="btn btn-icon btn-primary"><i
                                                    class="fas fa-exclamation-triangle"></i></a>
                                            <a href="#" wire:click.prevent="edit({{ $applicant->id }})"
                                                class="btn btn-icon btn-warning"><i
                                                    class="fas fa-exclamation-triangle"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="buttons">
                                            @if ($applicant->status === 'recommended')
                                                <span class="badge badge-success">Disarankan</span>
                                            @elseif ($applicant->status === 'neutral')
                                                <span class="badge badge-warning">Netral</span>
                                            @elseif ($applicant->status === 'not_recommended')
                                                <span class="badge badge-danger">Tidak Disarankan</span>
                                            @else
                                                <span class="badge badge-secondary">Belum Dinilai</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
    @endif

    @if ($isEdit)
        <div class="section-header">
            <h1>CV Screening</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Screening CV</h2>
            <p class="section-lead">In this section you can show details of the CV.</p>

            <div class="card" style="min-height: 900px;">
                <div class="card-body">
                    <div class="section-header">
                        <h1>Detail CV</h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if ($selectedApplicant->cv_file)
                                <div
                                    style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
                                    @php
                                        $filePath = Storage::url($selectedApplicant->cv_file);
                                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    @endphp

                                    @if ($fileExtension === 'pdf')
                                        <iframe src="{{ $filePath }}" width="100%" height="1000px"
                                            style="border: none;"></iframe>
                                    @elseif (in_array($fileExtension, ['doc', 'docx']))
                                        <iframe
                                            src="https://docs.google.com/viewer?url={{ urlencode($filePath) }}&embedded=true"
                                            width="100%" height="700px" style="border: none;"></iframe>
                                    @else
                                        <p>Pratinjau tidak tersedia untuk format file ini.</p>
                                        <a href="{{ url($filePath) }}" class="btn btn-primary" download>Download CV</a>
                                    @endif
                                </div>
                            @else
                                <p class="text-center">Tidak ada CV yang diunggah.</p>
                            @endif
                        </div>
                    </div>
                    <div class="section-header">
                        <h1>Penilaian CV Screening</h1>
                    </div>
                    <div class="section-body">
                        <div class="card">
                            <form wire:submit.prevent="createCv({{ $selectedApplicant->id }})">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="skor">Skor</label>
                                        <input type="text" class="form-control" id="skor" wire:model="skor">
                                        @error('skor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Decision</label>
                                        <select wire:model="decision" class="form-control">
                                            <option value="">Pilih Decision</option>
                                            @foreach ($decisions as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('decision')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea wire:model="notes" style="height: 150px" class="form-control" id="notes"></textarea>
                                        @error('notes')
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
                </div>
            </div>
        </div>
    @endif
    @if ($isShow)
        <div class="section-header">
            <h1>CV Screening</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Screening CV</h2>
            <p class="section-lead">In this section you can show details of the CV.</p>

            <div class="card" style="min-height: 900px;">
                <div class="card-body">
                    <div class="section-header">
                        <h1>Detail CV</h1>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if ($selectedApplicant->cv_file)
                                <div
                                    style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
                                    @php
                                        $filePath = Storage::url($selectedApplicant->cv_file);
                                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    @endphp

                                    @if ($fileExtension === 'pdf')
                                        <iframe src="{{ $filePath }}" width="100%" height="1000px"
                                            style="border: none;"></iframe>
                                    @elseif (in_array($fileExtension, ['doc', 'docx']))
                                        <iframe
                                            src="https://docs.google.com/viewer?url={{ urlencode($filePath) }}&embedded=true"
                                            width="100%" height="700px" style="border: none;"></iframe>
                                    @else
                                        <p>Pratinjau tidak tersedia untuk format file ini.</p>
                                        <a href="{{ url($filePath) }}" class="btn btn-primary" download>Download
                                            CV</a>
                                    @endif
                                </div>
                            @else
                                <p class="text-center">Tidak ada CV yang diunggah.</p>
                            @endif
                        </div>
                    </div>
                    <div class="section-header">
                        <h1>Detail Penilaian CV Screening</h1>
                    </div>
                    <div class="section-body">
                        <div class="card">
                            <form wire:submit.prevent="store">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="skor">Skor</label>
                                        <input type="text" class="form-control" id="skor" wire:model="skor"
                                            @if ($isShow) disabled @endif>
                                        @error('skor')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Decision</label>
                                        <select wire:model="decision" class="form-control"
                                            @if ($isShow) disabled @endif>
                                            <option value="">Pilih Decision</option>
                                            @foreach ($decisions as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('decision')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea wire:model="notes" style="height: 150px" class="form-control" id="notes"
                                            @if ($isShow) disabled @endif></textarea>
                                        @error('notes')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
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
