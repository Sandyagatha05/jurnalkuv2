@extends('layouts.app')

@section('page-title', 'Role Management')
@section('page-description', 'Manage system roles and permissions')

@section('page-actions')
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Add Role
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">System Roles</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Role management interface is under development. Coming soon!
        </div>
        
        <div class="text-center py-5">
            <i class="fas fa-user-tag fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">Role Management</h4>
            <p class="text-muted mb-4">
                This feature allows you to create, edit, and manage system roles and permissions.
            </p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection