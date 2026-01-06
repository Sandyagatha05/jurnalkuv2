@extends('layouts.app')

@section('page-title', 'Author Dashboard')
@section('page-description', 'Manage your paper submissions and track review progress')

@section('content')
<div class="row">

    {{-- ===================== --}}
    {{-- Statistics Overview --}}
    {{-- ===================== --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Submitted Papers</div>
                    <div class="h4 mb-0">
                        {{ $stats['submitted'] ?? auth()->user()->papers()->where('status','submitted')->count() }}
                    </div>
                </div>
                <div class="icon-circle bg-primary text-white">
                    <i class="fas fa-file-upload"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Under Review</div>
                    <div class="h4 mb-0">
                        {{ $stats['under_review'] ?? auth()->user()->papers()->where('status','under_review')->count() }}
                    </div>
                </div>
                <div class="icon-circle bg-warning text-white">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Accepted</div>
                    <div class="h4 mb-0">
                        {{ $stats['accepted'] ?? auth()->user()->papers()->where('status','accepted')->count() }}
                    </div>
                </div>
                <div class="icon-circle bg-success text-white">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-info">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Published</div>
                    <div class="h4 mb-0">
                        {{ $stats['published'] ?? auth()->user()->papers()->where('status','published')->count() }}
                    </div>
                </div>
                <div class="icon-circle bg-info text-white">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= --}}
    {{-- Recent Papers --}}
    {{-- ================= --}}
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i> Recent Submissions
                </h5>
                <a href="{{ route('author.papers.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>

            <div class="card-body">
                @php
                    $recentPapers = $recentPapers ?? auth()->user()
                        ->papers()
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp

                @if($recentPapers->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPapers as $paper)
                                    <tr>
                                        <td>
                                            <a href="{{ route('author.papers.show', $paper) }}" class="text-decoration-none">
                                                {{ Str::limit($paper->title, 60) }}
                                            </a>
                                        </td>
                                        <td>
                                            @include('components.status-badge', ['status' => $paper->status])
                                        </td>
                                        <td>{{ $paper->submitted_at?->format('M d, Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('author.papers.show', $paper) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">You haven’t submitted any papers yet.</p>
                        <a href="{{ route('author.papers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i> Submit Your First Paper
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= --}}
    {{-- Quick Actions --}}
    {{-- ================= --}}
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 mb-4">
                    <a href="{{ route('author.papers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Submit New Paper
                    </a>
                    <a href="{{ route('author.papers.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i> View All Papers
                    </a>
                    <a href="{{ route('guidelines') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-book me-2"></i> Author Guidelines
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-2"></i> Update Profile
                    </a>
                </div>

                <hr>

                <h6 class="mb-3">Submission Tips</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Follow formatting guidelines</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Complete all required sections</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Proofread before submission</li>
                    <li><i class="fas fa-check text-success me-2"></i> Upload PDF only</li>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- Styles --}}
<style>
.icon-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
</style>
@endsection
