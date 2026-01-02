@extends('layouts.app')

@section('page-title', 'Edit Profile')
@section('page-description', 'Update your profile information')

@section('page-actions')
    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Profile
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Profile Information Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Photo Upload -->
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <img src="{{ $user->profile_photo_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle img-thumbnail mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;"
                                     id="profilePhotoPreview">
                                
                                <div class="mt-2">
                                    <input type="file" class="form-control" id="photo" name="photo" 
                                           accept="image/*" onchange="previewImage(event)">
                                    <small class="text-muted">Max 2MB. JPG, PNG, GIF</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Basic Information -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="institution" class="form-label">Institution/Organization</label>
                                    <input type="text" class="form-control @error('institution') is-invalid @enderror" 
                                           id="institution" name="institution" value="{{ old('institution', $user->institution) }}">
                                    @error('institution')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                           id="department" name="department" value="{{ old('department', $user->department) }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Biography -->
                    <div class="mb-4">
                        <label for="biography" class="form-label">Biography</label>
                        <textarea class="form-control @error('biography') is-invalid @enderror" 
                                  id="biography" name="biography" rows="4">{{ old('biography', $user->biography) }}</textarea>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Tell us about your research interests, expertise, and background.
                        </small>
                    </div>
                    
                    <!-- Academic IDs -->
                    <h6 class="mb-3">Academic Profiles</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="orcid_id" class="form-label">ORCID ID</label>
                            <input type="text" class="form-control @error('orcid_id') is-invalid @enderror" 
                                   id="orcid_id" name="orcid_id" value="{{ old('orcid_id', $user->orcid_id) }}"
                                   placeholder="0000-0000-0000-0000">
                            @error('orcid_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <a href="https://orcid.org" target="_blank">Get ORCID</a>
                            </small>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="google_scholar_id" class="form-label">Google Scholar ID</label>
                            <input type="text" class="form-control @error('google_scholar_id') is-invalid @enderror" 
                                   id="google_scholar_id" name="google_scholar_id" value="{{ old('google_scholar_id', $user->google_scholar_id) }}">
                            @error('google_scholar_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="scopus_id" class="form-label">Scopus Author ID</label>
                            <input type="text" class="form-control @error('scopus_id') is-invalid @enderror" 
                                   id="scopus_id" name="scopus_id" value="{{ old('scopus_id', $user->scopus_id) }}">
                            @error('scopus_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password -->
        <div class="card" id="password">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Change Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Password must be at least 8 characters long.
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profilePhotoPreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        if (password && confirmPassword && password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Passwords do not match!');
            confirmPassword.focus();
        }
    });

    // Password visibility toggles
    function toggleVisibility(buttonId, inputId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        if (!btn || !input) return;
        btn.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                btn.querySelector('i').classList.remove('fa-eye');
                btn.querySelector('i').classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                btn.querySelector('i').classList.remove('fa-eye-slash');
                btn.querySelector('i').classList.add('fa-eye');
            }
        });
    }

    toggleVisibility('toggleCurrentPassword', 'current_password');
    toggleVisibility('toggleNewPassword', 'password');
    toggleVisibility('toggleConfirmPassword', 'password_confirmation');
</script>

<style>
    #profilePhotoPreview {
        border: 3px solid #dee2e6;
        transition: border-color 0.3s;
    }
    
    #profilePhotoPreview:hover {
        border-color: #4361ee;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
</style>
@endsection