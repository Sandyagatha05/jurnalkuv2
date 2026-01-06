@extends('layouts.app')

@section('page-title', 'Submit New Paper')
@section('page-description', 'Submit your research paper for publication')

{{-- RULE: page-actions MUST be empty --}}
@section('page-actions')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">

        {{-- Top Action --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Paper Submission</h4>
            <a href="{{ route('author.papers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Papers
            </a>
        </div>

        {{-- Main Form Card --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('author.papers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ================= --}}
                    {{-- Paper Information --}}
                    {{-- ================= --}}
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 mb-4">Paper Information</h5>

                        {{-- Title --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Paper Title *</label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title"
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter the complete title of your paper</small>
                        </div>

                        {{-- Abstract --}}
                        <div class="mb-3">
                            <label for="abstract" class="form-label fw-semibold">Abstract *</label>
                            <textarea class="form-control @error('abstract') is-invalid @enderror"
                                      id="abstract" name="abstract"
                                      rows="5" required>{{ old('abstract') }}</textarea>
                            @error('abstract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Provide a concise summary of your research (minimum 100 words)
                            </small>
                        </div>

                        {{-- Keywords --}}
                        <div class="mb-3">
                            <label for="keywords" class="form-label fw-semibold">Keywords *</label>
                            <input type="text"
                                   class="form-control @error('keywords') is-invalid @enderror"
                                   id="keywords" name="keywords"
                                   value="{{ old('keywords') }}" required>
                            @error('keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Separate keywords with commas (e.g., machine learning, data analysis, algorithms)
                            </small>
                        </div>
                    </div>

                    {{-- ========= --}}
                    {{-- Paper File --}}
                    {{-- ========= --}}
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 mb-4">Paper File</h5>

                        <div class="mb-3">
                            <label for="paper_file" class="form-label fw-semibold">
                                Upload Paper (PDF) *
                            </label>
                            <input type="file"
                                   class="form-control @error('paper_file') is-invalid @enderror"
                                   id="paper_file" name="paper_file"
                                   accept=".pdf" required>
                            @error('paper_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Maximum file size: 10MB. Only PDF files are accepted.
                            </small>
                        </div>

                        {{-- File Requirements --}}
                        <div class="alert alert-info mb-0">
                            <h6 class="mb-2">
                                <i class="fas fa-exclamation-circle me-2"></i> File Requirements
                            </h6>
                            <ul class="mb-0">
                                <li>Paper must be in PDF format</li>
                                <li>File name should not contain special characters</li>
                                <li>Ensure all figures and tables are included</li>
                                <li>Remove author information for blind review (if required)</li>
                                <li>Check formatting guidelines before submission</li>
                            </ul>
                        </div>
                    </div>

                    {{-- ===================== --}}
                    {{-- Submission Checklist --}}
                    {{-- ===================== --}}
                    <div class="mb-5">
                        <h5 class="border-bottom pb-2 mb-4">Submission Checklist</h5>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check1" required>
                            <label class="form-check-label" for="check1">
                                I confirm that this paper has not been published previously
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check2" required>
                            <label class="form-check-label" for="check2">
                                I have read and agree to the journal's publication ethics
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check3" required>
                            <label class="form-check-label" for="check3">
                                All co-authors have approved the submission
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check4" required>
                            <label class="form-check-label" for="check4">
                                The paper follows the journal's formatting guidelines
                            </label>
                        </div>
                    </div>

                    {{-- ========= --}}
                    {{-- Actions --}}
                    {{-- ========= --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i> Submit Paper
                        </button>
                        <a href="{{ route('author.papers.index') }}" class="btn btn-outline-secondary btn-lg">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- Guidelines Card --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="fas fa-book me-2"></i> Submission Guidelines
                </h6>
                <p>Before submitting, please ensure:</p>
                <ol>
                    <li>Your paper follows our template and formatting guidelines</li>
                    <li>All authors' information is complete and accurate</li>
                    <li>References are formatted correctly</li>
                    <li>Figures and tables are properly numbered and captioned</li>
                    <li>The paper is ready for blind review (if applicable)</li>
                </ol>
                <a href="{{ route('guidelines') }}" class="btn btn-sm btn-outline-primary">
                    View Complete Guidelines
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const abstractTextarea = document.getElementById('abstract');
    const wordCountDisplay = document.createElement('small');
    wordCountDisplay.className = 'text-muted mt-1 d-block';
    abstractTextarea.parentNode.appendChild(wordCountDisplay);

    function updateWordCount() {
        const text = abstractTextarea.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        wordCountDisplay.textContent = `Word count: ${words} (Minimum: 100 words)`;

        if (words < 100) {
            wordCountDisplay.classList.add('text-danger');
            wordCountDisplay.classList.remove('text-success');
        } else {
            wordCountDisplay.classList.add('text-success');
            wordCountDisplay.classList.remove('text-danger');
        }
    }

    abstractTextarea.addEventListener('input', updateWordCount);
    updateWordCount();

    const fileInput = document.getElementById('paper_file');
    const maxSize = 10 * 1024 * 1024;

    fileInput.addEventListener('change', function () {
        if (this.files[0] && this.files[0].size > maxSize) {
            alert('File size exceeds 10MB limit. Please choose a smaller file.');
            this.value = '';
        }

        const fileName = this.files[0]?.name.toLowerCase();
        if (fileName && !fileName.endsWith('.pdf')) {
            alert('Only PDF files are allowed.');
            this.value = '';
        }
    });
</script>
@endpush
