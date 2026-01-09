@if($issues->count() > 0)
    <div class="table-responsive" style="overflow: visible;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Volume/Number</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Papers</th>
                    <th>Editorial</th>
                    <th>Published Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $issue)
                    <tr>
                        <td>
                            <strong>Vol. {{ $issue->volume }}, No. {{ $issue->number }}</strong>
                        </td>
                        <td class="issue-title" title="{{ $issue->title }}">
                            <a href="{{ route('editor.issues.show', $issue) }}" class="text-decoration-none">
                                {{ $issue->title }}
                            </a>
                        </td>
                        <td>{{ $issue->year }}</td>
                        <td>
                            @include('components.status-badge', ['status' => $issue->status])
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $issue->papers->count() }} papers</span>
                        </td>
                        <td>
                            @if($issue->editorial)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Yes
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times"></i> No
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($issue->published_date)
                                {{ $issue->published_date->format('M d, Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('editor.issues.show', $issue) }}" class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('editor.issues.edit', $issue) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Status Change Dropdown -->
                                <div class="btn-group btn-group-sm dropdown-container">
                                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" title="Change Status">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($issue->status !== 'draft')
                                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); changeStatus({{ $issue->id }}, 'draft')">
                                                <i class="fas fa-file text-warning"></i> Set as Draft
                                            </a></li>
                                        @endif
                                        @if($issue->status !== 'published')
                                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); changeStatus({{ $issue->id }}, 'published')">
                                                <i class="fas fa-check text-success"></i> Set as Published
                                            </a></li>
                                        @endif
                                        @if($issue->status !== 'archived')
                                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); changeStatus({{ $issue->id }}, 'archived')">
                                                <i class="fas fa-archive text-secondary"></i> Set as Archived
                                            </a></li>
                                        @endif
                                    </ul>
                                </div>
                                
                                @if($issue->status == 'draft')
                                    <button type="button" class="btn btn-outline-danger" title="Delete"
                                            onclick="deleteIssue({{ $issue->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($issues instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $issues->firstItem() }} to {{ $issues->lastItem() }} of {{ $issues->total() }} issues
            </div>
            {{ $issues->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
        <p class="text-muted">No issues found in this category.</p>
    </div>
@endif

<style>
/* Fix dropdown clipping issue */
.table-responsive {
    overflow: visible !important;
}

.dropdown-container {
    position: static !important;
}

.dropdown-menu {
    position: absolute !important;
    z-index: 1050 !important;
}
</style>

@push('scripts')
<script>
    function changeStatus(issueId, status) {
    const messages = {
        'draft': 'set this issue to draft',
        'published': 'publish this issue',
        'archived': 'archive this issue'
    };
    
    // If trying to publish, use the existing publish function with checks
    if (status === 'published') {
        publishIssue(issueId);
        return;
    }
    
    // If trying to unpublish (set to draft from published), use unpublish function
    if (status === 'draft') {
        // Check if issue is currently published
        const row = event.target.closest('tr');
        const currentStatus = row.querySelector('.status-badge')?.textContent?.trim().toLowerCase();
        
        if (currentStatus === 'published') {
            unpublishIssue(issueId);
            return;
        }
    }
    
    // For other status changes (like archived), proceed normally
    customConfirm(`Are you sure you want to ${messages[status]}?`).then(result => {
        if (result) {
            fetch(`/editor/issues/${issueId}/change-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error changing status');
                }
            })
            .catch(error => {
                alert('An error occurred');
                console.error(error);
            });
        }
    });
}

    function publishIssue(issueId) {
        customConfirm('Are you sure you want to publish this issue?').then(result => {
            if (result) {
                fetch(`/editor/issues/${issueId}/publish`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error publishing issue');
                    }
                });
            }
        })
    }
    
    function unpublishIssue(issueId) {
        customConfirm('Are you sure you want to unpublish this issue?').then(result => {
            if (result) {
                fetch(`/editor/issues/${issueId}/unpublish`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error unpublishing issue');
                    }
                });
            }
        });
    }
    
    function deleteIssue(issueId) {
        customConfirm('Are you sure you want to delete this issue?<br>This action cannot be undone.').then(result => {
            if (result) {
                fetch(`/editor/issues/${issueId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error deleting issue');
                    }
                });
            }
        });
    }
</script>
@endpush