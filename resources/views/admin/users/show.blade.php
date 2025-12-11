@extends('layouts.app')

@section('title', 'User Details: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">User Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">{{ $user->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF&size=200' }}" 
                             class="rounded-circle img-thumbnail" alt="{{ $user->name }}">
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        @foreach($user->roles as $role)
                        <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'editor' ? 'warning' : ($role->name == 'reviewer' ? 'info' : 'primary')) }} mb-1">
                            {{ ucfirst($role->name) }}
                        </span>
                        @endforeach
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i> Edit User
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Account Information</h5>
                    <div class="mb-2">
                        <span class="text-muted">User ID:</span>
                        <span class="float-end fw-bold">#{{ $user->id }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Registered:</span>
                        <span class="float-end">{{ $user->created_at->format('F d, Y H:i') }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Last Login:</span>
                        <span class="float-end">{{ $user->last_login_at ? $user->last_login_at->format('F d, Y H:i') : 'Never' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Email Verified:</span>
                        <span class="float-end">
                            @if($user->email_verified_at)
                            <span class="badge bg-success">{{ $user->email_verified_at->format('F d, Y') }}</span>
                            @else
                            <span class="badge bg-warning">Not verified</span>
                            @endif
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="float-end">
                            <span class="badge bg-success">Active</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Statistics -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $user->papers_count ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Papers</p>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-primary rounded-3">
                                        <i class="ri-file-text-line font-size-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $user->reviews_count ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Reviews</p>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-success rounded-3">
                                        <i class="ri-chat-check-line font-size-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $user->editorials_count ?? 0 }}</h4>
                                    <p class="text-muted mb-0">Editorials</p>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-info rounded-3">
                                        <i class="ri-edit-box-line font-size-20"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Activity</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('M d, H:i') }}</td>
                                    <td>
                                        @if($activity->type == 'paper_submitted')
                                        <span class="badge bg-primary">Paper Submitted</span>
                                        @elseif($activity->type == 'review_submitted')
                                        <span class="badge bg-success">Review Submitted</span>
                                        @elseif($activity->type == 'login')
                                        <span class="badge bg-info">Login</span>
                                        @else
                                        <span class="badge bg-secondary">{{ $activity->type }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No recent activity</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- User Papers (if author) -->
            @if($user->hasRole('author') && $user->papers->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Submitted Papers</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->papers as $paper)
                                <tr>
                                    <td>{{ Str::limit($paper->title, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $paper->status == 'published' ? 'success' : ($paper->status == 'accepted' ? 'info' : ($paper->status == 'under_review' ? 'warning' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $paper->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-sm btn-light">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection