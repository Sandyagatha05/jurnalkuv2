@extends('layouts.app')

@section('page-title', 'Reviewer Dashboard')
@section('page-description', 'Manage your review assignments and track progress')

@section('page-actions')
    <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-warning">
        <i class="fas fa-tasks me-1"></i> Pending Assignments
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Review Guidelines</h6>
                        <h4 class="mb-0">Guides</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
                <a href="{{ route('reviewer.guidelines') }}" class="small text-warning text-decoration-none">
                    Read guidelines <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Completed Reviews</h6>
                        <h4 class="mb-0">{{ auth()->user()->reviewAssignments()->where('status', 'completed')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
                <a href="{{ route('reviewer.assignments.completed') }}" class="small text-success text-decoration-none">
                    View completed <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Overdue Reviews</h6>
                        <h4 class="mb-0">{{ auth()->user()->reviewAssignments()
                            ->where('status', 'pending')
                            ->where('due_date', '<', now())
                            ->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-exclamation-circle text-white"></i>
                    </div>
                </div>
                <a href="{{ route('reviewer.assignments.overdue') }}" class="small text-warning text-decoration-none">
                    View overdue <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Average Completion</h6>
                        <h4 class="mb-0">
                            @php
                                $completedAssignments = auth()->user()->reviewAssignments()
                                    ->where('status', 'completed')
                                    ->whereNotNull('completed_date')
                                    ->whereNotNull('assigned_date')
                                    ->get();
                                
                                if ($completedAssignments->count() > 0) {
                                    $totalDays = 0;
                                    foreach ($completedAssignments as $assignment) {
                                        $days = $assignment->assigned_date->diffInDays($assignment->completed_date);
                                        $totalDays += $days;
                                    }
                                    echo round($totalDays / $completedAssignments->count()) . ' days';
                                } else {
                                    echo 'N/A';
                                }
                            @endphp
                        </h4>
                    </div>
                    <div class="icon-circle bg-info">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                </div>
                <small class="text-muted">Average time to complete review</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Assignments -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending Assignments</h5>
                <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @php
                    $pendingAssignments = auth()->user()->reviewAssignments()
                        ->where('status', 'pending')
                        ->with(['paper.author', 'paper.issue'])
                        ->orderBy('due_date')
                        ->take(5)
                        ->get();
                @endphp
                
                @if($pendingAssignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Paper Title</th>
                                    <th>Author</th>
                                    <th>Due Date</th>
                                    <th>Days Left</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingAssignments as $assignment)
                                    <tr class="{{ $assignment->due_date < now() ? 'table-warning' : '' }}">
                                        <td>
                                            <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="text-decoration-none">
                                                {{ Str::limit($assignment->paper->title, 40) }}
                                            </a>
                                        </td>
                                        <td>{{ $assignment->paper->author->name }}</td>
                                        <td>{{ $assignment->due_date->format('M d, Y') }}</td>
                                        <td>
                                            @php
                                                $daysLeft = $assignment->due_date->diffInDays(now(), false);
                                            @endphp
                                            @if($daysLeft < 0)
                                                <span class="badge bg-success">{{ abs($daysLeft) }} days left</span>
                                            @elseif($daysLeft == 0)
                                                <span class="badge bg-warning">Due today</span>
                                            @else
                                                <span class="badge bg-danger">{{ $daysLeft }} days overdue</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-outline-success">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('reviewer.assignments.view-paper', $assignment) }}" class="btn btn-outline-info">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">No pending assignments. Great job!</p>
                        <a href="{{ route('reviewer.assignments.index') }}" class="btn btn-outline-primary">
                            View All Assignments
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Completed Reviews -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Recently Completed Reviews</h5>
            </div>
            <div class="card-body">
                @php
                    $recentReviews = auth()->user()->reviewAssignments()
                        ->where('status', 'completed')
                        ->with(['paper', 'review'])
                        ->orderBy('completed_date', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @if($recentReviews->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentReviews as $assignment)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="text-decoration-none">
                                                {{ Str::limit($assignment->paper->title, 50) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            Completed: {{ $assignment->completed_date->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @if($assignment->review)
                                            <span class="badge bg-{{ $assignment->review->recommendation == 'accept' ? 'success' : ($assignment->review->recommendation == 'reject' ? 'danger' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                Score: {{ $assignment->review->overall_score }}/5
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('reviewer.assignments.completed') }}" class="btn btn-sm btn-outline-primary">
                            View All Completed Reviews
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No completed reviews yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Your Review Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <div class="display-6 text-primary">{{ auth()->user()->reviewAssignments()->count() }}</div>
                            <small class="text-muted">Total Assigned</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-light rounded p-3">
                            <div class="display-6 text-success">{{ auth()->user()->reviewAssignments()->where('status', 'completed')->count() }}</div>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3">
                            <div class="display-6 text-warning">{{ auth()->user()->reviewAssignments()->where('status', 'pending')->count() }}</div>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3">
                            @php
                                $acceptCount = auth()->user()->reviews()->where('recommendation', 'accept')->count();
                                $totalReviews = auth()->user()->reviews()->count();
                                $acceptRate = $totalReviews > 0 ? round(($acceptCount / $totalReviews) * 100) : 0;
                            @endphp
                            <div class="display-6 text-info">{{ $acceptRate }}%</div>
                            <small class="text-muted">Accept Rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Deadlines -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Upcoming Deadlines</h6>
            </div>
            <div class="card-body">
                @php
                    $upcomingDeadlines = auth()->user()->reviewAssignments()
                        ->where('status', 'pending')
                        ->where('due_date', '>=', now())
                        ->orderBy('due_date')
                        ->take(3)
                        ->get();
                @endphp
                
                @if($upcomingDeadlines->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingDeadlines as $assignment)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">
                                            {{ Str::limit($assignment->paper->title, 30) }}
                                        </small>
                                        <small>
                                            <i class="far fa-calendar me-1"></i>
                                            Due: {{ $assignment->due_date->format('M d') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $assignment->due_date->diffInDays(now()) <= 3 ? 'danger' : 'warning' }}">
                                        {{ $assignment->due_date->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-sm btn-outline-primary">
                            View All Deadlines
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No upcoming deadlines</p>
                @endif
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