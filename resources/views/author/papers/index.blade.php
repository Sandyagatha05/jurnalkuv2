@extends('layouts.app')

@section('page-title', 'My Papers')
@section('page-description', 'View and manage your paper submissions')

@section('page-actions')
    <a href="{{ route('author.papers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Submit New Paper
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">My Paper Submissions</h5>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4" id="paperTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                    All Papers <span class="badge bg-secondary">{{ $papers->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="submitted-tab" data-bs-toggle="tab" data-bs-target="#submitted" type="button">
                    Submitted <span class="badge bg-secondary">{{ $papers->where('status', 'submitted')->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button">
                    Under Review <span class="badge bg-secondary">{{ $papers->where('status', 'under_review')->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="revision-tab" data-bs-toggle="tab" data-bs-target="#revision" type="button">
                    Needs Revision <span class="badge bg-secondary">{{ $papers->whereIn('status', ['revision_minor', 'revision_major'])->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted" type="button">
                    Accepted <span class="badge bg-secondary">{{ $papers->where('status', 'accepted')->count() }}</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="paperTabsContent">
            <!-- All Papers -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @include('author.papers.partials.papers-table', ['papers' => $papers])
            </div>
            
            <!-- Submitted Papers -->
            <div class="tab-pane fade" id="submitted" role="tabpanel">
                @include('author.papers.partials.papers-table', [
                    'papers' => $papers->where('status', 'submitted')
                ])
            </div>
            
            <!-- Under Review -->
            <div class="tab-pane fade" id="review" role="tabpanel">
                @include('author.papers.partials.papers-table', [
                    'papers' => $papers->where('status', 'under_review')
                ])
            </div>
            
            <!-- Needs Revision -->
            <div class="tab-pane fade" id="revision" role="tabpanel">
                @include('author.papers.partials.papers-table', [
                    'papers' => $papers->whereIn('status', ['revision_minor', 'revision_major'])
                ])
            </div>
            
            <!-- Accepted -->
            <div class="tab-pane fade" id="accepted" role="tabpanel">
                @include('author.papers.partials.papers-table', [
                    'papers' => $papers->where('status', 'accepted')
                ])
            </div>
        </div>
    </div>
</div>

@if($papers->isEmpty())
<div class="text-center py-5">
    <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
    <h4 class="text-muted mb-3">No Papers Submitted Yet</h4>
    <p class="text-muted mb-4">Start your academic publishing journey by submitting your first paper.</p>
    <a href="{{ route('author.papers.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus-circle me-2"></i> Submit Your First Paper
    </a>
</div>
@endif
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        color: #4361ee;
        font-weight: 600;
    }
    
    .paper-title {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush