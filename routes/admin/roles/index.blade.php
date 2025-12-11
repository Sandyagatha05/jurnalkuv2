<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Management - Jurnalku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .badge-admin { background-color: #dc3545; }
        .badge-editor { background-color: #ffc107; color: #000; }
        .badge-reviewer { background-color: #0dcaf0; }
        .badge-author { background-color: #0d6efd; }
        .avatar-xs { width: 32px; height: 32px; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">Role Management</h1>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="ri-shield-user-line me-1"></i> Add New Role
                    </a>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Roles Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Role Name</th>
                                        <th>Guard Name</th>
                                        <th>Users</th>
                                        <th>Permissions</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                    <tr>
                                        <td>#{{ $role->id }}</td>
                                        <td>
                                            @php
                                                $badgeClass = 'badge ';
                                                if($role->name == 'admin') $badgeClass .= 'bg-danger';
                                                elseif($role->name == 'editor') $badgeClass .= 'bg-warning text-dark';
                                                elseif($role->name == 'reviewer') $badgeClass .= 'bg-info';
                                                else $badgeClass .= 'bg-primary';
                                            @endphp
                                            <span class="{{ $badgeClass }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        </td>
                                        <td>{{ $role->guard_name }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $role->users_count ?? 0 }}</span> users
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $role->permissions_count ?? 0 }}</span> permissions
                                        </td>
                                        <td>{{ $role->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-secondary">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                @if(!in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDelete({{ $role->id }})">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endif
                                            </div>
                                            @if(!in_array($role->name, ['admin', 'editor', 'reviewer', 'author']))
                                            <form id="delete-form-{{ $role->id }}" 
                                                  action="{{ route('admin.roles.destroy', $role) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-3">
                                                <i class="ri-shield-keyhole-line display-4 text-muted"></i>
                                                <h5 class="mt-3">No roles found</h5>
                                                <p class="text-muted">Create your first role to get started</p>
                                                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mt-2">
                                                    <i class="ri-shield-user-line me-1"></i> Create First Role
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($roles->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $roles->links() }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Default Roles Info -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">System Default Roles</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-danger me-2">Admin</span>
                                        <span class="badge bg-secondary">System</span>
                                    </div>
                                    <p class="text-muted mb-0 small">Full system access, user management</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-warning me-2">Editor</span>
                                        <span class="badge bg-secondary">System</span>
                                    </div>
                                    <p class="text-muted mb-0 small">Manage journal issues, papers, assignments</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-info me-2">Reviewer</span>
                                        <span class="badge bg-secondary">System</span>
                                    </div>
                                    <p class="text-muted mb-0 small">Review papers, submit evaluations</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-primary me-2">Author</span>
                                        <span class="badge bg-secondary">System</span>
                                    </div>
                                    <p class="text-muted mb-0 small">Submit papers, track status</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(roleId) {
            if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
                document.getElementById('delete-form-' + roleId).submit();
            }
        }
    </script>
</body>
</html>