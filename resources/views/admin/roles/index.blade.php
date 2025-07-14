@extends('layouts.master')

@section('title', 'Role Management')

@section('content')
    <div class="section-header">
        <h1>Role Management</h1>
    </div>
    <div class="section-body">
        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">Role Management</h2>
            </div>
            <p class="section-lead">
                In this section you can manage system roles such as adding, changing, and deleting.
            </p>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 d-flex align-items-center">
                        <!-- Kolom pencarian di tengah -->
                        <div class="mx-auto" style="width: 40%;">
                            <form action="{{ route('admin.roles.index') }}" method="GET" id="searchForm">
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Search Role" value="{{ $search ?? '' }}">
                                </div>
                            </form>
                        </div>

                        <!-- Tombol Create di kanan -->
                        <div class="ms-auto">
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Create</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $index => $role)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.roles.show', $role->id) }}"
                                            class="btn btn-icon btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                            class="btn btn-icon btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-icon btn-danger">
                                                <i class="fas fa-times"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No roles found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="custom-pagination-wrapper">
                        {{ $roles->links() }}
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
            }, 3000);
        </script>
    @endpush
