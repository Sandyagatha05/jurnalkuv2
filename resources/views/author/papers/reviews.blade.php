@extends('layouts.app')

@section('page-title', 'Paper Reviews')
@section('page-description', 'View reviewer feedback for your paper')

{{-- RULE: page-actions MUST be empty --}}
@section('page-actions')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">

        {{-- Top Action --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Reviewer Feedback</h4>
            <div class="d-flex gap-2">
                @if(in_array($paper->status, ['revision_minor', 'revision_major']))
                    <a href="{{ route('author.papers.revision', $paper) }}" class="btn btn-warning">
                        <i class="fas fa-redo me-1"></i> Submit Revision
                    </a>
                @endif

                <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Paper
                </a>
            </div>
        </div>

        {{-- MAIN CARD --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1">{{ $paper->title }}</h5>
                        <small class="text-muted">
                            Submitted {{ $paper->submitted_at->format('F d, Y') }}
                            @if($paper->revision_count > 0)
                                · Revisions {{ $paper->revision_count }}
                            @endif
                        </small>
                    </div>
                    @include('components.status-badge', ['status' => $paper->status])
                </div>

                {{-- Info --}}
                <div class="alert alert-info mb-4">
                    <h6 class="mb-1">
                        <i class="fas fa-info-circle me-2"></i> About Reviews
                    </h6>
                    <p class="mb-0">
                        This section displays non-confidential reviewer feedback.
                        Use the comments below to improve your paper if revisions are requested.
                    </p>
                </div>

                @if($reviews->count() > 0)

                    {{-- Reviews --}}
                    <h6 class="mb-3 border-bottom pb-2">Reviewer Comments</h6>

                    @foreach($reviews as $review)
                        <div class="card border shadow-sm mb-4">
                            <div class="card-body p-4">

                                {{-- Review Header --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1">
                                            Review {{ $loop->iteration }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $review->assignment->reviewer->name }}
                                            @if($review->assignment->reviewer->institution)
                                                · {{ $review->assignment->reviewer->institution }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ 
                                            $review->recommendation == 'accept' ? 'success' :
                                            ($review->recommendation == 'reject' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $review->reviewed_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>

                                {{-- Scores --}}
                                @if($review->overall_score)
                                    <div class="mb-4">
                                        <h6 class="mb-2">Review Scores (1–5)</h6>
                                        <div class="row g-2">
                                            @if($review->originality_score)
                                                <div class="col-6 col-md-3">
                                                    <div class="bg-light rounded p-2 text-center">
                                                        <small class="text-muted d-block">Originality</small>
                                                        <strong>{{ $review->originality_score }}/5</strong>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($review->contribution_score)
                                                <div class="col-6 col-md-3">
                                                    <div class="bg-light rounded p-2 text-center">
                                                        <small class="text-muted d-block">Contribution</small>
                                                        <strong>{{ $review->contribution_score }}/5</strong>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($review->clarity_score)
                                                <div class="col-6 col-md-3">
                                                    <div class="bg-light rounded p-2 text-center">
                                                        <small class="text-muted d-block">Clarity</small>
                                                        <strong>{{ $review->clarity_score }}/5</strong>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-6 col-md-3">
                                                <div class="bg-primary text-white rounded p-2 text-center">
                                                    <small class="d-block">Overall</small>
                                                    <strong>{{ $review->overall_score }}/5</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Comments --}}
                                <div class="mb-4">
                                    <h6 class="border-bottom pb-2 mb-3">
                                        Comments to Author
                                    </h6>
                                    <div class="bg-light rounded p-3">
                                        {!! nl2br(e($review->comments_to_author)) !!}
                                    </div>
                                </div>

                                {{-- Recommendation Alert --}}
                                @if(in_array($review->recommendation, ['minor_revision', 'major_revision']))
                                    <div class="alert alert-warning mb-0">
                                        <strong>
                                            {{ $review->recommendation == 'minor_revision'
                                                ? 'Minor Revision Required'
                                                : 'Major Revision Required' }}
                                        </strong>
                                        <p class="mb-0">
                                            The reviewer requests revisions to your paper.
                                        </p>
                                    </div>
                                @elseif($review->recommendation == 'accept')
                                    <div class="alert alert-success mb-0">
                                        <strong>Paper Accepted</strong>
                                        <p class="mb-0">
                                            The reviewer recommends acceptance.
                                        </p>
                                    </div>
                                @elseif($review->recommendation == 'reject')
                                    <div class="alert alert-danger mb-0">
                                        <strong>Paper Rejected</strong>
                                        <p class="mb-0">
                                            The reviewer does not recommend publication.
                                        </p>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">No Reviews Available</h5>
                        <p class="text-muted mb-4">
                            @if($paper->status == 'submitted')
                                Your paper has not entered the review phase.
                            @elseif($paper->status == 'under_review')
                                Reviews will appear once reviewers complete their evaluation.
                            @else
                                No non-confidential reviews are available.
                            @endif
                        </p>
                        <a href="{{ route('author.papers.show', $paper) }}"
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Paper
                        </a>
                    </div>
                @endif

            </div>
        </div>

        {{-- Review Process Info --}}
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h6 class="mb-3">
                    <i class="fas fa-question-circle me-2"></i> About the Review Process
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li><strong>Accept:</strong> Ready for publication</li>
                            <li><strong>Minor Revision:</strong> Small improvements needed</li>
                            <li><strong>Major Revision:</strong> Significant changes needed</li>
                            <li><strong>Reject:</strong> Not suitable for publication</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li>Address reviewer comments carefully</li>
                            <li>Submit revision if requested</li>
                            <li>Monitor paper status regularly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
