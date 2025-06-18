@extends('layouts.master')

@section('title', 'CV Screening')

@section('content')
    <div class="section-header">
        <h1>Daftar List CV</h1>
    </div>
    <div class="section-body">
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

        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">CV Screening List</h2>
            </div>
            <p class="section-lead">In this section you can manage system cv data such as detail, creating and editing.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <form action="{{ route('admin.cv_screenings.index') }}" method="GET" id="searchForm">
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control"
                                    placeholder="Search User" value="{{ $search }}">
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Opportunity</th>
                            <th>Action</th>
                            <th>Decision</th>
                            <th>Notifikasi</th>
                            <th>Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applicants as $index => $applicant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $applicant->fullname }}</td>
                                <td>{{ $applicant->opportunity->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.cv_screenings.show', $applicant->id) }}"
                                        class="btn btn-icon btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.cv_screenings.edit', $applicant->id) }}"
                                        class="btn btn-icon btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>

                                <td>
                                    <span class="badge bg-secondary text-dark">
                                        {{ $applicant->ScreeningCv->decision->name ?? '-' }}
                                    </span>

                                </td>
                                <td>
                                    @php
                                        $cv = $applicant->ScreeningCv;
                                    @endphp

                                    @if ($cv && $cv->decision_id != 1)
                                        @if (in_array($cv->decision_id, [2, 3]))
                                            @if ($cv->notification_sent)
                                                @if (!$cv->info_sent)
                                                    {{-- Jika notifikasi sudah dikirim dan info tambahan belum dikirim --}}
                                                    <a href="{{ route('admin.cv_screenings.customEmailForm', $applicant->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        Send Advance Information
                                                    </a>
                                                @else
                                                    {{-- Info sudah dikirim, tampilkan tombol disabled --}}
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        Information has been sent
                                                    </button>
                                                @endif
                                            @else
                                                {{-- Notifikasi belum dikirim --}}
                                                <form
                                                    action="{{ route('admin.cv_screenings.sendNotification', $applicant->id) }}"
                                                    method="POST" style="display:inline-block;"
                                                    onsubmit="return confirm('Kirim notifikasi untuk {{ $applicant->fullname }}?');">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success mb-1" type="submit">
                                                        Send Notification
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif ($cv->decision_id == 4)
                                            {{-- Untuk decision gagal (id 4), selalu tampilkan Send Notification --}}
                                            <form
                                                action="{{ route('admin.cv_screenings.sendNotification', $applicant->id) }}"
                                                method="POST" style="display:inline-block;"
                                                onsubmit="return confirm('Kirim notifikasi untuk {{ $applicant->fullname }}?');">
                                                @csrf
                                                <button class="btn btn-sm btn-danger mb-1" type="submit">
                                                    Send Notification
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $applicant->ScreeningCv?->staff?->name ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $applicants->links() }}
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
