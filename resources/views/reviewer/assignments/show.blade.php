@extends('layouts.app')

@section('page-title', 'Review Assignment')
@section('page-description', 'Review paper details and submit your evaluation')

{{-- RULE: page-actions MUST be empty, all actions moved to content --}}
@section('page-actions')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">

        {{-- Top Action --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Review Assignment</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('reviewer.assignments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Assignments
                </a>
                
                @if($assignment->status == 'pending' || $assignment->status == 'accepted')
                    <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Submit Review
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            {{-- MAIN CONTENT --}}
            <div class="col-lg-8 mb-4">
                {{-- Assignment & Paper Information Combined Style --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Assignment Details</h5>
                            <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : ($assignment->status == 'accepted' ? 'info' : 'warning') }}">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </div>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <th width="160">Paper Title</th>
                                <td><strong>{{ $assignment->paper->title }}</strong></td>
                            </tr>
                            <tr>
                                <th>Paper ID</th>
                                <td>#{{ $assignment->paper->id }}</td>
                            </tr>
                            <tr>
                                <th>Abstract</th>
                                <td>{{ $assignment->paper->abstract }}</td>
                            </tr>
                            <tr>
                                <th>Keywords</th>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(explode(',', $assignment->paper->keywords) as $keyword)
                                            <span class="badge bg-secondary small">{{ trim($keyword) }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Assigned By</th>
                                <td>{{ $assignment->assignedBy->name }}</td>
                            </tr>
                            @if($assignment->editor_notes)
                            <tr>
                                <th>Editor Notes</th>
                                <td>
                                    <div class="alert alert-info py-2 px-3 mb-0 small">
                                        <i class="fas fa-sticky-note me-1"></i> {{ $assignment->editor_notes }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </table>

                        {{-- File Download Section --}}
                        <div class="bg-light rounded p-3 mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                    <div>
                                        <h6 class="mb-1">{{ $assignment->paper->original_filename }}</h6>
                                        <small class="text-muted">
                                            Submitted {{ $assignment->paper->submitted_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" 
                                       class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                    <a href="{{ route('reviewer.assignments.view-paper', $assignment) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- READY TO REVIEW / ACTIONS --}}
                @if($assignment->status != 'completed' && $assignment->status != 'declined')
                    <div class="card shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="border-bottom pb-2 mb-4">Ready to Review?</h5>
                            
                            <div class="alert alert-warning small mb-4">
                                <h6 class="small fw-bold"><i class="fas fa-exclamation-circle me-1"></i> Important Notes:</h6>
                                <ul class="mb-0">
                                    <li>Double-blind review (author and reviewer identities are hidden).</li>
                                    <li>Review deadline: <strong>{{ $assignment->due_date->format('M d, Y') }}</strong>.</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary btn-lg py-3 fw-bold">
                                    <i class="fas fa-edit me-2"></i> Start / Continue Review Evaluation
                                </a>
                                
                                @if($assignment->status == 'pending')
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <form action="{{ route('reviewer.assignments.accept', $assignment) }}" method="POST" onsubmit="event.preventDefault();
                                            customConfirm('Are you sure you want to accept this assignment?').then(result => {if(result) this.submit(); });">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100 fw-bold">
                                                    <i class="fas fa-check-circle me-1"></i> Accept
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-6">
                                            <form action="{{ route('reviewer.assignments.decline', $assignment) }}" method="POST" onsubmit="event.preventDefault();
                                            customConfirm('Are you sure you want to decline this assignment?').then(result => {if(result) this.submit(); });">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100 fw-bold">
                                                    <i class="fas fa-times-circle me-1"></i> Decline
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- SUBMITTED REVIEW RESULT --}}
                @if($assignment->review)
                    <div class="card shadow-sm border-success">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                <h5 class="mb-0 text-success"><i class="fas fa-check-circle me-2"></i>Your Evaluation Result</h5>
                                <a href="{{ route('reviewer.reviews.edit', $assignment->review) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Edit Review
                                </a>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <h6 class="small text-muted fw-bold">Recommendation:</h6>
                                    <span class="badge bg-{{ $assignment->review->recommendation == 'accept' ? 'success' : ($assignment->review->recommendation == 'reject' ? 'danger' : 'warning') }} fs-6">
                                        {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="small text-muted fw-bold">Overall Score:</h6>
                                    <div class="h4 mb-0 text-primary">{{ $assignment->review->overall_score }} / 5</div>
                                </div>
                            </div>

                            <h6 class="small text-muted fw-bold mb-2">Scores:</h6>
                            <div class="row g-2 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center bg-light small">
                                        Originality: <strong>{{ $assignment->review->originality_score }}</strong>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center bg-light small">
                                        Contribution: <strong>{{ $assignment->review->contribution_score }}</strong>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center bg-light small">
                                        Clarity: <strong>{{ $assignment->review->clarity_score }}</strong>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-2 text-center bg-light small">
                                        Method: <strong>{{ $assignment->review->methodology_score }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="small text-muted fw-bold mb-1">Comments to Author:</h6>
                                <div class="p-3 bg-light rounded small">{{ $assignment->review->comments_to_author }}</div>
                            </div>

                            @if($assignment->review->attachment_path)
                                <div class="mt-3 p-2 border rounded d-inline-block small">
                                    <i class="fas fa-paperclip me-2"></i> Review Attachment Included
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4 mb-4">
                {{-- TIMELINE --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="mb-4">
                            <i class="fas fa-history me-2"></i> Review Timeline
                        </h6>

                        <div class="timeline">
                            <div class="timeline-item active">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Assigned</h6>
                                    <small class="text-muted">{{ $assignment->assigned_date->format('M d, Y') }}</small>
                                </div>
                            </div>

                            <div class="timeline-item {{ $assignment->status != 'pending' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Accepted</h6>
                                    <small class="text-muted">
                                        {{ $assignment->status == 'pending' ? 'Waiting response' : 'Accepted' }}
                                    </small>
                                </div>
                            </div>

                            <div class="timeline-item {{ $assignment->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Submitted</h6>
                                    <small class="text-muted">
                                        {{ $assignment->completed_date ? $assignment->completed_date->format('M d, Y') : 'Pending evaluation' }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- DEADLINE COUNTDOWN --}}
                        @if($assignment->status != 'completed')
                            @php
                                $daysLeft = (int) now()->diffInDays($assignment->due_date, false);
                                $isOverdue = $assignment->due_date < now();
                            @endphp
                            <div class="mt-4 p-3 bg-light rounded text-center border">
                                <h6 class="small fw-bold text-muted text-uppercase mb-1">Due Date</h6>
                                <div class="h5 mb-1">{{ $assignment->due_date->format('M d, Y') }}</div>
                                <span class="badge bg-{{ $isOverdue ? 'danger' : ($daysLeft <= 3 ? 'warning' : 'success') }}">
                                    @if($isOverdue)
                                        {{ abs($daysLeft) }} Days Overdue
                                    @elseif($daysLeft == 0)
                                        Due Today
                                    @else
                                        {{ $daysLeft }} Days Left
                                    @endif
                                </span>
                                @if($isOverdue && $assignment->status != 'completed')
                                    <button class="btn btn-link btn-sm d-block mx-auto mt-2 text-decoration-none" data-bs-toggle="modal" data-bs-target="#extensionModal">
                                        Request Extension
                                    </button> 
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- GUIDELINES --}}
                <div class="card shadow-sm">
                    <div class="card-body p-4 small">
                        <h6 class="mb-3"><i class="fas fa-book me-2"></i>Reviewing Task</h6>
                        <ul class="list-unstyled mb-0 text-muted">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Be objective and constructive</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Focus on scientific merit</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Maintain confidentiality</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- EXTENSION MODAL --}}
<div class="modal fade" id="extensionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('reviewer.assignments.request-extension', $assignment) }}" method="POST" onsubmit="event.preventDefault();
                customConfirm('Are you sure you want to request an extention?').then(result => {if(result) this.submit(); });">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Request Extension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Reason for Extension</label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Additional Days</label>
                        <select class="form-select" name="days" required>
                            <option value="3">3 Days</option>
                            <option value="7">7 Days</option>
                            <option value="14">14 Days</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- TIMELINE STYLE (ALIGNED WITH PAPER DETAILS) --}}
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #dee2e6;
    border: 3px solid white;
}
.timeline-item.active .timeline-marker {
    background-color: #4361ee;
}
.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}
</style>
@endsection