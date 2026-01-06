@extends('layouts.app')

@section('page-title', 'Overdue Reviews')
@section('page-description', 'Monitor overdue review assignments')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-11">

        {{-- Toolbar --}}
        <div class="mb-3">
            <a href="{{ route('editor.reviews.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> All Reviews
            </a>
        </div>

        {{-- Main Card --}}
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle me-2 text-danger"></i> Overdue Reviews
                </h5>
                <span class="badge bg-danger">
                    {{ $assignments->total() }}
                </span>
            </div>

            <div class="card-body">

                @if($assignments->count())

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Paper</th>
                                    <th>Reviewer</th>
                                    <th>Assigned</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    <tr>
                                        <td style="max-width: 280px;">
                                            <a href="{{ route('editor.papers.show', $assignment->paper) }}"
                                               class="fw-semibold text-decoration-none d-block text-truncate">
                                                {{ $assignment->paper->title }}
                                            </a>
                                        </td>

                                        <td>
                                            {{ $assignment->reviewer->name }}
                                        </td>

                                        <td>
                                            {{ $assignment->assigned_date->format('M d, Y') }}
                                        </td>

                                        <td class="text-danger fw-semibold">
                                            {{ $assignment->due_date->format('M d, Y') }}
                                            <div class="small">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Overdue
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge bg-danger">Overdue</span>
                                        </td>

                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('editor.papers.show', $assignment->paper) }}"
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger"
                                                    onclick="sendReminder({{ $assignment->id }})"
                                                >
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $assignments->links() }}
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                        <h4 class="text-success mb-2">No Overdue Reviews</h4>
                        <p class="text-muted mb-0">
                            Great job! There are no overdue review assignments.
                        </p>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

{{-- Scripts --}}
@push('scripts')
<script>
    function sendReminder(assignmentId) {
        if (!confirm('Send reminder email to reviewer?')) return;

        fetch(`/editor/reviews/assignments/${assignmentId}/remind`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Reminder sent successfully.');
            } else {
                alert('Failed to send reminder.');
            }
        })
        .catch(() => {
            alert('Error sending reminder.');
        });
    }
</script>
@endpush
@endsection
