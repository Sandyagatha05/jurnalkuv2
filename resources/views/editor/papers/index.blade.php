@extends('layouts.app')

@section('page-title', 'Manage Papers')
@section('page-description', 'View and manage all paper submissions')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('editor.papers.submitted') }}" class="btn btn-outline-primary">
            <i class="fas fa-inbox me-1"></i> New Submissions
        </a>
        <a href="{{ route('editor.papers.under-review') }}" class="btn btn-outline-warning">
            <i class="fas fa-search me-1"></i> Under Review
        </a>
        <a href="{{ route('editor.papers.accepted') }}" class="btn btn-outline-success">
            <i class="fas fa-check-circle me-1"></i> Accepted
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Papers</h5>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" class="row g-2">
                    <div class="col-md-4">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Search title/author..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('editor.papers.index') }}" class="btn btn-sm btn-outline-secondary">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="text-muted">
                    Showing {{ $papers->firstItem() }} to {{ $papers->lastItem() }} of {{ $papers->total() }} papers
                </div>
            </div>
        </div>

        <!-- Papers Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Reviewers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($papers as $paper)
                        <tr>
                            <td>#{{ $paper->id }}</td>
                            <td>
                                <a href="{{ route('editor.papers.show', $paper) }}" class="text-decoration-none">
                                    {{ Str::limit($paper->title, 50) }}
                                </a>
                                @if($paper->doi)
                                    <br><small class="text-muted">DOI: {{ $paper->doi }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $paper->author->name }}</div>
                                <small class="text-muted">{{ $paper->author->institution }}</small>
                            </td>
                            <td>
                                @include('components.status-badge', ['status' => $paper->status])
                            </td>
                            <td>{{ $paper->submitted_at->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $completed = $paper->reviewAssignments->where('status', 'completed')->count();
                                    $total = $paper->reviewAssignments->count();
                                @endphp
                                @if($total > 0)
                                    <span class="badge bg-{{ $completed == $total ? 'success' : 'warning' }}">
                                        {{ $completed }}/{{ $total }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">None</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($paper->status == 'submitted')
                                        <a href="{{ route('editor.papers.assign-reviewers', $paper) }}" class="btn btn-outline-warning" title="Assign Reviewers">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                    @endif
                                    
                                    @if($paper->status == 'under_review' && $completed == $total)
                                        <a href="{{ route('editor.papers.decision', $paper) }}" class="btn btn-outline-success" title="Make Decision">
                                            <i class="fas fa-gavel"></i>
                                        </a>
                                    @endif
                                    
                                    @if($paper->status == 'accepted' && !$paper->issue_id)
                                        <button class="btn btn-outline-info" title="Assign to Issue" 
                                                data-bs-toggle="modal" data-bs-target="#assignIssueModal{{ $paper->id }}">
                                            <i class="fas fa-book"></i>
                                        </button>
                                    @endif
                                </div>
                                
                                <!-- Assign to Issue Modal -->
                                <div class="modal fade" id="assignIssueModal{{ $paper->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('editor.papers.assign-issue', $paper) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign to Issue</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="issue_id" class="form-label">Select Issue</label>
                                                        <select class="form-select" id="issue_id" name="issue_id" required>
                                                            <option value="">Select an issue...</option>
                                                            @foreach($issues as $issue)
                                                                <option value="{{ $issue->id }}">
                                                                    Vol. {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }}) - {{ $issue->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="page_from" class="form-label">Page From</label>
                                                            <input type="number" class="form-control" id="page_from" name="page_from">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="page_to" class="form-label">Page To</label>
                                                            <input type="number" class="form-control" id="page_to" name="page_to">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Assign</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No papers found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $papers->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Quick status update
    function updatePaperStatus(paperId, status) {
        if (confirm('Are you sure you want to update the status?')) {
            fetch(`/editor/papers/${paperId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush