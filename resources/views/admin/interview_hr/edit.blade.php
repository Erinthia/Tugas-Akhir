@extends('layouts.master')

@section('title', 'Edit InterviewHR')

@section('content')
    <div class="section-header">
        <h1>HR Interview</h1>
    </div>
    <div class="section-body">
        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">HR Interview Assessment</h2>
            </div>
            <p class="section-lead">In this section you can editing of the HR Interview.</p>
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
                    <form action="{{ route('admin.interview_hr.update', $applicant->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="fullname">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $applicant->fullname ?? '') }}"readonly>
                            @error('name')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="score">Score</label>
                            <input type="text" class="form-control" id="score" name="score"
                                value="{{ old('score', $InterviewHR->score ?? '') }}">
                            @error('score')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="decision_id">Decision</label>
                            <select name="decision_id" id="decision_id" class="form-control">
                                @foreach ($decisions as $decision)
                                    <option value="{{ $decision->id }}"
                                        {{ old('decision_id', optional($InterviewHR)->decision_id) == $decision->id ? 'selected' : '' }}>
                                        {{ $decision->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('decision_id')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" style="height: 150px">{{ old('notes', $InterviewHR->notes ?? '') }}</textarea>
                            @error('notes')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="event_date">Event Date</label>
                            <input type="datetime-local" id="event_date" name="event_date" class="form-control"
                                value="{{ old('event_date', $InterviewHR->event_date ? date('Y-m-d\TH:i', strtotime($InterviewHR->event_date)) : '') }}">
                            @error('event_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location"
                                value="{{ old('location', $InterviewHR->location ?? '') }}">
                            @error('location')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>

                        <div class="buttons">
                            <a href="{{ route('admin.interview_hr.index') }}" class="btn btn-primary">Back</a>
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
