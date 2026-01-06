@extends('layouts.app')

@section('page-title', 'Issue Details')
@section('page-description', 'Overview of issue content and publication status')

@section('page-actions')
    <a href="{{ route('editor.issues.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    @if($issue->status === 'draft')
        <button class="btn btn-success" onclick="publishIssue({{ $issue->id }})">
            <i class="fas fa-upload me-1"></i> Publish
        </button>
    @endif
@endsection

@section('content')
<div class="row g-4">

    <!-- MAIN CONTENT -->
    <div class="col-lg-8">

        <!-- ISSUE SUMMARY -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Issue Information</h5>
                <div>
                    @include('components.status-badge', ['status' => $issue->status])
                    @if($issue->is_special)
                        <span class="badge bg-info ms-2">Special Issue</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Volume / Number</dt>
                    <dd class="col-sm-8">
                        <strong>Vol. {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }})</strong>
                    </dd>

                    <dt class="col-sm-4">Title</dt>
                    <dd class="col-sm-8">{{ $issue->title }}</dd>

                    <dt class="col-sm-4">Description</dt>
                    <dd class="col-sm-8 text-muted">
                        {{ $issue->description ?? '—' }}
                    </dd>

                    <dt class="col-sm-4">Created At</dt>
                    <dd class="col-sm-8">
                        {{ $issue->created_at->format('d F Y, H:i') }}
                    </dd>

                    @if($issue->published_date)
                        <dt class="col-sm-4">Published Date</dt>
                        <dd class="col-sm-8">
                            {{ $issue->published_date->format('d F Y') }}
                        </dd>
                    @endif
                </dl>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('editor.issues.edit', $issue) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i> Edit Issue
                    </a>

                    @if($issue->status === 'draft')
                        <button class="btn btn-outline-danger" onclick="deleteIssue({{ $issue->id }})">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- EDITORIAL -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-pen-nib me-2"></i> Editorial
                </h5>

                <a href="{{ route('editor.issues.editorial', $issue) }}"
                   class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-edit me-1"></i>
                    {{ $issue->editorial ? 'Edit' : 'Add' }}
                </a>
            </div>

            <div class="card-body">
                @if($issue->editorial)
                    <h6 class="fw-semibold text-primary">
                        {{ $issue->editorial->title }}
                    </h6>

                    <p class="text-muted mb-2">
                        By {{ $issue->editorial->author->name }}
                        • {{ $issue->editorial->created_at->format('d M Y') }}
                    </p>

                    <p class="editorial-preview">
                        {{ Str::limit($issue->editorial->content, 300) }}
                    </p>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <p>No editorial added yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SIDEBAR -->
    <div class="col-lg-4">

        <!-- PAPERS -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i> Papers in This Issue
                </h6>
            </div>

            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-5 fw-semibold text-primary">
                        {{ $issue->papers->count() }}
                    </div>
                    <small class="text-muted">Total Papers</small>
                </div>

                @if($issue->papers->isNotEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($issue->papers as $paper)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">
                                        {{ Str::limit($paper->title, 45) }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $paper->author->name }}
                                    </small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center mb-0">
                        No papers assigned yet.
                    </p>
                @endif
            </div>
        </div>

        <!-- NAVIGATION -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-arrows-alt-h me-2"></i> Issue Navigation
                </h6>
            </div>

            <div class="card-body d-grid gap-2">
                @if($previousIssue)
                    <a href="{{ route('editor.issues.show', $previousIssue) }}"
                       class="btn btn-outline-secondary">
                        ← Previous Issue
                    </a>
                @endif

                @if($nextIssue)
                    <a href="{{ route('editor.issues.show', $nextIssue) }}"
                       class="btn btn-outline-secondary">
                        Next Issue →
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.editorial-preview {
    line-height: 1.7;
    color: #6c757d;
}
</style>
@endsection
