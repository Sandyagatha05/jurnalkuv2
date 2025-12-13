@extends('layouts.app')

@section('page-title', 'Completed Reviews')
@section('page-description', 'View your completed review assignments')

@section('page-actions')
    <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-outline-primary">
        <i class="fas fa-clock me-1"></i> View Pending
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Completed Reviews</h5>
        <div class="text-muted">
            {{ $assignments->total() }} completed reviews
        </div>
    </div>
    <div class="card-body">
        @if($assignments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
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
                                    <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="text-decoration-none">
                                        {{ Str::limit($assignment->paper->title, 50) }}
                                    </a>
                                    @if($assignment->paper->issue)
                                        <br>
                                        <small class="text-muted">
                                            Published in: Vol. {{ $assignment->paper->issue->volume }}, No. {{ $assignment->paper->issue->number }}
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $assignment->paper->author->name }}</td>
                                <td>
                                    {{ $assignment->completed_date->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">
                                        Due: {{ $assignment->due_date->format('M d') }}
                                    </small>
                                </td>
                                <td>
                                    @if($assignment->review)
                                        <span class="badge bg-{{ $assignment->review->recommendation == 'accept' ? 'success' : ($assignment->review->recommendation == 'reject' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">No review</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->review)
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="fas fa-star text-warning"></i>
                                            </div>
                                            <div>
                                                <div>{{ $assignment->review->overall_score }}/5</div>
                                                <small class="text-muted">Overall</small>
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
                </div>
                {{ $assignments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No Completed Reviews</h4>
                <p class="text-muted mb-4">You haven't completed any reviews yet.</p>
                <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-primary">
                    <i class="fas fa-tasks me-2"></i> View Pending Assignments
                </a>
            </div>
        @endif
        
        <!-- Statistics -->
        <div class="row mt-5">
            <div class="col-md-4 mb-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <div class="display-4 text-success mb-2">
                            {{ $stats['total_completed'] ?? 0 }}
                        </div>
                        <h6>Total Completed</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <div class="display-4 text-primary mb-2">
                            {{ $stats['avg_score'] ?? '0.0' }}
                        </div>
                        <h6>Average Score</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <div class="display-4 text-info mb-2">
                            {{ $stats['avg_days'] ?? 0 }}
                        </div>
                        <h6>Avg. Completion Days</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection