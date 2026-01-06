@extends('layouts.app')

@section('page-title', 'Submit Revision')
@section('page-description', 'Submit revised version of your paper')

{{-- RULE: page-actions MUST be empty --}}
@section('page-actions')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">

        {{-- Top Action --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Submit Paper Revision</h4>
            <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Paper
            </a>
        </div>

        {{-- Revision Notice --}}
        <div class="alert alert-warning mb-4">
            <h6 class="mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ $paper->status === 'revision_minor' ? 'Minor Revision Required' : 'Major Revision Required' }}
            </h6>
            <p class="mb-0">
                Based on reviewer feedback, you are required to submit a revised version of your paper.
                Please ensure all comments have been addressed before submission.
            </p>
        </div>

        {{-- Revision Form --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('author.papers.submit-revision', $paper) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ================= --}}
                    {{-- Revision File --}}
                    {{-- ================= --}}
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">Revised Paper File</h5>

                        <label for="revision_file" class="form-label fw-semibold">
                            Upload Revised Paper (PDF) *
                        </label>
                        <input type="file"
                               class="form-control @error('revision_file') is-invalid @enderror"
                               id="revision_file"
                               name="revision_file"
                               accept=".pdf"
                               required>

                        @error('revision_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted d-block mt-1">
                            Current revision count: {{ $paper->revision_count }} |
                            Max file size: 10MB | PDF only
                        </small>
                    </div>

                    {{-- ================= --}}
                    {{-- Revision Notes --}}
                    {{-- ================= --}}
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">Revision Notes (Optional)</h5>

                        <label for="revision_notes" class="form-label fw-semibold">
                            Summary of Changes
                        </label>
                        <textarea class="form-control"
                                  id="revision_notes"
                                  name="revision_notes"
                                  rows="4"
                                  placeholder="Briefly explain how you addressed the reviewer comments...">{{ old('revision_notes') }}</textarea>

                        <small class="text-muted">
                            This note helps editors and reviewers quickly understand your revisions.
                        </small>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-redo me-2"></i> Submit Revision
                        </button>
                        <a href="{{ route('author.papers.show', $paper) }}"
                           class="btn btn-outline-secondary btn-lg">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- Review Comments --}}
        @if($paper->reviews->where('is_confidential', false)->count() > 0)
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-4">
                        <i class="fas fa-comments me-2"></i>
                        Reviewer Comments
                    </h5>

                    <p class="text-muted mb-4">
                        Please carefully address the following reviewer comments in your revised submission.
                    </p>

                    @foreach($paper->reviews->where('is_confidential', false) as $review)
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong>Reviewer Feedback</strong>
                                <span class="badge d-inline-flex align-items-center px-2 py-1 fw-semibold
                                    bg-{{ $review->recommendation === 'accept'
                                        ? 'success'
                                        : ($review->recommendation === 'reject' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $review->recommendation)) }}
                                </span>
                            </div>

                            <p class="mb-3">
                                {!! nl2br(e($review->comments_to_author)) !!}
                            </p>

                            <small class="text-muted">
                                Reviewed on {{ $review->reviewed_at->format('M d, Y') }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
