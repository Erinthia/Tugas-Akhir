@extends('layouts.master')

@section('title', 'Offering')

@section('content')
    <div class="section-header">
        <h1>Offering</h1>
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
                <h2 class="section-title-text">Offering List</h2>
            </div>
            <p class="section-lead">
                In this section you can manage system Offering data such as editing and show detail.
            </p>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <form action="{{ route('admin.offerings.index') }}" method="GET" id="searchForm">
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
                            <th>Name</th>
                            <th>Opportunity</th>
                            <th>Action</th>
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
                                    <a href="{{ route('admin.offerings.show', $applicant->id) }}"
                                        class="btn btn-icon btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.offerings.edit', $applicant->id) }}"
                                        class="btn btn-icon btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>

                                <td>
                                    @if (
                                        $applicant->Offering &&
                                            $applicant->Offering->benefit !== '-' &&
                                            $applicant->Offering->selection_result !== '-' &&
                                            !empty($applicant->Offering->deadline_offering) &&
                                            $applicant->offering->Offering_result !== '-')
                                        @if (!$applicant->Offering->notification_sent)
                                            <form action="{{ route('admin.offerings.sendNotification', $applicant->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Kirim notifikasi untuk {{ $applicant->fullname }}?');">
                                                @csrf
                                                <button class="btn btn-sm btn-success" type="submit">
                                                    Send Notification
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled
                                                title="Notifikasi sudah dikirim">
                                                Notification Sent
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $applicant->offering?->staff?->name ?? '-' }}
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
    </style>
@endpush

@push('scripts')
    <script>
        const searchInput = document.getElementById('search');
        const searchForm = document.getElementById('searchForm');

        searchInput.addEventListener('input', function() {
            searchForm.submit(); // Submit form when input changes
        });
    </script>
    <script>
        // Auto hide alert after 3 seconds
        setTimeout(function() {
            $(".alert").alert('close');
        }, 3000); // 3000 ms = 1 detik
    </script>
@endpush
