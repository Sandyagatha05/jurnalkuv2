@extends('layouts.app')

@section('page-title', 'Editorial Decision')
@section('page-description', 'Final decision for paper: ' . $paper->title)

@section('page-actions')
    <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Paper
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">

        {{-- ===================== MAIN CARD ===================== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-gavel me-2 text-success"></i>
                        Make Editorial Decision
                    </h5>
                    <span class="badge bg-success-subtle text-success">
                        {{ $paper->reviewAssignments->where('status', 'completed')->count() }}
                        /
                        {{ $paper->reviewAssignments->count() }} Reviews Completed
                    </span>
                </div>
            </div>

            <div class="card-body">

                {{-- ===================== PAPER INFO ===================== --}}
                <div class="border rounded p-3 mb-4 bg-light">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-shape bg-primary text-white rounded-circle">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-1">{{ $paper->title }}</h6>
                            <div class="text-muted small mb-2">
                                <strong>Author:</strong> {{ $paper->author->name }}
                            </div>
                            <p class="mb-0 text-muted small">
                                <strong>Abstract:</strong> {{ Str::limit($paper->abstract, 160) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ===================== REVIEW SUMMARY ===================== --}}
                <div class="mb-5">
                    <h6 class="fw-semibold border-bottom pb-2 mb-3">
                        Review Summary
                    </h6>

                    @php
                        $recommendations = $paper->reviewAssignments
                            ->where('status', 'completed')
                            ->map(fn($a) => $a->review->recommendation ?? null)
                            ->filter();

                        $recommendationCounts = [
                            'accept' => 0,
                            'minor_revision' => 0,
                            'major_revision' => 0,
                            'reject' => 0,
                        ];

                        foreach ($recommendations as $rec) {
                            if (isset($recommendationCounts[$rec])) {
                                $recommendationCounts[$rec]++;
                            }
                        }
                    @endphp

                    <div class="row g-3 mb-4">
                        @foreach($recommendationCounts as $rec => $count)
                            @if($count > 0)
                                <div class="col-md-3 col-sm-6">
                                    <div class="card h-100 text-center border-0 shadow-sm">
                                        <div class="card-body py-4">
                                            <div class="fs-1 fw-bold 
                                                text-{{ $rec === 'accept' ? 'success' : ($rec === 'reject' ? 'danger' : 'warning') }}">
                                                {{ $count }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ ucfirst(str_replace('_', ' ', $rec)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- ===================== REVIEWER COMMENTS ===================== --}}
                    @if($paper->reviewAssignments->where('status', 'completed')->count() > 0)
                        <h6 class="fw-semibold mb-3">Reviewer Comments</h6>
                        <div class="row g-3">
                            @foreach($paper->reviewAssignments->where('status', 'completed') as $assignment)
                                @if($assignment->review)
                                    <div class="col-lg-6">
                                        <div class="card h-100 border shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="mb-0">{{ $assignment->reviewer->name }}</h6>
                                                        <small class="text-muted">
                                                            {{ $assignment->reviewer->institution }}
                                                        </small>
                                                    </div>
                                                    <span class="badge 
                                                        bg-{{ 
                                                            $assignment->review->recommendation === 'accept' ? 'success' :
                                                            ($assignment->review->recommendation === 'reject' ? 'danger' : 'warning')
                                                        }}">
                                                        {{ ucfirst(str_replace('_', ' ', $assignment->review->recommendation)) }}
                                                    </span>
                                                </div>

                                                <p class="text-muted small mb-3">
                                                    {{ Str::limit($assignment->review->comments_to_editor, 120) }}
                                                </p>

                                                <div class="d-flex flex-wrap gap-1">
                                                    <span class="badge bg-secondary">O: {{ $assignment->review->originality_score }}/5</span>
                                                    <span class="badge bg-secondary">C: {{ $assignment->review->contribution_score }}/5</span>
                                                    <span class="badge bg-secondary">M: {{ $assignment->review->methodology_score }}/5</span>
                                                    <span class="badge bg-secondary">Overall: {{ $assignment->review->overall_score }}/5</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ===================== DECISION FORM ===================== --}}
                <form action="{{ route('editor.papers.store-decision', $paper) }}" method="POST">
                    @csrf

                    <h6 class="fw-semibold border-bottom pb-2 mb-3">
                        Editorial Decision
                    </h6>

                    <div class="row g-3 mb-4">
                        @foreach([
                            'accept' => ['icon' => 'check-circle', 'color' => 'success', 'label' => 'Accept'],
                            'minor_revision' => ['icon' => 'edit', 'color' => 'warning', 'label' => 'Minor Revision'],
                            'major_revision' => ['icon' => 'redo', 'color' => 'warning', 'label' => 'Major Revision'],
                            'reject' => ['icon' => 'times-circle', 'color' => 'danger', 'label' => 'Reject'],
                        ] as $key => $opt)
                            <div class="col-md-3 col-sm-6">
                                <div class="card decision-option h-100 text-center border {{ 'border-' . $opt['color'] }}"
                                     data-decision="{{ $key }}">
                                    <div class="card-body py-4">
                                        <i class="fas fa-{{ $opt['icon'] }} fa-3x text-{{ $opt['color'] }} mb-3"></i>
                                        <h6 class="fw-semibold text-{{ $opt['color'] }}">
                                            {{ $opt['label'] }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="decision" id="decision" value="{{ old('decision') }}" required>
                    @error('decision')
                        <div class="text-danger small mb-3">{{ $message }}</div>
                    @enderror

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Decision Notes</label>
                        <textarea class="form-control" name="editor_notes" rows="4">{{ old('editor_notes') }}</textarea>
                        <small class="text-muted">
                            This message will be sent to the author.
                        </small>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="notify_author" value="1" checked>
                        <label class="form-check-label">
                            Notify author via email
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-success btn-lg">
                            <i class="fas fa-gavel me-2"></i> Submit Decision
                        </button>
                        <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-secondary btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>

        {{-- ===================== GUIDELINES ===================== --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Decision Guidelines
                </h6>
            </div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li><strong>Accept:</strong> No revision required</li>
                    <li><strong>Minor Revision:</strong> Small changes, editor verification</li>
                    <li><strong>Major Revision:</strong> Re-review required</li>
                    <li><strong>Reject:</strong> Does not meet journal standards</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<style>
.icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.decision-option {
    cursor: pointer;
    transition: all .2s ease;
}

.decision-option:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.08);
}

.decision-option.selected {
    outline: 3px solid rgba(13,110,253,.25);
}
</style>

@push('scripts')
<script>
document.querySelectorAll('.decision-option').forEach(option => {
    option.addEventListener('click', function () {
        document.querySelectorAll('.decision-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');

        const decision = this.dataset.decision;
        document.getElementById('decision').value = decision;

        const btn = document.getElementById('submitBtn');
        btn.className = 'btn btn-lg';

        if (decision === 'accept') btn.classList.add('btn-success');
        else if (decision === 'reject') btn.classList.add('btn-danger');
        else btn.classList.add('btn-warning');
    });
});
</script>
@endpush
@endsection
