@extends('layouts.app')

@section('page-title', 'Editor Dashboard')
@section('page-description', 'Manage papers, issues, and review process')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('editor.issues.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Create Issue
        </a>
        <a href="{{ route('editor.papers.submitted') }}" class="btn btn-outline-primary">
            <i class="fas fa-file-upload me-1"></i> New Submissions
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Submitted Papers</h6>
                        <h4 class="mb-0">{{ $stats['submitted'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-inbox text-white"></i>
                    </div>
                </div>
                <a href="{{ route('editor.papers.submitted') }}" class="small text-primary text-decoration-none">
                    View all <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Under Review</h6>
                        <h4 class="mb-0">{{ $stats['under_review'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-search text-white"></i>
                    </div>
                </div>
                <a href="{{ route('editor.papers.under-review') }}" class="small text-warning text-decoration-none">
                    View all <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Needs Decision</h6>
                        <h4 class="mb-0">{{ $stats['needs_decision'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-info">
                        <i class="fas fa-gavel text-white"></i>
                    </div>
                </div>
                <a href="{{ route('editor.papers.index') }}?status=under_review" class="small text-info text-decoration-none">
                    Make decisions <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Active Issues</h6>
                        <h4 class="mb-0">{{ $stats['active_issues'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
                <a href="{{ route('editor.issues.index') }}" class="small text-success text-decoration-none">
                    Manage issues <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Papers -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Paper Submissions</h5>
                <a href="{{ route('editor.papers.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($recentPapers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPapers as $paper)
                                    <tr>
                                        <td>
                                            <a href="{{ route('editor.papers.show', $paper) }}" class="text-decoration-none">
                                                {{ Str::limit($paper->title, 40) }}
                                            </a>
                                        </td>
                                        <td>{{ $paper->author->name }}</td>
                                        <td>
                                            @include('components.status-badge', ['status' => $paper->status])
                                        </td>
                                        <td>{{ $paper->submitted_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($paper->status == 'submitted')
                                                    <a href="{{ route('editor.papers.assign-reviewers', $paper) }}" class="btn btn-outline-warning">
                                                        <i class="fas fa-user-plus"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent paper submissions.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & Stats -->
    <div class="col-lg-4 mb-4">
        <!-- Pending Reviews -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-clock me-2"></i> Pending Reviews</h6>
            </div>
            <div class="card-body">
                @if($pendingReviews->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pendingReviews as $assignment)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">
                                            {{ Str::limit($assignment->paper->title, 30) }}
                                        </small>
                                        <small>
                                            Reviewer: {{ $assignment->reviewer->name }}
                                        </small>
                                    </div>
                                    <span class="badge bg-warning">
                                        Due {{ $assignment->due_date->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('editor.reviews.pending') }}" class="btn btn-sm btn-outline-warning w-100 mt-3">
                        View All Pending
                    </a>
                @else
                    <p class="text-muted text-center mb-0">No pending reviews</p>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('editor.issues.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus-circle me-2"></i> Create New Issue
                    </a>
                    <a href="{{ route('editor.papers.submitted') }}" class="btn btn-outline-success">
                        <i class="fas fa-inbox me-2"></i> Process New Submissions
                    </a>
                    <a href="{{ route('editor.reviews.pending') }}" class="btn btn-outline-warning">
                        <i class="fas fa-clock me-2"></i> Check Pending Reviews
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .border-left-primary { border-left: 4px solid #4361ee !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
</style>
@endsection