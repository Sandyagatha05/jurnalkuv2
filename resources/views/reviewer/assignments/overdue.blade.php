@extends('layouts.app')

@section('page-title', 'Overdue Assignments')
@section('page-description', 'Review assignments that are past their due date')

@section('page-actions')
    <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-outline-primary">
        <i class="fas fa-clock me-1"></i> Pending Assignments
    </a>
    <a href="{{ route('reviewer.assignments.completed') }}" class="btn btn-outline-success">
        <i class="fas fa-check-circle me-1"></i> Completed Assignments
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            Overdue Review Assignments
        </h5>
    </div>
    <div class="card-body">
        @if($assignments->count() > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i>
                You have {{ $assignments->count() }} overdue review assignments. 
                Please complete these reviews as soon as possible.
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Paper</th>
                            <th>Author</th>
                            <th>Due Date</th>
                            <th>Days Overdue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            @php
                                $daysOverdue = now()->diffInDays($assignment->due_date);
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="text-decoration-none">
                                        {{ Str::limit($assignment->paper->title, 60) }}
                                    </a>
                                    <br>
                                    <small class="text-muted">ID: #{{ $assignment->paper->id }}</small>
                                </td>
                                <td>
                                    {{ $assignment->paper->author->name }}
                                    <br>
                                    <small class="text-muted">{{ $assignment->paper->author->institution }}</small>
                                </td>
                                <td>
                                    {{ $assignment->due_date->format('M d, Y') }}
                                    <br>
                                    <small class="text-warning">
                                        <i class="far fa-calendar-times me-1"></i>
                                        Was due {{ $assignment->due_date->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $daysOverdue }} day(s)</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i> Review Now
                                        </a>
                                        <a href="{{ route('reviewer.assignments.view-paper', $assignment) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" class="btn btn-outline-danger">
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
            {{ $assignments->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                <h4 class="text-success mb-3">No Overdue Assignments</h4>
                <p class="text-muted mb-4">
                    Great job! You have no overdue review assignments.
                </p>
                <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-primary">
                    <i class="fas fa-tasks me-2"></i> View Pending Assignments
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Request Extension Modal -->
<div class="modal fade" id="requestExtensionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="extensionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Request Extension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="extension_reason" class="form-label">Reason for Extension</label>
                        <textarea class="form-control" id="extension_reason" name="extension_reason" rows="3" required></textarea>
                        <small class="text-muted">Explain why you need an extension</small>
                    </div>
                    <div class="mb-3">
                        <label for="extension_days" class="form-label">Additional Days Needed</label>
                        <input type="number" class="form-control" id="extension_days" name="extension_days" min="1" max="14" value="7" required>
                        <small class="text-muted">Maximum 14 days extension</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Request extension functionality
    let currentAssignmentId = null;
    
    function requestExtension(assignmentId) {
        currentAssignmentId = assignmentId;
        $('#requestExtensionModal').modal('show');
    }
    
    $('#extensionForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            reason: $('#extension_reason').val(),
            days: $('#extension_days').val(),
            _token: '{{ csrf_token() }}'
        };
        
        fetch(`/reviewer/assignments/${currentAssignmentId}/request-extension`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#requestExtensionModal').modal('hide');
                alert('Extension request submitted successfully!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to submit extension request');
        });
    });
</script>
@endpush