@extends('layouts.app')

@section('page-title', 'Admin Dashboard')
@section('page-description', 'Manage system settings, users, and roles')

@section('page-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Add User
    </a>
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
                        <h4 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small text-primary text-decoration-none">
                    View all <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Papers</h6>
                        <h4 class="mb-0">{{ $stats['total_papers'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <span class="small text-muted">Across all status</span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Published Issues</h6>
                        <h4 class="mb-0">{{ $stats['published_issues'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
                <a href="{{ route('editor.issues.index') }}" class="small text-warning text-decoration-none">
                    Manage issues <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">System Roles</h6>
                        <h4 class="mb-0">{{ $stats['total_roles'] ?? 0 }}</h4>
                    </div>
                    <div class="icon-circle bg-info">
                        <i class="fas fa-user-tag text-white"></i>
                    </div>
                </div>
                <a href="{{ route('admin.roles.index') }}" class="small text-info text-decoration-none">
                    Manage roles <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left column intentionally removed (Quick Actions & Recent Activity) -->
    <div class="col-lg-8 mb-4 d-none"></div>
    
    <!-- User Statistics -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="userRoleChart" height="250"></canvas>
                
                <div class="mt-4">
                    <h6>Role Breakdown</h6>
                    <div class="mt-3">
                        @foreach($roleStats as $role)
                            <div class="d-flex justify-content-between mb-2">
                                <span>
                                    <i class="fas fa-circle me-2" style="color: {{ $role['color'] }}"></i>
                                    {{ ucfirst($role['name']) }}
                                </span>
                                <span class="fw-bold">{{ $role['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-server me-2"></i> System Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Database</span>
                        <span class="badge bg-success">Connected</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Storage</span>
                        <span class="badge bg-info">Normal</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 65%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Performance</span>
                        <span class="badge bg-success">Optimal</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 85%"></div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Last updated: {{ now()->format('M d, Y H:i') }}
                    </small>
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
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
    
    .card-hover {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // User Role Chart
    const ctx = document.getElementById('userRoleChart').getContext('2d');
    const userRoleChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json(collect($roleStats)->pluck('name')->map(fn($name) => ucfirst($name))),
            datasets: [{
                data: @json(collect($roleStats)->pluck('count')),
                backgroundColor: @json(collect($roleStats)->pluck('color')),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
</script>
@endpush