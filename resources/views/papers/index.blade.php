@extends('layouts.public')

@section('title', 'Research Papers - ' . config('app.name'))
@section('description', 'Browse all published research papers')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">Research Papers</h1>
            <p class="lead">
                Browse through our collection of published research papers. 
                All papers are peer-reviewed and available for download.
            </p>
        </div>
        <div class="col-lg-4">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Search papers..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Papers List -->
    @if($papers->count() > 0)
        <div class="row">
            @foreach($papers as $paper)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 paper-card">
                        <div class="card-body">
                            @if($paper->issue)
                                <div class="mb-3">
                                    <span class="badge bg-primary">
                                        Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }}
                                    </span>
                                    <span class="badge bg-secondary">{{ $paper->issue->year }}</span>
                                </div>
                            @endif
                            
                            <h5 class="card-title mb-3">
                                <a href="{{ route('papers.show', $paper) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($paper->title, 80) }}
                                </a>
                            </h5>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <i class="fas fa-user-circle text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $paper->author->name }}</h6>
                                    <small class="text-muted">{{ $paper->author->institution }}</small>
                                </div>
                            </div>
                            
                            <p class="card-text text-muted mb-4">
                                {{ Str::limit($paper->abstract, 120) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($paper->doi)
                                        <small class="text-muted">
                                            DOI: <code>{{ $paper->doi }}</code>
                                        </small>
                                    @endif
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('papers.show', $paper) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-danger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        @if($paper->keywords)
                            <div class="card-footer bg-transparent border-top">
                                <small class="text-muted">
                                    <i class="fas fa-tags me-1"></i>
                                    {{ Str::limit($paper->keywords, 40) }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $papers->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">No Papers Found</h4>
            <p class="text-muted mb-4">
                @if(request()->has('search'))
                    Try adjusting your search terms
                @else
                    No papers have been published yet.
                @endif
            </p>
            <a href="{{ route('papers.index') }}" class="btn btn-primary">
                <i class="fas fa-sync me-2"></i> Clear Search
            </a>
        </div>
    @endif
    
    <!-- Statistics -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="display-6 text-primary mb-2">
                                {{ \App\Models\Paper::published()->count() }}
                            </div>
                            <h6>Published Papers</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="display-6 text-primary mb-2">
                                {{ \App\Models\Issue::published()->count() }}
                            </div>
                            <h6>Published Issues</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="display-6 text-primary mb-2">
                                {{ \App\Models\User::count() }}
                            </div>
                            <h6>Registered Users</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="display-6 text-primary mb-2">
                                {{ \App\Models\User::role('reviewer')->count() }}
                            </div>
                            <h6>Active Reviewers</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .paper-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .paper-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }
    
    .paper-card .card-title {
        min-height: 60px;
    }
</style>
@endsection