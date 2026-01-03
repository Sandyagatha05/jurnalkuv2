@extends('layouts.public')

@section('title', 'Journal Issues - ' . config('app.name'))
@section('description', 'Browse all published journal issues')

@section('content')

<style>
.btn-lift {
    display: inline-block;
    text-decoration: none !important;
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        background-color 0.25s ease,
        color 0.25s ease;
    will-change: transform, box-shadow;
}

.btn-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.18);
}

.btn-lift:active {
    transform: translateY(-1px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.filter-select {
    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        transform .15s ease;
}

.filter-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 .15rem rgba(25,51,102,.15);
}

.filter-select:hover {
    transform: translateY(-1px);
}

.pagination .page-link {
    border-radius:.5rem;
    border:1px solid var(--border);
    color:var(--foreground);
    padding:.5rem .75rem;
    background:white;
}
.pagination .page-item.active .page-link {
    background:var(--primary-color);
    color:white;
    border-color:var(--primary-color);
}
.pagination .page-item.disabled .page-link {
    opacity:.4;
    pointer-events:none;
}
</style>

<div class="journal-container py-5">

    <!-- HERO HEADER -->
    <section class="position-relative mb-5" style="border-radius: 1rem; overflow: hidden;">
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background: linear-gradient(135deg, #193366 0%, #253d6c 45%, #193366 100%);">
        </div>

        <div class="position-relative p-5 text-white">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3"
                          style="background: rgba(255,209,102,.15); border:1px solid rgba(255,209,102,.3);">
                        <i class="fas fa-book-open fa-xs" style="color:#FFD166"></i>
                        <span style="color:#FFD166;font-size:.85rem">Published Issues</span>
                    </span>

                    <h1 class="fw-bold mb-2" style="font-size:2.25rem;">
                        Journal Issues
                    </h1>
                    <p class="mb-0" style="opacity:.9; max-width:40rem;">
                        Browse officially published journal volumes containing peer-reviewed research
                        and editorial insights.
                    </p>
                </div>

                <!-- SEARCH -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <form method="GET" action="{{ route('issues.index') }}" class="d-flex gap-2">
                        @foreach(['year','volume'] as $f)
                            @if(request()->has($f))
                                <input type="hidden" name="{{ $f }}" value="{{ request($f) }}">
                            @endif
                        @endforeach
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search issue title…">
                        <button class="btn btn-lift"
                                style="background:#FFD166;color:#193366;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FILTER BAR -->
    <div class="mb-5 p-4 rounded"
        style="
            background: var(--muted);
            border:1px solid var(--border);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        ">

        <div class="row g-3 align-items-center">

            <!-- YEAR FILTER -->
            <div class="col-md-3">
                <label class="fw-medium mb-1 d-block"
                    style="font-size:.8rem; color:var(--foreground); opacity:.7;">
                    Year
                </label>
                <select id="yearFilter"
                        class="form-select filter-select">
                    <option value="">All Years</option>
                    @foreach($years ?? [] as $year)
                        <option value="{{ $year }}" @selected(request('year')==$year)>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- VOLUME FILTER -->
            <div class="col-md-3">
                <label class="fw-medium mb-1 d-block"
                    style="font-size:.8rem; color:var(--foreground); opacity:.7;">
                    Volume
                </label>
                <select id="volumeFilter"
                        class="form-select filter-select">
                    <option value="">All Volumes</option>
                    @foreach($volumes ?? [] as $vol)
                        <option value="{{ $vol }}" @selected(request('volume')==$vol)>
                            Volume {{ $vol }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="col-md-6 text-md-end">
                <div class="d-flex gap-2 justify-content-md-end align-items-end h-100">

                    <!-- RESET -->
                    <button onclick="resetFilters()"
                            class="btn btn-lift btn-sm"
                            style="
                                background: white;
                                border:1px solid var(--border);
                                color:var(--foreground);
                            ">
                        <i class="fas fa-sync me-1"></i>
                        Reset
                    </button>

                    <!-- ARCHIVE -->
                    <a href="{{ route('archive') }}"
                    class="btn btn-lift btn-sm"
                    style="
                            background: var(--primary-color);
                            color: white;
                    ">
                        <i class="fas fa-archive me-1"></i>
                        Archive
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- ISSUES GRID -->
    @php
        $issues = \App\Models\Issue::with('editorial','papers')
            ->orderBy('year','desc')
            ->orderBy('volume','desc')
            ->orderBy('number','desc')
            ->paginate(9);
    @endphp

    @if($issues->count())
        <div class="row g-4">
            @foreach($issues as $issue)
                <div class="col-md-6 col-lg-4">
                    <div class="h-100 rounded issue-card"
                         style="border:1px solid var(--border); background:white;">
    
                        <!-- HEADER -->
                        <div class="p-4" style="background:var(--primary-color); color:white; border-radius:.75rem .75rem 0 0;">
                            <div class="d-flex justify-content-between mb-2" style="font-size:.75rem; opacity:.85;">
                                <span>Vol {{ $issue->volume }}, No {{ $issue->number }}</span>
                                <span>{{ $issue->year }}</span>
                            </div>

                            <h3 class="fw-bold mb-1" style="font-size:1.15rem;">
                                {{ $issue->title }}
                            </h3>

                            <small style="opacity:.75;">
                                Published: {{ $issue->published_date->format('M d, Y') }}
                            </small>

                            <div class="d-flex gap-3 mt-2" style="font-size:.75rem; opacity:.75;">
                                <span><i class="fas fa-file-alt me-1"></i>{{ $issue->papers->count() }} papers</span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="p-4">
                            <p class="mb-3" style="font-size:.9rem; opacity:.75;">
                                {{ Str::limit($issue->description, 120) }}
                            </p>

                            @if($issue->editorial && $issue->editorial->is_published)
                                <div class="mb-3 p-3 rounded" style="background:var(--muted); font-size:.85rem;">
                                    <i class="fas fa-edit me-1 text-secondary"></i>
                                    <strong>Editorial:</strong> "{{ Str::limit($issue->editorial->title, 40) }}"
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted" style="font-size:.8rem;">
                                    <i class="fas fa-book me-1"></i>{{ $issue->papers->count() }} Articles
                                </span>

                                <a href="{{ route('issues.show', $issue) }}"
                                   class="btn-lift px-3 py-2 rounded"
                                   style="background:var(--primary-color); color:white; font-size:.8rem;">
                                    View Issue <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->

        <!-- PAGINATION ISSUES -->
        @if ($issues->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination gap-2">

                    {{-- Previous --}}
                    <li class="page-item {{ $issues->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link btn-lift" href="{{ $issues->previousPageUrl() ?? '#' }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    {{-- Page Numbers --}}
                    @foreach ($issues->getUrlRange(1, $issues->lastPage()) as $page => $url)
                        @php
                            // Keep search/year/volume query
                            $url = request()->has('search') ? $url . '&search=' . request('search') : $url;
                            $url = request()->has('year') ? $url . '&year=' . request('year') : $url;
                            $url = request()->has('volume') ? $url . '&volume=' . request('volume') : $url;
                        @endphp
                        <li class="page-item {{ $page == $issues->currentPage() ? 'active' : '' }}">
                            <a class="page-link btn-lift" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next --}}
                    <li class="page-item {{ $issues->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link btn-lift" href="{{ $issues->nextPageUrl() ?? '#' }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
        @endif

    @else
        <!-- EMPTY STATE -->
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
            <h4 class="fw-semibold">No Issues Found</h4>
            <p class="text-muted mb-4">
                No published issues match your current filters.
            </p>
            <button onclick="resetFilters()" class="btn btn-primary btn-sm">
                Clear Filters
            </button>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function applyFilter(key, val) {
    const url = new URL(window.location.href);
    url.searchParams.delete('page');
    val ? url.searchParams.set(key,val) : url.searchParams.delete(key);
    window.location.href = url.toString();
}
document.getElementById('yearFilter')?.addEventListener('change',e=>applyFilter('year',e.target.value));
document.getElementById('volumeFilter')?.addEventListener('change',e=>applyFilter('volume',e.target.value));
function resetFilters() {
    window.location.href = "{{ route('issues.index') }}";
}
</script>
@endpush
