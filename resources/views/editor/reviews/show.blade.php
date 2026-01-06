@extends('layouts.app')

@section('page-title', 'Review Details')
@section('page-description', 'View review details and comments')

@section('page-actions')
    <a href="{{ route('editor.reviews.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('content')
<div class="row">

    {{-- ================= LEFT CONTENT ================= --}}
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Review Details</h5>

                @if($review->is_confidential)
                    <span class="badge bg-danger">
                        <i class="fas fa-lock me-1"></i> Confidential
                    </span>
                @endif
            </div>

            <div class="card-body">

                {{-- Review Summary --}}
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
                                    <a href="{{ route('editor.papers.show', $paper) }}">
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
                            <div class="me-3 text-center">
                                <div class="display-6 fw-bold text-primary">
                                    {{ $review->average_score ?? 'N/A' }}
                                </div>
                                <small class="text-muted">Average Score</small>
                            </div>

                            <span class="badge bg-{{ 
                                $review->recommendation === 'accept' ? 'success' :
                                ($review->recommendation === 'reject' ? 'danger' : 'warning')
                            }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                            </span>
                        </div>

                        {{-- Score Breakdown --}}
                        <div class="row g-2">
                            @foreach ([
                                ['label'=>'Originality','score'=>$review->originality_score],
                                ['label'=>'Contribution','score'=>$review->contribution_score],
                                ['label'=>'Clarity','score'=>$review->clarity_score],
                                ['label'=>'Methodology','score'=>$review->methodology_score],
                                ['label'=>'Overall','score'=>$review->overall_score],
                            ] as $item)
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small>{{ $item['label'] }}</small>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $item['score'] ? 'text-warning' : 'text-muted' }}"
                                                   style="font-size:12px;"></i>
                                            @endfor
                                            <small class="ms-2">{{ $item['score'] }}/5</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Comments to Author --}}
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Comments to Author</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            {{ $review->comments_to_author }}
                        </div>
                    </div>
                </div>

                {{-- Comments to Editor --}}
                @if(!$review->is_confidential || auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Comments to Editor</h6>
                        <div class="card {{ $review->is_confidential ? 'border-danger' : 'bg-light' }}">
                            <div class="card-body">
                                {{ $review->comments_to_editor }}

                                @if($review->is_confidential)
                                    <small class="text-danger d-block mt-2">
                                        <i class="fas fa-lock me-1"></i>
                                        Confidential — not visible to author
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Attachment --}}
                @if($review->attachment_path)
                    <div class="mt-4">
                        <h6 class="mb-3">Attachment</h6>
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-paperclip me-2"></i>
                                {{ basename($review->attachment_path) }}
                            </span>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ================= RIGHT SIDEBAR ================= --}}
    <div class="col-lg-4 mb-4">

        {{-- Actions --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i> Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">

                @if(auth()->id() === $review->assignment->reviewer_id)
                    <a href="{{ route('reviews.edit', $review) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i> Edit Review
                    </a>
                @endif

                <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-info">
                    <i class="fas fa-file-alt me-2"></i> View Paper
                </a>

                @if(auth()->user()->hasRole('editor') || auth()->user()->hasRole('admin'))
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#sendReminderModal">
                        <i class="fas fa-bell me-2"></i> Send Reminder
                    </button>
                @endif

                @if(auth()->user()->hasRole('admin'))
                    <button class="btn btn-outline-danger"
                            onclick="confirm('Delete this review?') && document.getElementById('delete-review').submit()">
                        <i class="fas fa-trash me-2"></i> Delete Review
                    </button>

                    <form id="delete-review" action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>

        {{-- Assignment Info --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Assignment Info</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-{{ $assignment->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Assigned:</th>
                        <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Due:</th>
                        <td>
                            {{ $assignment->due_date->format('M d, Y') }}
                            @if($assignment->due_date < now() && $assignment->status !== 'completed')
                                <span class="badge bg-danger ms-2">Overdue</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Completed:</th>
                        <td>{{ $assignment->completed_date?->format('M d, Y') ?? 'Not yet' }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ================= MODAL ================= --}}
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
                    Send reminder to reviewer?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Send</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
