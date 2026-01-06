@extends('layouts.app')

@section('page-title', 'All Reviews')
@section('page-description', 'View all submitted reviews')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-11">

        {{-- Filter Actions --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
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

        {{-- Main Card --}}
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="fas fa-comments me-2"></i> All Reviews
                </h5>
                <span class="badge bg-primary">
                    {{ $reviews->total() }}
                </span>
            </div>

            <div class="card-body">

                @if($reviews->count())

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Paper</th>
                                    <th>Reviewer</th>
                                    <th>Recommendation</th>
                                    <th>Scores</th>
                                    <th>Submitted</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                    @php
                                        $recommendationClass = [
                                            'accept' => 'success',
                                            'minor_revision' => 'info',
                                            'major_revision' => 'warning',
                                            'reject' => 'danger'
                                        ][$review->recommendation] ?? 'secondary';
                                    @endphp

                                    <tr>
                                        <td style="max-width: 280px;">
                                            <a href="{{ route('editor.papers.show', $review->assignment->paper) }}"
                                               class="fw-semibold text-decoration-none d-block text-truncate">
                                                {{ $review->assignment->paper->title }}
                                            </a>
                                        </td>

                                        <td>
                                            {{ $review->assignment->reviewer->name }}
                                        </td>

                                        <td>
                                            <span class="badge bg-{{ $recommendationClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="small text-muted lh-sm">
                                                <div>Orig: <strong>{{ $review->originality_score }}/5</strong></div>
                                                <div>Overall: <strong>{{ $review->overall_score }}/5</strong></div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="small">
                                                {{ $review->reviewed_at->format('M d, Y') }}
                                            </span>
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('editor.reviews.show', $review) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $reviews->links() }}
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-2">No Reviews Found</h4>
                        <p class="text-muted mb-0">
                            No reviews have been submitted yet.
                        </p>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
