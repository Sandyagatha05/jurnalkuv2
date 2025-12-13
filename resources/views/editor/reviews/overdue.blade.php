@extends('layouts.app')

@section('page-title', 'Overdue Reviews')
@section('page-description', 'Monitor overdue review assignments')

@section('page-actions')
    <a href="{{ route('editor.reviews.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> All Reviews
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-circle me-2 text-danger"></i> Overdue Reviews
            <span class="badge bg-danger">{{ $assignments->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @include('editor.reviews.pending')
    </div>
</div>
@endsection