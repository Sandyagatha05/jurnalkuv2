@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-0">Edit User</h4>
                    <small class="text-muted">{{ $user->name }}</small>
                </div>
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- MAIN FORM --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

                    <h5 class="mb-4">User Information</h5>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        {{-- Name & Email --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Change Password
                                <small class="text-muted">(optional)</small>
                            </label>

                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="New password">
                                </div>
                                <div class="col-md-6">
                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control"
                                           placeholder="Confirm password">
                                </div>
                            </div>
                        </div>

                        {{-- Roles --}}
                        <div class="mb-4">
                            <label class="form-label d-block mb-2">Roles</label>

                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['admin','editor','reviewer','author'] as $role)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="roles[]"
                                               value="{{ $role }}"
                                               id="role_{{ $role }}"
                                               {{ in_array($role, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                               for="role_{{ $role }}">
                                            {{ ucfirst($role) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Email Verified --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="email_verified"
                                   value="1"
                                   id="email_verified"
                                   {{ $user->email_verified_at ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_verified">
                                Email verified
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button type="submit"
                                    class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Update User
                            </button>

                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">

            {{-- Profile --}}
            <div class="card mb-3">
                <div class="card-body text-center">
                    <img
                        src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                        class="rounded-circle mb-3"
                        width="96"
                        height="96"
                        alt="{{ $user->name }}">

                    <h5 class="mb-0">{{ $user->name }}</h5>
                    <small class="text-muted">{{ $user->email }}</small>

                    <div class="mt-3">
                        @foreach($user->roles as $role)
                            <span class="badge bg-secondary me-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3">User Info</h6>

                    <div class="small text-muted mb-1">
                        Registered
                    </div>
                    <div class="mb-2">
                        {{ $user->created_at->format('d M Y') }}
                    </div>

                    <div class="small text-muted mb-1">
                        Email Status
                    </div>
                    <div>
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Verified</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-danger mb-3">Danger Zone</h6>

                    <button type="button"
                            class="btn btn-outline-danger w-100"
                            onclick="confirmDelete({{ $user->id }})">
                        <i class="ri-delete-bin-line me-1"></i>
                        Delete User
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<form id="delete-form-{{ $user->id }}"
      action="{{ route('admin.users.destroy', $user) }}"
      method="POST"
      class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
