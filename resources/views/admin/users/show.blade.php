@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">User Details</h4>
                    <small class="text-muted">{{ $user->name }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="btn btn-primary btn-sm">
                        <i class="ri-edit-line me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-outline-secondary btn-sm">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-4">

            {{-- Profile --}}
            <div class="card mb-3">
                <div class="card-body text-center">
                    <img class="rounded-circle img-thumbnail mb-3"
                         width="120"
                         src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                         alt="{{ $user->name }}">

                    <h5 class="mb-0">{{ $user->name }}</h5>
                    <small class="text-muted">{{ $user->email }}</small>

                    <div class="mt-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-secondary me-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Account Info --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Account Information</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">User ID</span>
                        <strong>#{{ $user->id }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Registered</span>
                        <span>{{ $user->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Email Verified</span>
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-warning">No</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-lg-8">

            {{-- Stats --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4>{{ $user->papers_count ?? 0 }}</h4>
                            <small class="text-muted">Papers</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4>{{ $user->reviews_count ?? 0 }}</h4>
                            <small class="text-muted">Reviews</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4>{{ $user->editorials_count ?? 0 }}</h4>
                            <small class="text-muted">Editorials</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Placeholder --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-2">Recent Activity</h6>
                    <p class="text-muted mb-0">
                        Activity tracking is not available yet.
                    </p>
                </div>
            </div>

            {{-- Papers --}}
            @if($user->hasRole('author') && $user->papers->count())
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Submitted Papers</h6>

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->papers as $paper)
                                        <tr>
                                            <td>{{ Str::limit($paper->title, 50) }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_',' ',$paper->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $paper->created_at->format('M d, Y') }}</td>
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
