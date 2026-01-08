@extends('layouts.app')

@section('page-title', 'Manage Editorial')
@section('page-description', 'Add or edit editorial for this issue')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-pen-nib me-2"></i>
                    {{ $editorial ? 'Edit' : 'Add' }} Editorial
                </h5>
                <a href="{{ route('editor.issues.show', $issue) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Issue
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('editor.issues.store-editorial', $issue) }}" 
                      method="POST"
                      onsubmit="event.preventDefault(); customConfirm('Are you sure you want to save this editorial?').then(result => { if(result) this.submit(); });">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Editorial Title *</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $editorial->title ?? '') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Editorial Content *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="15" 
                                  required>{{ old('content', $editorial->content ?? '') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Write the editorial content for this issue.</div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="is_published" 
                               value="1" 
                               id="is_published"
                               {{ old('is_published', $editorial->is_published ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            Publish editorial immediately
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Editorial
                        </button>
                        <a href="{{ route('editor.issues.show', $issue) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($editorial)
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3">Preview</h6>
                    <div class="border-start border-primary border-3 ps-3">
                        <h5 class="text-primary">{{ $editorial->title }}</h5>
                        <p class="text-muted small mb-2">
                            By {{ $editorial->author->name }} • 
                            {{ $editorial->created_at->format('d M Y') }}
                        </p>
                        <div class="editorial-content">
                            {{ $editorial->content }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.editorial-content {
    line-height: 1.8;
    white-space: pre-wrap;
}
</style>
@endsection