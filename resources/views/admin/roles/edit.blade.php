@extends('layouts.app')

@section('page-title', 'Edit Role: ' . ucfirst($role->name))
@section('page-description', 'Modify role details and permissions')

@section('page-actions')
<a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back to Roles
</a>
@endsection

@section('content')
<div class="row">
    {{-- LEFT: Form --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Edit Role Information</h5>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                    <div class="row g-3">
                        <div class="col-md-6">
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

                        <div class="col-md-6">
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

                    <div class="mb-4 mt-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="3" placeholder="Describe this role...">{{ old('description', $role->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Permissions --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Assign Permissions</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                <label class="form-check-label" for="selectAllPermissions">Select All</label>
                            </div>
                        </div>

                        <div class="row g-2">
                            @forelse($permissions->chunk(3) as $chunk)
                                @foreach($chunk as $permission)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" 
                                                   name="permissions[]" value="{{ $permission->id }}" 
                                                   id="perm_{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                            <small class="text-muted d-block">{{ $permission->guard_name }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        <i class="ri-alert-line me-1"></i> No permissions found
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        @error('permissions')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Role Info --}}
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="avatar-lg mx-auto mb-3 d-flex flex-column align-items-center">
                    <span class="avatar-title rounded-circle 
                        bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'editor' ? 'warning' : ($role->name == 'reviewer' ? 'info' : 'primary')) }}"
                        style="width:80px; height:80px; display:flex; align-items:center; justify-content:center; font-size:2rem; line-height:1;">
                        {{ strtoupper(substr($role->name, 0, 1)) }}
                    </span>
                </div>
                <h4 class="text-center">{{ ucfirst($role->name) }}</h4>
                @if(in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                    <span class="badge bg-secondary mb-2">System Role</span>
                @endif

                <hr>

                <div class="text-start">
                    <p class="mb-1"><strong>Users Assigned:</strong> {{ $role->users_count ?? 0 }}</p>
                    <p class="mb-1"><strong>Permissions:</strong> {{ $role->permissions_count ?? 0 }}</p>
                    <p class="mb-0"><strong>Created:</strong> {{ $role->created_at->format('M d, Y') }}</p>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="ri-information-line me-1"></i> Changes affect all users with this role
                </div>

                @if(!in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                    <button type="button" class="btn btn-outline-danger w-100 mt-3" onclick="confirmDelete({{ $role->id }})">
                        <i class="ri-delete-bin-line me-1"></i> Delete Role
                    </button>
                    <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Select All Permissions
    document.getElementById('selectAllPermissions').addEventListener('change', function() {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // Confirm Delete
    function confirmDelete(roleId) {
        if(confirm('Are you sure you want to delete this role?')) {
            document.getElementById('delete-form-' + roleId).submit();
        }
    }
</script>
@endpush
