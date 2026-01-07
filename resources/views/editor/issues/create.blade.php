@extends('layouts.app')

@section('title', 'Create New Issue')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Create New Issue</h4>
            <p class="text-muted mb-0">Create and configure a new journal issue</p>
        </div>
        <a href="{{ route('editor.issues.index') }}" class="btn btn-outline-secondary btn-lift">
            <i class="fas fa-arrow-left me-1"></i> Back to Issues
        </a>
    </div>

    <div class="row justify-content-center">

        {{-- Main Form --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('editor.issues.store') }}" method="POST">
                        @csrf

                        {{-- Issue Identification --}}
                        <div class="mb-4">
                            <h6 class="fw-semibold border-bottom pb-2 mb-3">
                                Issue Identification
                            </h6>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Volume *</label>
                                    <input type="number"
                                           class="form-control @error('volume') is-invalid @enderror"
                                           name="volume"
                                           value="{{ old('volume') }}"
                                           min="1"
                                           required>
                                    @error('volume')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">e.g. 1, 2, 3</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Number *</label>
                                    <input type="number"
                                           class="form-control @error('number') is-invalid @enderror"
                                           name="number"
                                           value="{{ old('number') }}"
                                           min="1"
                                           required>
                                    @error('number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Issue number in volume</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Year *</label>
                                    <input type="number"
                                           class="form-control @error('year') is-invalid @enderror"
                                           name="year"
                                           value="{{ old('year', date('Y')) }}"
                                           min="2000"
                                           max="2050"
                                           required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Publication year</small>
                                </div>
                            </div>

                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Volume, Number, and Year combination must be unique.
                            </div>
                        </div>

                        {{-- Issue Details --}}
                        <div class="mb-4">
                            <h6 class="fw-semibold border-bottom pb-2 mb-3">
                                Issue Details
                            </h6>

                            <div class="mb-3">
                                <label class="form-label">Issue Title *</label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       name="title"
                                       value="{{ old('title') }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Example: Advances in Computer Science Research
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          name="description"
                                          rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Brief overview of this issue
                                </small>
                            </div>
                        </div>

                        {{-- Publication --}}
                        <div class="mb-4">
                            <h6 class="fw-semibold border-bottom pb-2 mb-3">
                                Publication Settings
                            </h6>

                            <div class="mb-3">
                                <label class="form-label">Scheduled Publication Date</label>
                                <input type="date"
                                       class="form-control @error('published_date') is-invalid @enderror"
                                       name="published_date"
                                       value="{{ old('published_date') }}">
                                @error('published_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Optional — set when publishing
                                </small>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_special"
                                       value="1"
                                       {{ old('is_special') ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    This is a special issue
                                </label>
                            </div>
                            <small class="text-muted">
                                Mark this if the issue has a special theme
                            </small>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-primary btn-lift">
                                <i class="fas fa-plus-circle me-1"></i> Create Issue
                            </button>
                            <a href="{{ route('editor.issues.index') }}"
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-clock me-1"></i> Recent Issues
                    </h6>

                    @php
                        $recentIssues = \App\Models\Issue::latest()
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse($recentIssues as $issue)
                        <div class="border rounded p-2 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        Vol. {{ $issue->volume }},
                                        No. {{ $issue->number }}
                                        ({{ $issue->year }})
                                    </strong>
                                    <div class="small text-muted">
                                        {{ Str::limit($issue->title, 40) }}
                                    </div>
                                </div>
                                <span class="badge bg-{{ $issue->status === 'published' ? 'success' : ($issue->status === 'draft' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($issue->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No issues created yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Local Style --}}
<style>
.btn-lift {
    transition: transform .15s ease, box-shadow .15s ease;
}
.btn-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
</style>
@endsection
