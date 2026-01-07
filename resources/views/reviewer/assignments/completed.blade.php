@extends('layouts.app')

@section('title', 'Completed Reviews')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0">Completed Reviews</h3>
        <small class="text-muted">View your completed review assignments</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-outline-primary">
            <i class="fas fa-clock me-1"></i> View Pending
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-success border-start border-4 border-0 h-100 hover-scale">
            <div class="card-body text-center">
                <div class="display-5 text-success mb-1 fw-bold">
                    {{ $stats['total_completed'] ?? 0 }}
                </div>
                <h6 class="text-muted text-uppercase small fw-bold">Total Completed</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-primary border-start border-4 border-0 h-100 hover-scale">
            <div class="card-body text-center">
                <div class="display-5 text-primary mb-1 fw-bold">
                    {{ $stats['avg_score'] ?? '0.0' }}
                </div>
                <h6 class="text-muted text-uppercase small fw-bold">Average Score</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-info border-start border-4 border-0 h-100 hover-scale">
            <div class="card-body text-center">
                <div class="display-5 text-info mb-1 fw-bold">
                    {{ $stats['avg_days'] ?? 0 }}
                </div>
                <h6 class="text-muted text-uppercase small fw-bold">Avg. Completion Days</h6>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm hover-scale">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Review History</h5>
        <span class="badge bg-secondary">{{ $assignments->total() }} records</span>
    </div>
    <div class="card-body">
        @if($assignments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Paper Title</th>
                            <th>Author</th>
                            <th>Review Submitted</th>
                            <th>Recommendation</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="fw-semibold text-primary text-decoration-none">
                                        {{ Str::limit($assignment->paper->title, 50) }}
                                    </a>
                                    @if($assignment->paper->issue)
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-book-reader me-1"></i>
                                            Vol. {{ $assignment->paper->issue->volume }}, No. {{ $assignment->paper->issue->number }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $assignment->paper->author->name }}</td>
                                <td>
                                    <div class="fw-medium">{{ $assignment->completed_date->format('M d, Y') }}</div>
                                    <small class="text-muted">
                                        Due: {{ $assignment->due_date->format('M d') }}
                                    </small>
                                </td>
                                <td>
                                    @if($assignment->review)
                                        <span class="badge bg-{{ $assignment->review->recommendation == 'accept' ? 'success' : ($assignment->review->recommendation == 'reject' ? 'danger' : 'warning') }} px-2 py-1">
                                            {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">No review</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->review)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-warning me-2"></i>
                                            <div>
                                                <div class="fw-bold">{{ $assignment->review->overall_score }}/5</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">Overall</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($assignment->review)
                                            <a href="{{ route('reviewer.reviews.edit', $assignment->review) }}" class="btn btn-outline-secondary" title="Edit Review">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" class="btn btn-outline-success" title="Download Paper">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div class="text-muted small">
                    Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
                </div>
                <div>
                    {{ $assignments->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-4x text-muted opacity-50"></i>
                </div>
                <h5 class="text-muted mb-2">No Completed Reviews</h5>
                <p class="text-muted mb-4 small">You haven't completed any reviews yet.</p>
                <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-primary">
                    <i class="fas fa-tasks me-2"></i> View Pending Assignments
                </a>
            </div>
        @endif
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