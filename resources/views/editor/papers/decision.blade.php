@extends('layouts.app')

@section('page-title', 'Make Editorial Decision')
@section('page-description', 'Final decision for paper: ' . $paper->title)

@section('page-actions')
    <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Paper
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-gavel me-2"></i> Make Editorial Decision
                    <span class="badge bg-light text-dark">{{ $paper->reviewAssignments->where('status', 'completed')->count() }}/{{ $paper->reviewAssignments->count() }} reviews complete</span>
                </h5>
            </div>
            <div class="card-body">
                <!-- Paper Info -->
                <div class="alert alert-light border mb-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-1">{{ $paper->title }}</h6>
                            <p class="mb-1"><strong>Author:</strong> {{ $paper->author->name }}</p>
                            <p class="mb-0"><strong>Abstract:</strong> {{ Str::limit($paper->abstract, 150) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Review Summary -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Review Summary</h6>
                    
                    <div class="row mb-4">
                        @php
                            $recommendations = $paper->reviewAssignments
                                ->where('status', 'completed')
                                ->map(function($assignment) {
                                    return $assignment->review->recommendation ?? null;
                                })
                                ->filter()
                                ->values();
                            
                            $recommendationCounts = [
                                'accept' => 0,
                                'minor_revision' => 0,
                                'major_revision' => 0,
                                'reject' => 0,
                            ];
                            
                            foreach ($recommendations as $rec) {
                                if (isset($recommendationCounts[$rec])) {
                                    $recommendationCounts[$rec]++;
                                }
                            }
                        @endphp
                        
                        @foreach($recommendationCounts as $rec => $count)
                            @if($count > 0)
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body py-3">
                                            <div class="display-6 fw-bold text-{{ 
                                                $rec == 'accept' ? 'success' : 
                                                ($rec == 'reject' ? 'danger' : 'warning') 
                                            }} mb-2">{{ $count }}</div>
                                            <h6 class="mb-0 text-{{ 
                                                $rec == 'accept' ? 'success' : 
                                                ($rec == 'reject' ? 'danger' : 'warning') 
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $rec)) }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <!-- Reviewer Comments -->
                    @if($paper->reviewAssignments->where('status', 'completed')->count() > 0)
                        <h6 class="mb-3">Reviewer Comments</h6>
                        <div class="row">
                            @foreach($paper->reviewAssignments->where('status', 'completed') as $assignment)
                                @if($assignment->review)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="mb-1">{{ $assignment->reviewer->name }}</h6>
                                                        <small class="text-muted">{{ $assignment->reviewer->institution }}</small>
                                                    </div>
                                                    <span class="badge bg-{{ 
                                                        $assignment->review->recommendation == 'accept' ? 'success' : 
                                                        ($assignment->review->recommendation == 'reject' ? 'danger' : 'warning') 
                                                    }}">
                                                        {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                                    </span>
                                                </div>
                                                
                                                <p class="card-text">
                                                    <small>
                                                        {{ Str::limit($assignment->review->comments_to_editor, 100) }}
                                                    </small>
                                                </p>
                                                
                                                <!-- Scores -->
                                                <div class="mt-3">
                                                    <small class="text-muted d-block mb-1">Scores:</small>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="badge bg-secondary">O: {{ $assignment->review->originality_score }}/5</span>
                                                        <span class="badge bg-secondary">C: {{ $assignment->review->contribution_score }}/5</span>
                                                        <span class="badge bg-secondary">M: {{ $assignment->review->methodology_score }}/5</span>
                                                        <span class="badge bg-secondary">O: {{ $assignment->review->overall_score }}/5</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Decision Form -->
                <form action="{{ route('editor.papers.store-decision', $paper) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Editorial Decision</h6>
                        
                        <!-- Decision Options -->
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card decision-option border-success text-center h-100" data-decision="accept">
                                    <div class="card-body py-4">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h5 class="card-title text-success">Accept</h5>
                                        <p class="card-text small">Paper accepted for publication</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card decision-option border-warning text-center h-100" data-decision="minor_revision">
                                    <div class="card-body py-4">
                                        <i class="fas fa-edit fa-3x text-warning mb-3"></i>
                                        <h5 class="card-title text-warning">Minor Revision</h5>
                                        <p class="card-text small">Minor changes required</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card decision-option border-warning text-center h-100" data-decision="major_revision">
                                    <div class="card-body py-4">
                                        <i class="fas fa-redo fa-3x text-warning mb-3"></i>
                                        <h5 class="card-title text-warning">Major Revision</h5>
                                        <p class="card-text small">Significant changes required</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card decision-option border-danger text-center h-100" data-decision="reject">
                                    <div class="card-body py-4">
                                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                        <h5 class="card-title text-danger">Reject</h5>
                                        <p class="card-text small">Paper not suitable</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden Decision Field -->
                        <input type="hidden" name="decision" id="decision" value="{{ old('decision') }}" required>
                        @error('decision')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <!-- Editor Notes -->
                        <div class="mb-3">
                            <label for="editor_notes" class="form-label">Decision Notes (Optional)</label>
                            <textarea class="form-control @error('editor_notes') is-invalid @enderror" 
                                      id="editor_notes" name="editor_notes" rows="4">{{ old('editor_notes') }}</textarea>
                            @error('editor_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Provide feedback to the author. This will be included in the decision email.
                            </small>
                        </div>
                        
                        <!-- Notify Author -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notify_author" name="notify_author" value="1" checked>
                            <label class="form-check-label" for="notify_author">
                                Send email notification to author
                            </label>
                        </div>
                    </div>

                    <!-- Submission -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                            <i class="fas fa-gavel me-2"></i> Submit Decision
                        </button>
                        <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Decision Guidelines -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Decision Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>Accept:</strong> Paper meets all criteria and requires no changes</li>
                    <li><strong>Minor Revision:</strong> Small changes needed, can be approved by editor</li>
                    <li><strong>Major Revision:</strong> Significant changes needed, requires re-review</li>
                    <li><strong>Reject:</strong> Paper does not meet journal standards</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .decision-option {
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .decision-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .decision-option.selected {
        background-color: rgba(67, 97, 238, 0.05);
        border-width: 2px;
    }
</style>

@push('scripts')
<script>
    // Decision selection
    document.querySelectorAll('.decision-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            document.querySelectorAll('.decision-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Set hidden input value
            const decision = this.getAttribute('data-decision');
            document.getElementById('decision').value = decision;
            
            // Update submit button color based on decision
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.className = submitBtn.className.replace(/btn-(success|warning|danger)/, '');
            
            if (decision === 'accept') {
                submitBtn.classList.add('btn-success');
            } else if (decision === 'reject') {
                submitBtn.classList.add('btn-danger');
            } else {
                submitBtn.classList.add('btn-warning');
            }
        });
    });
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const decision = document.getElementById('decision').value;
        
        if (!decision) {
            e.preventDefault();
            alert('Please select a decision option.');
            return false;
        }
        
        if (decision === 'reject' && !confirm('Are you sure you want to reject this paper?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Initialize with first option selected if not already
    if (!document.getElementById('decision').value) {
        document.querySelector('.decision-option').click();
    }
</script>
@endpush
@endsection