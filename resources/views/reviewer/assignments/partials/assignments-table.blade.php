@if($assignments->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Paper Title</th>
                    <th>Author</th>
                    <th>Assigned Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assignment)
                    <tr>
                        <td>
                            <div class="assignment-title" title="{{ $assignment->paper->title }}">
                                <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="text-decoration-none">
                                    {{ $assignment->paper->title }}
                                </a>
                            </div>
                        </td>
                        <td>
                            <div>{{ $assignment->paper->author->name }}</div>
                            <small class="text-muted">{{ $assignment->paper->author->institution }}</small>
                        </td>
                        <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                        <td>
                            <div class="due-date {{ $assignment->due_date < now() ? 'overdue' : '' }}">
                                {{ $assignment->due_date->format('M d, Y') }}
                                @if($assignment->due_date < now())
                                    <br>
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-circle"></i> Overdue
                                    </small>
                                @else
                                    <br>
                                    <small class="text-muted">
                                        {{ $assignment->due_date->diffForHumans() }}
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $assignment->status == 'pending' ? 'warning' : ($assignment->status == 'accepted' ? 'primary' : 'secondary') }}">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($assignment->status == 'pending')
                                    <button type="button" class="btn btn-outline-success" title="Accept Assignment"
                                            onclick="acceptAssignment({{ $assignment->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline-danger" title="Decline Assignment"
                                            onclick="declineAssignment({{ $assignment->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                
                                @if($assignment->status == 'accepted' || $assignment->status == 'pending')
                                    <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-outline-warning" title="Submit Review">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                
                                <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" class="btn btn-outline-info" title="Download Paper">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($assignments instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
            </div>
            {{ $assignments->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
        <p class="text-muted">No assignments found in this category.</p>
    </div>
@endif

@push('scripts')
<script>
    function acceptAssignment(assignmentId) {
        if (confirm('Are you sure you want to accept this review assignment?')) {
            fetch(`/reviewer/assignments/${assignmentId}/accept`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    function declineAssignment(assignmentId) {
        if (confirm('Are you sure you want to decline this review assignment?')) {
            fetch(`/reviewer/assignments/${assignmentId}/decline`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endpush