@extends('layouts.master')

@section('title', 'Edit Offering')

@section('content')
    <div class="section-header">
        <h1>Offering</h1>
    </div>
    <div class="section-body">
        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">Offering Assessment</h2>
            </div>
            <p class="section-lead">In this section you can editing of the Offering.</p>
        </div>
        <div class="card" style="min-height: 900px;">
            <div class="card">
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
                    <form action="{{ route('admin.offerings.update', $applicant->id) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="fullname">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $applicant->fullname ?? '') }}" readonly>
                            @error('name')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="benefit">Benefit</label>
                            <textarea class="form-control" id="benefit" name="benefit" style="height: 150px">{{ old('benefit', $Offering->benefit ?? '') }}</textarea>
                            @error('benefit')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="selection_result">Selection Result</label>
                            <textarea class="form-control" id="selection_result" name="selection_result" style="height: 150px">{{ old('selection_result', $Offering->selection_result ?? '') }}</textarea>
                            @error('selection_result')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deadline_offering">Deadline Offering</label>
                            <input type="date" id="deadline_offering" name="deadline_offering" class="form-control"
                                value="{{ old('deadline_offering', $Offering->deadline_offering ? date('Y-m-d', strtotime($Offering->deadline_offering)) : '') }}">
                            @error('deadline_offering')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="offering_result">Offering Result</label>
                            <textarea class="form-control" id="offering_result" name="offering_result" style="height: 150px">{{ old('offering_result', $Offering->offering_result ?? '') }}</textarea>
                            @error('offering_result')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>

                        <div class="buttons">
                            <a href="{{ route('admin.offerings.index') }}" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
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
    </style>
@endpush
