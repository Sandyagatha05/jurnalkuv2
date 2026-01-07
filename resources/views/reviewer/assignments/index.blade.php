@extends('layouts.app')

@section('page-title', 'My Assignments')
@section('page-description', 'View all your review assignments')

@section('content')
<div class="card shadow-sm hover-scale">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Review Assignments</h5>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4" id="assignmentTabs" role="tablist">
            @php
                $pendingCount = $assignments->where('status', 'pending')->count();
                $completedCount = $assignments->where('status', 'completed')->count();
                $overdueCount = $assignments->where('status', 'pending')->where('due_date', '<', now())->count();
            @endphp
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                    All <span class="badge bg-primary">{{ $assignments->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                    Pending <span class="badge bg-warning">{{ $pendingCount }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                    Completed <span class="badge bg-success">{{ $completedCount }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="overdue-tab" data-bs-toggle="tab" data-bs-target="#overdue" type="button">
                    Overdue <span class="badge bg-danger">{{ $overdueCount }}</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="assignmentTabsContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', ['assignments' => $assignments])
            </div>
            <div class="tab-pane fade" id="pending" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', ['assignments' => $assignments->where('status', 'pending')])
            </div>
            <div class="tab-pane fade" id="completed" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', ['assignments' => $assignments->where('status', 'completed')])
            </div>
            <div class="tab-pane fade" id="overdue" role="tabpanel">
                @include('reviewer.assignments.partials.assignments-table', ['assignments' => $assignments->where('status', 'pending')->where('due_date', '<', now())])
            </div>
        </div>
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

    /* Table row hover */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
    }

    /* Badge hover effect */
    .badge:hover {
        transform: scale(1.2);
        transition: all 0.3s ease;
    }
</style>
@endpush
