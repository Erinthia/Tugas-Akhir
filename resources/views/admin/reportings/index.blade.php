@extends('layouts.master')

@section('title', 'Reporting')

@section('content')
    <div class="section-header">
        <h1>Reporting</h1>
    </div>
    <div class="section-body">
        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">Applicant Report</h2>
            </div>
            <p class="section-lead">
                This section displays applicant tracking based on opportunities.
            </p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    {{-- Form untuk filter --}}
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.reportings.index') }}">
                            <select name="id_opportunity" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Filter by Opportunity --</option>
                                @foreach ($opportunities as $opportunity)
                                    <option value="{{ $opportunity->id }}"
                                        {{ $id_opportunity == $opportunity->id ? 'selected' : '' }}>
                                        {{ $opportunity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    {{-- Form untuk export --}}
                    <div class="col-md-4">
                        <form action="{{ route('admin.reportings.export') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_opportunity" value="{{ $id_opportunity }}">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-export"></i> Export Excel
                            </button>
                        </form>
                    </div>
                </div>


                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Applicant Name</th>
                            <th>Opportunity</th>
                            <th>CV Screening</th>
                            <th>Psikotest</th>
                            <th>HR Interview</th>
                            <th>User Interview</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applicants as $index => $applicant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $applicant->fullname }}</td>
                                <td>{{ $applicant->opportunity->name ?? '-' }}</td>
                                <td>{{ $applicant->ScreeningCv->decision->name ?? '-' }}</td>
                                <td>{{ $applicant->psikotest->decision->name ?? '-' }}</td>
                                <td>{{ $applicant->InterviewHr->decision->name ?? '-' }}</td>
                                <td>{{ $applicant->InterviewUser->decision->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No applicants found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
    </style>
@endpush
