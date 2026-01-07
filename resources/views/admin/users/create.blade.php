@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-0">Add New User</h4>
                    <small class="text-muted">Create a new user account</small>
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
                          action="{{ route('admin.users.store') }}">
                        @csrf

                        {{-- Name & Email --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name') }}"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ old('email') }}"
                                       required>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       required>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        {{-- Roles --}}
                        <div class="mb-4">
                            <label class="form-label d-block mb-2">
                                Assign Roles
                            </label>

                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['admin','editor','reviewer','author'] as $role)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="roles[]"
                                               value="{{ $role }}"
                                               id="role_{{ $role }}"
                                               {{ in_array($role, old('roles', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                               for="role_{{ $role }}">
                                            {{ ucfirst($role) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @error('roles')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Options --}}
                        <div class="form-check mb-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="send_welcome_email"
                                   value="1"
                                   id="send_welcome_email"
                                   checked>
                            <label class="form-check-label"
                                   for="send_welcome_email">
                                Send welcome email
                            </label>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="email_verified"
                                   value="1"
                                   id="email_verified"
                                   checked>
                            <label class="form-check-label"
                                   for="email_verified">
                                Mark email as verified
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button onclick="return confirm('Are you sure?')" type="submit"
                                    class="btn btn-primary">
                                <i class="ri-user-add-line me-1"></i>
                                Create User
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

            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Role Overview</h6>

                    <div class="mb-3">
                        <strong>Admin</strong>
                        <p class="text-muted small mb-0">
                            Full system access & user management.
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Editor</strong>
                        <p class="text-muted small mb-0">
                            Manage papers & editorial workflow.
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Reviewer</strong>
                        <p class="text-muted small mb-0">
                            Review assigned submissions.
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Author</strong>
                        <p class="text-muted small mb-0">
                            Submit and track papers.
                        </p>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="ri-information-line me-1"></i>
                        A user can have multiple roles.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
