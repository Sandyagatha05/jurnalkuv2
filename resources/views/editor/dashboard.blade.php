@extends('layouts.app')

@section('title', 'Editor Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Editor Dashboard</h4>
            <p class="text-muted mb-0">Manage papers, issues, and review workflow</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('editor.issues.create') }}" class="btn btn-primary btn-lift">
                <i class="fas fa-plus-circle me-1"></i> Create Issue
            </a>
            <a href="{{ route('editor.papers.submitted') }}" class="btn btn-outline-primary btn-lift">
                <i class="fas fa-inbox me-1"></i> New Submissions
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label'=>'Submitted Papers','value'=>$stats['submitted'] ?? 0,'icon'=>'fas fa-file-upload','color'=>'primary','route'=>route('editor.papers.submitted')],
                ['label'=>'Under Review','value'=>$stats['under_review'] ?? 0,'icon'=>'fas fa-search','color'=>'warning','route'=>route('editor.papers.under-review')],
                ['label'=>'Needs Decision','value'=>$stats['needs_decision'] ?? 0,'icon'=>'fas fa-gavel','color'=>'info','route'=>route('editor.papers.index').'?status=under_review'],
                ['label'=>'Active Issues','value'=>$stats['active_issues'] ?? 0,'icon'=>'fas fa-book','color'=>'success','route'=>route('editor.issues.index')],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 stat-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">{{ $card['label'] }}</small>
                        <h4 class="mb-1">{{ $card['value'] }}</h4>
                        <a href="{{ $card['route'] }}" class="text-{{ $card['color'] }} small text-decoration-none">
                            View details <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="stat-icon bg-{{ $card['color'] }}">
                        <i class="{{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        {{-- Recent Papers --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Paper Submissions</h6>
                    <a href="{{ route('editor.papers.index') }}" class="btn btn-sm btn-light btn-lift">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentPapers->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPapers as $paper)
                                <tr>
                                    <td class="fw-medium">
                                        <a href="{{ route('editor.papers.show',$paper) }}" class="text-decoration-none">
                                            {{ Str::limit($paper->title,45) }}
                                        </a>
                                    </td>
                                    <td>{{ $paper->author->name }}</td>
                                    <td>@include('components.status-badge',['status'=>$paper->status])</td>
                                    <td>{{ $paper->submitted_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('editor.papers.show',$paper) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($paper->status === 'submitted')
                                            <a href="{{ route('editor.papers.assign-reviewers',$paper) }}" class="btn btn-outline-warning">
                                                <i class="fas fa-user-plus"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt text-muted fs-1 mb-3"></i>
                        <p class="text-muted mb-0">No recent submissions</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">

            {{-- Pending Reviews --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i> Pending Reviews
                    </h6>
                </div>
                <div class="card-body">
                    @if($pendingReviews->count())
                        @foreach($pendingReviews as $assignment)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="fw-medium small">
                                    {{ Str::limit($assignment->paper->title,35) }}
                                </div>
                                <small class="text-muted">
                                    Reviewer: {{ $assignment->reviewer->name }}
                                </small>
                            </div>
                            <span class="badge bg-warning">
                                {{ $assignment->due_date->diffForHumans() }}
                            </span>
                        </div>
                        @endforeach
                        <a href="{{ route('editor.reviews.pending') }}" class="btn btn-outline-warning w-100 btn-lift">
                            View All
                        </a>
                    @else
                        <p class="text-muted text-center mb-0">No pending reviews</p>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('editor.issues.create') }}" class="btn btn-outline-primary btn-lift">
                        <i class="fas fa-plus-circle me-2"></i> Create Issue
                    </a>
                    <a href="{{ route('editor.papers.submitted') }}" class="btn btn-outline-success btn-lift">
                        <i class="fas fa-inbox me-2"></i> Process Submissions
                    </a>
                    <a href="{{ route('editor.reviews.pending') }}" class="btn btn-outline-warning btn-lift">
                        <i class="fas fa-clock me-2"></i> Pending Reviews
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Local Styles --}}
<style>
.stat-card {
    border: 1px solid var(--border);
    border-radius: .75rem;
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.25rem;
}
.btn-lift {
    transition: transform .15s ease, box-shadow .15s ease;
}
.btn-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}
</style>
@endsection
