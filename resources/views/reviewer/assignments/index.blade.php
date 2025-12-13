@extends('layouts.app')

@section('page-title', 'My Assignments')
@section('page-description', 'View all your review assignments')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-outline-primary">
            <i class="fas fa-clock me-1"></i> Pending
        </a>
        <a href="{{ route('reviewer.assignments.completed') }}" class="btn btn-outline-success">
            <i class="fas fa-check-circle me-1"></i> Completed
        </a>
        <a href="{{ route('reviewer.assignments.overdue') }}" class="btn btn-outline-warning">
            <i class="fas fa-exclamation-triangle me-1"></i> Overdue
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Review Assignments</h5>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4" id="assignmentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                    All Assignments <span class="badge bg-secondary">{{ $assignments->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                    Pending <span class="badge bg-secondary">{{ $assignments->where('status', 'pending')->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                    Completed <span class="badge bg-secondary">{{ $assignments->where('status', 'completed')->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="overdue-tab" data-bs-toggle="tab" data-bs-target="#overdue" type="button">
                    Overdue <span class="badge bg-secondary">{{ $assignments->where('status', 'pending')->where('due_date', '<', now())->count() }}</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="assignmentTabsContent">
            <!-- All Assignments -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', ['assignments' => $assignments])
            </div>
            
            <!-- Pending Assignments -->
            <div class="tab-pane fade" id="pending" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', [
                    'assignments' => $assignments->where('status', 'pending')
                ])
            </div>
            
            <!-- Completed Assignments -->
            <div class="tab-pane fade" id="completed" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', [
                    'assignments' => $assignments->where('status', 'completed')
                ])
            </div>
            
            <!-- Overdue Assignments -->
            <div class="tab-pane fade" id="overdue" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', [
                    'assignments' => $assignments->where('status', 'pending')->where('due_date', '<', now())
                ])
            </div>
        </div>
    </div>
</div>

@if($assignments->isEmpty())
<div class="text-center py-5">
    <i class="fas fa-tasks fa-4x text-muted mb-4"></i>
    <h4 class="text-muted mb-3">No Review Assignments</h4>
    <p class="text-muted mb-4">You have not been assigned any papers to review yet.</p>
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
</style>
@endpush