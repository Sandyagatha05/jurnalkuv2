@extends('layouts.public')

@section('title', 'Journal Issues - ' . config('app.name'))
@section('description', 'Browse all published journal issues')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">Journal Issues</h1>
            <p class="lead">
                Browse through our collection of published journal issues. 
                Each issue contains carefully selected research papers and editorial content.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Search issues..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="year" class="form-select" onchange="this.form.submit()">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="volume" class="form-select" onchange="this.form.submit()">
                                <option value="">All Volumes</option>
                                @foreach($volumes as $volume)
                                    <option value="{{ $volume }}" {{ request('volume') == $volume ? 'selected' : '' }}>
                                        Volume {{ $volume }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('archive') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-archive me-2"></i> View Archive
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Issues Grid -->
    @if($issues->count() > 0)
        <div class="row">
            @foreach($issues as $issue)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 issue-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary">Vol. {{ $issue->volume }}, No. {{ $issue->number }}</span>
                                    <span class="badge bg-secondary">{{ $issue->year }}</span>
                                </div>
                                <small class="text-muted">
                                    {{ $issue->published_date->format('M d, Y') }}
                                </small>
                            </div>
                            
                            <h5 class="card-title mb-3">
                                <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none text-dark">
                                    {{ $issue->title }}
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted mb-4">
                                {{ Str::limit($issue->description, 120) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-alt text-primary me-1"></i>
                                    <small class="text-muted">
                                        {{ $issue->papers->count() }} papers
                                    </small>
                                </div>
                                <a href="{{ route('issues.show', $issue) }}" class="btn btn-sm btn-outline-primary">
                                    View Issue <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        @if($issue->editorial && $issue->editorial->is_published)
                            <div class="card-footer bg-transparent border-top">
                                <small class="text-muted">
                                    <i class="fas fa-edit me-1"></i>
                                    Editorial: "{{ Str::limit($issue->editorial->title, 40) }}"
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $issues->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">No Issues Found</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'year', 'volume']))
                    Try adjusting your search filters
                @else
                    No issues have been published yet.
                @endif
            </p>
            <a href="{{ route('issues.index') }}" class="btn btn-primary">
                <i class="fas fa-sync me-2"></i> Clear Filters
            </a>
        </div>
    @endif
</div>

<style>
    .issue-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .issue-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }
    
    .issue-card .card-title {
        min-height: 60px;
    }
</style>
@endsection