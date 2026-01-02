@extends('layouts.app')

@section('page-title', 'Review Details')
@section('page-description', 'View review details and comments')

@section('page-actions')
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    
    @if(auth()->id() == $review->assignment->reviewer_id)
        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-1"></i> Edit Review
        </a>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Review Details -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Review Details</h5>
                <div>
                    @if($review->is_confidential)
                        <span class="badge bg-danger">
                            <i class="fas fa-lock me-1"></i> Confidential
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Review Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Review Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="120">Reviewer:</th>
                                <td>{{ $review->assignment->reviewer->name }}</td>
                            </tr>
                            <tr>
                                <th>Submitted:</th>
                                <td>{{ $review->reviewed_at->format('F d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Paper:</th>
                                <td>
                                    <a href="{{ route('papers.show', $paper) }}">
                                        {{ $paper->title }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Author:</th>
                                <td>{{ $paper->author->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Scores & Recommendation</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <div class="display-6 fw-bold text-primary">
                                    {{ $review->average_score ?? 'N/A' }}
                                </div>
                                <small class="text-muted">Average Score</small>
                            </div>
                            <div>
                                <span class="badge bg-{{ $review->recommendation == 'accept' ? 'success' : ($review->recommendation == 'reject' ? 'danger' : 'warning') }} fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Score Breakdown -->
                        <div class="row g-2">
                            @foreach([
                                ['label' => 'Originality', 'score' => $review->originality_score],
                                ['label' => 'Contribution', 'score' => $review->contribution_score],
                                ['label' => 'Clarity', 'score' => $review->clarity_score],
                                ['label' => 'Methodology', 'score' => $review->methodology_score],
                                ['label' => 'Overall', 'score' => $review->overall_score]
                            ] as $item)
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small>{{ $item['label'] }}</small>
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $item['score'] ? 'text-warning' : 'text-muted' }} me-1" style="font-size: 12px;"></i>
                                            @endfor
                                            <small class="ms-2">{{ $item['score'] }}/5</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Comments -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Comments to Author</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            {{ $review->comments_to_author }}
                        </div>
                    </div>
                </div>
                
                @if(!$review->is_confidential || auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Comments to Editor</h6>
                        <div class="card {{ $review->is_confidential ? 'border-danger' : 'bg-light' }}">
                            <div class="card-body">
                                {{ $review->comments_to_editor }}
                                @if($review->is_confidential)
                                    <div class="mt-2">
                                        <small class="text-danger">
                                            <i class="fas fa-lock me-1"></i>
                                            These comments are confidential and not shared with the author
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Attachment -->
                @if($review->attachment_path)
                    <div class="mt-4">
                        <h6 class="mb-3">Attachment</h6>
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-paperclip me-2"></i>
                                <span>{{ basename($review->attachment_path) }}</span>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i> Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(auth()->id() == $review->assignment->reviewer_id)
                        <a href="{{ route('reviews.edit', $review) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i> Edit Review
                        </a>
                    @endif
                    
                    <a href="{{ route('papers.show', $paper) }}" class="btn btn-outline-info">
                        <i class="fas fa-file-alt me-2"></i> View Paper
                    </a>
                    
                    @if(auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#sendReminderModal">
                            <i class="fas fa-bell me-2"></i> Send Reminder
                        </button>
                    @endif
                    
                    @if(auth()->user()->hasRole('admin'))
                        <button class="btn btn-outline-danger" onclick="if(confirm('Delete this review?')) document.getElementById('delete-review').submit()">
                            <i class="fas fa-trash me-2"></i> Delete Review
                        </button>
                        
                        <form id="delete-review" action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Assignment Info -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Assignment Info</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="100">Status:</th>
                        <td>
                            <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </td>
                    </tr>
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
                    <tr>
                        <th>Completed:</th>
                        <td>{{ $assignment->completed_date ? $assignment->completed_date->format('M d, Y') : 'Not yet' }}</td>
                    </tr>
                    @if($assignment->editor_notes)
                        <tr>
                            <th>Editor Notes:</th>
                            <td>
                                <small class="text-muted">{{ $assignment->editor_notes }}</small>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <!-- Paper Quick Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i> Paper Quick Info</h6>
            </div>
            <div class="card-body">
                <h6>{{ Str::limit($paper->title, 50) }}</h6>
                <p class="text-muted mb-2">
                    <small>
                        <strong>Author:</strong> {{ $paper->author->name }}<br>
                        <strong>Status:</strong> 
                        @include('components.status-badge', ['status' => $paper->status])
                    </small>
                </p>
                <a href="{{ route('papers.download', $paper) }}" class="btn btn-sm btn-outline-danger w-100">
                    <i class="fas fa-download me-2"></i> Download Paper
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Send Reminder Modal -->
<div class="modal fade" id="sendReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('editor.reviews.assignments.remind', $assignment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reminder_message" class="form-label">Message</label>
                        <textarea class="form-control" id="reminder_message" name="message" rows="3" placeholder="Optional custom message..."></textarea>
                        <small class="text-muted">Leave empty to use default reminder message</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Reminder will be sent to: {{ $assignment->reviewer->email }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Send Reminder</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection