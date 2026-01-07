@extends('layouts.app')

@section('title', 'Pending Assignments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0">Pending Reviews</h3>
        <small class="text-muted">View and manage your pending review assignments</small>
    </div>
    <div>
        <a href="{{ route('reviewer.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="card shadow-sm hover-scale">
    <div class="card-header bg-light">
        <h5 class="mb-0">Assignments List</h5>
    </div>
    <div class="card-body">
        
        @if($assignments->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-4x text-success opacity-50"></i>
                </div>
                <h4 class="text-muted mb-2">No Pending Assignments</h4>
                <p class="text-muted mb-4 small">You don't have any pending review assignments at the moment.</p>
                <a href="{{ route('reviewer.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                </a>
            </div>
        @else
            <ul class="nav nav-tabs mb-4" id="assignmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                        All Pending <span class="badge bg-secondary">{{ $assignments->total() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="overdue-tab" data-bs-toggle="tab" data-bs-target="#overdue" type="button">
                        Overdue <span class="badge bg-danger">{{ $assignments->where('due_date', '<', now())->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button">
                        Upcoming <span class="badge bg-warning">{{ $assignments->where('due_date', '>=', now())->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="assignmentTabsContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    @include('reviewer.assignments.partials.assignments-table', ['assignments' => $assignments])
                </div>
                
                <div class="tab-pane fade" id="overdue" role="tabpanel">
                    @include('reviewer.assignments.partials.assignments-table', [
                        'assignments' => $assignments->filter(function($assignment) {
                            return $assignment->due_date < now();
                        })
                    ])
                </div>
                
                <div class="tab-pane fade" id="upcoming" role="tabpanel">
                    @include('reviewer.assignments.partials.assignments-table', [
                        'assignments' => $assignments->filter(function($assignment) {
                            return $assignment->due_date >= now();
                        })
                    ])
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Tab Styling */
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link.active {
        color: #4361ee;
        font-weight: 600;
        border-bottom: 3px solid #4361ee;
    }
    .nav-tabs .nav-link:hover {
        color: #4361ee;
    }

    /* Card hover effect */
    .hover-scale {
        transition: all 0.3s ease;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .assignment-title {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .due-date {
        font-size: 0.875rem;
    }
    
    .due-date.overdue {
        color: #dc3545;
        font-weight: 600;
    }
</style>
@endpush