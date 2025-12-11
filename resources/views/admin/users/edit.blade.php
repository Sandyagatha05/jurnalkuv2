@extends('layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Edit User: {{ $user->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Edit User Information</h4>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Change Password (Optional)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="New password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                </div>
                            </div>
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assign Roles *</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="admin" id="role_admin" {{ in_array('admin', old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_admin">
                                            <span class="badge bg-danger">Admin</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="editor" id="role_editor" {{ in_array('editor', old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_editor">
                                            <span class="badge bg-warning">Editor</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="reviewer" id="role_reviewer" {{ in_array('reviewer', old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_reviewer">
                                            <span class="badge bg-info">Reviewer</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="author" id="role_author" {{ in_array('author', old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_author">
                                            <span class="badge bg-primary">Author</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('roles')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_verified" 
                                       id="email_verified" value="1" {{ $user->email_verified_at ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verified">
                                    Email verified
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-save-line me-1"></i> Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                                <i class="ri-close-line me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">User Activity</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Registered:</strong> {{ $user->created_at->format('F d, Y H:i') }}</p>
                            <p><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('F d, Y H:i') : 'Never' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email Verified:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('F d, Y') : 'Not verified' }}</p>
                            <p><strong>Updated:</strong> {{ $user->updated_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- User Profile Card -->
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
                        <a href="#" class="btn btn-outline-primary">
                            <i class="ri-mail-line me-1"></i> Send Message
                        </a>
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete({{ $user->id }})">
                            <i class="ri-delete-bin-line me-1"></i> Delete User
                        </button>
                    </div>
                </div>
            </div>

            <!-- User Stats Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">User Statistics</h5>
                    <div class="mb-2">
                        <span class="text-muted">Papers Submitted:</span>
                        <span class="float-end fw-bold">{{ $user->papers_count ?? 0 }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Reviews Completed:</span>
                        <span class="float-end fw-bold">{{ $user->reviews_count ?? 0 }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Editorial Decisions:</span>
                        <span class="float-end fw-bold">{{ $user->editorials_count ?? 0 }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Last Active:</span>
                        <span class="float-end">{{ $user->last_activity ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-form-{{ $user->id }}" 
      action="{{ route('admin.users.destroy', $user) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function confirmDelete(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            document.getElementById('delete-form-' + userId).submit();
        }
    }
</script>
@endpush