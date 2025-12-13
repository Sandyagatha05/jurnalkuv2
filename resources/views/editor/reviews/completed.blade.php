@extends('layouts.app')

@section('page-title', 'Completed Reviews')
@section('page-description', 'View completed review submissions')

@section('page-actions')
    <a href="{{ route('editor.reviews.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> All Reviews
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-check-circle me-2 text-success"></i> Completed Reviews
            <span class="badge bg-success">{{ $reviews->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        <!-- Content similar to index.blade.php -->
        @include('editor.reviews.index')
    </div>
</div>
@endsection