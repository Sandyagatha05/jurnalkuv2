@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">My Profile</h4>
            <p class="text-muted mb-0">Profile overview and personal information</p>
        </div>

        {{-- EDIT BUTTON --}}
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="ri-edit-line me-1"></i> Edit Profile
        </a>
    </div>

    <div class="row">
        {{-- LEFT : PROFILE CARD --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">

                    {{-- AVATAR --}}
                    <div class="mb-3">
                        <img
                            src="{{ $user->profile_photo_url }}"
                            alt="{{ $user->name }}"
                            class="rounded-circle img-thumbnail"
                            style="width:140px;height:140px;object-fit:cover"
                        >
                    </div>

                    {{-- NAME --}}
                    <h5 class="mb-0">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>

                    {{-- ROLES --}}
                    <div class="mb-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary me-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>

                    <hr>

                    {{-- EXTRA INFO --}}
                    <div class="text-start small">
                        @if($user->institution)
                            <div class="mb-2">
                                <i class="ri-building-line me-2 text-muted"></i>
                                {{ $user->institution }}
                            </div>
                        @endif

                        @if($user->phone)
                            <div class="mb-2">
                                <i class="ri-phone-line me-2 text-muted"></i>
                                {{ $user->phone }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- ACCOUNT INFO --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Account Information</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Joined</span>
                        <span>{{ $user->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT : DETAILS --}}
        <div class="col-lg-8">

            {{-- BIOGRAPHY --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Biography</h6>
                </div>
                <div class="card-body">
                    @if($user->biography)
                        <p class="mb-0">{{ $user->biography }}</p>
                    @else
                        <p class="text-muted mb-0">
                            No biography provided.
                        </p>
                    @endif
                </div>
            </div>

            {{-- AUTHOR STATS --}}
            @if($user->hasRole('author'))
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Author Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h4 class="mb-0">
                                    {{ $roleData['papers_count'] ?? 0 }}
                                </h4>
                                <small class="text-muted">Total Papers</small>
                            </div>

                            <div class="col-md-6">
                                <h4 class="mb-0">
                                    {{ $roleData['published_papers'] ?? 0 }}
                                </h4>
                                <small class="text-muted">Published Papers</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
