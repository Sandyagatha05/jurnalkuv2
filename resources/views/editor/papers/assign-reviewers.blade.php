@extends('layouts.app')

@section('page-title', 'Assign Reviewers')
@section('page-description', 'Assign reviewers to paper: ' . $paper->title)

@section('page-actions')
    <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Paper
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">

        {{-- Main Card --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-user-plus me-2"></i> Assign Reviewers
                    </span>
                    <span class="badge bg-primary">
                        {{ $paper->reviewAssignments->count() }} assigned
                    </span>
                </h5>
            </div>

            <div class="card-body">

                {{-- Paper Info --}}
                <div class="alert alert-light border mb-4">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-file-alt fa-2x text-primary me-3 mt-1"></i>
                        <div>
                            <h6 class="mb-1">{{ $paper->title }}</h6>
                            <div class="small text-muted">
                                <div><strong>Author:</strong> {{ $paper->author->name }}</div>
                                <div><strong>Submitted:</strong> {{ $paper->submitted_at->format('F d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('editor.papers.store-assign-reviewers', $paper) }}" onsubmit="event.preventDefault(); 
                customConfirm('Are you sure you want to select this reviewer?').then(result => {if(result) this.submit(); });
                " method="POST">
                    @csrf

                    {{-- Reviewer Selection --}}
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Select Reviewers</h6>

                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Select <strong>2–3 reviewers</strong>. Notifications will be sent automatically.
                        </div>

                        @if($reviewers->count())
                            <div class="row">
                                @foreach($reviewers as $reviewer)
                                    @php
                                        $isAssigned = in_array(
                                            $reviewer->id,
                                            $assignedReviewers->pluck('id')->toArray()
                                        );
                                    @endphp

                                    <div class="col-md-6 mb-3">
                                        <div class="card reviewer-card h-100 {{ $isAssigned ? 'selected' : '' }}">
                                            <div class="card-body">

                                                <div class="form-check mb-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="reviewers[]"
                                                        value="{{ $reviewer->id }}"
                                                        id="reviewer{{ $reviewer->id }}"
                                                        {{ $isAssigned ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label fw-semibold" for="reviewer{{ $reviewer->id }}">
                                                        {{ $reviewer->name }}
                                                    </label>
                                                </div>

                                                <div class="ms-4 small text-muted">
                                                    <div><i class="fas fa-university me-1"></i> {{ $reviewer->institution }}</div>
                                                    <div><i class="fas fa-briefcase me-1"></i> {{ $reviewer->department }}</div>
                                                    <div><i class="fas fa-envelope me-1"></i> {{ $reviewer->email }}</div>
                                                </div>

                                                <hr class="my-2">

                                                <div class="d-flex justify-content-between small text-muted">
                                                    <span>
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $reviewer->reviewAssignments->where('status','pending')->count() }} pending
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        {{ $reviewer->reviewAssignments->where('status','completed')->count() }} completed
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Reviewers Available</h5>
                                <p class="text-muted mb-3">Please add reviewers to the system first.</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                                    <i class="fas fa-users me-2"></i> Manage Users
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Review Details --}}
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Review Details</h6>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">
                                Review Due Date <span class="text-danger">*</span>
                            </label>
                            <input
                                type="date"
                                id="due_date"
                                name="due_date"
                                class="form-control @error('due_date') is-invalid @enderror"
                                value="{{ old('due_date', now()->addWeeks(2)->format('Y-m-d')) }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required
                            >
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended: 2–4 weeks</small>
                        </div>

                        <div class="mb-3">
                            <label for="editor_notes" class="form-label">Notes to Reviewers (Optional)</label>
                            <textarea
                                class="form-control @error('editor_notes') is-invalid @enderror"
                                id="editor_notes"
                                name="editor_notes"
                                rows="3"
                            >{{ old('editor_notes') }}</textarea>
                            @error('editor_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Assigned Reviewers --}}
                    @if($assignedReviewers->count())
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Currently Assigned Reviewers</h6>
                            <div class="row">
                                @foreach($assignedReviewers as $rev)
                                    <div class="col-md-6 mb-2">
                                        <div class="card border-primary">
                                            <div class="card-body py-2 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $rev->name }}</strong>
                                                    <div class="small text-muted">{{ $rev->institution }}</div>
                                                </div>
                                                <span class="badge bg-primary">Assigned</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i> Assign Reviewers & Start Review
                        </button>
                        <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- Guidelines --}}
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Review Assignment Guidelines</h6>
            </div>
            <div class="card-body small">
                <ol class="mb-0">
                    <li>Select 2–3 qualified reviewers</li>
                    <li>Avoid conflicts of interest</li>
                    <li>Match reviewer expertise</li>
                    <li>Set reasonable deadlines</li>
                    <li>Reviewers are notified automatically</li>
                </ol>
            </div>
        </div>

    </div>
</div>

{{-- Inline Styles --}}
<style>
.reviewer-card {
    cursor: pointer;
    transition: all .2s ease;
}
.reviewer-card:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
}
.reviewer-card.selected {
    border-color: var(--primary-color);
    background: rgba(67,97,238,.05);
}
</style>

{{-- Inline Scripts --}}
<script>
const maxReviewers = 3;
const checkboxes = document.querySelectorAll('input[name="reviewers[]"]');

checkboxes.forEach(cb => {
    const card = cb.closest('.reviewer-card');

    if (cb.checked && card) card.classList.add('selected');

    cb.addEventListener('change', () => {
        const checked = document.querySelectorAll('input[name="reviewers[]"]:checked').length;

        if (checked > maxReviewers) {
            cb.checked = false;
            alert(`Maximum ${maxReviewers} reviewers allowed.`);
            return;
        }

        card?.classList.toggle('selected', cb.checked);
    });
});
</script>
@endsection
