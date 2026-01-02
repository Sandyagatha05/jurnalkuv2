@extends('layouts.app')

@section('page-title', 'View Paper')
@section('page-description', 'Read paper for review')

@section('page-actions')
    <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Assignment
    </a>
    <a href="{{ route('reviewer.assignments.review', $assignment) }}" class="btn btn-primary">
        <i class="fas fa-edit me-1"></i> Write Review
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Paper Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Paper: {{ $paper->title }}</h5>
            </div>
            <div class="card-body">
                <!-- Paper Metadata -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th width="120">Author:</th>
                                <td>{{ $paper->author->name }}</td>
                            </tr>
                            <tr>
                                <th>Institution:</th>
                                <td>{{ $paper->author->institution }}</td>
                            </tr>
                            <tr>
                                <th>Submitted:</th>
                                <td>{{ $paper->submitted_at->format('F d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            @if($paper->doi)
                            <tr>
                                <th width="120">DOI:</th>
                                <td><code>{{ $paper->doi }}</code></td>
                            </tr>
                            @endif
                            @if($paper->keywords)
                            <tr>
                                <th>Keywords:</th>
                                <td>{{ $paper->keywords }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>File:</th>
                                <td>
                                    <a href="{{ route('reviewer.assignments.download-paper', $assignment) }}" 
                                       class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-download me-1"></i> Download PDF
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Abstract -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Abstract</h6>
                    <p>{{ $paper->abstract }}</p>
                </div>

                <!-- PDF Embed Preview -->
                <div class="mb-4">
                    <h6 class="border-bottom pb-2 mb-3">Paper Preview</h6>
                    <div class="card border">
                        <div class="card-body p-0">
                            <embed src="{{ route('reviewer.assignments.view-paper-file', $assignment) }}#toolbar=0&navpanes=0" type="application/pdf" width="100%" height="600px">
                        </div>
                        <div class="card-footer d-flex justify-content-center gap-3">
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
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4 mb-4">
        <!-- Assignment Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Assignment Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Assigned:</th>
                        <td>{{ $assignment->assigned_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Due Date:</th>
                        <td>
                            {{ $assignment->due_date->format('M d, Y') }}
                            @if($assignment->due_date < now())
                                <span class="badge bg-danger ms-2">Overdue</span>
                            @elseif($assignment->due_date->diffInDays(now()) <= 3)
                                <span class="badge bg-warning ms-2">Due Soon</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-{{ $assignment->status == 'pending' ? 'warning' : ($assignment->status == 'completed' ? 'success' : 'secondary') }}">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
                
                @if($assignment->editor_notes)
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-sticky-note me-2"></i> Editor Notes</h6>
                    <p class="mb-0">{{ $assignment->editor_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Review Guidelines -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-book me-2"></i> Review Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Read the paper thoroughly
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Evaluate originality and contribution
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Check methodology and analysis
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Provide constructive feedback
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Maintain confidentiality
                    </li>
                    <li>
                        <i class="fas fa-check text-success me-2"></i>
                        Submit before deadline
                    </li>
                </ul>
                
                <a href="{{ route('reviewer.guidelines') }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
                    <i class="fas fa-external-link-alt me-2"></i> Full Guidelines
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h6>
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
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#extensionModal">
                            <i class="fas fa-clock me-2"></i> Request Extension
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extension Modal -->
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

<style>
    .table-borderless td, .table-borderless th {
        padding: 0.25rem 0;
    }
</style>
@endsection