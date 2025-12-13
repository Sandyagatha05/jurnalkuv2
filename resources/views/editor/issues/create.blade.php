@extends('layouts.app')

@section('page-title', 'Create New Issue')
@section('page-description', 'Create a new journal issue')

@section('page-actions')
    <a href="{{ route('editor.issues.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Issues
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create New Issue</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('editor.issues.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Issue Identification</h6>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="volume" class="form-label">Volume *</label>
                                <input type="number" class="form-control @error('volume') is-invalid @enderror" 
                                       id="volume" name="volume" value="{{ old('volume') }}" min="1" required>
                                @error('volume')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Volume number (e.g., 1, 2, 3)</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="number" class="form-label">Number *</label>
                                <input type="number" class="form-control @error('number') is-invalid @enderror" 
                                       id="number" name="number" value="{{ old('number') }}" min="1" required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Issue number within volume (e.g., 1, 2, 3, 4)</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label">Year *</label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                       id="year" name="year" value="{{ old('year', date('Y')) }}" 
                                       min="2000" max="2050" required>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Publication year</small>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            The combination of Volume, Number, and Year must be unique.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Issue Details</h6>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Issue Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">e.g., "Advances in Computer Science Research"</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Brief description of the issue's focus</small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Publication Details</h6>
                        
                        <div class="mb-3">
                            <label for="published_date" class="form-label">Scheduled Publication Date</label>
                            <input type="date" class="form-control @error('published_date') is-invalid @enderror" 
                                   id="published_date" name="published_date" value="{{ old('published_date') }}">
                            @error('published_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty for now, set when publishing</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_special" name="is_special" value="1"
                                       {{ old('is_special') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_special">
                                    This is a special issue
                                </label>
                            </div>
                            <small class="text-muted">Check if this is a special/theme issue</small>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i> Create Issue
                        </button>
                        <a href="{{ route('editor.issues.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Existing Issues -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i> Recent Issues</h6>
            </div>
            <div class="card-body">
                @php
                    $recentIssues = \App\Models\Issue::with('editor')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @if($recentIssues->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentIssues as $issue)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            Vol. {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }})
                                        </h6>
                                        <small class="text-muted">{{ Str::limit($issue->title, 50) }}</small>
                                    </div>
                                    <span class="badge bg-{{ $issue->status == 'published' ? 'success' : ($issue->status == 'draft' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($issue->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No issues created yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-calculate volume based on year if needed
    document.getElementById('year').addEventListener('change', function() {
        const year = parseInt(this.value);
        const currentYear = new Date().getFullYear();
        
        // Suggest volume based on year (e.g., 2023 -> Volume 4 if base year is 2020)
        if (year >= 2020) {
            const suggestedVolume = year - 2019; // Assuming Vol 1 = 2020
            document.getElementById('volume').value = suggestedVolume;
        }
    });
    
    // Validate unique combination
    document.querySelector('form').addEventListener('submit', function(e) {
        const volume = document.getElementById('volume').value;
        const number = document.getElementById('number').value;
        const year = document.getElementById('year').value;
        
        // You could add AJAX check here for existing combination
        // For now, just basic validation
        if (volume && number && year) {
            console.log(`Checking: Vol. ${volume}, No. ${number} (${year})`);
        }
    });
</script>
@endpush