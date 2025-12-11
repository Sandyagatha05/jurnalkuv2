@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Add New User</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">User Information</h4>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assign Roles *</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="admin" id="role_admin" {{ in_array('admin', old('roles', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_admin">
                                            <span class="badge bg-danger">Admin</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="editor" id="role_editor" {{ in_array('editor', old('roles', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_editor">
                                            <span class="badge bg-warning">Editor</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="reviewer" id="role_reviewer" {{ in_array('reviewer', old('roles', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_reviewer">
                                            <span class="badge bg-info">Reviewer</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" 
                                               value="author" id="role_author" {{ in_array('author', old('roles', [])) ? 'checked' : '' }}>
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
                                <input class="form-check-input" type="checkbox" name="send_welcome_email" 
                                       id="send_welcome_email" value="1" checked>
                                <label class="form-check-label" for="send_welcome_email">
                                    Send welcome email with login credentials
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_verified" 
                                       id="email_verified" value="1" checked>
                                <label class="form-check-label" for="email_verified">
                                    Mark email as verified
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-user-add-line me-1"></i> Create User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                                <i class="ri-close-line me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Role Information</h4>
                    
                    <div class="mb-3">
                        <h6><span class="badge bg-danger">Admin</span></h6>
                        <p class="text-muted mb-2">Full system access including user management, system settings, and all editorial functions.</p>
                    </div>

                    <div class="mb-3">
                        <h6><span class="badge bg-warning">Editor</span></h6>
                        <p class="text-muted mb-2">Manage journal issues, assign reviewers, make editorial decisions, and handle paper workflow.</p>
                    </div>

                    <div class="mb-3">
                        <h6><span class="badge bg-info">Reviewer</span></h6>
                        <p class="text-muted mb-2">Review assigned papers, submit evaluations, and provide feedback to authors.</p>
                    </div>

                    <div class="mb-3">
                        <h6><span class="badge bg-primary">Author</span></h6>
                        <p class="text-muted mb-2">Submit papers, track submission status, and respond to reviewer feedback.</p>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i>
                        <strong>Note:</strong> Users can have multiple roles. For example, an Editor can also be assigned as a Reviewer.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection