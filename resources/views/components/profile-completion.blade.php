@props(['user'])

<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><i class="fas fa-tasks me-2"></i> Profile Completion</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <div class="d-flex justify-content-between mb-2">
                <span>Completion Status</span>
                <span>{{ $user->profile_completion }}%</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-success" role="progressbar" 
                     style="width: {{ $user->profile_completion }}%"></div>
            </div>
        </div>
        
        <ul class="list-unstyled mb-0">
            @php
                $fields = [
                    'name' => ['label' => 'Full Name', 'value' => !empty($user->name)],
                    'email' => ['label'