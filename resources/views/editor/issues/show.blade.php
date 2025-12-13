@extends('layouts.app')

@section('page-title', 'Issue Details')
@section('page-description', 'Manage issue details and papers')

@section('page-actions')
    <a href="{{ route('editor.issues.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Issues
    </a>
    
    @if($issue->status == 'draft')
        <button class="btn btn-success" onclick="publishIssue({{ $issue->id }})">
            <i class="fas fa-upload me-1"></i> Publish Issue
        </button>
    @endif
    
    @if($issue->status == 'published')
        <button class="btn btn-warning" onclick="unpublishIssue({{ $issue->id }})">
            <i class="fas fa-download me-1"></i> Unpublish
        </button>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Issue Details -->
    <div class="col-lg-8 mb-4">
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
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Volume/Number:</th>
                        <td>
                            <strong>Vol. {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }})</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Title:</th>
                        <td>{{ $issue->title }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $issue->description ?: 'No description' }}</td>
                    </tr>
                    <tr>
                        <th>Editor:</th>
                        <td>
                            @if($issue->editor)
                                {{ $issue->editor->name }}
                                <small class="text-muted d-block">{{ $issue->editor->email }}</small>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $issue->created_at->format('F d, Y H:i') }}</td>
                    </tr>
                    @if($issue->published_date)
                        <tr>
                            <th>Published Date:</th>
                            <td>{{ $issue->published_date->format('F d, Y') }}</td>
                        </tr>
                    @endif
                </table>
                
                <div class="mt-4">
                    <a href="{{ route('editor.issues.edit', $issue) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i> Edit Issue Details
                    </a>
                    
                    @if($issue->status == 'draft')
                        <button class="btn btn-outline-danger" onclick="deleteIssue({{ $issue->id }})">
                            <i class="fas fa-trash me-2"></i> Delete Issue
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Editorial -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i> Editorial
                    @if($issue->editorial)
                        <span class="badge bg-success">
                            <i class="fas fa-check"></i> Added
                        </span>
                    @endif
                </h5>
                @if(!$issue->editorial)
                    <a href="{{ route('editor.issues.editorial', $issue) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-plus me-1"></i> Add Editorial
                    </a>
                @endif
            </div>
            <div class="card-body">
                @if($issue->editorial)
                    <h6 class="text-primary">{{ $issue->editorial->title }}</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-user-circle text-muted"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $issue->editorial->author->name }}</h6>
                            <small class="text-muted">Written on {{ $issue->editorial->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                    <div class="editorial-preview">
                        {{ Str::limit($issue->editorial->content, 300) }}
                        @if(strlen($issue->editorial->content) > 300)
                            ... <a href="{{ route('editor.issues.editorial', $issue) }}" class="text-decoration-none">Read more</a>
                        @endif
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('editor.issues.editorial', $issue) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Editorial
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-edit fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">No editorial has been added to this issue yet.</p>
                        <a href="{{ route('editor.issues.editorial', $issue) }}" class="btn btn-warning">
                            <i class="fas fa-plus me-2"></i> Add Editorial
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Papers Management -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i> Papers in this Issue</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="display-4 text-primary">{{ $issue->papers->count() }}</div>
                    <h6>Papers Published</h6>
                </div>
                
                @if($issue->papers->count() > 0)
                    <div class="list-group list-group-flush mb-3">
                        @foreach($issue->papers->take(3) as $paper)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ Str::limit($paper->title, 40) }}</h6>
                                        <small class="text-muted">{{ $paper->author->name }}</small>
                                    </div>
                                    <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($issue->papers->count() > 3)
                        <div class="text-center">
                            <a href="#" class="btn btn-sm btn-outline-secondary">
                                View all {{ $issue->papers->count() }} papers
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-muted text-center mb-3">No papers assigned to this issue yet.</p>
                @endif
                
                <hr>
                
                <h6 class="mb-3">Add Papers</h6>
                @if($availablePapers->count() > 0)
                    <form action="{{ route('editor.issues.add-paper', $issue) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <select class="form-select" name="paper_id" required>
                                <option value="">Select a paper...</option>
                                @foreach($availablePapers as $paper)
                                    <option value="{{ $paper->id }}">
                                        {{ Str::limit($paper->title, 50) }} ({{ $paper->author->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <input type="number" class="form-control" name="page_from" placeholder="Page from">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="page_to" placeholder="Page to">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i> Add Paper to Issue
                        </button>
                    </form>
                @else
                    <p class="text-muted text-center mb-0">No accepted papers available to add.</p>
                @endif
            </div>
        </div>
        
        <!-- Issue Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i> Issue Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('editor.issues.edit', $issue) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i> Edit Issue
                    </a>
                    
                    <a href="{{ route('editor.issues.editorial', $issue) }}" class="btn btn-outline-warning">
                        <i class="fas fa-edit me-2"></i> 
                        @if($issue->editorial)
                            Edit Editorial
                        @else
                            Add Editorial
                        @endif
                    </a>
                    
                    @if($issue->status == 'draft')
                        <button class="btn btn-success" onclick="publishIssue({{ $issue->id }})">
                            <i class="fas fa-upload me-2"></i> Publish Issue
                        </button>
                    @endif
                    
                    @if($issue->status == 'published')
                        <button class="btn btn-warning" onclick="unpublishIssue({{ $issue->id }})">
                            <i class="fas fa-download me-2"></i> Unpublish
                        </button>
                    @endif
                    
                    @if($issue->status == 'draft')
                        <button class="btn btn-outline-danger" onclick="deleteIssue({{ $issue->id }})">
                            <i class="fas fa-trash me-2"></i> Delete Issue
                        </button>
                    @endif
                    
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-download me-2"></i> Download Issue (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .editorial-preview {
        line-height: 1.6;
        color: #6c757d;
    }
</style>
@endsection

@push('scripts')
<script>
    function publishIssue(issueId) {
        if (confirm('Are you sure you want to publish this issue?\n\nBefore publishing, ensure:\n1. Editorial is added\n2. At least one paper is included\n3. All information is correct')) {
            fetch(`/editor/issues/${issueId}/publish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Issue published successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Error publishing issue');
                }
            });
        }
    }
    
    function unpublishIssue(issueId) {
        if (confirm('Are you sure you want to unpublish this issue?\n\nThis will change the status to draft.')) {
            fetch(`/editor/issues/${issueId}/unpublish`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Issue unpublished successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Error unpublishing issue');
                }
            });
        }
    }
    
    function deleteIssue(issueId) {
        if (confirm('Are you sure you want to delete this issue?\n\nThis action cannot be undone. All editorial content will be deleted.')) {
            fetch(`/editor/issues/${issueId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Issue deleted successfully!');
                    window.location.href = '{{ route("editor.issues.index") }}';
                } else {
                    alert(data.message || 'Error deleting issue');
                }
            });
        }
    }
</script>
@endpush