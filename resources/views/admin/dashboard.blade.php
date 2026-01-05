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

    <!-- ===================== -->
    <!-- STAT CARDS -->
    <!-- ===================== -->
    <div class="col-12">
        <div class="row g-4">

            @php
                $cards = [
                    [
                        'title' => 'Total Users',
                        'value' => $stats['total_users'] ?? 0,
                        'icon'  => 'fa-users',
                        'color' => 'primary',
                        'link'  => route('admin.users.index'),
                        'linkText' => 'View all'
                    ],
                    [
                        'title' => 'Total Papers',
                        'value' => $stats['total_papers'] ?? 0,
                        'icon'  => 'fa-file-alt',
                        'color' => 'success',
                        'note'  => 'Across all statuses'
                    ],
                    [
                        'title' => 'Published Issues',
                        'value' => $stats['published_issues'] ?? 0,
                        'icon'  => 'fa-book',
                        'color' => 'warning',
                        'link'  => route('editor.issues.index'),
                        'linkText' => 'Manage issues'
                    ],
                    [
                        'title' => 'System Roles',
                        'value' => $stats['total_roles'] ?? 0,
                        'icon'  => 'fa-user-tag',
                        'color' => 'info',
                        'link'  => route('admin.roles.index'),
                        'linkText' => 'Manage roles'
                    ],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card h-100">
                        <div class="stat-icon bg-{{ $card['color'] }}">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>

                        <div class="stat-content">
                            <p class="stat-title">{{ $card['title'] }}</p>
                            <h3 class="stat-value">{{ $card['value'] }}</h3>

                            @isset($card['link'])
                                <a href="{{ $card['link'] }}" class="stat-link text-{{ $card['color'] }}">
                                    {{ $card['linkText'] }}
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            @endisset

                            @isset($card['note'])
                                <small class="text-muted">{{ $card['note'] }}</small>
                            @endisset
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <!-- ===================== -->
    <!-- CHART + SYSTEM STATUS -->
    <!-- ===================== -->
    <div class="col-12">
        <div class="row g-4">

            <!-- User Distribution -->
            <div class="col-md-6">
                <div class="card dashboard-card h-100">
                    <div class="card-header dashboard-card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2 text-primary"></i>
                                User Distribution
                            </h5>
                            <span class="badge bg-primary-subtle text-primary fw-semibold">
                                {{ $stats['total_users'] }} Users
                            </span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="chart-container mb-4">
                            <canvas id="userRoleChart"></canvas>
                        </div>

                        <div class="mt-auto">
                            <h6 class="text-muted fw-semibold mb-3">
                                Role Breakdown
                            </h6>

                            <div class="row gx-3 gy-2">
                                @foreach($roleStats as $role)
                                    <div class="col-6">
                                        <div class="d-flex align-items-center small">
                                            <span
                                                class="me-2 rounded-circle"
                                                style="width:10px;height:10px;background-color:{{ $role['color'] }}">
                                            </span>
                                            <span class="text-truncate">
                                                {{ ucfirst($role['name']) }}
                                            </span>
                                            <span class="fw-bold ms-auto">
                                                {{ $role['count'] }}
                                            </span>
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
                <div class="card dashboard-card h-100">
                    <div class="card-header dashboard-card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-server me-2 text-primary"></i>
                            System Status
                        </h5>
                    </div>

                    <div class="card-body d-flex flex-column pt-2">

                        @php
                            $systems = [
                                ['name'=>'Database','value'=>100,'color'=>'success','status'=>'Connected'],
                                ['name'=>'Storage','value'=>65,'color'=>'info','status'=>'Normal'],
                                ['name'=>'Performance','value'=>85,'color'=>'success','status'=>'Optimal'],
                            ];
                        @endphp

                        @foreach($systems as $sys)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small mb-2">
                                    <span>{{ $sys['name'] }}</span>
                                    <span class="badge bg-{{ $sys['color'] }}-subtle text-{{ $sys['color'] }}">
                                        {{ $sys['status'] }}
                                    </span>
                                </div>

                                <div class="progress progress-soft">
                                    <div
                                        class="progress-bar bg-{{ $sys['color'] }}"
                                        style="width: {{ $sys['value'] }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-auto text-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Last updated: {{ now()->format('j M Y, H:i') }}
                            </small>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ===================== -->
<!-- STYLES -->
<!-- ===================== -->
<style>
.stat-card {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    transition: all .25s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,.06);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.stat-content {
    flex: 1;
}
.stat-title {
    font-size: .85rem;
    color: #64748b;
    margin-bottom: .25rem;
}
.stat-value {
    font-weight: 700;
    margin-bottom: .25rem;
}
.stat-link {
    font-size: .8rem;
    text-decoration: none;
}

.dashboard-card {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
}
.dashboard-card-header {
    background: transparent;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 1.25rem;
}

.chart-container {
    position: relative;
    height: 260px;
}

.progress-soft {
    height: 8px;
    border-radius: 999px;
    background-color: #f1f5f9;
}
.progress-soft .progress-bar {
    border-radius: 999px;
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('userRoleChart');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: @json(collect($roleStats)->pluck('name')->map(fn($n)=>ucfirst($n))),
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
                    padding: 18,
                    usePointStyle: true
                }
            }
        }
    }
});
</script>
@endpush
