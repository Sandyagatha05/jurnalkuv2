@extends('layouts.app')

@section('page-title', 'Author Dashboard')
@section('page-description', 'Manage your paper submissions and track review progress')

@section('page-actions')
    <a href="{{ route('author.papers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Submit New Paper
    </a>
@endsection

@section('content')
<div class="row">
<!-- Stats Cards -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Submitted Papers</h6>
                    <h4 class="mb-0">{{ $stats['submitted'] ?? auth()->user()->papers()->where('status', 'submitted')->count() }}</h4>
                </div>
                <div class="icon-circle bg-primary">
                    <i class="fas fa-file-upload text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Under Review</h6>
                    <h4 class="mb-0">{{ $stats['under_review'] ?? auth()->user()->papers()->where('status', 'under_review')->count() }}</h4>
                </div>
                <div class="icon-circle bg-warning">
                    <i class="fas fa-search text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-success h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Accepted</h6>
                    <h4 class="mb-0">{{ $stats['accepted'] ?? auth()->user()->papers()->where('status', 'accepted')->count() }}</h4>
                </div>
                <div class="icon-circle bg-success">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-info h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Published</h6>
                    <h4 class="mb-0">{{ $stats['published'] ?? auth()->user()->papers()->where('status', 'published')->count() }}</h4>
                </div>
                <div class="icon-circle bg-info">
                    <i class="fas fa-book text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Recent Papers -->
    <!-- Recent Papers -->
<div class="col-lg-8 mb-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Submissions</h5>
            <a href="{{ route('author.papers.index') }}" class="btn btn-sm btn-outline-primary">
                View All
            </a>
        </div>
        <div class="card-body">
            @php
                // Jika $recentPapers tidak ada, ambil dari user yang login
                if (!isset($recentPapers)) {
                    $recentPapers = auth()->user()->papers()
                        ->with(['issue', 'reviewAssignments'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                }
            @endphp
            
            @if($recentPapers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPapers as $paper)
                                <tr>
                                    <td>
                                        <a href="{{ route('author.papers.show', $paper) }}" class="text-decoration-none">
                                            {{ Str::limit($paper->title, 50) }}
                                        </a>
                                    </td>
                                    <td>
                                        @include('components.status-badge', ['status' => $paper->status])
                                    </td>
                                    <td>{{ $paper->submitted_at->format('M d, Y') }}</td>
                                    <td>
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
                <div class="text-center py-4">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No papers submitted yet.</p>
                    <a href="{{ route('author.papers.create') }}" class="btn btn-primary">
                        Submit Your First Paper
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
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
                <ul class="list-unstyled text-small">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Ensure your paper follows our guidelines
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Include all required sections
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Check formatting before submission
                    </li>
                    <li>
                        <i class="fas fa-check text-success me-2"></i>
                        Upload PDF format only
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .border-left-primary { border-left: 4px solid #4361ee !important; }
    .border-left-warning { border-left: 4px solid #ffc107 !important; }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
</style>
@endsection

@push('scripts')
<script>
    // Update stats every 30 seconds if needed
    function updateStats() {
        fetch('{{ route("author.dashboard") }}?partial=stats')
            .then(response => response.text())
            .then(html => {
                // Update stats section
                document.getElementById('stats-section').innerHTML = html;
            });
    }
    
    // Auto-update every 30 seconds
    // setInterval(updateStats, 30000);
</script>
@endpush