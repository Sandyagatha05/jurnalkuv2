@extends('layouts.app')

@section('title', 'Overdue Assignments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0 text-danger">Overdue Assignments</h3>
        <small class="text-muted">Review assignments that are past their due date</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-outline-primary">
            <i class="fas fa-clock me-1"></i> Pending Assignments
        </a>
        <a href="{{ route('reviewer.assignments.completed') }}" class="btn btn-outline-success">
            <i class="fas fa-check-circle me-1"></i> Completed Assignments
        </a>
    </div>
</div>

@if($assignments->count() > 0)
    <div class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-lg me-3 text-warning"></i>
            <div>
                <strong>Action Required:</strong> You have {{ $assignments->count() }} overdue review assignments. 
                Please complete these reviews as soon as possible.
            </div>
        </div>
    </div>
@endif

<div class="card shadow-sm hover-scale">
    <div class="card-header bg-light">
        <h5 class="mb-0 text-danger">
            <i class="fas fa-clock me-2"></i> Overdue List
        </h5>
    </div>
    <div class="card-body">
        @if($assignments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
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
                                    <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ Str::limit($assignment->paper->title, 60) }}
                                    </a>
                                    <div class="small text-muted mt-1">ID: #{{ $assignment->paper->id }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $assignment->paper->author->name }}</div>
                                    <small class="text-muted">{{ $assignment->paper->author->institution }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $assignment->due_date->format('M d, Y') }}</div>
                                    <small class="text-danger fw-semibold">
                                        <i class="far fa-calendar-times me-1"></i>
                                        Due {{ $assignment->due_date->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-danger px-2 py-1">{{ $daysOverdue }} day(s)</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i> Review Now
                                        </a>
                                        <a href="{{ route('reviewer.assignments.view-paper', $assignment) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-warning" onclick="requestExtension({{ $assignment->id }})" title="Request Extension">
                                            <i class="fas fa-hourglass-half"></i>
                                        </button>
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

            <div class="mt-4 pt-3 border-top">
                {{ $assignments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-4x text-success opacity-50"></i>
                </div>
                <h4 class="text-success mb-2">No Overdue Assignments</h4>
                <p class="text-muted mb-4">Great job! You have no overdue review assignments.</p>
                <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-primary">
                    <i class="fas fa-tasks me-2"></i> View Pending Assignments
                </a>
            </div>
        @endif
    </div>
</div>

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

@push('styles')
<style>
    .hover-scale {
        transition: all 0.3s ease;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

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