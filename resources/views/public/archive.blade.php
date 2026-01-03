@extends('layouts.public')

@section('title', 'Journal Archive - ' . config('app.name'))
@section('description', 'Browse archived journal issues and papers')

@section('content')
<div class="journal-container py-5">

    <!-- HERO HEADER -->
    <section class="position-relative mb-5" style="border-radius: 1rem; overflow: hidden;">
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background: linear-gradient(135deg, #193366 0%, #253d6c 45%, #193366 100%);">
        </div>
        <div class="position-relative p-5 text-white">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="fw-bold mb-2" style="font-size:2.25rem;">Journal Archive</h1>
                    <p class="mb-0" style="opacity:.9; max-width:40rem;">
                        Browse through our complete collection of archived journal issues and research papers from previous years.
                    </p>
                </div>
                <!-- SEARCH -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <form method="GET" action="{{ route('archive') }}" class="d-flex gap-2">
                        @foreach(request()->except('search','page') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search archive…">
                        <button class="btn btn-lift" style="background:#FFD166;color:#193366;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- YEAR FILTER -->
    <div class="mb-5 p-4 rounded"
         style="background: var(--muted); border:1px solid var(--border); box-shadow:0 4px 6px rgba(0,0,0,0.05);">
        <div class="row g-3 align-items-center">
            <div class="col-md-12 d-flex flex-wrap gap-2 justify-content-center">
                @php
                    $years = \App\Models\Issue::select('year')->distinct()->orderBy('year','desc')->pluck('year');
                @endphp
                @foreach($years as $year)
                    @php
                        $query = request()->except('page');
                        $query['year'] = $year;
                    @endphp
                    <a href="{{ route('archive',$query) }}" 
                       class="btn btn-outline-primary btn-lift btn-sm">
                        {{ $year }} <span class="badge bg-primary rounded-pill">{{ \App\Models\Issue::where('year', $year)->count() }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- BACK BUTTON -->
    <div class="mb-4 d-flex justify-content-end">
        <a href="{{ route('issues.index') }}" class="btn btn-lift btn-sm" style="background: var(--primary-color); color:white;">
            <i class="fas fa-arrow-left me-1"></i> Back to Current Issues
        </a>
    </div>

    <!-- ISSUES GRID -->
    @php
        $issues = \App\Models\Issue::with('editorial','papers')
            ->when(request('year'), fn($q)=>$q->where('year',request('year')))
            ->when(request('search'), fn($q)=>$q->where('title','like','%'.request('search').'%'))
            ->orderBy('year','desc')
            ->orderBy('volume','desc')
            ->orderBy('number','desc')
            ->paginate(9)
            ->appends(request()->except('page'));
    @endphp

    @if($issues->count())
        <div class="row g-4">
            @foreach($issues as $issue)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 archive-card">

                        <!-- HEADER -->
                        <div class="p-4" style="background:var(--primary-color); color:white; border-radius:.75rem .75rem 0 0;">
                            <div class="d-flex justify-content-between mb-2" style="font-size:.95rem; opacity:.85;">
                                <div>
                                    <span class="badge bg-{{ $issue->status=='published'?'success':($issue->status=='draft'?'warning':'secondary') }} btn-lift">
                                        {{ ucfirst($issue->status) }}
                                    </span>
                                    <span class="badge bg-secondary ms-2">{{ $issue->year }}</span>
                                </div>
                                <small>Vol {{ $issue->volume }}, No {{ $issue->number }}</small>
                            </div>

                            <h3 class="fw-bold mb-1" style="font-size:1.15rem;">{{ $issue->title }}</h3>
                            <small style="opacity:.75;">
                                Published: {{ $issue->published_date?->format('M d, Y') ?? 'N/A' }}
                            </small>

                            <div class="d-flex gap-3 mt-2" style="font-size:.75rem; opacity:.75;">
                                <span><i class="fas fa-file-alt me-1"></i>{{ $issue->papers->count() }} papers</span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="p-4">
                            <p class="mb-3" style="font-size:.9rem; opacity:.75;">{{ Str::limit($issue->description, 120) }}</p>

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

                                @if($issue->status=='published')
                                    <a href="{{ route('issues.show', $issue) }}" class="btn-lift px-3 py-2 rounded" style="background:var(--primary-color); color:white; font-size:.8rem;">
                                        View Issue <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                @else
                                    <span class="text-muted">Not published</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
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

                    {{-- Pages --}}
                    @foreach ($issues->getUrlRange(1, $issues->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $issues->currentPage() ? 'active' : '' }}">
                            <a class="page-link btn-lift" href="{{ $url }}">
                                {{ $page }}
                            </a>
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
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
            <h4 class="fw-semibold">No Issues Found</h4>
            <p class="text-muted mb-4">No published issues match your current filters.</p>
            <button onclick="resetFilters()" class="btn btn-primary btn-sm">Clear Filters</button>
        </div>
    @endif

</div>

<style>
.btn-lift {
    display: inline-block;
    text-decoration: none !important;
    transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease, color .25s ease;
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

.archive-card {
    transition: transform 0.3s;
    border: 1px solid #e9ecef;
}
.archive-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}


</style>

@push('scripts')
<script>
function resetFilters() {
    window.location.href = "{{ route('archive') }}";
}
</script>
@endpush
@endsection
