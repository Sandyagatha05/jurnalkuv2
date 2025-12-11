@extends('layouts.public')

@section('title', 'Journal Issues - ' . config('app.name'))
@section('description', 'Browse all published journal issues')

@section('content')
<div class="container py-5">
    <!-- Header dengan Search -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">Journal Issues</h1>
            <p class="lead">
                Browse through our collection of published journal issues. 
                Each issue contains carefully selected research papers and editorial content.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <form method="GET" action="{{ route('issues.index') }}" class="d-flex">
                <!-- Keep existing filters -->
                @if(request()->has('year'))
                    <input type="hidden" name="year" value="{{ request('year') }}">
                @endif
                @if(request()->has('volume'))
                    <input type="hidden" name="volume" value="{{ request('volume') }}">
                @endif
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Search issues..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="year" class="form-select" id="yearFilter">
                                <option value="">All Years</option>
                                @if(isset($years) && count($years) > 0)
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="volume" class="form-select" id="volumeFilter">
                                <option value="">All Volumes</option>
                                @if(isset($volumes) && count($volumes) > 0)
                                    @foreach($volumes as $volume)
                                        <option value="{{ $volume }}" {{ request('volume') == $volume ? 'selected' : '' }}>
                                            Volume {{ $volume }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                                    <i class="fas fa-sync me-2"></i> Reset Filters
                                </button>
                                <a href="{{ route('archive') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-archive me-2"></i> View Archive
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Issues Grid -->
    @if($issues->count() > 0)
        <div class="row">
            @foreach($issues as $issue)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 issue-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary">Vol. {{ $issue->volume }}, No. {{ $issue->number }}</span>
                                    <span class="badge bg-secondary">{{ $issue->year }}</span>
                                </div>
                                <small class="text-muted">
                                    {{ $issue->published_date->format('M d, Y') }}
                                </small>
                            </div>
                            
                            <h5 class="card-title mb-3">
                                <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none text-dark">
                                    {{ $issue->title }}
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted mb-4">
                                {{ Str::limit($issue->description, 120) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-alt text-primary me-1"></i>
                                    <small class="text-muted">
                                        {{ $issue->papers->count() }} papers
                                    </small>
                                </div>
                                <a href="{{ route('issues.show', $issue) }}" class="btn btn-sm btn-outline-primary">
                                    View Issue <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        @if($issue->editorial && $issue->editorial->is_published)
                            <div class="card-footer bg-transparent border-top">
                                <small class="text-muted">
                                    <i class="fas fa-edit me-1"></i>
                                    Editorial: "{{ Str::limit($issue->editorial->title, 40) }}"
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $issues->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
            <h4 class="text-muted mb-3">No Issues Found</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'year', 'volume']))
                    Try adjusting your search filters
                @else
                    No issues have been published yet.
                @endif
            </p>
            <button class="btn btn-primary" onclick="resetFilters()">
                <i class="fas fa-sync me-2"></i> Clear Filters
            </button>
        </div>
    @endif
</div>

<style>
    .issue-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e9ecef;
    }
    
    .issue-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }
    
    .issue-card .card-title {
        min-height: 60px;
    }
</style>
@endsection

@push('scripts')
<script>
    // Filter by year
    document.getElementById('yearFilter').addEventListener('change', function() {
        applyFilter('year', this.value);
    });
    
    // Filter by volume
    document.getElementById('volumeFilter').addEventListener('change', function() {
        applyFilter('volume', this.value);
    });
    
    // Function to apply filter
    function applyFilter(field, value) {
        const url = new URL(window.location.href);
        
        // Reset to page 1 when filtering
        if (url.searchParams.has('page')) {
            url.searchParams.delete('page');
        }
        
        if (value) {
            url.searchParams.set(field, value);
        } else {
            url.searchParams.delete(field);
        }
        
        // Keep search term if exists
        const searchTerm = "{{ request('search') }}";
        if (searchTerm) {
            url.searchParams.set('search', searchTerm);
        }
        
        window.location.href = url.toString();
    }
    
    // Reset all filters
    function resetFilters() {
        window.location.href = "{{ route('issues.index') }}";
    }
    
    // Show active filters info
    document.addEventListener('DOMContentLoaded', function() {
        const yearFilter = document.getElementById('yearFilter');
        const volumeFilter = document.getElementById('volumeFilter');
        const searchValue = "{{ request('search') }}";
        
        // Add visual indicator for active filters
        if (yearFilter.value) {
            yearFilter.classList.add('border-primary', 'border-2');
        }
        if (volumeFilter.value) {
            volumeFilter.classList.add('border-primary', 'border-2');
        }
        
        // Show active filters count
        let activeFilters = 0;
        if (yearFilter.value) activeFilters++;
        if (volumeFilter.value) activeFilters++;
        if (searchValue) activeFilters++;
        
        if (activeFilters > 0) {
            const filterInfo = document.createElement('div');
            filterInfo.className = 'alert alert-info mt-3';
            filterInfo.innerHTML = `
                <i class="fas fa-filter me-2"></i>
                <strong>${activeFilters} filter(s) active</strong>
                <button class="btn btn-sm btn-outline-info ms-3" onclick="resetFilters()">
                    Clear all
                </button>
            `;
            document.querySelector('.card-body.py-3').appendChild(filterInfo);
        }
    });
</script>
@endpush