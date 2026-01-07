@extends('layouts.app')

@section('title', 'View Paper')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">View Paper</h4>
            <p class="text-muted mb-0">Read paper for review</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Assignment
            </a>
            <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Write Review
            </a>
        </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title fw-bold text-primary mb-4">{{ $paper->title }}</h4>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="100">Author:</td>
                                <td class="fw-medium">{{ $paper->author->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Institution:</td>
                                <td class="fw-medium">{{ $paper->author->institution }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Submitted:</td>
                                <td class="fw-medium">{{ $paper->submitted_at->format('F d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            @if($paper->doi)
                            <tr>
                                <td class="text-muted" width="100">DOI:</td>
                                <td><code class="text-primary">{{ $paper->doi }}</code></td>
                            </tr>
                            @endif
                            
                            @if($paper->keywords)
                            <tr>
                                <td class="text-muted">Keywords:</td>
                                <td>{{ $paper->keywords }}</td>
                            </tr>
                            @endif

                            <tr>
                                <td class="text-muted">File:</td>
                                <td>
                                    <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" 
                                       class="text-danger text-decoration-none fw-medium">
                                        <i class="fas fa-file-pdf me-1"></i> Download PDF
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-0">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Abstract</h6>
                    <p class="text-justify mb-0 text-secondary">{{ $paper->abstract }}</p>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-eye me-2 text-primary"></i>Paper Preview</h6>
            </div>
            <div class="card-body p-0">
                <div class="ratio ratio-1x1" style="min-height: 600px;">
                    <embed src="{{ route('reviewer.assignments.view-paper-file', $assignment) }}#toolbar=0&navpanes=0" type="application/pdf">
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" class="btn btn-danger">
                        <i class="fas fa-download me-2"></i> Download PDF
                    </a>
                    <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Start Review
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Assignment Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <th class="ps-0">Assigned:</th>
                        <td class="text-end pe-0">{{ $assignment->assigned_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th class="ps-0 align-middle">Due Date:</th>
                        <td class="text-end pe-0">
                            <span class="d-flex flex-column align-items-end">
                                <span>{{ $assignment->due_date->format('M d, Y') }}</span>
                                @if($assignment->due_date < now())
                                    <span class="badge bg-danger d-inline-flex align-items-center px-2 py-1 small mt-1">Overdue</span>
                                @elseif($assignment->due_date->diffInDays(now()) <= 3)
                                    <span class="badge bg-warning text-dark d-inline-flex align-items-center px-2 py-1 small mt-1">Due Soon</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-0 align-middle">Status:</th>
                        <td class="text-end pe-0">
                            <span class="badge bg-{{ $assignment->status == 'pending' ? 'warning text-dark' : ($assignment->status == 'completed' ? 'success' : 'secondary') }} d-inline-flex align-items-center px-2 py-1 small">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </td>
                    </tr>
                </table>

                @if($assignment->editor_notes)
                <div class="alert alert-info d-flex align-items-start mt-3 mb-0">
                    <i class="fas fa-sticky-note me-2 mt-1"></i>
                    <div>
                        <h6 class="alert-heading fw-bold fs-6 mb-1">Editor Notes</h6>
                        <p class="mb-0 small">{{ $assignment->editor_notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-book me-2 text-primary"></i>Review Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-3">
                    <li class="mb-2 d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i> Read the paper thoroughly
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i> Evaluate originality
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i> Check methodology
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i> Provide constructive feedback
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i> Maintain confidentiality
                    </li>
                    <li class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i> Submit before deadline
                    </li>
                </ul>
                
                <a href="{{ route('reviewer.guidelines') }}" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-external-link-alt me-2"></i> Full Guidelines
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-bolt me-2 text-primary"></i>Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Write Review
                    </a>
                    <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" class="btn btn-outline-danger">
                        <i class="fas fa-download me-2"></i> Download Paper
                    </a>
                    @if($assignment->status == 'pending' && $assignment->due_date > now())
                        <button class="btn btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#extensionModal">
                            <i class="fas fa-clock me-2"></i> Request Extension
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="extensionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('reviewer.assignments.request-extension', $assignment) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Request Extension</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Extension</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="new_due_date" class="form-label">Requested New Due Date</label>
                        <input type="date" class="form-control" id="new_due_date" name="new_due_date" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               max="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-borderless td, .table-borderless th {
        padding: 0.35rem 0;
    }
    .text-justify {
        text-align: justify;
    }
</style>
@endpush

@endsection