@extends('layouts.master')

@section('title', 'Show Offering')

@section('content')
    <div class="section-header">
        <h1>Offering</h1>
    </div>
    <div class="section-body">
        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">Offering Detail</h2>
            </div>
            <p class="section-lead">In this section you can show details of the Offering.</p>
        </div>
        <div class="card" style="min-height: 900px;">
            <div class="card-body">
                <div class="form-group">
                    <label for="fullname">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $applicant->fullname ?? '') }}" readonly>
                </div>
                <div class="form-group">
                    <label for="benefit">Benefit</label>
                    <input type="text" class="form-control" id="benefit" value="{{ $Offering->benefit }}" readonly>
                </div>
                <div class="form-group">
                    <label for="selection_result">Selection Result</label>
                    <input type="text" class="form-control" id="selection_result"
                        value="{{ $Offering->selection_result }}" readonly>
                </div>
                <div class="form-group">
                    <label>Deadline Offering:</label>
                    <p>
                        {{ $Offering->deadline_offering ? \Carbon\Carbon::parse($Offering->deadline_offering)->format('d M Y') : '-' }}
                    </p>
                </div>
                <div class="form-group">
                    <label for="offering_result">Offering Result</label>
                    <input type="text" class="form-control" id="offering_result" value="{{ $Offering->offering_result }}"
                        readonly>
                </div>
                <div class="buttons">
                    <a href="{{ route('admin.offerings.index') }}" class="btn btn-primary">Back</a>
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
