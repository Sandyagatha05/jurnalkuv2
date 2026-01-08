@extends('layouts.app')

@section('page-title', 'Edit Paper')
@section('page-description', 'Edit paper before review process')

{{-- RULE: page-actions MUST be empty --}}
@section('page-actions')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9">

        {{-- Top Navigation --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Edit Paper</h4>
            <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Paper
            </a>
        </div>

        {{-- Main Card --}}
        <div class="card shadow-sm">
            <div class="card-body p-4">

                {{-- Header --}}
                <div class="mb-4">
                    <h5 class="mb-1">{{ $paper->title }}</h5>
                    <small class="text-muted">
                        Last updated {{ $paper->updated_at->format('F d, Y H:i') }}
                    </small>
                </div>

                <form action="{{ route('author.papers.update', $paper) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      onsubmit="event.preventDefault(); customConfirm('Are you sure you want to update this paper?').then(result => { if(result) this.submit(); });">
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">
                            Paper Title <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="title"
                               name="title"
                               value="{{ old('title', $paper->title) }}"
                               required>
                    </div>

                    {{-- Abstract --}}
                    <div class="mb-3">
                        <label for="abstract" class="form-label fw-semibold">
                            Abstract <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control"
                                  id="abstract"
                                  name="abstract"
                                  rows="6"
                                  required>{{ old('abstract', $paper->abstract) }}</textarea>
                    </div>

                    {{-- Keywords --}}
                    <div class="mb-3">
                        <label for="keywords" class="form-label fw-semibold">
                            Keywords <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="keywords"
                               name="keywords"
                               value="{{ old('keywords', $paper->keywords) }}"
                               placeholder="e.g. Machine Learning, Recommender System"
                               required>
                    </div>

                    {{-- File Upload --}}
                    <div class="mb-4">
                        <label for="paper_file" class="form-label fw-semibold">
                            Upload New Paper (PDF)
                        </label>
                        <input type="file"
                               class="form-control"
                               id="paper_file"
                               name="paper_file"
                               accept=".pdf">
                        <small class="text-muted d-block mt-1">
                            Current file: <strong>{{ $paper->original_filename }}</strong>
                        </small>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('author.papers.show', $paper) }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Paper
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
