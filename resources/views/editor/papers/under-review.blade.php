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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-search me-2"></i> Papers Under Review
        </h5>
        <span class="badge bg-warning">{{ $papers->total() }}</span>
    </div>

    <div class="card-body">
        @if($papers->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 22%">Title</th>
                            <th style="width: 15%">Author</th>
                            <th style="width: 18%">Review Progress</th>
                            <th style="width: 20%">Reviewers</th>
                            <th style="width: 12%">Next Due</th>
                            <th style="width: 13%" class="text-end">Actions</th>
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
                                {{-- Title --}}
                                <td>
                                    <a href="{{ route('editor.papers.show', $paper) }}" class="fw-semibold text-decoration-none">
                                        {{ Str::limit($paper->title, 55) }}
                                    </a>
                                </td>

                                {{-- Author --}}
                                <td>
                                    <div class="fw-medium">{{ $paper->author->name }}</div>
                                </td>

                                {{-- Review Progress --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar bg-success"
                                                 role="progressbar"
                                                 style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $completed }}/{{ $total }}</small>
                                    </div>
                                </td>

                                {{-- Reviewers --}}
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($paper->reviewAssignments as $assignment)
                                            <span class="badge bg-{{ $assignment->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ $assignment->reviewer->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Due Date --}}
                                <td>
                                    @if($nextDueDate)
                                        <div class="fw-medium">
                                            {{ $nextDueDate->due_date->format('M d') }}
                                        </div>
                                        @if($nextDueDate->due_date < now())
                                            <span class="badge bg-danger mt-1">Overdue</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('editor.papers.show', $paper) }}"
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($completed == $total)
                                            <a href="{{ route('editor.papers.decision', $paper) }}"
                                               class="btn btn-success">
                                                <i class="fas fa-gavel"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Custom Pagination (Project Default) --}}
            @if ($papers->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav>
                        <ul class="pagination gap-2">

                            {{-- Previous --}}
                            <li class="page-item {{ $papers->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link btn-lift"
                                   href="{{ $papers->previousPageUrl() ?? '#' }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>

                            {{-- Pages --}}
                            @foreach ($papers->getUrlRange(1, $papers->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $papers->currentPage() ? 'active' : '' }}">
                                    <a class="page-link btn-lift" href="{{ $url }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endforeach

                            {{-- Next --}}
                            <li class="page-item {{ $papers->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link btn-lift"
                                   href="{{ $papers->nextPageUrl() ?? '#' }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            @endif
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
