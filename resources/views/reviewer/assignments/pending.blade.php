@extends('layouts.app')

@section('page-title', 'Pending Review Assignments')
@section('page-description', 'View and manage your pending review assignments')

@section('page-actions')
    <a href="{{ route('reviewer.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Pending Review Assignments</h5>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
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

        <!-- Tab Content -->
        <div class="tab-content" id="assignmentTabsContent">
            <!-- All Assignments -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', ['assignments' => $assignments])
            </div>
            
            <!-- Overdue Assignments -->
            <div class="tab-pane fade" id="overdue" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', [
                    'assignments' => $assignments->filter(function($assignment) {
                        return $assignment->due_date < now();
                    })
                ])
            </div>
            
            <!-- Upcoming Assignments -->
            <div class="tab-pane fade" id="upcoming" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', [
                    'assignments' => $assignments->filter(function($assignment) {
                        return $assignment->due_date >= now();
                    })
                ])
            </div>
        </div>
    </div>
</div>

@if($assignments->isEmpty())
<div class="text-center py-5">
    <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
    <h4 class="text-muted mb-3">No Pending Assignments</h4>
    <p class="text-muted">You don't have any pending review assignments at the moment.</p>
    <a href="{{ route('reviewer.dashboard') }}" class="btn btn-primary">
        <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
    </a>
</div>
@endif
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        color: #4361ee;
        font-weight: 600;
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