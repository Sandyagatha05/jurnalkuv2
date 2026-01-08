@extends('layouts.app')

@section('page-title', 'Edit Issue')
@section('page-description', 'Manage papers in this issue')

@section('content')

{{-- Issue Header Card --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-book me-2"></i>
            Volume {{ $issue->volume }}, Number {{ $issue->number }} ({{ $issue->year }})
        </h5>
        <div class="btn-group">
            <a href="{{ route('editor.issues.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Issues
            </a>
            <button class="btn btn-outline-danger btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteIssueModal">
                <i class="fas fa-trash me-1"></i> Delete Issue
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <p class="mb-1"><strong>Title:</strong> {{ $issue->title }}</p>
                @if($issue->description)
                    <p class="mb-0 text-muted">{{ $issue->description }}</p>
                @endif
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-{{ $issue->status === 'published' ? 'success' : 'warning' }} fs-6">
                    {{ ucfirst($issue->status) }}
                </span>
                <div class="small text-muted mt-2">
                    {{ $issue->papers->count() }} papers
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Papers in this Issue --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i> Papers in Issue
                </h5>
                <button class="btn btn-primary btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#addPaperModal">
                    <i class="fas fa-plus-circle me-1"></i> Add Paper
                </button>
            </div>

            <div class="card-body">
                @if($issue->papers->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">No Papers Yet</h5>
                        <p class="text-muted mb-3">Add accepted papers to this issue</p>
                        <button class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addPaperModal">
                            <i class="fas fa-plus-circle me-1"></i> Add First Paper
                        </button>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Title</th>
                                    <th width="150">Author</th>
                                    <th width="100">Pages</th>
                                    <th width="100" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($issue->papers->sortBy('page_from') as $paper)
                                    <tr>
                                        <td class="fw-semibold">#{{ $paper->id }}</td>
                                        <td>
                                            <a href="{{ route('editor.papers.show', $paper) }}" 
                                               class="fw-semibold text-decoration-none">
                                                {{ Str::limit($paper->title, 50) }}
                                            </a>
                                            @if($paper->doi)
                                                <div class="small text-muted">DOI: {{ $paper->doi }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $paper->author->name }}</div>
                                            <div class="small text-muted">{{ Str::limit($paper->author->institution, 20) }}</div>
                                        </td>
                                        <td>
                                            @if($paper->page_from && $paper->page_to)
                                                <span class="badge bg-secondary">
                                                    {{ $paper->page_from }}–{{ $paper->page_to }}
                                                </span>
                                            @else
                                                <span class="text-muted small">Not set</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editPaperModal{{ $paper->id }}"
                                                        title="Edit Pages">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#removePaperModal{{ $paper->id }}"
                                                        title="Remove from Issue">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Edit Paper Pages Modal --}}
                                    <div class="modal fade" id="editPaperModal{{ $paper->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('editor.issues.update-paper', [$issue, $paper]) }}" method="POST"
                                                onsubmit="event.preventDefault(); 
                                                customConfirm('Are you sure you want to update this paper?').then(result => {if(result) this.submit();});">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Page Numbers</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-3"><strong>{{ Str::limit($paper->title, 60) }}</strong></p>
                                                        <div class="row g-3">
                                                            <div class="col-6">
                                                                <label class="form-label">Page From</label>
                                                                <input type="number" 
                                                                       name="page_from" 
                                                                       class="form-control" 
                                                                       value="{{ $paper->page_from }}"
                                                                       required>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label">Page To</label>
                                                                <input type="number" 
                                                                       name="page_to" 
                                                                       class="form-control" 
                                                                       value="{{ $paper->page_to }}"
                                                                       required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Pages</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Remove Paper Modal --}}
                                    <div class="modal fade" id="removePaperModal{{ $paper->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('editor.issues.remove-paper', [$issue, $paper]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Remove Paper from Issue</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to remove this paper from the issue?</p>
                                                        <p class="mb-0"><strong>{{ $paper->title }}</strong></p>
                                                        <div class="alert alert-warning mt-3 mb-0">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            The paper will not be deleted, only removed from this issue.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Remove Paper</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Issue Details Sidebar --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Issue Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="small text-muted">Volume</label>
                    <div class="fw-semibold">{{ $issue->volume }}</div>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Number</label>
                    <div class="fw-semibold">{{ $issue->number }}</div>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Year</label>
                    <div class="fw-semibold">{{ $issue->year }}</div>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Status</label>
                    <div>
                        <span class="badge bg-{{ $issue->status === 'published' ? 'success' : 'warning' }}">
                            {{ ucfirst($issue->status) }}
                        </span>
                    </div>
                </div>
                @if($issue->published_at)
                    <div class="mb-0">
                        <label class="small text-muted">Published Date</label>
                        <div class="fw-semibold">{{ $issue->published_at->format('d M Y') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Papers</span>
                    <span class="fw-semibold">{{ $issue->papers->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Pages</span>
                    <span class="fw-semibold">
                        @if($issue->papers->whereNotNull('page_from')->count() > 0)
                            {{ $issue->papers->sum(fn($p) => ($p->page_to ?? 0) - ($p->page_from ?? 0) + 1) }}
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-0">
                    <span class="text-muted">With DOI</span>
                    <span class="fw-semibold">{{ $issue->papers->whereNotNull('doi')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Paper Modal --}}
<div class="modal fade" id="addPaperModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('editor.issues.add-paper', $issue) }}" onsubmit="event.preventDefault(); 
            customConfirm('Are you sure you want to add this paper to this issue?').then(result => {if(result) this.submit();});" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Paper to Issue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Paper</label>
                        <select name="paper_id" class="form-select" required>
                            <option value="">Choose an accepted paper...</option>
                            @foreach($availablePapers as $paper)
                                <option value="{{ $paper->id }}">
                                    #{{ $paper->id }} - {{ Str::limit($paper->title, 80) }} ({{ $paper->author->name }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Only accepted papers without an issue are shown</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Page From</label>
                            <input type="number" name="page_from" class="form-control" placeholder="e.g., 1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Page To</label>
                            <input type="number" name="page_to" class="form-control" placeholder="e.g., 15" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Paper</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Issue Modal --}}
<div class="modal fade" id="deleteIssueModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('editor.issues.destroy', $issue) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Issue
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to permanently delete this issue?</p>
                    <div class="alert alert-danger mb-0">
                        <strong>Warning:</strong> This action cannot be undone. All papers in this issue will be unassigned but not deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Issue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Smooth transitions */
.card {
    transition: box-shadow 0.2s;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
}

/* Modal styling */
.modal-header.bg-danger {
    border-bottom: none;
}

/* Button group spacing */
.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}
</style>

@endsection