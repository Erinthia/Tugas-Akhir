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
                <div class="table-responsive">

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
                                                        onsubmit="return confirm('Send notification to {{ $applicant->fullname }}?');">
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
                                                    onsubmit="return confirm('Send notification to {{ $applicant->fullname }}?');">
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

            /* Wrapper untuk pagination Laravel bawaan */
            .custom-pagination-wrapper nav {
                display: flex;
                justify-content: right;
                align-items: right;
            }

            .custom-pagination-wrapper .page-item {
                margin: 0 4px;
                /* Jarak antar tombol */
            }

            .custom-pagination-wrapper .page-link {
                display: flex;
                min-width: 40px;
                /* Lebar minimum tombol */
                height: 40px;
                /* Tinggi tombol */
                padding: 0;
                /* Hapus padding default */
                border-radius: 8px !important;
                /* Sudut membulat */
                color: #ffffff !important;
                /* Warna teks default */
                background-color: #6a5acd !important;
                /* Warna latar belakang default */
                border: 1px solid #6a5acd !important;
                /* Border tipis */
                transition: all 0.3s ease;
                /* Transisi halus untuk hover */
                font-weight: 500;
                font-size: 16px;
                /* Ukuran font untuk angka */
                line-height: 1;
                /* Penting untuk memastikan teks/ikon tidak terlalu besar */
            }

            /* Gaya untuk tombol aktif */
            .custom-pagination-wrapper .page-item.active .page-link {
                /* .relative { */
                background-color: #ffffff !important;
                border-color: #ffffff !important;
                color: #6a5acd !important;
            }

            /* Sembunyikan tombol pagination versi mobile */
            .flex.justify-between.flex-1.sm\:hidden {
                display: none !important;
            }

            .dataTables_info {
                display: none !important;
            }


            /* Untuk Font Awesome */
            span.icon,
            /* Untuk ikon generik */
            svg

            /* Untuk ikon SVG */
                {
                font-size: inherit;
                /* Mengambil ukuran font dari parent */
                width: 1em;
                /* Mengatur ulang lebar */
                height: 1em;
                /* Mengatur ulang tinggi */
                max-width: 100%;
                /* Batasi agar tidak melebihi parent */
                max-height: 100%;
                /* Batasi agar tidak melebihi parent */
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const searchInput = document.getElementById('search');
            const searchForm = document.getElementById('searchForm');

            // Submit realtime saat mengetik
            searchInput.addEventListener('input', function() {
                searchForm.submit();
            });

            // Auto-focus HANYA jika sudah ada pencarian sebelumnya
            @if (!empty($search))
                window.onload = function() {
                    searchInput.focus();
                    searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                };
            @endif
        </script>
        <script>
            // Auto hide alert after 3 seconds
            setTimeout(function() {
                $(".alert").alert('close');
            }, 3000); // 3000 ms = 1 detik
        </script>
    @endpush
