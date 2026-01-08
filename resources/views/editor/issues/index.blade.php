@extends('layouts.app')

@section('title', 'Manage Issues')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Manage Issues</h4>
            <p class="text-muted mb-0">View and manage all journal issues</p>
        </div>
        <a href="{{ route('editor.issues.create') }}" class="btn btn-primary btn-lift">
            <i class="fas fa-plus-circle me-1"></i> Create New Issue
        </a>
    </div>

    {{-- Empty State --}}
    @if($issues->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-open fs-1 text-muted mb-3"></i>
                <h5 class="mb-2">No Issues Created Yet</h5>
                <p class="text-muted mb-4">
                    Start by creating your first journal issue.
                </p>
                <a href="{{ route('editor.issues.create') }}" class="btn btn-primary btn-lift">
                    <i class="fas fa-plus-circle me-1"></i> Create First Issue
                </a>
            </div>
        </div>
    @else

    {{-- Issues Card --}}
    <div class="card">
        <div class="card-body">

            {{-- Tabs --}}
            <ul class="nav nav-pills gap-2 mb-4" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all">
                        All
                        <span class="badge bg-secondary ms-1">
                            {{ $issues->total() }}
                        </span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#published">
                        Published
                        <span class="badge bg-success ms-1">
                            {{ $statusCounts['published'] }}
                        </span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#draft">
                        Draft
                        <span class="badge bg-warning ms-1">
                            {{ $statusCounts['draft'] }}
                        </span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#archived">
                        Archived
                        <span class="badge bg-secondary ms-1">
                            {{ $statusCounts['archived'] }}
                        </span>
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content">

                <div class="tab-pane fade show active" id="all">
                    @include('editor.issues.partials.issues-table', [
                        'issues' => $issues
                    ])
                </div>

                <div class="tab-pane fade" id="published">
                    @include('editor.issues.partials.issues-table', [
                        'issues' => $issues->where('status','published')
                    ])
                </div>

                <div class="tab-pane fade" id="draft">
                    @include('editor.issues.partials.issues-table', [
                        'issues' => $issues->where('status','draft')
                    ])
                </div>

                <div class="tab-pane fade" id="archived">
                    @include('editor.issues.partials.issues-table', [
                        'issues' => $issues->where('status','archived')
                    ])
                </div>

            </div>
        </div>
    </div>
    @endif
</div>

{{-- Local Styles --}}

<style>

.nav-pills .nav-link {
    color: var(--foreground);
    background: #f8f9fa;
    border-radius: .5rem;
    font-weight: 500;
}

.nav-pills .nav-link.active {
    background: var(--primary-color);
    color: #fff;
}

.btn-lift {
    transition: transform .15s ease, box-shadow .15s ease;
}

.btn-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}

/* Target all SVGs in pagination more aggressively */
svg[class*="w-5"],
svg[class*="h-5"],
.pagination svg,
nav[role="navigation"] svg {
    width: 12px !important;
    height: 12px !important;
}

/* Target Livewire/Tailwind pagination specifically */
.relative svg,
a[rel="prev"] svg,
a[rel="next"] svg,
span[aria-hidden="true"] svg {
    width: 12px !important;
    height: 12px !important;
}
</style>
@endsection
