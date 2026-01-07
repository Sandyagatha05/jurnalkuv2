@extends('layouts.app')

@section('page-title', 'Manage Papers')
@section('page-description', 'View and manage all paper submissions')

@section('content')

<div class="card">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-file-alt me-2"></i> All Papers
        </h5>

        <div class="btn-group">
            <a href="{{ route('editor.papers.submitted') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-inbox me-1"></i> Submitted
            </a>
            <a href="{{ route('editor.papers.under-review') }}" class="btn btn-outline-warning btn-sm">
                <i class="fas fa-search me-1"></i> Under Review
            </a>
            <a href="{{ route('editor.papers.accepted') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-check-circle me-1"></i> Accepted
            </a>
        </div>
    </div>

    <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-3">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="submitted" @selected(request('status')=='submitted')>Submitted</option>
                    <option value="under_review" @selected(request('status')=='under_review')>Under Review</option>
                    <option value="accepted" @selected(request('status')=='accepted')>Accepted</option>
                    <option value="rejected" @selected(request('status')=='rejected')>Rejected</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="search"
                       class="form-control form-control-sm"
                       placeholder="Title or author..."
                       value="{{ request('search') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Apply
                </button>
                <a href="{{ route('editor.papers.index') }}"
                   class="btn btn-sm btn-outline-secondary w-100">
                    Reset
                </a>
            </div>

            <div class="col-md-2 text-end small text-muted">
                {{ $papers->total() }} papers
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                <tr>
                    <th width="70">ID</th>
                    <th>Paper</th>
                    <th width="180">Author</th>
                    <th width="120">Status</th>
                    <th width="140">Submitted</th>
                    <th width="120">Reviews</th>
                    <th width="160" class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($papers as $paper)
                    @php
                        $completed = $paper->reviewAssignments->where('status','completed')->count();
                        $total = $paper->reviewAssignments->count();
                    @endphp

                    <tr>
                        <td class="fw-semibold">#{{ $paper->id }}</td>

                        <td>
                            <a href="{{ route('editor.papers.show', $paper) }}"
                               class="fw-semibold text-decoration-none">
                                {{ Str::limit($paper->title, 60) }}
                            </a>

                            @if($paper->doi)
                                <div class="small text-muted">
                                    DOI: {{ $paper->doi }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $paper->author->name }}</div>
                            <div class="small text-muted">
                                {{ $paper->author->institution }}
                            </div>
                        </td>

                        <td>
                            @include('components.status-badge', ['status' => $paper->status])
                        </td>

                        <td class="small text-muted">
                            {{ $paper->submitted_at->format('d M Y') }}
                        </td>

                        <td>
                            @if($total)
                                <span class="badge bg-{{ $completed === $total ? 'success' : 'warning' }}">
                                    {{ $completed }}/{{ $total }}
                                </span>
                            @else
                                <span class="badge bg-secondary">—</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('editor.papers.show', $paper) }}"
                                   class="btn btn-outline-primary"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($paper->status === 'submitted')
                                    <a href="{{ route('editor.papers.assign-reviewers', $paper) }}"
                                       class="btn btn-outline-warning"
                                       title="Assign Reviewers">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                @endif

                                @if($paper->status === 'under_review' && $completed === $total)
                                    <a href="{{ route('editor.papers.decision', $paper) }}"
                                       class="btn btn-outline-success"
                                       title="Decision">
                                        <i class="fas fa-gavel"></i>
                                    </a>
                                @endif

                                @if($paper->status === 'accepted' && !$paper->issue_id)
                                    <button class="btn btn-outline-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#assignIssueModal{{ $paper->id }}"
                                            title="Assign to Issue">
                                        <i class="fas fa-book"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL --}}
                    <div class="modal fade" id="assignIssueModal{{ $paper->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('editor.papers.assign-issue', $paper) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Assign Paper to Issue</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Issue</label>
                                            <select name="issue_id" class="form-select" required>
                                                <option value="">Select issue</option>
                                                @foreach($issues as $issue)
                                                    <option value="{{ $issue->id }}">
                                                        Vol. {{ $issue->volume }}, No. {{ $issue->number }}
                                                        ({{ $issue->year }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col">
                                                <input type="number" name="page_from"
                                                       class="form-control"
                                                       placeholder="Page from">
                                            </div>
                                            <div class="col">
                                                <input type="number" name="page_to"
                                                       class="form-control"
                                                       placeholder="Page to">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn btn-primary">Assign</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <div>No papers found</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <!-- <small class="text-muted">
                Showing {{ $papers->firstItem() }}–{{ $papers->lastItem() }} of {{ $papers->total() }}
            </small> -->

            {{ $papers->links() }}
        </div>
    </div>
</div>

<style>
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