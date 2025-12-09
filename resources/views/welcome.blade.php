@extends('layouts.public')

@section('title', 'Home - ' . config('app.name'))
@section('description', 'Welcome to Jurnalku - Academic Journal Management System')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-3">Welcome to <span class="text-primary">Jurnalku</span></h1>
            <p class="lead mb-4">
                A comprehensive academic journal management system that streamlines 
                the publishing process for authors, reviewers, and editors.
            </p>
            <div class="d-flex gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i> Get Started
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                @endauth
            </div>
        </div>
        <div class="col-lg-6">
            <img src="https://via.placeholder.com/600x400" alt="Journal Management" class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- Features -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Key Features</h2>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 text-center p-4">
                <div class="card-body">
                    <i class="fas fa-file-upload fa-3x text-primary mb-3"></i>
                    <h4 class="card-title">Easy Submission</h4>
                    <p class="card-text">Submit your papers easily with our streamlined submission system.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 text-center p-4">
                <div class="card-body">
                    <i class="fas fa-search fa-3x text-primary mb-3"></i>
                    <h4 class="card-title">Peer Review</h4>
                    <p class="card-text">Robust double-blind peer review process with expert reviewers.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 text-center p-4">
                <div class="card-body">
                    <i class="fas fa-book-open fa-3x text-primary mb-3"></i>
                    <h4 class="card-title">Issue Management</h4>
                    <p class="card-text">Organize and publish journal issues with editorial content.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Issues -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Latest Issues</h2>
            @php
                $issues = App\Models\Issue::with('editorial')
                    ->published()
                    ->orderBy('year', 'desc')
                    ->orderBy('volume', 'desc')
                    ->orderBy('number', 'desc')
                    ->take(3)
                    ->get();
            @endphp
            
            @if($issues->count() > 0)
                <div class="row">
                    @foreach($issues as $issue)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $issue->title }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        Vol. {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }})
                                    </h6>
                                    <p class="card-text">{{ Str::limit($issue->description, 100) }}</p>
                                    <a href="{{ route('issues.show', $issue) }}" class="btn btn-sm btn-outline-primary">
                                        View Issue
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('issues.index') }}" class="btn btn-primary">
                        View All Issues <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No published issues yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection