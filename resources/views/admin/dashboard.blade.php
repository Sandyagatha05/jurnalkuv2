@extends('layouts.app')

@section('page-title', 'Admin Dashboard')
@section('page-description', 'Manage system settings, users, and roles')

@section('page-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Add User
    </a>
@endsection

@section('content')
<div class="row g-4">
    <!-- === Stat Cards Row === -->
    <div class="col-12">
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-users text-primary fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted fw-normal mb-1">Total Users</h6>
                            <h4 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h4>
                            <a href="{{ route('admin.users.index') }}" class="small text-primary mt-1 d-inline-block text-decoration-none">
                                View all <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-file-alt text-success fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted fw-normal mb-1">Total Papers</h6>
                            <h4 class="mb-0">{{ $stats['total_papers'] ?? 0 }}</h4>
                            <small class="text-muted">Across all statuses</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-book text-warning fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted fw-normal mb-1">Published Issues</h6>
                            <h4 class="mb-0">{{ $stats['published_issues'] ?? 0 }}</h4>
                            <a href="{{ route('editor.issues.index') }}" class="small text-warning mt-1 d-inline-block text-decoration-none">
                                Manage issues <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-user-tag text-info fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-muted fw-normal mb-1">System Roles</h6>
                            <h4 class="mb-0">{{ $stats['total_roles'] ?? 0 }}</h4>
                            <a href="{{ route('admin.roles.index') }}" class="small text-info mt-1 d-inline-block text-decoration-none">
                                Manage roles <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-12">
        <div class="row g-4">
            <!-- User Distribution -->


            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-users me-2 text-secondary"></i> User Distribution
                            </h5>
                            <span class="badge bg-white text-primary fw-bold border">
                                {{ $stats['total_users'] }} Total
                            </span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="chart-container mb-4" style="height: 250px;">
                            <canvas id="userRoleChart"></canvas>
                        </div>
                        <div class="mt-auto">
                            <h6 class="fw-bold text-muted mb-3">
                                <i class="fas fa-chart-pie me-2"></i> Role Breakdown
                            </h6>
                            <div class="row gx-2 gy-1">
                                @foreach($roleStats as $role)
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <span class="badge rounded-pill me-2" style="background-color: {{ $role['color'] }}; width: 10px; height: 10px;"></span>
                                            <small class="text-truncate">{{ ucfirst($role['name']) }}</small>
                                            <span class="fw-bold ms-auto">{{ $role['count'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-server me-2 text-secondary"></i> System Status
                        </h5>
                    </div>
                    <div class="card-body pt-0 d-flex flex-column">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Database</span>
                                <span class="badge bg-success">Connected</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-success rounded-pill" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Storage</span>
                                <span class="badge bg-info">Normal</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-info rounded-pill" style="width: 65%"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Performance</span>
                                <span class="badge bg-success">Optimal</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-success rounded-pill" style="width: 85%"></div>
                            </div>
                        </div>

                        <div class="mt-auto text-center">
                            <small class="text-muted">
                                <i class="fas fa-sync-alt me-1"></i>
                                Last updated: {{ now()->format('j M Y, H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
}
.card {
    transition: all 0.3s ease;
    border-radius: 12px;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.chart-container {
    position: relative;
    width: 100%;
    height: 250px;
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