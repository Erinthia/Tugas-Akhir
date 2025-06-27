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
    </style>
@endpush
