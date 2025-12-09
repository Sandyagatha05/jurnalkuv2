@extends('layouts.public')

@section('title', $issue->title . ' - ' . config('app.name'))
@section('description', Str::limit($issue->description, 160))

@section('content')
<div class="container py-5">
    <!-- Issue Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('issues.index') }}">Issues</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Vol. {{ $issue->volume }}, No. {{ $issue->number }}
                    </li>
                </ol>
            </nav>
            
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-primary fs-6 me-3">Vol. {{ $issue->volume }}, No. {{ $issue->number }}</span>
                <span class="badge bg-secondary fs-6">{{ $issue->year }}</span>
            </div>
            
            <h1 class="display-5 fw-bold mb-3">{{ $issue->title }}</h1>
            
            <div class="d-flex align-items-center text-muted mb-4">
                <i class="far fa-calendar me-2"></i>
                <span>Published: {{ $issue->published_date->format('F d, Y') }}</span>
                <span class="mx-3">•</span>
                <i class="fas fa-user-edit me-2"></i>
                <span>Editor: {{ $issue->editor->name ?? 'Not assigned' }}</span>
            </div>
            
            <p class="lead">{{ $issue->description }}</p>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">Issue Details</h5>
                    <div class="display-1 fw-bold text-primary mb-3">
                        {{ $issue->volume }}.{{ $issue->number }}
                    </div>
                    <p class="card-text">
                        <strong>{{ $issue->year }}</strong><br>
                        {{ $issue->published_date->format('F Y') }}
                    </p>
                    <hr>
                    <p class="mb-2">
                        <i class="fas fa-file-alt me-2"></i>
                        {{ $issue->papers->count() }} Research Papers
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-download me-2"></i>
                        <a href="#" class="text-decoration-none">Download Full Issue (PDF)</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Editorial -->
    @if($issue->editorial && $issue->editorial->is_published)
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Editorial
                        </h3>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3">{{ $issue->editorial->title }}</h4>
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $issue->editorial->author->name }}</h6>
                                <small class="text-muted">{{ $issue->editorial->author->institution }}</small>
                            </div>
                        </div>
                        <div class="editorial-content">
                            {!! nl2br(e($issue->editorial->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Papers -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i> Research Papers
                    <span class="badge bg-primary fs-6 ms-2">{{ $issue->papers->count() }}</span>
                </h2>
                
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active" data-filter="all">
                        All Papers
                    </button>
                    @foreach($categories as $category)
                        <button type="button" class="btn btn-outline-primary" data-filter="{{ Str::slug($category) }}">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>
            </div>
            
            @if($issue->papers->count() > 0)
                <div class="row" id="papers-container">
                    @foreach($issue->papers as $paper)
                        <div class="col-lg-6 mb-4 paper-item" data-categories="{{ Str::slug($paper->category ?? 'uncategorized') }}">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge bg-info">Paper {{ $loop->iteration }}</span>
                                            @if($paper->page_from && $paper->page_to)
                                                <span class="badge bg-secondary ms-2">
                                                    Pages {{ $paper->page_from }}-{{ $paper->page_to }}
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            {{ $paper->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                    
                                    <h5 class="card-title mb-3">
                                        <a href="{{ route('papers.show', $paper) }}" class="text-decoration-none text-dark">
                                            {{ $paper->title }}
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
                                        {{ Str::limit($paper->abstract, 150) }}
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
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-danger">
                                                <i class="fas fa-download me-1"></i> PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($paper->keywords)
                                    <div class="card-footer bg-transparent border-top">
                                        <small class="text-muted">
                                            <i class="fas fa-tags me-1"></i>
                                            {{ $paper->keywords }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Download All -->
                <div class="text-center mt-5">
                    <div class="card border-dashed">
                        <div class="card-body py-4">
                            <i class="fas fa-download fa-3x text-primary mb-3"></i>
                            <h4 class="mb-3">Download Complete Issue</h4>
                            <p class="text-muted mb-4">
                                Get all papers from this issue in a single PDF file
                            </p>
                            <button class="btn btn-primary btn-lg">
                                <i class="fas fa-file-pdf me-2"></i> Download Full Issue (PDF)
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">No Papers Published</h4>
                    <p class="text-muted">This issue does not contain any papers yet.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Navigation -->
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Issue navigation">
                <ul class="pagination justify-content-center">
                    @if($previousIssue)
                        <li class="page-item">
                            <a class="page-link" href="{{ route('issues.show', $previousIssue) }}">
                                <i class="fas fa-arrow-left me-2"></i> Previous Issue
                            </a>
                        </li>
                    @endif
                    
                    <li class="page-item">
                        <a class="page-link" href="{{ route('issues.index') }}">
                            <i class="fas fa-list me-2"></i> All Issues
                        </a>
                    </li>
                    
                    @if($nextIssue)
                        <li class="page-item">
                            <a class="page-link" href="{{ route('issues.show', $nextIssue) }}">
                                Next Issue <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
    .border-dashed {
        border: 2px dashed #dee2e6;
        background-color: #f8f9fa;
    }
    
    .editorial-content {
        line-height: 1.8;
    }
    
    .editorial-content p {
        margin-bottom: 1rem;
    }
</style>

@push('scripts')
<script>
    // Filter papers by category
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filter papers
            document.querySelectorAll('.paper-item').forEach(item => {
                if (filter === 'all' || item.getAttribute('data-categories') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection