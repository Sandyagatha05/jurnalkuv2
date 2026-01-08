@extends('layouts.app')

@section('page-title', 'Role Details: ' . ucfirst($role->name))
@section('page-description', 'View detailed information about the role and its assigned users/permissions')

@section('content')
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="ri-shield-user-line me-2"></i> Role Details</h4>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
            <i class="ri-arrow-left-line me-1"></i> Back to Roles
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Role Info -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="avatar-lg mx-auto mb-3 d-flex justify-content-center align-items-center">
                    <span class="avatar-title rounded-circle bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'editor' ? 'warning' : ($role->name == 'reviewer' ? 'info' : 'primary')) }} display-4 text-white d-flex justify-content-center align-items-center"
                          style="width:80px; height:80px; font-size:2rem;">
                        {{ strtoupper(substr($role->name,0,1)) }}
                    </span>
                </div>

                <h4 class="mb-1">{{ ucfirst($role->name) }}</h4>
                <p class="text-muted mb-2">Role ID: #{{ $role->id }}</p>
                
                @if(in_array($role->name, ['admin','editor','reviewer','author']))
                <span class="badge bg-secondary mb-3">System Role</span>
                @endif

                <div class="text-start mt-3">
                    <h6 class="text-muted">Role Information</h6>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Guard Name:</span>
                        <span class="fw-bold">{{ $role->guard_name }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Users Assigned:</span>
                        <span class="fw-bold">{{ $role->users_count ?? 0 }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Permissions:</span>
                        <span class="fw-bold">{{ $role->permissions_count ?? 0 }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Created:</span>
                        <span>{{ $role->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Updated:</span>
                        <span>{{ $role->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                        <i class="ri-edit-line me-1"></i> Edit Role
                    </a>
                    @if(!in_array($role->name, ['admin','editor','reviewer','author']))
                    <button type="button" class="btn btn-outline-danger"
                            onclick="confirmDelete({{ $role->id }})">
                        <i class="ri-delete-bin-line me-1"></i> Delete Role
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-8 d-flex flex-column gap-4">
        <!-- Permissions Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0"><i class="ri-shield-keyhole-line me-1"></i> Assigned Permissions</h5>
                    <a href="{{ route('admin.roles.edit', $role) }}#permissions" class="btn btn-sm btn-light">
                        <i class="ri-edit-line me-1"></i> Manage Permissions
                    </a>
                </div>

                @if($role->permissions->count())
                <div class="row g-2">
                    @foreach($role->permissions->chunk(3) as $chunk)
                        @foreach($chunk as $permission)
                        <div class="col-md-4">
                            <div class="border rounded p-2 d-flex align-items-center justify-content-between">
                                <span>{{ $permission->name }}</span>
                                <span class="badge bg-secondary">{{ $permission->guard_name }}</span>
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

        <!-- Users Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="ri-user-line me-1"></i> Users with this Role</h5>

                @if(isset($users) && $users->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
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
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                            class="rounded-circle" style="width:36px; height:36px; object-fit:cover;" alt="{{ $user->name }}">
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="align-middle">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav>
                        <ul class="pagination gap-2">

                            {{-- Previous --}}
                            <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link btn-lift" href="{{ $users->previousPageUrl() ?? '#' }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            {{-- Page Numbers --}}
                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                @php
                                    $url = request()->has('search')
                                        ? $url . '&search=' . request('search')
                                        : $url;
                                @endphp
                                <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                                    <a class="page-link btn-lift" href="{{ $url }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endforeach

                            {{-- Next --}}
                            <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link btn-lift" href="{{ $users->nextPageUrl() ?? '#' }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>

                        </ul>
                    </nav>
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

@if(!in_array($role->name, ['admin','editor','reviewer','author']))
<form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endif
@endsection

@push('scripts')
<script>
function confirmDelete(roleId) {
    customConfirm('Are you sure you want to delete this role? <br>This action cannot be undone.').then(result => {
        if (result) {
            document.getElementById('delete-form-' + roleId).submit();
        }
    });
}
</script>
@endpush
