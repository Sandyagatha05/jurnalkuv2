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
<div class="row g-4">

    {{-- MAIN --}}
    <div class="col-lg-8">

        {{-- PAPER INFO --}}
        <div class="card shadow-sm mb-4">
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
                <table class="table table-borderless align-middle">
                    <tr>
                        <th width="160">Title</th>
                        <td>{{ $paper->title }}</td>
                    </tr>
                    <tr>
                        <th>DOI</th>
                        <td>
                            @if($paper->doi)
                                <code>{{ $paper->doi }}</code>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Author</th>
                        <td>
                            <strong>{{ $paper->author->name }}</strong><br>
                            <small class="text-muted">{{ $paper->author->institution }}</small><br>
                            <small class="text-muted">{{ $paper->author->email }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Abstract</th>
                        <td>{{ $paper->abstract }}</td>
                    </tr>
                    <tr>
                        <th>Keywords</th>
                        <td>{{ $paper->keywords }}</td>
                    </tr>
                    <tr>
                        <th>Submitted</th>
                        <td>{{ $paper->submitted_at->format('F d, Y H:i') }}</td>
                    </tr>
                    @if($paper->issue)
                        <tr>
                            <th>Published in</th>
                            <td>
                                <a href="{{ route('editor.issues.show', $paper->issue) }}">
                                    {{ $paper->issue->title }}
                                </a>
                                (Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }})
                            </td>
                        </tr>
                    @endif
                </table>

                <div class="mt-4 p-3 bg-light rounded d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                        <div>
                            <h6 class="mb-0">{{ $paper->original_filename }}</h6>
                            <small class="text-muted">
                                Uploaded: {{ $paper->submitted_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                    <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-danger">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- REVIEWS --}}
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Reviews</h5>
                <span class="badge bg-{{ $completedReviews == $totalReviews ? 'success' : 'warning' }}">
                    {{ $completedReviews }}/{{ $totalReviews }} Completed
                </span>
            </div>

            <div class="card-body">
                @if($paper->reviewAssignments->count() > 0)
                    @php
                        $percentage = $totalReviews > 0 ? ($completedReviews / $totalReviews) * 100 : 0;
                    @endphp

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Review Completion</span>
                            <span>{{ $completedReviews }}/{{ $totalReviews }}</span>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($paper->reviewAssignments as $assignment)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border
                                    {{ $assignment->status == 'completed' ? 'border-success' : ($assignment->status == 'pending' ? 'border-warning' : 'border-secondary') }}">
                                    <div class="card-body">
                                        <h6 class="mb-1">{{ $assignment->reviewer->name }}</h6>
                                        <small class="text-muted">
                                            {{ $assignment->reviewer->institution }}<br>
                                            {{ $assignment->reviewer->email }}
                                        </small>

                                        <div class="mt-2">
                                            <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : ($assignment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </div>

                                        <div class="mt-3 d-flex gap-2">
                                            @if($assignment->status == 'pending')
                                                <button class="btn btn-sm btn-outline-warning"
                                                        onclick="sendReminder({{ $assignment->id }})">
                                                    <i class="fas fa-bell me-1"></i> Reminder
                                                </button>
                                            @endif

                                            @if($assignment->review)
                                                <a href="{{ route('editor.reviews.show', $assignment->review) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> View Review
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No reviewers assigned yet.</p>
                        <a href="{{ route('editor.papers.assign-reviewers', $paper) }}" class="btn btn-warning">
                            Assign Reviewers
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- SIDEBAR --}}
    <div class="col-lg-4">

        {{-- MANAGEMENT --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i> Paper Management</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('editor.papers.update-status', $paper) }}" method="POST" onsubmit="event.preventDefault(); 
                customConfirm('Are you sure you want to update the status?').then(result => {if(result) this.submit(); })">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <select class="form-select" name="status">
                            <option value="submitted" {{ $paper->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ $paper->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="accepted" {{ $paper->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $paper->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="revision_minor" {{ $paper->status == 'revision_minor' ? 'selected' : '' }}>Minor Revision</option>
                            <option value="revision_major" {{ $paper->status == 'revision_major' ? 'selected' : '' }}>Major Revision</option>
                            <option value="published" {{ $paper->status == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i> Timeline</h6>
            </div>
            <div class="card-body">

                <div class="timeline-pro">
                    <div class="timeline-step {{ $paper->submitted_at ? 'active' : '' }}">
                        <div class="icon"><i class="fas fa-upload"></i></div>
                        <div>
                            <h6>Submitted</h6>
                            <small>{{ $paper->submitted_at->format('M d, Y') }}</small>
                        </div>
                    </div>

                    <div class="timeline-step {{ $paper->reviewAssignments->count() > 0 ? 'active' : '' }}">
                        <div class="icon"><i class="fas fa-user-check"></i></div>
                        <div>
                            <h6>Review Assigned</h6>
                            <small>
                                @if($paper->reviewAssignments->count() > 0)
                                    {{ $paper->reviewAssignments->first()->assigned_date->format('M d, Y') }}
                                @else
                                    Pending
                                @endif
                            </small>
                        </div>
                    </div>

                    <div class="timeline-step {{ $completedReviews > 0 ? 'active' : '' }}">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <div>
                            <h6>Reviews Completed</h6>
                            <small>{{ $completedReviews }}/{{ $totalReviews }}</small>
                        </div>
                    </div>

                    <div class="timeline-step {{ $paper->status == 'accepted' ? 'active' : '' }}">
                        <div class="icon"><i class="fas fa-gavel"></i></div>
                        <div>
                            <h6>Decision Made</h6>
                            <small>{{ $paper->status == 'accepted' ? 'Accepted' : 'Pending' }}</small>
                        </div>
                    </div>

                    <div class="timeline-step {{ $paper->published_at ? 'active' : '' }}">
                        <div class="icon"><i class="fas fa-book-open"></i></div>
                        <div>
                            <h6>Published</h6>
                            <small>
                                {{ $paper->published_at ? $paper->published_at->format('M d, Y') : 'Pending' }}
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
.timeline-pro {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.timeline-step {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    opacity: .4;
}
.timeline-step.active {
    opacity: 1;
}
.timeline-step .icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.timeline-step.active .icon {
    background: #4361ee;
    color: #fff;
}
.timeline-step h6 {
    margin-bottom: 2px;
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
        .then(r => r.json())
        .then(d => {
            if (d.success) alert('Reminder sent successfully!');
        });
    }
}
</script>
@endpush
