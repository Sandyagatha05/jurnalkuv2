@extends('layouts.public')

@section('title', $paper->title . ' - ' . config('app.name'))
@section('description', Str::limit($paper->abstract, 160))

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('papers.index') }}">Papers</a></li>
            @if($paper->issue)
                <li class="breadcrumb-item"><a href="{{ route('issues.show', $paper->issue) }}">Issue {{ $paper->issue->volume }}.{{ $paper->issue->number }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($paper->title, 50) }}</li>
        </ol>
    </nav>

    <!-- Paper Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-success fs-6 me-3">Published</span>
                @if($paper->doi)
                    <span class="badge bg-info fs-6">DOI: {{ $paper->doi }}</span>
                @endif
            </div>
            
            <h1 class="display-6 fw-bold mb-4">{{ $paper->title }}</h1>
            
            <!-- Author Information -->
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                </div>
                <div>
                    <h5 class="mb-1">{{ $paper->author->name }}</h5>
                    <p class="text-muted mb-0">
                        {{ $paper->author->institution }}
                        @if($paper->author->department)
                            , {{ $paper->author->department }}
                        @endif
                    </p>
                </div>
            </div>
            
            <!-- Publication Details -->
            <div class="row g-3 mb-4">
                @if($paper->issue)
                    <div class="col-md-6">
                        <div class="card border-primary h-100">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-book me-2"></i>Published in
                                </h6>
                                <p class="mb-0">
                                    <a href="{{ route('issues.show', $paper->issue) }}" class="text-decoration-none">
                                        {{ $paper->issue->title }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }} ({{ $paper->issue->year }})
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="col-md-6">
                    <div class="card border-success h-100">
                        <div class="card-body">
                            <h6 class="card-title text-success">
                                <i class="fas fa-calendar-alt me-2"></i>Publication Date
                            </h6>
                            <p class="mb-0">
                                {{ $paper->published_at->format('F d, Y') }}
                                <br>
                                <small class="text-muted">
                                    Submitted: {{ $paper->submitted_at->format('M d, Y') }}
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Download Card -->
            <div class="card border-danger mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-download me-2"></i> Download</h6>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                    <h5 class="card-title mb-3">Full Text PDF</h5>
                    <p class="card-text text-muted mb-4">
                        Download the complete paper in PDF format
                    </p>
                    <a href="{{ route('papers.download', $paper) }}" class="btn btn-danger btn-lg w-100">
                        <i class="fas fa-download me-2"></i> Download PDF
                    </a>
                    <small class="text-muted mt-2 d-block">
                        File: {{ $paper->original_filename }}
                    </small>
                </div>
            </div>
            
            <!-- Citation Card -->
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-quote-right me-2"></i> Cite This Paper</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">APA Format</label>
                        <textarea class="form-control" rows="3" readonly>
{{ $paper->author->name }} ({{ $paper->published_at->format('Y') }}). {{ $paper->title }}. 
{{ config('app.name') }}{{ $paper->issue ? ', ' . $paper->issue->volume . '(' . $paper->issue->number . ')' : '' }}, 
{{ $paper->page_from ? $paper->page_from . '-' . $paper->page_to : '' }}. 
{{ $paper->doi ? 'https://doi.org/' . $paper->doi : '' }}
                        </textarea>
                    </div>
                    <button class="btn btn-outline-info w-100" onclick="copyCitation()">
                        <i class="fas fa-copy me-2"></i> Copy Citation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Abstract -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Abstract</h5>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $paper->abstract }}</p>
                    
                    @if($paper->keywords)
                        <div class="mt-4">
                            <h6 class="text-primary">
                                <i class="fas fa-tags me-2"></i> Keywords
                            </h6>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach(explode(',', $paper->keywords) as $keyword)
                                    <span class="badge bg-secondary">{{ trim($keyword) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Paper Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Paper ID:</th>
                            <td>#{{ $paper->id }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-success">Published</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Submission Date:</th>
                            <td>{{ $paper->submitted_at->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Publication Date:</th>
                            <td>{{ $paper->published_at->format('F d, Y') }}</td>
                        </tr>
                        @if($paper->revision_count > 0)
                            <tr>
                                <th>Revisions:</th>
                                <td>{{ $paper->revision_count }} revision(s)</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i> Author Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3">
                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $paper->author->name }}</h5>
                            <p class="text-muted mb-1">{{ $paper->author->institution }}</p>
                            @if($paper->author->department)
                                <p class="text-muted mb-2">{{ $paper->author->department }}</p>
                            @endif
                            @if($paper->author->email)
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-2"></i>
                                    <a href="mailto:{{ $paper->author->email }}" class="text-decoration-none">
                                        {{ $paper->author->email }}
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    @if($paper->author->orcid_id || $paper->author->google_scholar_id)
                        <hr>
                        <h6 class="text-primary">Research Profiles</h6>
                        <div class="d-flex gap-2">
                            @if($paper->author->orcid_id)
                                <a href="https://orcid.org/{{ $paper->author->orcid_id }}" 
                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fab fa-orcid me-1"></i> ORCID
                                </a>
                            @endif
                            @if($paper->author->google_scholar_id)
                                <a href="https://scholar.google.com/citations?user={{ $paper->author->google_scholar_id }}" 
                                   target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="fab fa-google me-1"></i> Google Scholar
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Paper navigation">
                <ul class="pagination justify-content-center">
                    @if($previousPaper)
                        <li class="page-item">
                            <a class="page-link" href="{{ route('papers.show', $previousPaper) }}">
                                <i class="fas fa-arrow-left me-2"></i> Previous Paper
                            </a>
                        </li>
                    @endif
                    
                    <li class="page-item">
                        <a class="page-link" href="{{ route('papers.index') }}">
                            <i class="fas fa-list me-2"></i> All Papers
                        </a>
                    </li>
                    
                    @if($nextPaper)
                        <li class="page-item">
                            <a class="page-link" href="{{ route('papers.show', $nextPaper) }}">
                                Next Paper <i class="fas fa-arrow-right ms-2"></i>
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
    
    textarea.form-control {
        font-size: 0.875rem;
        line-height: 1.4;
    }
</style>

@push('scripts')
<script>
    function copyCitation() {
        const citationText = document.querySelector('textarea').value;
        navigator.clipboard.writeText(citationText)
            .then(() => {
                alert('Citation copied to clipboard!');
            })
            .catch(err => {
                console.error('Failed to copy: ', err);
            });
    }
</script>
@endpush
@endsection