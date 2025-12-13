@extends('layouts.app')

@section('page-title', 'All Reviews')
@section('page-description', 'View all submitted reviews')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('editor.reviews.pending') }}" class="btn btn-outline-warning">
            <i class="fas fa-clock me-1"></i> Pending
        </a>
        <a href="{{ route('editor.reviews.completed') }}" class="btn btn-outline-success">
            <i class="fas fa-check-circle me-1"></i> Completed
        </a>
        <a href="{{ route('editor.reviews.overdue') }}" class="btn btn-outline-danger">
            <i class="fas fa-exclamation-circle me-1"></i> Overdue
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Reviews</h5>
    </div>
    <div class="card-body">
        @if($reviews->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Paper</th>
                            <th>Reviewer</th>
                            <th>Recommendation</th>
                            <th>Scores</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>
                                    <a href="{{ route('editor.papers.show', $review->assignment->paper) }}" class="text-decoration-none">
                                        {{ Str::limit($review->assignment->paper->title, 50) }}
                                    </a>
                                </td>
                                <td>{{ $review->assignment->reviewer->name }}</td>
                                <td>
                                    @php
                                        $recommendationClass = [
                                            'accept' => 'success',
                                            'minor_revision' => 'info',
                                            'major_revision' => 'warning',
                                            'reject' => 'danger'
                                        ][$review->recommendation] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $recommendationClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        Orig: {{ $review->originality_score }}/5
                                        <br>
                                        Overall: {{ $review->overall_score }}/5
                                    </small>
                                </td>
                                <td>{{ $review->reviewed_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('editor.reviews.show', $review) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $reviews->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-comments fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No Reviews Found</h4>
                <p class="text-muted">No reviews have been submitted yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection