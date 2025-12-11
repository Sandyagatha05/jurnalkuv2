@extends('layouts.public')

@section('title', 'Journal Archive - ' . config('app.name'))
@section('description', 'Browse archived journal issues and papers')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">Journal Archive</h1>
            <p class="lead">
                Browse through our complete collection of archived journal issues 
                and research papers from previous years.
            </p>
        </div>
        <div class="col-lg-4">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Search archive..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Year Navigation -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Browse by Year</h5>
                    <div class="row">
                        @php
                            $years = \App\Models\Issue::select('year')
                                ->distinct()
                                ->orderBy('year', 'desc')
                                ->pluck('year');
                        @endphp
                        
                        @foreach($years->chunk(4) as $yearChunk)
                            <div class="col-md-3 mb-3">
                                <div class="list-group">
                                    @foreach($yearChunk as $year)
                                        <a href="{{ route('issues.index') }}?year={{ $year }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            {{ $year }}
                                            <span class="badge bg-primary rounded-pill">
                                                {{ \App\Models\Issue::where('year', $year)->count() }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Issues -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">All Issues</h2>
                <a href="{{ route('issues.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Current Issues
                </a>
            </div>
            
            @php
                $allIssues = \App\Models\Issue::with('editorial', 'papers')
                    ->orderBy('year', 'desc')
                    ->orderBy('volume', 'desc')
                    ->orderBy('number', 'desc')
                    ->paginate(20);
            @endphp
            
            @if($allIssues->count() > 0)
                <div class="row">
                    @foreach($allIssues as $issue)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 archive-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge bg-{{ $issue->status == 'published' ? 'primary' : ($issue->status == 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($issue->status) }}
                                            </span>
                                            <span class="badge bg-secondary ms-2">{{ $issue->year }}</span>
                                        </div>
                                        <small class="text-muted">
                                            Vol. {{ $issue->volume }}, No. {{ $issue->number }}
                                        </small>
                                    </div>
                                    
                                    <h5 class="card-title mb-3">
                                        <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none text-dark">
                                            {{ Str::limit($issue->title, 60) }}
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-muted mb-4">
                                        {{ Str::limit($issue->description, 100) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-alt text-primary me-1"></i>
                                            <small class="text-muted">
                                                {{ $issue->papers->count() }} papers
                                            </small>
                                        </div>
                                        <div>
                                            @if($issue->isPublished())
                                                <a href="{{ route('issues.show', $issue) }}" class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>
                                            @else
                                                <span class="text-muted">Not published</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $allIssues->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-archive fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">Archive Empty</h4>
                    <p class="text-muted">No issues found in the archive.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .archive-card {
        transition: transform 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .archive-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        border-color: #4361ee;
    }
</style>
@endsection