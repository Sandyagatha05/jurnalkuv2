@extends('layouts.app')

@section('page-title', 'My Profile')
@section('page-description', 'View and manage your profile information')

@section('page-actions')
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
        <i class="fas fa-edit me-1"></i> Edit Profile
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Profile Overview -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <!-- Profile Photo -->
                <div class="mb-4">
                    <img src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->name }}" 
                         class="rounded-circle img-thumbnail"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    
                    @if($user->photo)
                        <form action="{{ route('profile.delete-photo') }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash me-1"></i> Remove Photo
                            </button>
                        </form>
                    @endif
                </div>
                
                <!-- User Info -->
                <h4 class="mb-2">{{ $user->name }}</h4>
                <p class="text-muted mb-3">
                    @if($user->hasRole('admin'))
                        <span class="badge bg-danger">Administrator</span>
                    @elseif($user->hasRole('editor'))
                        <span class="badge bg-primary">Editor</span>
                    @elseif($user->hasRole('reviewer'))
                        <span class="badge bg-warning">Reviewer</span>
                    @elseif($user->hasRole('author'))
                        <span class="badge bg-success">Author</span>
                    @endif
                </p>
                
                <!-- Contact Info -->
                <div class="text-start mb-4">
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        {{ $user->email }}
                    </p>
                    @if($user->phone)
                        <p class="mb-2">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            {{ $user->phone }}
                        </p>
                    @endif
                    @if($user->institution)
                        <p class="mb-0">
                            <i class="fas fa-university me-2 text-muted"></i>
                            {{ $user->institution }}
                        </p>
                    @endif
                </div>
                
                <!-- Profile Completion -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Profile Completion</span>
                        <span>{{ $user->profile_completion }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $user->profile_completion }}%"></div>
                    </div>
                    @if($user->profile_completion < 80)
                        <small class="text-muted">
                            Complete your profile for better visibility
                        </small>
                    @endif
                </div>
                
                <!-- Quick Stats -->
                <div class="row g-2">
                    @if($user->hasRole('author'))
                        <div class="col-6">
                            <div class="bg-light p-3 rounded text-center">
                                <h5 class="mb-0">{{ $roleData['papers_count'] ?? 0 }}</h5>
                                <small class="text-muted">Papers</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded text-center">
                                <h5 class="mb-0">{{ $roleData['published_papers'] ?? 0 }}</h5>
                                <small class="text-muted">Published</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($user->hasRole('reviewer'))
                        <div class="col-6">
                            <div class="bg-light p-3 rounded text-center">
                                <h5 class="mb-0">{{ $roleData['total_reviews'] ?? 0 }}</h5>
                                <small class="text-muted">Reviews</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded text-center">
                                <h5 class="mb-0">{{ $roleData['pending_reviews'] ?? 0 }}</h5>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Academic IDs -->
        @if($user->orcid_id || $user->google_scholar_id || $user->scopus_id)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-id-card me-2"></i> Academic Profiles</h6>
                </div>
                <div class="card-body">
                    @if($user->orcid_id)
                        <div class="d-flex align-items-center mb-3">
                            <i class="fab fa-orcid fa-2x text-green me-3"></i>
                            <div>
                                <small class="text-muted d-block">ORCID</small>
                                <a href="https://orcid.org/{{ $user->orcid_id }}" target="_blank" class="text-decoration-none">
                                    {{ $user->orcid_id }}
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    @if($user->google_scholar_id)
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-graduation-cap fa-2x text-blue me-3"></i>
                            <div>
                                <small class="text-muted d-block">Google Scholar</small>
                                <span>{{ $user->google_scholar_id }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if($user->scopus_id)
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book fa-2x text-orange me-3"></i>
                            <div>
                                <small class="text-muted d-block">Scopus</small>
                                <span>{{ $user->scopus_id }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    <!-- Profile Details -->
    <div class="col-lg-8">
        <!-- Biography -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i> Biography</h6>
            </div>
            <div class="card-body">
                @if($user->biography)
                    <p>{{ $user->biography }}</p>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-edit fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No biography added yet.</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                            Add Biography
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Role-Specific Information -->
        @if($user->hasRole('author'))
            <!-- Author Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Author Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6>Paper Status</h6>
                            <canvas id="paperStatusChart" height="150"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h6>Submission Timeline</h6>
                            <div class="list-group list-group-flush">
                                @foreach($user->papers()->latest()->take(5)->get() as $paper)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between">
                                            <div class="paper-title" title="{{ $paper->title }}">
                                                {{ Str::limit($paper->title, 40) }}
                                            </div>
                                            <div>
                                                @include('components.status-badge', ['status' => $paper->status])
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Submitted: {{ $paper->submitted_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        @if($user->hasRole('reviewer'))
            <!-- Reviewer Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-star me-2"></i> Reviewer Performance</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="display-4 fw-bold text-primary">
                                {{ $roleData['avg_review_time'] ?? 0 }}
                            </div>
                            <small class="text-muted">Avg. Review Days</small>
                        </div>
                        <div class="col-md-8">
                            <h6>Recent Reviews</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Paper</th>
                                            <th>Recommendation</th>
                                            <th>Completed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->reviews()->with('assignment.paper')->latest()->take(5)->get() as $review)
                                            <tr>
                                                <td>{{ Str::limit($review->assignment->paper->title, 30) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $review->recommendation == 'accept' ? 'success' : ($review->recommendation == 'reject' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $review->reviewed_at->format('M d') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        @if($user->hasRole('editor'))
            <!-- Editor Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-edit me-2"></i> Editor Dashboard</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Managed Issues</h6>
                            <div class="list-group list-group-flush">
                                @foreach($user->managedIssues()->latest()->take(5)->get() as $issue)
                                    <a href="{{ route('editor.issues.show', $issue) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>Vol. {{ $issue->volume }}, No. {{ $issue->number }}</strong>
                                                <div class="text-muted">{{ $issue->year }}</div>
                                            </div>
                                            <span class="badge bg-{{ $issue->status == 'published' ? 'success' : 'warning' }}">
                                                {{ ucfirst($issue->status) }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Recent Decisions</h6>
                            <div class="list-group list-group-flush">
                                <!-- You can add recent editorial decisions here -->
                                <div class="text-center py-4">
                                    <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No recent decisions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Account Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i> Account Settings</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Email & Password</h6>
                        <p class="text-muted">Update your email address and password</p>
                        <a href="{{ route('profile.edit') }}#password" class="btn btn-sm btn-outline-primary">
                            Change Password
                        </a>
                    </div>
                    <div class="col-md-6">
                        <h6>Account Status</h6>
                        <p class="text-muted">
                            <i class="fas fa-circle text-success me-1"></i>
                            Active since {{ $user->created_at->format('M d, Y') }}
                        </p>
                        <small class="text-muted">
                            Last login: {{ $user->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->hasRole('author'))
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Paper Status Chart
        const paperStatusCtx = document.getElementById('paperStatusChart').getContext('2d');
        
        // You can fetch real data via AJAX or pass from controller
        const paperStatusData = {
            labels: ['Submitted', 'Under Review', 'Accepted', 'Published', 'Rejected'],
            datasets: [{
                data: [3, 2, 1, 4, 1], // Example data
                backgroundColor: [
                    '#6c757d',
                    '#ffc107',
                    '#17a2b8',
                    '#28a745',
                    '#dc3545'
                ]
            }]
        };
        
        new Chart(paperStatusCtx, {
            type: 'doughnut',
            data: paperStatusData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
    @endpush
@endif

<style>
    .paper-title {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .text-green { color: #a6ce39; }
    .text-blue { color: #4285f4; }
    .text-orange { color: #ff6f00; }
</style>
@endsection