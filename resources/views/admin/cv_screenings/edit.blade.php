@extends('layouts.master')

@section('content')
    <div class="section-header">
        <h1>CV Screening</h1>
    </div>
    <div class="section-body">

        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">CV Screening List</h2>
            </div>
            <p class="section-lead">In this section you can manage editing cv screening.</p>
        </div>

        <div class="card" style="min-height: 900px;">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                </div>
            @endif
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
                        <div class="card-body">
                            <form action="{{ route('admin.cv_screenings.update', $ScreeningCv->id) }}" method="POST">
                                @csrf
                                {{-- @method('PUT') <!-- Untuk update --> --}}


                                <div class="form-group">
                                    <label for="score">Score</label>
                                    <input type="text" class="form-control" id="score" name="score"
                                        value={{ $ScreeningCv->score }}>
                                    @error('score')
                                        <i>{{ $message }}</i>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Decision</label>
                                    <select name="decision_id" class="form-control">
                                        @foreach ($decisions as $decision)
                                            <option value="{{ $decision->id }}"
                                                {{ isset($selectedApplicant->ScreeningCv) && $selectedApplicant->ScreeningCv->decision_id == $decision->id ? 'selected' : '' }}>
                                                {{ $decision->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('score')
                                        <i>{{ $message }}</i>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" style="height: 150px">{{ $ScreeningCv->notes }}</textarea>
                                    @error('notes')
                                        <i>{{ $message }}</i>
                                    @enderror
                                </div>
                                <div class="buttons">
                                    <a href="{{ route('admin.cv_screenings.index') }}" class="btn btn-primary">Back</a>
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .section-header {
            background-color: white;
            color: rgb(59, 59, 59);
            font-weight: bold;
            padding: 1.1rem;
            vertical-align: middle;
            height: 80px;
        }

        .section-header h1 {
            font-size: 24px;
            line-height: 50px;
        }

        .section-title-lead-wrapper {
            margin-bottom: 1.5rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            margin-top: 1rem;
        }

        .toggle-indicator {
            width: 28px;
            height: 8px;
            background-color: rgb(122, 138, 227);
            border-radius: 7.5px;
            margin-right: 0.5rem;
        }

        .section-title-text {
            font-size: 18px;
            font-weight: normal;
            color: #020202;
            margin-bottom: 0;
        }

        .section-lead {
            font-size: 1rem;
            color: #868e96;
            margin-bottom: 0;
            margin-left: calc(28px + 0.5rem);
            /* Margin kiri sebesar lebar toggle + jaraknya */
        }

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .badge {
            border-radius: 0.25rem;
            font-size: 0.875rem;
            padding: 0.35em 0.65em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto hide alert after 3 seconds
        setTimeout(function() {
            $(".alert").alert('close');
        }, 3000); // 3000 ms = 1 detik
    </script>
@endpush
