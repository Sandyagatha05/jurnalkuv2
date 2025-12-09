@extends('layouts.app')

@section('page-title', 'Paper Details')
@section('page-description', 'Review paper details and manage review process')

@section('page-actions')
    <a href="{{ route('editor.papers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Papers
    </a>
    
    @if($paper->status == 'submitted')
        <a href="{{ route('editor.papers.assign-reviewers', $paper) }}" class="btn btn-warning">
            <i class="fas fa-user-plus me-1"></i> Assign Reviewers
        </a>
    @endif
    
    @if($paper->status == 'under_review' && $completedReviews == $totalReviews)
        <a href="{{ route('editor.papers.decision', $paper) }}" class="btn btn-success">
            <i class="fas fa-gavel me-1"></i> Make Decision
        </a>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Paper Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Paper Information</h5>
                <div>
                    @include('components.status-badge', ['status' => $paper->status])
                    @if($paper->revision_count > 0)
                        <span class="badge bg-info ms-2">Revision {{ $paper->revision_count }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Title:</th>
                        <td>{{ $paper->title }}</td>
                    </tr>
                    <tr>
                        <th>DOI:</th>
                        <td>
                            @if($paper->doi)
                                <code>{{ $paper->doi }}</code>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Author:</th>
                        <td>
                            {{ $paper->author->name }}
                            <br>
                            <small class="text-muted">{{ $paper->author->institution }}</small>
                            <br>
                            <small class="text-muted">{{ $paper->author->email }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Abstract:</th>
                        <td>{{ $paper->abstract }}</td>
                    </tr>
                    <tr>
                        <th>Keywords:</th>
                        <td>{{ $paper->keywords }}</td>
                    </tr>
                    <tr>
                        <th>Submitted:</th>
                        <td>{{ $paper->submitted_at->format('F d, Y H:i') }}</td>
                    </tr>
                    @if($paper->issue)
                        <tr>
                            <th>Published in:</th>
                            <td>
                                <a href="{{ route('editor.issues.show', $paper->issue) }}">
                                    {{ $paper->issue->title }}
                                </a>
                                (Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }})
                            </td>
                        </tr>
                    @endif
                </table>
                
                <!-- File Download -->
                <div class="mt-4 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                            <div class="d-inline-block">
                                <h6 class="mb-1">{{ $paper->original_filename }}</h6>
                                <small class="text-muted">Uploaded: {{ $paper->submitted_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-danger">
                                <i class="fas fa-download me-1"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    Reviews 
                    <span class="badge bg-{{ $completedReviews == $totalReviews ? 'success' : 'warning' }}">
                        {{ $completedReviews }}/{{ $totalReviews }} Completed
                    </span>
                </h5>
            </div>
            <div class="card-body">
                @if($paper->reviewAssignments->count() > 0)
                    <!-- Review Progress -->
                    <div class="mb-4">
                        @php
                            $percentage = $totalReviews > 0 ? ($completedReviews / $totalReviews) * 100 : 0;
                        @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span>Review Completion</span>
                            <span>{{ $completedReviews }}/{{ $totalReviews }} reviews completed</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    
                    <!-- Reviewers List -->
                    <h6 class="mb-3">Assigned Reviewers</h6>
                    <div class="row">
                        @foreach($paper->reviewAssignments as $assignment)
                            <div class="col-md-6 mb-3">
                                <div class="card border {{ $assignment->status == 'completed' ? 'border-success' : ($assignment->status == 'pending' ? 'border-warning' : 'border-secondary') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">{{ $assignment->reviewer->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $assignment->reviewer->institution }}
                                                    <br>
                                                    {{ $assignment->reviewer->email }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : ($assignment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                                @if($assignment->due_date && $assignment->status == 'pending')
                                                    <br>
                                                    <small class="text-muted d-block mt-1">
                                                        Due: {{ $assignment->due_date->format('M d') }}
                                                        @if($assignment->due_date < now())
                                                            <span class="text-danger">
                                                                <i class="fas fa-exclamation-circle"></i> Overdue
                                                            </span>
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($assignment->editor_notes)
                                            <div class="alert alert-info py-2 my-2">
                                                <small>
                                                    <strong>Editor Notes:</strong> {{ $assignment->editor_notes }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        @if($assignment->review)
                                            <div class="mt-3 border-top pt-3">
                                                <strong>Recommendation:</strong>
                                                <span class="badge bg-{{ $assignment->review->recommendation == 'accept' ? 'success' : ($assignment->review->recommendation == 'reject' ? 'danger' : 'warning') }} ms-2">
                                                    {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                                </span>
                                                
                                                <div class="mt-2">
                                                    <strong>Scores:</strong>
                                                    <div class="d-flex gap-2 mt-1">
                                                        <span class="badge bg-secondary">Originality: {{ $assignment->review->originality_score }}/5</span>
                                                        <span class="badge bg-secondary">Contribution: {{ $assignment->review->contribution_score }}/5</span>
                                                        <span class="badge bg-secondary">Overall: {{ $assignment->review->overall_score }}/5</span>
                                                    </div>
                                                </div>
                                                
                                                @if(!$assignment->review->is_confidential)
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-info" type="button" 
                                                                data-bs-toggle="collapse" data-bs-target="#reviewComments{{ $assignment->id }}">
                                                            <i class="fas fa-comment me-1"></i> View Comments
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="collapse mt-2" id="reviewComments{{ $assignment->id }}">
                                                        <div class="card card-body">
                                                            <h6>Comments to Author:</h6>
                                                            <p>{{ $assignment->review->comments_to_author }}</p>
                                                            
                                                            <h6>Comments to Editor:</h6>
                                                            <p>{{ $assignment->review->comments_to_editor }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            @if($assignment->status == 'pending')
                                                <button class="btn btn-sm btn-outline-warning" 
                                                        onclick="sendReminder({{ $assignment->id }})">
                                                    <i class="fas fa-bell me-1"></i> Send Reminder
                                                </button>
                                            @endif
                                            
                                            @if($assignment->review)
                                                <a href="{{ route('editor.reviews.show', $assignment->review) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> View Full Review
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">No reviewers assigned yet.</p>
                        <a href="{{ route('editor.papers.assign-reviewers', $paper) }}" class="btn btn-warning">
                            <i class="fas fa-user-plus me-2"></i> Assign Reviewers
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Status Management -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i> Paper Management</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('editor.papers.update-status', $paper) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Update Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="submitted" {{ $paper->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ $paper->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="accepted" {{ $paper->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $paper->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="revision_minor" {{ $paper->status == 'revision_minor' ? 'selected' : '' }}>Minor Revision</option>
                            <option value="revision_major" {{ $paper->status == 'revision_major' ? 'selected' : '' }}>Major Revision</option>
                            <option value="published" {{ $paper->status == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
                
                <hr>
                
                <div class="d-grid gap-2">
                    @if($paper->status == 'submitted')
                        <a href="{{ route('editor.papers.assign-reviewers', $paper) }}" class="btn btn-warning">
                            <i class="fas fa-user-plus me-2"></i> Assign Reviewers
                        </a>
                    @endif
                    
                    @if($paper->status == 'under_review' && $completedReviews == $totalReviews)
                        <a href="{{ route('editor.papers.decision', $paper) }}" class="btn btn-success">
                            <i class="fas fa-gavel me-2"></i> Make Final Decision
                        </a>
                    @endif
                    
                    @if($paper->status == 'accepted' && !$paper->issue_id)
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#assignIssueModal">
                            <i class="fas fa-book me-2"></i> Assign to Issue
                        </button>
                    @endif
                    
                    <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-danger">
                        <i class="fas fa-download me-2"></i> Download Paper
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i> Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $paper->submitted_at ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Submitted</h6>
                            <small class="text-muted">
                                {{ $paper->submitted_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $paper->reviewAssignments->count() > 0 ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Review Assigned</h6>
                            <small class="text-muted">
                                @if($paper->reviewAssignments->count() > 0)
                                    {{ $paper->reviewAssignments->first()->assigned_date->format('M d, Y') }}
                                @else
                                    Pending
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $completedReviews > 0 ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Reviews Completed</h6>
                            <small class="text-muted">
                                {{ $completedReviews }}/{{ $totalReviews }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $paper->status == 'accepted' ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Decision Made</h6>
                            <small class="text-muted">
                                @if($paper->status == 'accepted')
                                    Accepted
                                @else
                                    Pending
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $paper->published_at ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Published</h6>
                            <small class="text-muted">
                                @if($paper->published_at)
                                    {{ $paper->published_at->format('M d, Y') }}
                                @else
                                    Pending
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign to Issue Modal -->
<div class="modal fade" id="assignIssueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('editor.papers.assign-issue', $paper) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Paper to Issue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="issue_id" class="form-label">Select Issue</label>
                        <select class="form-select" id="issue_id" name="issue_id" required>
                            <option value="">Select an issue...</option>
                            @foreach($issues as $issue)
                                <option value="{{ $issue->id }}">
                                    Vol. {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }}) - {{ $issue->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="page_from" class="form-label">Page From</label>
                            <input type="number" class="form-control" id="page_from" name="page_from">
                        </div>
                        <div class="col-md-6">
                            <label for="page_to" class="form-label">Page To</label>
                            <input type="number" class="form-control" id="page_to" name="page_to">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign to Issue</button>
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

@push('scripts')
<script>
    function sendReminder(assignmentId) {
        if (confirm('Send reminder to reviewer?')) {
            fetch(`/editor/reviews/assignments/${assignmentId}/remind`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reminder sent successfully!');
                }
            });
        }
    }
</script>
@endpush