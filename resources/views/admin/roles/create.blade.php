@extends('layouts.app')

@section('page-title', 'Create New Role')
@section('page-description', 'Define a new role and assign permissions to it')

@section('content')
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="ri-shield-user-line me-2"></i> Create New Role</h4>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back to Roles
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="ri-shield-user-line me-2"></i> Role Information</h5>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Role Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="e.g., managing_editor">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                Use lowercase with underscores (e.g., managing_editor)
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="guard_name" class="form-label">Guard Name *</label>
                            <select class="form-select @error('guard_name') is-invalid @enderror"
                                    id="guard_name" name="guard_name" required>
                                <option value="web" {{ old('guard_name', 'web') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                            @error('guard_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Usually "web" for web applications</small>
                        </div>
                    </div>

                    <div class="mb-4 mt-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  name="description" rows="3"
                                  placeholder="Describe the purpose of this role...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label mb-3">Assign Permissions</label>
                        <div class="row g-2">
                            @if(isset($permissions) && $permissions->count())
                                @foreach($permissions->chunk(3) as $chunk)
                                    @foreach($chunk as $permission)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   id="perm_{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                            <small class="text-muted d-block">{{ $permission->guard_name }}</small>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                            @else
                            <div class="col-12">
                                <div class="alert alert-warning mb-0">
                                    <i class="ri-alert-line me-1"></i>
                                    No permissions found. Please create permissions first.
                                </div>
                            </div>
                            @endif
                        </div>
                        @error('permissions')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-start gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-shield-user-line me-1"></i> Create Role
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="ri-information-line me-2"></i> Role Guidelines</h5>

                <div class="alert alert-info">
                    <strong>Role Naming Convention:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Use lowercase letters</li>
                        <li>Use underscores for spaces</li>
                        <li>Be descriptive (e.g., managing_editor)</li>
                        <li>Avoid spaces and special characters</li>
                    </ul>
                </div>

                <h6>Common Permission Groups:</h6>
                <div class="border rounded p-2 mb-2">
                    <strong>Paper Management:</strong>
                    <small class="text-muted d-block">manage_papers, view_papers, edit_papers</small>
                </div>
                <div class="border rounded p-2 mb-2">
                    <strong>User Management:</strong>
                    <small class="text-muted d-block">manage_users, view_users, edit_users</small>
                </div>
                <div class="border rounded p-2">
                    <strong>System Management:</strong>
                    <small class="text-muted d-block">manage_system, view_logs, manage_settings</small>
                </div>

                <div class="alert alert-warning mt-3 mb-0">
                    <i class="ri-alert-line me-1"></i>
                    Avoid creating roles that duplicate system default roles (admin, editor, reviewer, author).
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
