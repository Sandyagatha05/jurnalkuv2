@extends('layouts.app')

@section('page-title', 'Edit Review')
@section('page-description', 'Edit your review submission')

@section('page-actions')
    <a href="{{ route('reviews.show', $review) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Review
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    Edit Review for: {{ $paper->title }}
                    <small class="text-muted d-block">Paper ID: #{{ $paper->id }}</small>
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-4">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i> Editing Review</h6>
                    <p class="mb-0">
                        You can edit your review until the editor makes a final decision. 
                        All changes will be recorded.
                    </p>
                </div>
                
                <form action="{{ route('reviews.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Review Scores -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Review Scores (1-5)</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="originality_score" class="form-label">Originality</label>
                                <select class="form-select" id="originality_score" name="originality_score" required>
                                    <option value="">Select score</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('originality_score', $review->originality_score) == $i ? 'selected' : '' }}>
                                            {{ $i }} - {{ $i == 1 ? 'Poor' : ($i == 3 ? 'Average' : ($i == 5 ? 'Excellent' : '')) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="contribution_score" class="form-label">Contribution</label>
                                <select class="form-select" id="contribution_score" name="contribution_score" required>
                                    <option value="">Select score</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('contribution_score', $review->contribution_score) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="overall_score" class="form-label">Overall Score</label>
                                <select class="form-select" id="overall_score" name="overall_score" required>
                                    <option value="">Select score</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('overall_score', $review->overall_score) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="clarity_score" class="form-label">Clarity</label>
                                <select class="form-select" id="clarity_score" name="clarity_score" required>
                                    <option value="">Select score</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('clarity_score', $review->clarity_score) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="methodology_score" class="form-label">Methodology</label>
                                <select class="form-select" id="methodology_score" name="methodology_score" required>
                                    <option value="">Select score</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('methodology_score', $review->methodology_score) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comments -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Comments</h6>
                        
                        <div class="mb-3">
                            <label for="comments_to_editor" class="form-label">Comments to Editor (Confidential)</label>
                            <textarea class="form-control" id="comments_to_editor" name="comments_to_editor" rows="4" required>{{ old('comments_to_editor', $review->comments_to_editor) }}</textarea>
                            <small class="text-muted">These comments will only be visible to editors</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comments_to_author" class="form-label">Comments to Author</label>
                            <textarea class="form-control" id="comments_to_author" name="comments_to_author" rows="4" required>{{ old('comments_to_author', $review->comments_to_author) }}</textarea>
                            <small class="text-muted">These comments will be shared with the author</small>
                        </div>
                    </div>
                    
                    <!-- Recommendation -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Recommendation</h6>
                        <div class="row">
                            @foreach(['accept' => 'Accept', 'minor_revision' => 'Minor Revision', 'major_revision' => 'Major Revision', 'reject' => 'Reject'] as $value => $label)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recommendation" 
                                               id="rec_{{ $value }}" value="{{ $value }}" 
                                               {{ old('recommendation', $review->recommendation) == $value ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="rec_{{ $value }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Additional Options -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Additional Options</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_confidential" 
                                           id="is_confidential" value="1" {{ old('is_confidential', $review->is_confidential) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_confidential">
                                        Mark review as confidential (comments will not be shared with author)
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Attachment (Optional)</label>
                                    <input type="file" class="form-control" id="attachment" name="attachment">
                                    <small class="text-muted">
                                        @if($review->attachment_path)
                                            Current attachment: {{ basename($review->attachment_path) }}
                                        @else
                                            No attachment uploaded
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('reviews.show', $review) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Paper Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i> Paper Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $paper->title }}</h5>
                        <p class="text-muted mb-2">
                            <strong>Author:</strong> {{ $paper->author->name }}<br>
                            <strong>Institution:</strong> {{ $paper->author->institution }}
                        </p>
                        <p><strong>Abstract:</strong> {{ Str::limit($paper->abstract, 300) }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" class="btn btn-outline-danger">
                            <i class="fas fa-download me-2"></i> Download Paper
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Character counter for comments
    const editorTextarea = document.getElementById('comments_to_editor');
    const authorTextarea = document.getElementById('comments_to_author');
    
    function createCounter(textarea, min = 100) {
        const counter = document.createElement('div');
        counter.className = 'text-muted mt-1';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const length = textarea.value.length;
            counter.textContent = `${length} characters (minimum: ${min})`;
            
            if (length < min) {
                counter.classList.add('text-danger');
                counter.classList.remove('text-success');
            } else {
                counter.classList.add('text-success');
                counter.classList.remove('text-danger');
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    createCounter(editorTextarea, 100);
    createCounter(authorTextarea, 50);
</script>
@endpush