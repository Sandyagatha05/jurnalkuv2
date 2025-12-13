@extends('layouts.app')

@section('page-title', 'Papers Under Review')
@section('page-description', 'Monitor papers currently being reviewed')

@section('page-actions')
    <a href="{{ route('editor.papers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to All Papers
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-search me-2"></i> Papers Under Review
            <span class="badge bg-warning">{{ $papers->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($papers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Review Status</th>
                            <th>Assigned Reviewers</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($papers as $paper)
                            @php
                                $completed = $paper->reviewAssignments->where('status', 'completed')->count();
                                $total = $paper->reviewAssignments->count();
                                $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
                                $nextDueDate = $paper->reviewAssignments->where('status', 'pending')
                                    ->sortBy('due_date')
                                    ->first();
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('editor.papers.show', $paper) }}" class="text-decoration-none">
                                        {{ Str::limit($paper->title, 50) }}
                                    </a>
                                </td>
                                <td>{{ $paper->author->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <small>{{ $completed }}/{{ $total }}</small>
                                    </div>
                                </td>
                                <td>
                                    @foreach($paper->reviewAssignments as $assignment)
                                        <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : 'warning' }} mb-1">
                                            {{ $assignment->reviewer->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($nextDueDate)
                                        {{ $nextDueDate->due_date->format('M d') }}
                                        @if($nextDueDate->due_date < now())
                                            <span class="badge bg-danger ms-1">Overdue</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($completed == $total)
                                            <a href="{{ route('editor.papers.decision', $paper) }}" class="btn btn-success">
                                                <i class="fas fa-gavel"></i> Decision
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $papers->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No Papers Under Review</h4>
                <p class="text-muted">All papers have completed the review process.</p>
            </div>
        @endif
    </div>
</div>
@endsection