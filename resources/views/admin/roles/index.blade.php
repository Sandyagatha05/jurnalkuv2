@extends('layouts.app')

@section('page-title', 'Roles Management')
@section('page-description', 'Manage user roles and permissions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="fas fa-user-tag me-2"></i> Roles Management</h4>
    <div class="btn-group">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Create Role
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if($roles->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#ID</th>
                            <th>Role Name</th>
                            <th>Users Count</th>
                            <th>Permissions</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>#{{ $role->id }}</td>
                            <td>
                                <strong>{{ ucfirst($role->name) }}</strong>
                                @if(in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                                    <span class="badge bg-info ms-2">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $role->users_count }} user{{ $role->users_count > 1 ? 's' : '' }}</span>
                            </td>
                            <td>
                                @if($role->permissions->count())
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
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!in_array($role->name, ['admin','editor','reviewer','author']))
                                        <button type="button" class="btn btn-outline-danger" title="Delete" 
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

            <div class="p-3 d-flex justify-content-end">
                {{ $roles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                <h4 class="text-muted mb-3">No Roles Found</h4>
                <p class="text-muted mb-3">Create your first role to get started.</p>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Create Role
                </a>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(roleName, deleteUrl) {
    customConfirm(`Are you sure you want to delete the role "${roleName}"?
    <br>This action cannot be undone.`).then(result => {
        if (result) {
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
    });
}
</script>
@endpush