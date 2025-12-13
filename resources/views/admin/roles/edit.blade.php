@extends('layouts.app')

@section('title', 'Edit Role: ' . $role->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Edit Role: {{ $role->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
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
                    <h4 class="card-title mb-4">Edit Role Information</h4>

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

                    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $role->name) }}" 
                                           {{ in_array($role->name, ['admin', 'editor', 'reviewer', 'author']) ? 'readonly' : '' }} required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if(in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                                    <small class="text-muted">System roles cannot be renamed</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guard_name" class="form-label">Guard Name *</label>
                                    <select class="form-control @error('guard_name') is-invalid @enderror" 
                                            id="guard_name" name="guard_name" required>
                                        <option value="web" {{ old('guard_name', $role->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                                        <option value="api" {{ old('guard_name', $role->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                                    </select>
                                    @error('guard_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="3"
                                      placeholder="Describe the purpose of this role...">{{ old('description', $role->description ?? '') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4" id="permissions">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">Assign Permissions</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                    <label class="form-check-label" for="selectAllPermissions">
                                        Select All
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                @if(isset($permissions) && $permissions->count() > 0)
                                    @foreach($permissions->chunk(3) as $chunk)
                                        @foreach($chunk as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input permission-checkbox" type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}" 
                                                       id="perm_{{ $permission->id }}"
                                                       {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                    <div class="alert alert-warning">
                                        <i class="ri-alert-line me-1"></i>
                                        No permissions found in the system.
                                    </div>
                                </div>
                                @endif
                            </div>
                            @error('permissions')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-save-line me-1"></i> Update Role
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
            <!-- Role Info Card -->
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title rounded-circle bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'editor' ? 'warning' : ($role->name == 'reviewer' ? 'info' : 'primary')) }} display-4">
                                {{ strtoupper(substr($role->name, 0, 1)) }}
                            </span>
                        </div>
                        <h4>{{ ucfirst($role->name) }}</h4>
                        
                        @if(in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                        <span class="badge bg-secondary">System Role</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6>Role Statistics</h6>
                        <div class="mb-2">
                            <span class="text-muted">Users assigned:</span>
                            <span class="float-end fw-bold">{{ $role->users_count ?? 0 }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Current permissions:</span>
                            <span class="float-end fw-bold">{{ $role->permissions_count ?? 0 }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Created:</span>
                            <span class="float-end">{{ $role->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri-information-line me-1"></i>
                        <strong>Note:</strong> Changes to permissions will affect all users assigned to this role.
                    </div>

                    @if(!in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete({{ $role->id }})">
                            <i class="ri-delete-bin-line me-1"></i> Delete Role
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(!in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
<form id="delete-form-{{ $role->id }}" 
      action="{{ route('admin.roles.destroy', $role) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection

@push('scripts')
<script>
    // Select All Permissions
    document.getElementById('selectAllPermissions').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Confirm Delete
    function confirmDelete(roleId) {
        if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            document.getElementById('delete-form-' + roleId).submit();
        }
    }
</script>
@endpush