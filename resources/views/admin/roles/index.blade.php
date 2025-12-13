@extends('layouts.app')

@section('page-title', 'Roles Management')
@section('page-description', 'Manage user roles and permissions')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Create Role
        </a>
        <a href="{{ route('admin.roles.permissions.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-key me-1"></i> Manage Permissions
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Roles List</h5>
    </div>
    <div class="card-body">
        @if($roles->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role Name</th>
                            <th>Users Count</th>
                            <th>Permissions</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>#{{ $role->id }}</td>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if(in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                                        <span class="badge bg-info ms-2">System Role</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $role->users_count }} users</span>
                                </td>
                                <td>
                                    @if($role->permissions->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($role->permissions->take(3) as $permission)
                                                <span class="badge bg-secondary">{{ $permission->name }}</span>
                                            @endforeach
                                            @if($role->permissions->count() > 3)
                                                <span class="badge bg-light text-dark">+{{ $role->permissions->count() - 3 }} more</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No permissions</span>
                                    @endif
                                </td>
                                <td>{{ $role->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="confirmDelete('{{ $role->name }}', '{{ route('admin.roles.destroy', $role) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $roles->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                <h4 class="text-muted mb-3">No Roles Found</h4>
                <p class="text-muted">Create your first role to get started.</p>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i> Create Role
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(roleName, deleteUrl) {
        if (confirm(`Are you sure you want to delete the role "${roleName}"? This action cannot be undone.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush