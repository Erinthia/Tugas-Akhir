@extends('layouts.master')

@section('title', 'Edit Interview User')

@section('content')
    <div class="section-header">
        <h1>Additional Information Interview User</h1>
    </div>

    <div class="section-body">
        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">Information Interview User</h2>
            </div>
            <p class="section-lead">In this section you can create additional information regarding the continuation of the
                selection stage.</p>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <form action="{{ route('admin.interview_user.sendCustomEmail', $applicant->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="message">Information</label>
                        <textarea name="message" id="message" rows="5" class="form-control" style="height: 150px" required></textarea>
                    </div>
                    <div class="buttons">
                        <a href="{{ route('admin.interview_user.index') }}" class="btn btn-primary">Back</a>
                        <button type="submit" class="btn btn-success">Send Email</button>
                    </div>
                </form>
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
