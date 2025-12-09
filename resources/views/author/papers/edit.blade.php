@extends('layouts.app')

@section('page-title', 'Edit Paper')
@section('page-description', 'Edit paper before review process')

@section('page-actions')
    <a href="{{ route('author.papers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Papers
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Paper: {{ $paper->title }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('author.papers.update', $paper) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Paper Title *</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="{{ old('title', $paper->title) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="abstract" class="form-label">Abstract *</label>
                        <textarea class="form-control" id="abstract" name="abstract" rows="5" required>{{ old('abstract', $paper->abstract) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keywords" class="form-label">Keywords *</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" 
                               value="{{ old('keywords', $paper->keywords) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="paper_file" class="form-label">Upload New Paper (PDF) - Optional</label>
                        <input type="file" class="form-control" id="paper_file" name="paper_file" accept=".pdf">
                        <small class="text-muted">Current file: {{ $paper->original_filename }}</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Paper
                        </button>
                        <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection