@extends('layouts.app')

@section('page-title', 'Paper Reviews')
@section('page-description', 'View reviewer feedback for your paper')

@section('page-actions')
    <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Paper
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reviewer Feedback</h5>
                    <div>
                        @include('components.status-badge', ['status' => $paper->status])
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <h6><i class="fas fa-info-circle me-2"></i> About Reviews</h6>
                    <p class="mb-0">
                        This section shows feedback from reviewers. Only non-confidential reviews are displayed.
                        Use this feedback to improve your paper if revisions are requested.
                    </p>
                </div>

                @if($reviews->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">Paper Information</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <h5>{{ $paper->title }}</h5>
                                <p class="text-muted mb-0">
                                    Submitted: {{ $paper->submitted_at->format('F d, Y') }}
                                    @if($paper->revision_count > 0)
                                        | Revisions: {{ $paper->revision_count }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="{{ route('author.papers.download', $paper) }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-download me-1"></i> Download Paper
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Reviews List -->
                    <h6 class="mb-3">Reviewer Comments</h6>
                    
                    @foreach($reviews as $review)
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Review {{ $loop->iteration }}</h6>
                                        <small class="text-muted">
                                            Reviewer: {{ $review->assignment->reviewer->name }}
                                            @if($review->assignment->reviewer->institution)
                                                ({{ $review->assignment->reviewer->institution }})
                                            @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $review->recommendation == 'accept' ? 'success' : ($review->recommendation == 'reject' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $review->reviewed_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <!-- Scores -->
                                @if($review->overall_score)
                                    <div class="mb-4">
                                        <h6 class="mb-2">Review Scores (1-5 scale)</h6>
                                        <div class="row g-2">
                                            @if($review->originality_score)
                                                <div class="col-6 col-md-3">
                                                    <div class="bg-light p-2 rounded text-center">
                                                        <small class="text-muted d-block">Originality</small>
                                                        <span class="fw-bold">{{ $review->originality_score }}/5</span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($review->contribution_score)
                                                <div class="col-6 col-md-3">
                                                    <div class="bg-light p-2 rounded text-center">
                                                        <small class="text-muted d-block">Contribution</small>
                                                        <span class="fw-bold">{{ $review->contribution_score }}/5</span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($review->clarity_score)
                                                <div class="col-6 col-md-3">
                                                    <div class="bg-light p-2 rounded text-center">
                                                        <small class="text-muted d-block">Clarity</small>
                                                        <span class="fw-bold">{{ $review->clarity_score }}/5</span>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($review->overall_score)
                                                <div class="col-6 col-md-3">
                                                    <div class="bg-primary text-white p-2 rounded text-center">
                                                        <small class="d-block">Overall</small>
                                                        <span class="fw-bold">{{ $review->overall_score }}/5</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Comments to Author -->
                                <div class="mb-4">
                                    <h6 class="border-bottom pb-2 mb-3">Comments to Author</h6>
                                    <div class="bg-light p-3 rounded">
                                        {!! nl2br(e($review->comments_to_author)) !!}
                                    </div>
                                </div>

                                <!-- Additional Notes -->
                                @if($review->recommendation == 'minor_revision' || $review->recommendation == 'major_revision')
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-exclamation-triangle me-2"></i> 
                                            {{ $review->recommendation == 'minor_revision' ? 'Minor Revision' : 'Major Revision' }} Required
                                        </h6>
                                        <p class="mb-0">
                                            The reviewer has requested revisions to your paper. 
                                            @if(in_array($paper->status, ['revision_minor', 'revision_major']))
                                                You can submit a revised version from the paper details page.
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                @if($review->recommendation == 'accept')
                                    <div class="alert alert-success">
                                        <h6><i class="fas fa-check-circle me-2"></i> Paper Accepted</h6>
                                        <p class="mb-0">The reviewer recommends acceptance of your paper.</p>
                                    </div>
                                @endif

                                @if($review->recommendation == 'reject')
                                    <div class="alert alert-danger">
                                        <h6><i class="fas fa-times-circle me-2"></i> Paper Rejected</h6>
                                        <p class="mb-0">The reviewer does not recommend publication.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        @if(in_array($paper->status, ['revision_minor', 'revision_major']))
                            <a href="{{ route('author.papers.revision', $paper) }}" class="btn btn-warning">
                                <i class="fas fa-redo me-2"></i> Submit Revision
                            </a>
                        @endif
                        
                        <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-alt me-2"></i> Back to Paper Details
                        </a>
                    </div>

                @else
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No Reviews Available</h4>
                        <p class="text-muted mb-4">
                            @if($paper->status == 'submitted')
                                Your paper is still in the initial submission phase.
                            @elseif($paper->status == 'under_review')
                                Your paper is currently under review. Reviews will appear here once completed.
                            @else
                                No non-confidential reviews are available for this paper.
                            @endif
                        </p>
                        <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Paper
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Review Process Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i> About the Review Process</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Review Types</h6>
                        <ul class="mb-0">
                            <li><strong>Accept:</strong> Paper is ready for publication</li>
                            <li><strong>Minor Revision:</strong> Small changes needed</li>
                            <li><strong>Major Revision:</strong> Significant changes needed</li>
                            <li><strong>Reject:</strong> Paper not suitable for publication</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Next Steps</h6>
                        <ul class="mb-0">
                            <li>Address reviewer comments in your revision</li>
                            <li>Submit revised version if requested</li>
                            <li>Contact editor if you have questions</li>
                            <li>Check paper status regularly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .review-score {
        border-left: 4px solid #4361ee;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    
    .review-comment {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .review-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 5px 5px 0 0;
    }
</style>
@endsection