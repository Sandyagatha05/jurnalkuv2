@extends('layouts.app')

@section('page-title', 'Assign Reviewers')
@section('page-description', 'Assign reviewers to paper: ' . $paper->title)

@section('page-actions')
    <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Paper
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i> Assign Reviewers
                    <span class="badge bg-primary">{{ $paper->reviewAssignments->count() }} assigned</span>
                </h5>
            </div>
            <div class="card-body">
                <!-- Paper Info -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-file-alt fa-2x text-info"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">{{ $paper->title }}</h6>
                            <p class="mb-1"><strong>Author:</strong> {{ $paper->author->name }}</p>
                            <p class="mb-0"><strong>Submitted:</strong> {{ $paper->submitted_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('editor.papers.store-assign-reviewers', $paper) }}" method="POST">
                    @csrf
                    
                    <!-- Reviewers Selection -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Select Reviewers</h6>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Select 2-3 reviewers for this paper. Reviewers will receive email notifications.
                        </div>
                        
                        @if($reviewers->count() > 0)
                            <div class="row">
                                @foreach($reviewers as $reviewer)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border reviewer-card">
                                            <div class="card-body">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="reviewers[]" value="{{ $reviewer->id }}" 
                                                           id="reviewer{{ $reviewer->id }}"
                                                           {{ in_array($reviewer->id, $assignedReviewers->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="reviewer{{ $reviewer->id }}">
                                                        {{ $reviewer->name }}
                                                    </label>
                                                </div>
                                                
                                                <div class="ms-4">
                                                    <p class="mb-1">
                                                        <i class="fas fa-university me-1 text-muted"></i>
                                                        {{ $reviewer->institution }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <i class="fas fa-briefcase me-1 text-muted"></i>
                                                        {{ $reviewer->department }}
                                                    </p>
                                                    <p class="mb-2">
                                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                                        {{ $reviewer->email }}
                                                    </p>
                                                    
                                                    <!-- Reviewer Stats -->
                                                    <div class="d-flex justify-content-between small text-muted">
                                                        <span>
                                                            <i class="fas fa-tasks me-1"></i>
                                                            {{ $reviewer->reviewAssignments->where('status', 'pending')->count() }} pending
                                                        </span>
                                                        <span>
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            {{ $reviewer->reviewAssignments->where('status', 'completed')->count() }} completed
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted mb-3">No Reviewers Available</h5>
                                <p class="text-muted">Please add reviewers to the system first.</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                                    <i class="fas fa-users me-2"></i> Manage Users
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Review Details -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Review Details</h6>
                        
                        <!-- Due Date -->
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Review Due Date *</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" 
                                   value="{{ old('due_date', date('Y-m-d', strtotime('+2 weeks'))) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Set a reasonable deadline for reviewers (recommended: 2-4 weeks)</small>
                        </div>
                        
                        <!-- Editor Notes -->
                        <div class="mb-3">
                            <label for="editor_notes" class="form-label">Notes to Reviewers (Optional)</label>
                            <textarea class="form-control @error('editor_notes') is-invalid @enderror" 
                                      id="editor_notes" name="editor_notes" rows="3">{{ old('editor_notes') }}</textarea>
                            @error('editor_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Provide any specific instructions or focus areas for the reviewers
                            </small>
                        </div>
                    </div>

                    <!-- Currently Assigned Reviewers -->
                    @if($assignedReviewers->count() > 0)
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Currently Assigned Reviewers</h6>
                            <div class="row">
                                @foreach($assignedReviewers as $reviewer)
                                    <div class="col-md-6 mb-2">
                                        <div class="card border border-primary">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $reviewer->name }}</strong>
                                                        <small class="d-block text-muted">{{ $reviewer->institution }}</small>
                                                    </div>
                                                    <span class="badge bg-primary">Assigned</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Submission -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i> Assign Reviewers & Start Review
                        </button>
                        <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Review Guidelines -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Review Assignment Guidelines</h6>
            </div>
            <div class="card-body">
                <ol>
                    <li>Select 2-3 qualified reviewers for each paper</li>
                    <li>Avoid assigning reviewers with conflicts of interest</li>
                    <li>Consider reviewers' expertise and availability</li>
                    <li>Set reasonable deadlines (2-4 weeks recommended)</li>
                    <li>Reviewers will receive email notifications automatically</li>
                    <li>You can reassign reviewers if needed</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
    .reviewer-card {
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .reviewer-card:hover {
        border-color: #4361ee;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .reviewer-card .form-check-input:checked + label {
        color: #4361ee;
    }
    
    .reviewer-card.selected {
        border-color: #4361ee;
        background-color: rgba(67, 97, 238, 0.05);
    }
</style>

@push('scripts')
<script>
    // Style reviewer cards when selected
    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.reviewer-card');
            if (this.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
        
        // Initialize selected state
        if (checkbox.checked) {
            checkbox.closest('.reviewer-card')?.classList.add('selected');
        }
    });
    
    // Limit to 3 reviewers maximum
    const maxReviewers = 3;
    const checkboxes = document.querySelectorAll('input[name="reviewers[]"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('input[name="reviewers[]"]:checked').length;
            
            if (checkedCount > maxReviewers) {
                this.checked = false;
                alert(`Maximum ${maxReviewers} reviewers allowed.`);
            }
        });
    });
    
    // Set minimum date for due date
    document.getElementById('due_date').min = new Date().toISOString().split('T')[0];
</script>
@endpush
@endsection