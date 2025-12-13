@extends('layouts.app')

@section('page-title', 'New Submissions')
@section('page-description', 'Review newly submitted papers')

@section('page-actions')
    <a href="{{ route('editor.papers.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to All Papers
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-inbox me-2"></i> New Paper Submissions
            <span class="badge bg-primary">{{ $papers->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($papers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Submitted</th>
                            <th>Abstract Preview</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($papers as $paper)
                            <tr>
                                <td>#{{ $paper->id }}</td>
                                <td>
                                    <a href="{{ route('editor.papers.show', $paper) }}" class="text-decoration-none">
                                        {{ Str::limit($paper->title, 60) }}
                                    </a>
                                </td>
                                <td>
                                    <div>{{ $paper->author->name }}</div>
                                    <small class="text-muted">{{ $paper->author->institution }}</small>
                                </td>
                                <td>{{ $paper->submitted_at->format('M d, Y') }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($paper->abstract, 80) }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('editor.papers.show', $paper) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('papers.download', $paper) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-download"></i> PDF
                                        </a>
                                        <a href="{{ route('editor.papers.assign-reviewers', $paper) }}" class="btn btn-warning">
                                            <i class="fas fa-user-plus"></i> Assign Reviewers
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $papers->firstItem() }} to {{ $papers->lastItem() }} of {{ $papers->total() }} submissions
                </div>
                {{ $papers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                <h4 class="text-muted mb-3">No New Submissions</h4>
                <p class="text-muted">All submitted papers have been processed.</p>
            </div>
        @endif
    </div>
</div>
@endsection