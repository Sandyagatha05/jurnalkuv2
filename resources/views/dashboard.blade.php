@extends('layouts.app')

@section('page-title', 'Admin Dashboard')
@section('page-description', 'System administration and management')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Add User
        </a>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus-circle me-1"></i> Add Role
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
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h4 class="mb-0">{{ \App\Models\User::count() }}</h4>
                    </div>
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        <span class="text-success">Admin: {{ \App\Models\User::role('admin')->count() }}</span> |
                        <span class="text-primary">Editor: {{ \App\Models\User::role('editor')->count() }}</span> |
                        <span class="text-warning">Reviewer: {{ \App\Models\User::role('reviewer')->count() }}</span> |
                        <span class="text-info">Author: {{ \App\Models\User::role('author')->count() }}</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Papers</h6>
                        <h4 class="mb-0">{{ \App\Models\Paper::count() }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        Published: {{ \App\Models\Paper::published()->count() }} |
                        Under Review: {{ \App\Models\Paper::underReview()->count() }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Issues</h6>
                        <h4 class="mb-0">{{ \App\Models\Issue::count() }}</h4>
                    </div>
                    <div class="icon-circle bg-info">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        Published: {{ \App\Models\Issue::published()->count() }} |
                        Draft: {{ \App\Models\Issue::draft()->count() }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Reviews</h6>
                        <h4 class="mb-0">{{ \App\Models\Review::count() }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-search text-white"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        Completed: {{ \App\Models\ReviewAssignment::where('status', 'completed')->count() }} |
                        Pending: {{ \App\Models\ReviewAssignment::where('status', 'pending')->count() }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activity -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent System Activity</h5>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @if($activities->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($activities as $activity)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $activity->description }}</h6>
                                        <small class="text-muted">
                                            @if($activity->causer)
                                                By: {{ $activity->causer->name }}
                                            @endif
                                            | {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <span class="badge bg-secondary">{{ $activity->log_name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i> Manage Users
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-tag me-2"></i> Manage Roles
                    </a>
                    <a href="{{ route('admin.system.settings') }}" class="btn btn-outline-info">
                        <i class="fas fa-cog me-2"></i> System Settings
                    </a>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-warning">
                        <i class="fas fa-history me-2"></i> View Activity Logs
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-chart-bar me-2"></i> View Reports
                    </a>
                </div>
                
                <hr class="my-4">
                
                <h6 class="mb-3">System Health</h6>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Storage</span>
                        <span>75%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Database</span>
                        <span>45%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 45%"></div>
                    </div>
                </div>
                
                <div class="alert alert-success py-2">
                    <i class="fas fa-check-circle me-2"></i>
                    <small>All systems operational</small>
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
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
</style>
@endsection