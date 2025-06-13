@extends('layouts.master')

@section('title', 'Category Management')

@push('styles')
    @livewireStyles
@endpush

@push('scripts')
    @livewireScripts
@endpush

@section('content')
    <section class="section">
        @livewire('applicants-menu')
    </section>
@endsection
