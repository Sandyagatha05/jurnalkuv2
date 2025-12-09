@extends('layouts.app')

@section('page-title', 'Paper Details')
@section('page-description', 'View paper details and review status')

@section('page-actions')
    <a href="{{ route('author.papers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Papers
    </a>
    
    @if($paper->status == 'submitted')
        <a href="{{ route('author.papers.edit', $paper) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
    @endif
    
    @if(in_array($paper->status, ['revision_minor', 'revision_major']))
        <a href="{{ route('author.papers.revision', $paper) }}" class="btn btn-warning">
            <i class="fas fa-redo me-1"></i> Submit Revision
        </a>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Paper Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Paper Information</h5>
                <div>
                    @include('components.status-badge', ['status' => $paper->status])
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
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $paper->updated_at->format('F d, Y H:i') }}</td>
                    </tr>
                    @if($paper->issue)
                        <tr>
                            <th>Published in:</th>
                            <td>
                                <a href="{{ route('issues.show', $paper->issue) }}">
                                    {{ $paper->issue->title }}
                                </a>
                                (Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }})
                            </td>
                        </tr>
                        <tr>
                            <th>Pages:</th>
                            <td>
                                @if($paper->page_from && $paper->page_to)
                                    {{ $paper->page_from }} - {{ $paper->page_to }}
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
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
                            <a href="{{ route('author.papers.download', $paper) }}" class="btn btn-outline-danger">
                                <i class="fas fa-download me-1"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        @if($paper->reviewAssignments->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Review Progress</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        @php
                            $completed = $paper->reviewAssignments->where('status', 'completed')->count();
                            $total = $paper->reviewAssignments->count();
                            $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
                        @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span>Review Completion</span>
                            <span>{{ $completed }}/{{ $total }} completed</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Assigned Reviewers</h6>
                    <div class="row">
                        @foreach($paper->reviewAssignments as $assignment)
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">{{ $assignment->reviewer->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $assignment->reviewer->institution }}
                                                </small>
                                            </div>
                                            <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : ($assignment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </div>
                                        
                                        @if($assignment->due_date)
                                            <small class="text-muted d-block mb-2">
                                                <i class="far fa-calendar me-1"></i>
                                                Due: {{ $assignment->due_date->format('M d, Y') }}
                                                @if($assignment->due_date < now() && $assignment->status == 'pending')
                                                    <span class="text-danger ms-2">
                                                        <i class="fas fa-exclamation-circle"></i> Overdue
                                                    </span>
                                                @endif
                                            </small>
                                        @endif
                                        
                                        @if($assignment->review && !$assignment->review->is_confidential)
                                            <div class="mt-3">
                                                <strong>Recommendation:</strong>
                                                <span class="badge bg-info ms-2">
                                                    {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($paper->reviews->where('is_confidential', false)->count() > 0)
                        <div class="mt-4">
                            <a href="{{ route('author.papers.reviews', $paper) }}" class="btn btn-outline-primary">
                                <i class="fas fa-comments me-1"></i> View Review Comments
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i> Submission Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $paper->submitted_at ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Submitted</h6>
                            <small class="text-muted">
                                @if($paper->submitted_at)
                                    {{ $paper->submitted_at->format('M d, Y H:i') }}
                                @else
                                    Pending
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $paper->reviewed_at ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Under Review</h6>
                            <small class="text-muted">
                                @if($paper->reviewed_at)
                                    {{ $paper->reviewed_at->format('M d, Y H:i') }}
                                @else
                                    Pending
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $paper->status == 'accepted' ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Accepted</h6>
                            <small class="text-muted">
                                {{ $paper->status == 'accepted' ? 'Completed' : 'Pending' }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="timeline-item {{ $paper->published_at ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Published</h6>
                            <small class="text-muted">
                                @if($paper->published_at)
                                    {{ $paper->published_at->format('M d, Y H:i') }}
                                @else
                                    Pending
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i> Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('author.papers.download', $paper) }}" class="btn btn-outline-danger">
                        <i class="fas fa-download me-2"></i> Download Paper
                    </a>
                    
                    @if($paper->status == 'submitted')
                        <a href="{{ route('author.papers.edit', $paper) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i> Edit Paper
                        </a>
                        
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="if(confirm('Are you sure you want to withdraw this submission?')) document.getElementById('withdraw-form').submit()">
                            <i class="fas fa-times me-2"></i> Withdraw Submission
                        </button>
                        
                        <form id="withdraw-form" action="{{ route('author.papers.destroy', $paper) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                    
                    @if(in_array($paper->status, ['revision_minor', 'revision_major']))
                        <a href="{{ route('author.papers.revision', $paper) }}" class="btn btn-warning">
                            <i class="fas fa-redo me-2"></i> Submit Revision
                        </a>
                    @endif
                    
                    @if($paper->reviews->where('is_confidential', false)->count() > 0)
                        <a href="{{ route('author.papers.reviews', $paper) }}" class="btn btn-outline-info">
                            <i class="fas fa-comments me-2"></i> View Reviews
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Revision History -->
        @if($paper->revision_count > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-redo me-2"></i> Revision History</h6>
                </div>
                <div class="card-body">
                    <p>This paper has been revised {{ $paper->revision_count }} time(s).</p>
                    <small class="text-muted">
                        Last revision: {{ $paper->updated_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        @endif
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
    }
</style>
@endsection