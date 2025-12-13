@extends('layouts.app')

@section('page-title', 'Pending Reviews')
@section('page-description', 'Monitor pending review assignments')

@section('page-actions')
    <a href="{{ route('editor.reviews.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> All Reviews
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-clock me-2 text-warning"></i> Pending Reviews
            <span class="badge bg-warning">{{ $assignments->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($assignments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Paper</th>
                            <th>Reviewer</th>
                            <th>Assigned</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('editor.papers.show', $assignment->paper) }}" class="text-decoration-none">
                                        {{ Str::limit($assignment->paper->title, 50) }}
                                    </a>
                                </td>
                                <td>{{ $assignment->reviewer->name }}</td>
                                <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="{{ $assignment->due_date < now() ? 'text-danger' : '' }}">
                                        {{ $assignment->due_date->format('M d, Y') }}
                                    </span>
                                    @if($assignment->due_date < now())
                                        <br>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-circle"></i> Overdue
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning">Pending</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('editor.papers.show', $assignment->paper) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-outline-warning" onclick="sendReminder({{ $assignment->id }})">
                                            <i class="fas fa-bell"></i> Remind
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $assignments->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                <h4 class="text-success mb-3">All Caught Up!</h4>
                <p class="text-muted">No pending review assignments.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function sendReminder(assignmentId) {
        if (confirm('Send reminder email to reviewer?')) {
            fetch(`/editor/reviews/assignments/${assignmentId}/remind`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reminder sent successfully!');
                }
            })
            .catch(error => {
                alert('Error sending reminder.');
                console.error(error);
            });
        }
    }
</script>
@endpush
@endsection