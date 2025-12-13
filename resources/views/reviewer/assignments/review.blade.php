@extends('layouts.app')

@section('page-title', 'Submit Review')
@section('page-description', 'Submit your review for the assigned paper')

@section('page-actions')
    <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Assignment
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Submit Review</h5>
            </div>
            <div class="card-body">
                <!-- Paper Information -->
                <div class="card border mb-4">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">Paper Information</h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="120">Title:</th>
                                <td>{{ $paper->title }}</td>
                            </tr>
                            <tr>
                                <th>Author:</th>
                                <td>{{ $paper->author->name }} ({{ $paper->author->institution }})</td>
                            </tr>
                            <tr>
                                <th>Submitted:</th>
                                <td>{{ $paper->submitted_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Your Deadline:</th>
                                <td class="{{ $assignment->due_date < now() ? 'text-danger' : '' }}">
                                    {{ $assignment->due_date->format('M d, Y') }}
                                    @if($assignment->due_date < now())
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        
                        <div class="mt-3">
                            <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" 
                               class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-download me-1"></i> Download Paper
                            </a>
                            <a href="{{ route('reviewer.assignments.view-paper', $assignment) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> View Paper
                            </a>
                        </div>
                        
                        @if($assignment->editor_notes)
                            <div class="alert alert-info mt-3 mb-0">
                                <h6><i class="fas fa-sticky-note me-2"></i> Editor Notes</h6>
                                <p class="mb-0">{{ $assignment->editor_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Review Form -->
                <form action="{{ route('reviewer.assignments.submit-review', $assignment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Review Scores (1-5 scale)</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="originality_score" class="form-label">
                                    Originality & Innovation
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="originality_score" name="originality_score" required>
                                    <option value="">Select score</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                                <small class="text-muted">Novelty and contribution to the field</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="contribution_score" class="form-label">
                                    Contribution to Field
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="contribution_score" name="contribution_score" required>
                                    <option value="">Select score</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                                <small class="text-muted">Significance and impact</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="clarity_score" class="form-label">
                                    Clarity & Organization
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="clarity_score" name="clarity_score" required>
                                    <option value="">Select score</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                                <small class="text-muted">Writing quality and structure</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="methodology_score" class="form-label">
                                    Methodology
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="methodology_score" name="methodology_score" required>
                                    <option value="">Select score</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                                <small class="text-muted">Research design and methods</small>
                            </div>
                            
                            <div class="col-md-12">
                                <label for="overall_score" class="form-label">
                                    Overall Recommendation Score
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="overall_score" name="overall_score" required>
                                    <option value="">Select overall score</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                                <small class="text-muted">Your overall assessment of the paper</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Review Recommendation</h6>
                        
                        <div class="mb-3">
                            <label for="recommendation" class="form-label">
                                Your Recommendation
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="recommendation" name="recommendation" required>
                                <option value="">Select recommendation</option>
                                <option value="accept">Accept</option>
                                <option value="minor_revision">Minor Revision</option>
                                <option value="major_revision">Major Revision</option>
                                <option value="reject">Reject</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-success mb-3">
                                    <div class="card-body">
                                        <h6 class="text-success">
                                            <i class="fas fa-check-circle me-2"></i>Accept
                                        </h6>
                                        <small class="text-muted">
                                            Paper is ready for publication as is.
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning mb-3">
                                    <div class="card-body">
                                        <h6 class="text-warning">
                                            <i class="fas fa-redo me-2"></i>Revision
                                        </h6>
                                        <small class="text-muted">
                                            Paper needs revisions before acceptance.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Review Comments</h6>
                        
                        <div class="mb-3">
                            <label for="comments_to_author" class="form-label">
                                Comments to Author
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="comments_to_author" name="comments_to_author" 
                                      rows="6" required placeholder="Provide constructive feedback for the author..."></textarea>
                            <small class="text-muted">
                                These comments will be shared with the author (if not marked confidential).
                                Be specific about strengths, weaknesses, and suggested improvements.
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comments_to_editor" class="form-label">
                                Confidential Comments to Editor
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="comments_to_editor" name="comments_to_editor" 
                                      rows="4" required placeholder="Provide confidential comments for the editor..."></textarea>
                            <small class="text-muted">
                                These comments are confidential and will only be seen by the editor.
                                Include any concerns about ethics, methodology, or other sensitive issues.
                            </small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Additional Options</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Additional Attachment</label>
                                    <input type="file" class="form-control" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.txt">
                                    <small class="text-muted">
                                        Optional: Upload additional comments or annotated manuscript.
                                        Max file size: 5MB
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_confidential" name="is_confidential">
                                    <label class="form-check-label" for="is_confidential">
                                        Keep comments confidential
                                    </label>
                                    <small class="text-muted d-block">
                                        If checked, your comments to author will not be shared.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Checklist -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Submission Checklist</h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check1" required>
                            <label class="form-check-label" for="check1">
                                I have thoroughly read and evaluated the paper
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check2" required>
                            <label class="form-check-label" for="check2">
                                My review is fair, objective, and constructive
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check3" required>
                            <label class="form-check-label" for="check3">
                                I have no conflict of interest with the authors or content
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check4" required>
                            <label class="form-check-label" for="check4">
                                I agree to maintain confidentiality of the review process
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-paper-plane me-2"></i> Submit Review
                        </button>
                        <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Review Guidelines -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-book me-2"></i> Review Guidelines</h6>
            </div>
            <div class="card-body">
                <h6>Please consider the following when reviewing:</h6>
                <ol class="mb-3">
                    <li><strong>Originality:</strong> Does the paper present new ideas or approaches?</li>
                    <li><strong>Significance:</strong> Is the contribution important to the field?</li>
                    <li><strong>Methodology:</strong> Are the methods appropriate and well-described?</li>
                    <li><strong>Results:</strong> Are results clearly presented and supported by data?</li>
                    <li><strong>Clarity:</strong> Is the paper well-written and organized?</li>
                    <li><strong>References:</strong> Are references appropriate and current?</li>
                </ol>
                
                <div class="alert alert-warning mb-0">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i> Important Reminders</h6>
                    <ul class="mb-0">
                        <li>Reviews should be completed by: <strong>{{ $assignment->due_date->format('M d, Y') }}</strong></li>
                        <li>Be constructive and specific in your feedback</li>
                        <li>Maintain confidentiality of the review process</li>
                        <li>Declare any conflicts of interest</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-save draft (optional)
    let autoSaveTimer;
    
    function saveDraft() {
        const formData = new FormData();
        formData.append('comments_to_author', document.getElementById('comments_to_author').value);
        formData.append('comments_to_editor', document.getElementById('comments_to_editor').value);
        formData.append('recommendation', document.getElementById('recommendation').value);
        
        fetch('{{ route("reviewer.assignments.save-draft", $assignment) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Draft saved');
            }
        });
    }
    
    // Auto-save every 30 seconds
    document.querySelectorAll('textarea, select, input[type="text"]').forEach(element => {
        element.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(saveDraft, 30000);
        });
    });
    
    // File size validation
    const fileInput = document.getElementById('attachment');
    const maxSize = 5 * 1024 * 1024; // 5MB
    
    fileInput.addEventListener('change', function() {
        if (this.files[0] && this.files[0].size > maxSize) {
            alert('File size exceeds 5MB limit. Please choose a smaller file.');
            this.value = '';
        }
    });
    
    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const scores = ['originality_score', 'contribution_score', 'clarity_score', 'methodology_score', 'overall_score'];
        let isValid = true;
        
        scores.forEach(field => {
            const select = document.getElementById(field);
            if (!select.value) {
                select.classList.add('is-invalid');
                isValid = false;
            } else {
                select.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required scores before submitting.');
        }
    });
</script>
@endpush