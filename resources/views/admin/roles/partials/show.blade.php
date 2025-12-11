@extends('layouts.app')

@section('title', 'Role Details: ' . $role->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Role Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">{{ ucfirst($role->name) }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
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
                        <p class="text-muted">Role ID: #{{ $role->id }}</p>
                        
                        @if(in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                        <span class="badge bg-secondary">System Role</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Role Information</h6>
                        <div class="mb-2">
                            <span class="text-muted">Guard Name:</span>
                            <span class="float-end fw-bold">{{ $role->guard_name }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Users with this role:</span>
                            <span class="float-end fw-bold">{{ $role->users_count ?? 0 }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Assigned Permissions:</span>
                            <span class="float-end fw-bold">{{ $role->permissions_count ?? 0 }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Created:</span>
                            <span class="float-end">{{ $role->created_at->format('F d, Y H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Updated:</span>
                            <span class="float-end">{{ $role->updated_at->format('F d, Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                            <i class="ri-edit-line me-1"></i> Edit Role
                        </a>
                        @if(!in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete({{ $role->id }})">
                            <i class="ri-delete-bin-line me-1"></i> Delete Role
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Permissions Card -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Assigned Permissions</h5>
                        <a href="{{ route('admin.roles.edit', $role) }}#permissions" class="btn btn-sm btn-light">
                            <i class="ri-edit-line me-1"></i> Manage Permissions
                        </a>
                    </div>
                    
                    @if($role->permissions->count() > 0)
                    <div class="row">
                        @foreach($role->permissions->chunk(3) as $chunk)
                            @foreach($chunk as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="border rounded p-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked disabled>
                                        <label class="form-check-label">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                    <small class="text-muted">{{ $permission->guard_name }}</small>
                                </div>
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="ri-shield-keyhole-line display-4 text-muted"></i>
                        <h5 class="mt-3">No permissions assigned</h5>
                        <p class="text-muted">This role doesn't have any permissions yet.</p>
                        <a href="{{ route('admin.roles.edit', $role) }}#permissions" class="btn btn-primary">
                            <i class="ri-shield-check-line me-1"></i> Assign Permissions
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Users with this Role -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Users with this Role</h5>
                    
                    @if(isset($users) && $users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                                     class="rounded-circle avatar-xs" alt="{{ $user->name }}">
                                            </div>
                                            <div class="flex-grow-1">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($user->email_verified_at)
                                        <span class="badge bg-success">Verified</span>
                                        @else
                                        <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for Users -->
                    @if($users->hasPages())
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                    @endif
                    
                    @else
                    <div class="text-center py-4">
                        <i class="ri-user-search-line display-4 text-muted"></i>
                        <h5 class="mt-3">No users assigned</h5>
                        <p class="text-muted">No users have been assigned this role yet.</p>
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
    function confirmDelete(roleId) {
        if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            document.getElementById('delete-form-' + roleId).submit();
        }
    }
</script>
@endpush