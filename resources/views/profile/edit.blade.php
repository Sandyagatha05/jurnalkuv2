@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Profile</h4>
            <p class="text-muted mb-0">Update your personal and account information</p>
        </div>
        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- PROFILE INFO --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-user-edit me-2"></i> Profile Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault();
                    customConfirm('Are you sure you want to update profile?').then(result => { if(result) this.submit(); });">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- PHOTO --}}
                            <div class="col-md-4 mb-4 text-center">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                                     class="rounded-circle img-thumbnail mb-3" id="profilePhotoPreview"
                                     style="width:140px;height:140px;object-fit:cover;border:3px solid #dee2e6;transition:border-color 0.3s;">
                                
                                <input type="file" class="form-control mt-2" name="photo" accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted d-block mt-1">Max 2MB. JPG, PNG, GIF</small>
                            </div>

                            {{-- BASIC INFO --}}
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="name">Full Name *</label>
                                        <input type="text" name="name" id="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="email">Email Address *</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="tel" name="phone" id="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone', $user->phone) }}">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="institution">Institution</label>
                                        <input type="text" name="institution" id="institution"
                                               class="form-control @error('institution') is-invalid @enderror"
                                               value="{{ old('institution', $user->institution) }}">
                                        @error('institution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label" for="department">Department</label>
                                        <input type="text" name="department" id="department"
                                               class="form-control @error('department') is-invalid @enderror"
                                               value="{{ old('department', $user->department) }}">
                                        @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea name="address" id="address" rows="2"
                                                  class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BIOGRAPHY --}}
                        <div class="mb-4 mt-3">
                            <label class="form-label" for="biography">Biography</label>
                            <textarea name="biography" id="biography" rows="4"
                                      class="form-control @error('biography') is-invalid @enderror">{{ old('biography', $user->biography) }}</textarea>
                            @error('biography')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Research interests, expertise, and background.</small>
                        </div>

                        {{-- ACADEMIC IDS --}}
                        <h6 class="mb-3">Academic Profiles</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label" for="orcid_id">ORCID ID</label>
                                <input type="text" name="orcid_id" id="orcid_id"
                                       class="form-control @error('orcid_id') is-invalid @enderror"
                                       value="{{ old('orcid_id', $user->orcid_id) }}">
                                <small class="text-muted">
                                    <a href="https://orcid.org" target="_blank">Get ORCID</a>
                                </small>
                                @error('orcid_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="google_scholar_id">Google Scholar ID</label>
                                <input type="text" name="google_scholar_id" id="google_scholar_id"
                                       class="form-control @error('google_scholar_id') is-invalid @enderror"
                                       value="{{ old('google_scholar_id', $user->google_scholar_id) }}">
                                @error('google_scholar_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="scopus_id">Scopus Author ID</label>
                                <input type="text" name="scopus_id" id="scopus_id"
                                       class="form-control @error('scopus_id') is-invalid @enderror"
                                       value="{{ old('scopus_id', $user->scopus_id) }}">
                                @error('scopus_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CHANGE PASSWORD --}}
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-lock me-2"></i> Change Password</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST" onsubmit="event.preventDefault();
                    customConfirm('Are you sure you want to change your password?').then(result => { if(result) this.submit(); });">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="current_password">Current Password *</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggleCurrentPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="password">New Password *</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="password_confirmation">Confirm New Password *</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-key me-1"></i> Update Password
                                </button>
                            </div>
                        </div>

                        <small class="text-muted mt-3 d-block">
                            <i class="fas fa-info-circle me-1"></i> Password must be at least 8 characters long.
                        </small>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = () => document.getElementById('profilePhotoPreview').src = reader.result;
        reader.readAsDataURL(event.target.files[0]);
    }

    function toggleVisibility(buttonId, inputId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        if (!btn || !input) return;
        btn.addEventListener('click', () => {
            if (input.type === 'password') {
                input.type = 'text';
                btn.querySelector('i').classList.replace('fa-eye','fa-eye-slash');
            } else {
                input.type = 'password';
                btn.querySelector('i').classList.replace('fa-eye-slash','fa-eye');
            }
        });
    }

    toggleVisibility('toggleCurrentPassword','current_password');
    toggleVisibility('toggleNewPassword','password');
    toggleVisibility('toggleConfirmPassword','password_confirmation');
</script>

<style>
    #profilePhotoPreview:hover { border-color: var(--primary-color); }
</style>
@endsection
