@extends('layouts.app')

@section('page-title', 'My Papers')
@section('page-description', 'View and manage your paper submissions')

@section('content')
<div class="row">

    {{-- ================= --}}
    {{-- Header & Action --}}
    {{-- ================= --}}
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-file-alt me-2"></i> My Paper Submissions
            </h4>
            <a href="{{ route('author.papers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Submit New Paper
            </a>
        </div>
    </div>

    {{-- ================= --}}
    {{-- Papers Card --}}
    {{-- ================= --}}
    <div class="col-12">
        <div class="card">

            <div class="card-body">

                {{-- Filter Tabs --}}
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-all" type="button">
                            All
                            <span class="badge bg-secondary ms-1">{{ $papers->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-submitted" type="button">
                            Submitted
                            <span class="badge bg-secondary ms-1">
                                {{ $papers->where('status','submitted')->count() }}
                            </span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-review" type="button">
                            Under Review
                            <span class="badge bg-secondary ms-1">
                                {{ $papers->where('status','under_review')->count() }}
                            </span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-revision" type="button">
                            Needs Revision
                            <span class="badge bg-secondary ms-1">
                                {{ $papers->whereIn('status',['revision_minor','revision_major'])->count() }}
                            </span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-accepted" type="button">
                            Accepted
                            <span class="badge bg-secondary ms-1">
                                {{ $papers->where('status','accepted')->count() }}
                            </span>
                        </button>
                    </li>
                </ul>

                {{-- Tab Contents --}}
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="tab-all">
                        @include('author.papers.partials.papers-table', ['papers' => $papers])
                    </div>

                    <div class="tab-pane fade" id="tab-submitted">
                        @include('author.papers.partials.papers-table', [
                            'papers' => $papers->where('status','submitted')
                        ])
                    </div>

                    <div class="tab-pane fade" id="tab-review">
                        @include('author.papers.partials.papers-table', [
                            'papers' => $papers->where('status','under_review')
                        ])
                    </div>

                    <div class="tab-pane fade" id="tab-revision">
                        @include('author.papers.partials.papers-table', [
                            'papers' => $papers->whereIn('status',['revision_minor','revision_major'])
                        ])
                    </div>

                    <div class="tab-pane fade" id="tab-accepted">
                        @include('author.papers.partials.papers-table', [
                            'papers' => $papers->where('status','accepted')
                        ])
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- ================= --}}
    {{-- Empty State --}}
    {{-- ================= --}}
    @if($papers->isEmpty())
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No Papers Submitted Yet</h4>
                <p class="text-muted mb-4">
                    Start your academic publishing journey by submitting your first paper.
                </p>
                <a href="{{ route('author.papers.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i> Submit Your First Paper
                </a>
            </div>
        </div>
    @endif

</div>

{{-- Styles --}}
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
    max-width: 320px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endsection
