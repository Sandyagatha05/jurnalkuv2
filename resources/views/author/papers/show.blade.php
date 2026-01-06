@extends('layouts.app')

@section('page-title', 'Paper Details')
@section('page-description', 'View paper details and review status')

{{-- RULE: page-actions MUST be empty --}}
@section('page-actions')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">

        {{-- Top Action --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Paper Details</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('author.papers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Papers
                </a>

                @if($paper->status == 'submitted')
                    <a href="{{ route('author.papers.edit', $paper) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                @endif

                @if(in_array($paper->status, ['revision_minor', 'revision_major']))
                    <a href="{{ route('author.papers.revision', $paper) }}" class="btn btn-warning">
                        <i class="fas fa-redo me-1"></i> Submit Revision
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            {{-- MAIN CONTENT --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Paper Information</h5>
                            @include('components.status-badge', ['status' => $paper->status])
                        </div>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <th width="160">Title</th>
                                <td>{{ $paper->title }}</td>
                            </tr>
                            <tr>
                                <th>DOI</th>
                                <td>
                                    @if($paper->doi)
                                        <code>{{ $paper->doi }}</code>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Abstract</th>
                                <td>{{ $paper->abstract }}</td>
                            </tr>
                            <tr>
                                <th>Keywords</th>
                                <td>{{ $paper->keywords }}</td>
                            </tr>
                            <tr>
                                <th>Submitted</th>
                                <td>{{ $paper->submitted_at->format('F d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $paper->updated_at->format('F d, Y H:i') }}</td>
                            </tr>

                            @if($paper->issue)
                                <tr>
                                    <th>Published In</th>
                                    <td>
                                        <a href="{{ route('issues.show', $paper->issue) }}">
                                            {{ $paper->issue->title }}
                                        </a>
                                        (Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }})
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pages</th>
                                    <td>
                                        {{ $paper->page_from && $paper->page_to
                                            ? $paper->page_from.' - '.$paper->page_to
                                            : 'Not assigned' }}
                                    </td>
                                </tr>
                            @endif
                        </table>

                        {{-- File --}}
                        <div class="bg-light rounded p-3 mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                    <div>
                                        <h6 class="mb-1">{{ $paper->original_filename }}</h6>
                                        <small class="text-muted">
                                            Uploaded {{ $paper->submitted_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ route('author.papers.download', $paper) }}"
                                   class="btn btn-outline-danger">
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REVIEW PROGRESS --}}
                @if($paper->reviewAssignments->count() > 0)
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="border-bottom pb-2 mb-4">Review Progress</h5>

                            @php
                                $completed = $paper->reviewAssignments->where('status', 'completed')->count();
                                $total = $paper->reviewAssignments->count();
                                $percentage = $total ? ($completed / $total) * 100 : 0;
                            @endphp

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $completed }} of {{ $total }} completed</span>
                                    <span>{{ round($percentage) }}%</span>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-success"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($paper->reviewAssignments as $assignment)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 h-100">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <strong>{{ $assignment->reviewer->name }}</strong><br>
                                                    <small class="text-muted">
                                                        {{ $assignment->reviewer->institution }}
                                                    </small>
                                                </div>
                                                <span class="badge d-inline-flex align-items-center px-2 bg-{{ $assignment->status == 'completed' ? 'success' : 'warning' }}" 
                                                    style="height: 25px; font-size: 11px; line-height: 1; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>

                                            </div>

                                            @if($assignment->due_date)
                                                <small class="text-muted">
                                                    <i class="far fa-calendar me-1"></i>
                                                    Due {{ $assignment->due_date->format('M d, Y') }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($paper->reviews->where('is_confidential', false)->count() > 0)
                                <a href="{{ route('author.papers.reviews', $paper) }}"
                                   class="btn btn-outline-primary mt-3">
                                    <i class="fas fa-comments me-1"></i> View Review Comments
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="mb-3">
                            <i class="fas fa-history me-2"></i> Submission Timeline
                        </h6>

                        {{-- TIMELINE (STYLE DIPERTAHANKAN) --}}
                        <div class="timeline">
                            <div class="timeline-item {{ $paper->submitted_at ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Submitted</h6>
                                    <small class="text-muted">
                                        {{ optional($paper->submitted_at)->format('M d, Y H:i') ?? 'Pending' }}
                                    </small>
                                </div>
                            </div>

                            <div class="timeline-item {{ $paper->reviewed_at ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Under Review</h6>
                                    <small class="text-muted">
                                        {{ optional($paper->reviewed_at)->format('M d, Y H:i') ?? 'Pending' }}
                                    </small>
                                </div>
                            </div>

                            <div class="timeline-item {{ $paper->status == 'accepted' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Accepted</h6>
                                    <small class="text-muted">
                                        {{ $paper->status == 'accepted' ? 'Completed' : 'Pending' }}
                                    </small>
                                </div>
                            </div>

                            <div class="timeline-item {{ $paper->published_at ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Published</h6>
                                    <small class="text-muted">
                                        {{ optional($paper->published_at)->format('M d, Y H:i') ?? 'Pending' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($paper->revision_count > 0)
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="mb-2">
                                <i class="fas fa-redo me-2"></i> Revision History
                            </h6>
                            <p class="mb-1">
                                Revised {{ $paper->revision_count }} time(s)
                            </p>
                            <small class="text-muted">
                                Last revision {{ $paper->updated_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- TIMELINE STYLE (UNCHANGED) --}}
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #dee2e6;
    border: 3px solid white;
}
.timeline-item.active .timeline-marker {
    background-color: #4361ee;
}
.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}
</style>
@endsection
