@if($issues->count() > 0)
    <div class="table-responsive">
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
                                
                                @if($issue->status == 'draft')
                                    <button class="btn btn-outline-success" title="Publish"
                                            onclick="publishIssue({{ $issue->id }})">
                                        <i class="fas fa-upload"></i>
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline-danger" title="Delete"
                                            onclick="deleteIssue({{ $issue->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                                
                                @if($issue->status == 'published')
                                    <button class="btn btn-outline-warning" title="Unpublish"
                                            onclick="unpublishIssue({{ $issue->id }})">
                                        <i class="fas fa-download"></i>
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

@push('scripts')
<script>
    function publishIssue(issueId) {
        if (confirm('Are you sure you want to publish this issue?')) {
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
    }
    
    function unpublishIssue(issueId) {
        if (confirm('Are you sure you want to unpublish this issue?')) {
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
    }
    
    function deleteIssue(issueId) {
        if (confirm('Are you sure you want to delete this issue? This cannot be undone.')) {
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
    }
</script>
@endpush