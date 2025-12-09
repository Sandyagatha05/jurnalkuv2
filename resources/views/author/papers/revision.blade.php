@extends('layouts.app')

@section('page-title', 'Submit Revision')
@section('page-description', 'Submit revised version of your paper')

@section('page-actions')
    <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Paper
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Submit Revision: {{ $paper->title }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-4">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i> Revision Required</h6>
                    <p class="mb-0">
                        Your paper requires {{ $paper->status == 'revision_minor' ? 'minor' : 'major' }} revisions 
                        based on reviewer feedback. Please upload the revised version.
                    </p>
                </div>
                
                <form action="{{ route('author.papers.submit-revision', $paper) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="revision_file" class="form-label">Upload Revised Paper (PDF) *</label>
                        <input type="file" class="form-control @error('revision_file') is-invalid @enderror" 
                               id="revision_file" name="revision_file" accept=".pdf" required>
                        @error('revision_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Current revision: {{ $paper->revision_count }}. 
                            Maximum file size: 10MB
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="revision_notes" class="form-label">Revision Notes (Optional)</label>
                        <textarea class="form-control" id="revision_notes" name="revision_notes" rows="3">{{ old('revision_notes') }}</textarea>
                        <small class="text-muted">
                            Briefly describe the changes made in this revision
                        </small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-redo me-2"></i> Submit Revision
                        </button>
                        <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Review Comments -->
        @if($paper->reviews->where('is_confidential', false)->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-comments me-2"></i> Review Comments</h6>
                </div>
                <div class="card-body">
                    <p>Please address the following comments in your revision:</p>
                    
                    @foreach($paper->reviews->where('is_confidential', false) as $review)
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6>Reviewer Comments:</h6>
                                <p class="mb-3">{{ $review->comments_to_author }}</p>
                                
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Recommendation:</strong>
                                        <span class="badge bg-info ms-2">
                                            {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        Reviewed: {{ $review->reviewed_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection