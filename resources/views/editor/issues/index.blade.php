@extends('layouts.app')

@section('page-title', 'Manage Issues')
@section('page-description', 'View and manage all journal issues')

@section('page-actions')
    <a href="{{ route('editor.issues.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Create New Issue
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Issues</h5>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4" id="issueTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                    All Issues <span class="badge bg-secondary">{{ $issues->total() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="published-tab" data-bs-toggle="tab" data-bs-target="#published" type="button">
                    Published <span class="badge bg-secondary">{{ $issues->where('status', 'published')->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="draft-tab" data-bs-toggle="tab" data-bs-target="#draft" type="button">
                    Draft <span class="badge bg-secondary">{{ $issues->where('status', 'draft')->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="archived-tab" data-bs-toggle="tab" data-bs-target="#archived" type="button">
                    Archived <span class="badge bg-secondary">{{ $issues->where('status', 'archived')->count() }}</span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="issueTabsContent">
            <!-- All Issues -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @include('editor.issues.partials.issues-table', ['issues' => $issues])
            </div>
            
            <!-- Published -->
            <div class="tab-pane fade" id="published" role="tabpanel">
                @include('editor.issues.partials.issues-table', [
                    'issues' => $issues->where('status', 'published')
                ])
            </div>
            
            <!-- Draft -->
            <div class="tab-pane fade" id="draft" role="tabpanel">
                @include('editor.issues.partials.issues-table', [
                    'issues' => $issues->where('status', 'draft')
                ])
            </div>
            
            <!-- Archived -->
            <div class="tab-pane fade" id="archived" role="tabpanel">
                @include('editor.issues.partials.issues-table', [
                    'issues' => $issues->where('status', 'archived')
                ])
            </div>
        </div>
    </div>
</div>

@if($issues->isEmpty())
<div class="text-center py-5">
    <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
    <h4 class="text-muted mb-3">No Issues Created Yet</h4>
    <p class="text-muted mb-4">Start by creating your first journal issue.</p>
    <a href="{{ route('editor.issues.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus-circle me-2"></i> Create First Issue
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
    
    .issue-title {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush