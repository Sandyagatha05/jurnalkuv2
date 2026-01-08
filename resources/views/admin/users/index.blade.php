@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">

    <!-- ===================== -->
    <!-- PAGE HEADER -->
    <!-- ===================== -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">User Management</h4>
            <p class="text-muted mb-0">Manage registered users, roles, and access</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ri-user-add-line me-1"></i>
            Add New User
        </a>
    </div>

    <!-- ===================== -->
    <!-- CARD -->
    <!-- ===================== -->
    <div class="card dashboard-card">
        <div class="card-body">

            <!-- ===================== -->
            <!-- FILTERS -->
            <!-- ===================== -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="ri-search-line"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search users..."
                            id="searchInput">
                    </div>
                </div>

                <div class="col-md-3">
                    <select class="form-select" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="reviewer">Reviewer</option>
                        <option value="author">Author</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="verified">Verified</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <!-- ===================== -->
            <!-- TABLE -->
            <!-- ===================== -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Papers</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <input
                                    class="form-check-input user-checkbox"
                                    type="checkbox"
                                    value="{{ $user->id }}">
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img
                                        src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=193366&background=E8EEF9' }}"
                                        class="rounded-circle avatar-sm"
                                        alt="{{ $user->name }}">

                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $user->email }}</td>

                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge role-badge role-{{ $role->name }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </td>

                            <td>
                                @if($user->hasRole('author'))
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        {{ $user->papers_count ?? 0 }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success-subtle text-success">
                                        Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-1">

                                    {{-- View --}}
                                    <a href="{{ route('admin.users.show', $user) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="View User">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Edit User">
                                        <i class="ri-edit-line"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete User"
                                        onclick="confirmDelete({{ $user->id }})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                </div>

                                <form
                                    id="delete-form-{{ $user->id }}"
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <i class="ri-user-search-line fs-1 text-muted"></i>
                                    <h5 class="mt-3">No users found</h5>
                                    <p class="text-muted mb-3">
                                        Start by adding your first user
                                    </p>
                                    <a href="{{ route('admin.users.create') }}"
                                       class="btn btn-primary">
                                        <i class="ri-user-add-line me-1"></i>
                                        Add First User
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ===================== -->
            <!-- PAGINATION -->
            <!-- ===================== -->
            @if ($users->hasPages())
            <div class="d-flex justify-content-center mt-5">
                <nav>
                    <ul class="pagination gap-2">

                        {{-- Previous --}}
                        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link btn-lift"
                            href="{{ $users->previousPageUrl() ?? '#' }}">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Pages --}}
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @php
                                // pertahankan query lain (misal ?search=)
                                foreach(request()->except('page') as $key => $value) {
                                    $url .= '&'.$key.'='.$value;
                                }
                            @endphp

                            <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                                <a class="page-link btn-lift" href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endforeach

                        {{-- Next --}}
                        <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link btn-lift"
                            href="{{ $users->nextPageUrl() ?? '#' }}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ===================== -->
<!-- STYLES -->
<!-- ===================== -->
<style>
.dashboard-card {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
}

.admin-table th {
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #64748b;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    object-fit: cover;
}

.role-badge {
    font-weight: 500;
    padding: .35em .6em;
    border-radius: 999px;
}

.role-admin { background:#fee2e2; color:#b91c1c; }
.role-editor { background:#fef3c7; color:#92400e; }
.role-reviewer { background:#e0f2fe; color:#0369a1; }
.role-author { background:#e0e7ff; color:#3730a3; }

.pagination .page-link {
    border-radius: .5rem;
    border: 1px solid var(--border);
    color: var(--foreground);
    padding: .5rem .75rem;
    background: white;
    transition: all .2s ease;
}

.pagination .page-link:hover {
    background: #1933660d;
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination .page-item.disabled .page-link {
    opacity: .4;
    pointer-events: none;
}

</style>
@endsection

@push('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.user-checkbox')
        .forEach(cb => cb.checked = this.checked);
});

function confirmDelete(userId) {
    customConfirm('Are you sure you want to delete this user?<br>This action cannot be undone.').then(result => {
        if(result)
        document.getElementById('delete-form-' + userId).submit();
    });
}

document.getElementById('searchInput').addEventListener('keyup', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});

document.getElementById('roleFilter').addEventListener('change', function () {
    const role = this.value;
    document.querySelectorAll('tbody tr').forEach(row => {
        const badges = row.querySelectorAll('.role-badge');
        let show = role === '';
        badges.forEach(b => {
            if (b.textContent.toLowerCase().includes(role)) show = true;
        });
        row.style.display = show ? '' : 'none';
    });
});
</script>
@endpush
