@extends('layouts.app')

@section('page-title', 'New Submissions')
@section('page-description', 'Review newly submitted papers')

@section('page-actions')
    <a href="{{ route('editor.papers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to All Papers
    </a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 d-flex align-items-center">
            <i class="fas fa-inbox me-2 text-primary"></i>
            New Paper Submissions
            <span class="badge bg-primary ms-2">{{ $papers->total() }}</span>
        </h5>
    </div>

    <div class="card-body p-0">
        @if($papers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:80px;">ID</th>
                            <th>Title</th>
                            <th style="width:220px;">Author</th>
                            <th style="width:140px;">Submitted</th>
                            <th>Abstract Preview</th>
                            <th class="text-end pe-4" style="width:200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($papers as $paper)
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">
                                    #{{ $paper->id }}
                                </td>

                                <td>
                                    <a href="{{ route('editor.papers.show', $paper) }}"
                                       class="fw-semibold text-decoration-none text-dark d-block">
                                        {{ Str::limit($paper->title, 60) }}
                                    </a>
                                </td>

                                <td>
                                    <div class="fw-medium">{{ $paper->author->name }}</div>
                                    <small class="text-muted">{{ $paper->author->institution }}</small>
                                </td>

                                <td>
                                    <span class="text-muted">
                                        {{ $paper->submitted_at->format('M d, Y') }}
                                    </span>
                                </td>

                                <td>
                                    <small class="text-muted d-block" style="max-width:380px;">
                                        {{ Str::limit($paper->abstract, 80) }}
                                    </small>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('editor.papers.show', $paper) }}"
                                           class="btn btn-outline-primary"
                                           title="View Paper">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('papers.download', $paper) }}"
                                           class="btn btn-outline-secondary"
                                           title="Download PDF">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <a href="{{ route('editor.papers.assign-reviewers', $paper) }}"
                                           class="btn btn-warning"
                                           title="Assign Reviewers">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($papers->hasPages())
                <div class="d-flex justify-content-center mt-5 mb-4">
                    <nav>
                        <ul class="pagination gap-2">

                            {{-- Previous --}}
                            <li class="page-item {{ $papers->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link btn-lift" href="{{ $papers->previousPageUrl() ?? '#' }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            {{-- Page Numbers --}}
                            @foreach ($papers->getUrlRange(1, $papers->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $papers->currentPage() ? 'active' : '' }}">
                                    <a class="page-link btn-lift" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Next --}}
                            <li class="page-item {{ $papers->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link btn-lift" href="{{ $papers->nextPageUrl() ?? '#' }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No New Submissions</h4>
                <p class="text-muted">All submitted papers have been processed.</p>
            </div>
        @endif
    </div>
</div>

<style>
.pagination .page-link {
    border-radius: .5rem;
    border: 1px solid var(--border);
    color: var(--foreground);
    padding: .5rem .75rem;
    background: white;
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination .page-item.disabled .page-link {
    opacity: .4;
    pointer-events: none;
}
</style>
@endsection
