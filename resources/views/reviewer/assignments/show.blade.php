@extends('layouts.app')

@section('page-title', 'Review Assignment')
@section('page-description', 'Review paper details and submit your evaluation')

@section('page-actions')
    <a href="{{ route('reviewer.assignments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Assignments
    </a>
    
    @if($assignment->status == 'pending' || $assignment->status == 'accepted')
        <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i> Submit Review
        </a>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Assignment Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Assignment Details</h5>
                <div>
                    <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : ($assignment->status == 'accepted' ? 'info' : 'warning') }}">
                        {{ ucfirst($assignment->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar-alt me-2"></i> Timeline</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>Assigned:</th>
                                <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Due Date:</th>
                                <td>
                                    {{ $assignment->due_date->format('M d, Y') }}
                                    @if($assignment->due_date < now() && $assignment->status != 'completed')
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                    @endif
                                </td>
                            </tr>
                            @if($assignment->completed_date)
                                <tr>
                                    <th>Completed:</th>
                                    <td>{{ $assignment->completed_date->format('M d, Y') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2"></i> Assignment Info</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>Paper ID:</th>
                                <td>#{{ $assignment->paper->id }}</td>
                            </tr>
                            <tr>
                                <th>Paper Status:</th>
                                <td>
                                    @include('components.status-badge', ['status' => $assignment->paper->status])
                                </td>
                            </tr>
                            <tr>
                                <th>Assigned by:</th>
                                <td>{{ $assignment->assignedBy->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($assignment->editor_notes)
                    <div class="alert alert-info">
                        <h6><i class="fas fa-sticky-note me-2"></i> Editor Notes</h6>
                        <p class="mb-0">{{ $assignment->editor_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Paper Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Paper Information</h5>
            </div>
            <div class="card-body">
                <h5 class="mb-3">{{ $assignment->paper->title }}</h5>
                
                <div class="mb-4">
                    <h6>Abstract</h6>
                    <p class="text-muted">{{ $assignment->paper->abstract }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Author Information</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                            </div>
                            <div>
                                <p class="mb-1">{{ $assignment->paper->author->name }}</p>
                                <small class="text-muted">{{ $assignment->paper->author->institution }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Keywords</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(explode(',', $assignment->paper->keywords) as $keyword)
                                <span class="badge bg-secondary">{{ trim($keyword) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- File Download -->
                <div class="mt-4 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                            <div class="d-inline-block">
                                <h6 class="mb-1">{{ $assignment->paper->original_filename }}</h6>
                                <small class="text-muted">
                                    Submitted: {{ $assignment->paper->submitted_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-download me-1"></i> Download PDF
                            </a>
                            <a href="{{ route('reviewer.assignments.view-paper', $assignment) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> View Paper
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Form (if not completed) -->
        @if($assignment->status != 'completed' && $assignment->status != 'declined')
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Ready to Review?</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-circle me-2"></i> Important Notes</h6>
                        <ul class="mb-0">
                            <li>This is a double-blind review (author and reviewer identities are hidden)</li>
                            <li>Provide constructive feedback for both author and editor</li>
                            <li>Your review should be completed by {{ $assignment->due_date->format('M d, Y') }}</li>
                            <li>You can save your review as draft and submit later</li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-edit me-2"></i> Start / Continue Review
                        </a>
                        
                        @if($assignment->status == 'pending')
                            <div class="d-flex gap-2">
                                <form action="{{ route('reviewer.assignments.accept', $assignment) }}" method="POST" class="w-50">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check-circle me-2"></i> Accept Assignment
                                    </button>
                                </form>
                                <form action="{{ route('reviewer.assignments.decline', $assignment) }}" method="POST" class="w-50">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times-circle me-2"></i> Decline Assignment
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Submitted Review (if completed) -->
        @if($assignment->review)
            <div class="card mt-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Your Review</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Recommendation</h6>
                            <span class="badge bg-{{ $assignment->review->recommendation == 'accept' ? 'success' : ($assignment->review->recommendation == 'reject' ? 'danger' : 'warning') }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                            </span>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Review Scores</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">Originality: {{ $assignment->review->originality_score }}/5</span>
                                <span class="badge bg-primary">Contribution: {{ $assignment->review->contribution_score }}/5</span>
                                <span class="badge bg-primary">Clarity: {{ $assignment->review->clarity_score }}/5</span>
                                <span class="badge bg-primary">Methodology: {{ $assignment->review->methodology_score }}/5</span>
                                <span class="badge bg-success">Overall: {{ $assignment->review->overall_score }}/5</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Average: {{ number_format($assignment->review->average_score, 1) }}/5</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Comments to Author</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $assignment->review->comments_to_author }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Comments to Editor</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $assignment->review->comments_to_editor }}</p>
                                    @if($assignment->review->is_confidential)
                                        <small class="text-muted">
                                            <i class="fas fa-lock me-1"></i> Confidential
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($assignment->review->attachment_path)
                        <div class="mt-4">
                            <h6>Attachment</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-paperclip fa-2x text-muted me-3"></i>
                                <div>
                                    <p class="mb-1">Additional review file attached</p>
                                    <small class="text-muted">
                                        <a href="#" class="text-decoration-none">
                                            <i class="fas fa-download me-1"></i> Download Attachment
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-4 text-end">
                        <small class="text-muted">
                            Submitted on {{ $assignment->review->reviewed_at->format('M d, Y H:i') }}
                        </small>
                        <div class="mt-2">
                            <a href="{{ route('reviewer.reviews.edit', $assignment->review) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit Review
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Assignment Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i> Assignment Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($assignment->status == 'pending' || $assignment->status == 'accepted')
                        <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Submit Review
                        </a>
                    @endif
                    
                    <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" 
                       class="btn btn-outline-danger">
                        <i class="fas fa-download me-2"></i> Download Paper
                    </a>
                    
                    <a href="{{ route('reviewer.assignments.view-paper', $assignment) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i> View Paper
                    </a>
                    
                    @if($assignment->status == 'pending')
                        <div class="d-flex gap-2">
                            <form action="{{ route('reviewer.assignments.accept', $assignment) }}" method="POST" class="w-50">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check me-2"></i> Accept
                                </button>
                            </form>
                            <form action="{{ route('reviewer.assignments.decline', $assignment) }}" method="POST" class="w-50">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-times me-2"></i> Decline
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    @if($assignment->due_date < now() && $assignment->status != 'completed')
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#extensionModal">
                            <i class="fas fa-clock me-2"></i> Request Extension
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i> Review Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item active">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Assigned</h6>
                            <small class="text-muted">{{ $assignment->assigned_date->format('M d') }}</small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $assignment->status == 'accepted' || $assignment->status == 'completed' ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Accepted</h6>
                            <small class="text-muted">
                                {{ $assignment->status == 'pending' ? 'Pending' : 'Completed' }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $assignment->status == 'completed' ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Review Started</h6>
                            <small class="text-muted">
                                {{ $assignment->review ? 'Completed' : 'Not started' }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $assignment->status == 'completed' ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Review Submitted</h6>
                            <small class="text-muted">
                                {{ $assignment->review ? $assignment->review->reviewed_at->format('M d') : 'Pending' }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $assignment->status == 'completed' ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Completed</h6>
                            <small class="text-muted">
                                {{ $assignment->completed_date ? $assignment->completed_date->format('M d') : 'Pending' }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Due Date Countdown -->
                @if($assignment->status != 'completed')
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6>Due Date</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $assignment->due_date->format('M d, Y') }}</span>
                            @php
                                $daysLeft = $assignment->due_date->diffInDays(now());
                                $isOverdue = $assignment->due_date < now();
                            @endphp
                            <span class="badge bg-{{ $isOverdue ? 'danger' : ($daysLeft <= 3 ? 'warning' : 'success') }}">
                                @if($isOverdue)
                                    {{ abs($daysLeft) }} days overdue
                                @else
                                    {{ $daysLeft }} days left
                                @endif
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Review Guidelines -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-book me-2"></i> Review Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Be objective and constructive
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Focus on scientific merit
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Maintain confidentiality
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Declare conflicts of interest
                    </li>
                    <li>
                        <i class="fas fa-check text-success me-2"></i>
                        Submit before deadline
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Extension Request Modal -->
<div class="modal fade" id="extensionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('reviewer.assignments.request-extension', $assignment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Request Extension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="extension_reason" class="form-label">Reason for Extension</label>
                        <textarea class="form-control" id="extension_reason" name="reason" rows="3" required></textarea>
                        <small class="text-muted">Explain why you need more time for this review</small>
                    </div>
                    <div class="mb-3">
                        <label for="extension_days" class="form-label">Additional Days Needed</label>
                        <select class="form-select" id="extension_days" name="days" required>
                            <option value="3">3 days</option>
                            <option value="7">7 days</option>
                            <option value="14">14 days</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Request Extension</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
        margin-bottom: 15px;
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
        margin-bottom: 3px;
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection