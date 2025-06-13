@extends('layouts.master')

@section('title', 'Edit Psikotest')

@section('content')
    <div class="section-header">
        <h1>Psikotest</h1>
    </div>
    <div class="section-body">
        <div class="section-title-lead-wrapper">
            <div class="section-title">
                <span class="toggle-indicator inactive"></span>
                <h2 class="section-title-text">Psikotest Assessment</h2>
            </div>
            <p class="section-lead">In this section you can editing of the Psikotest.</p>
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
                    <form action="{{ route('admin.psikotests.update', $applicant->id) }}" method="POST">
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
                                value="{{ old('score', $psikotest->score ?? '') }}">
                            @error('score')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="decision_id">Decision</label>
                            <select name="decision_id" id="decision_id" class="form-control">
                                @foreach ($decisions as $decision)
                                    <option value="{{ $decision->id }}"
                                        {{ old('decision_id', optional($psikotest)->decision_id) == $decision->id ? 'selected' : '' }}>
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
                            <textarea class="form-control" id="notes" name="notes" style="height: 150px">{{ old('notes', $psikotest->notes ?? '') }}</textarea>
                            @error('notes')
                                <i>{{ $message }}</i>
                            @enderror
                        </div>

                        <div class="buttons">
                            <a href="{{ route('admin.psikotests.index') }}" class="btn btn-primary">Back</a>
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
@push('scripts')
    <script>
        // Auto hide alert after 3 seconds
        setTimeout(function() {
            $(".alert").alert('close');
        }, 3000); // 3000 ms = 1 detik
    </script>
@endpush
