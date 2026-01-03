@extends('layouts.public')

@section('title', 'Research Papers - ' . config('app.name'))
@section('description', 'Browse all published research papers')

@section('content')

<style>
.btn-lift {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:.25s ease;
}
.btn-lift:hover {
    transform:translateY(-3px);
    box-shadow:0 10px 20px rgba(0,0,0,.18);
}
.btn-lift:active {
    transform:translateY(-1px);
}

.paper-card {
    border:1px solid var(--border);
    border-radius:.75rem;
    background:white;
    height:100%;
    transition:.25s ease;
}
.paper-card:hover {
    transform:translateY(-5px);
    box-shadow:0 12px 28px rgba(0,0,0,.1);
    border-color:var(--primary-color);
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

.stat-box {
    background:var(--muted);
    border:1px solid var(--border);
    border-radius:.75rem;
    padding:1.25rem;
    text-align:center;
}
.stat-box h3 {
    margin:0;
    font-weight:700;
    color:var(--primary-color);
}
.stat-box span {
    font-size:.8rem;
    opacity:.75;
}
</style>

<div class="journal-container py-5">

    <!-- HERO HEADER -->
    <section class="position-relative mb-5" style="border-radius:1rem;overflow:hidden;">
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background:linear-gradient(135deg,#193366 0%,#253d6c 45%,#193366 100%);">
        </div>

        <div class="position-relative p-5 text-white">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3"
                          style="background:rgba(255,209,102,.15);border:1px solid rgba(255,209,102,.3);">
                        <i class="fas fa-file-alt fa-xs" style="color:#FFD166"></i>
                        <span style="color:#FFD166;font-size:.85rem">Published Papers</span>
                    </span>

                    <h1 class="fw-bold mb-2" style="font-size:2.25rem;">
                        Research Papers
                    </h1>
                    <p class="mb-0" style="opacity:.9;max-width:40rem;">
                        Browse peer-reviewed research articles published in our journal.
                    </p>
                </div>

                <!-- SEARCH -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <form method="GET" action="{{ route('papers.index') }}" class="d-flex gap-2">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search papers…">
                        <button class="btn btn-lift"
                                style="background:#FFD166;color:#193366;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTICS -->
    <div class="row g-4 mb-5">
        @php
            $stats = [
                ['val' => \App\Models\Paper::published()->count(), 'label' => 'Published Papers'],
                ['val' => \App\Models\Issue::published()->count(), 'label' => 'Published Issues'],
                ['val' => \App\Models\User::count(), 'label' => 'Registered Users'],
                ['val' => \App\Models\User::role('reviewer')->count(), 'label' => 'Active Reviewers'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="col-md-3">
                <div class="stat-box text-center">
                    <h3 class="stat-number" data-target="{{ $stat['val'] }}">0</h3>
                    <span>{{ $stat['label'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <script>
    document.querySelectorAll('.stat-number').forEach(counter => {
        const target = Number(counter.dataset.target);
        const duration = 300; // durasi animasi dalam ms
        const start = performance.now();

        function animate(time) {
            const progress = Math.min((time - start) / duration, 1);
            counter.textContent = Math.floor(progress * target);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                counter.textContent = target;
            }
        }

        requestAnimationFrame(animate);
    });
    </script>

    <!-- PAPERS GRID -->
    @if($papers->count())
        <div class="row g-4">
            @foreach($papers as $paper)
                <div class="col-md-6 col-lg-4">
                    <div class="paper-card h-100 d-flex flex-column">

                        <!-- HEADER -->
                        <div class="p-4"
                             style="background:var(--primary-color);color:white;border-radius:.75rem .75rem 0 0;">
                            @if($paper->issue)
                                <div class="mb-2" style="font-size:.75rem;opacity:.85;">
                                    Vol {{ $paper->issue->volume }},
                                    No {{ $paper->issue->number }} ·
                                    {{ $paper->issue->year }}
                                </div>
                            @endif

                            <h5 class="mb-0">
                                <a href="{{ route('papers.show',$paper) }}"
                                   class="text-white text-decoration-none">
                                    {{ Str::limit($paper->title,80) }}
                                </a>
                            </h5>
                        </div>

                        <!-- BODY -->
                        <div class="p-4 flex-grow-1">

                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-circle text-muted me-2"></i>
                                <div>
                                    <h6 class="mb-0">{{ $paper->author->name }}</h6>
                                    <small class="text-muted">
                                        {{ $paper->author->institution }}
                                    </small>
                                </div>
                            </div>

                            <p class="text-muted mb-4">
                                {{ Str::limit($paper->abstract,120) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($paper->doi)
                                        <small class="text-muted">
                                            DOI: <code>{{ $paper->doi }}</code>
                                        </small>
                                    @endif
                                </div>

                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('papers.show',$paper) }}"
                                       class="btn btn-outline-primary btn-lift">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('papers.download',$paper) }}"
                                       class="btn btn-outline-danger btn-lift">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        @if($paper->keywords)
                            <div class="px-4 py-2 border-top text-muted"
                                 style="font-size:.75rem;">
                                <i class="fas fa-tags me-1"></i>
                                {{ Str::limit($paper->keywords,40) }}
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>


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
                        @php
                            $url = request()->has('search')
                                ? $url . '&search=' . request('search')
                                : $url;
                        @endphp
                        <li class="page-item {{ $page == $papers->currentPage() ? 'active' : '' }}">
                            <a class="page-link btn-lift"
                            href="{{ $url }}">
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
            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
            <h4 class="fw-semibold">No Papers Found</h4>
            <p class="text-muted mb-4">
                @if(request()->has('search'))
                    Try adjusting your search terms
                @else
                    No papers have been published yet.
                @endif
            </p>
            <a href="{{ route('papers.index') }}" class="btn btn-primary btn-lift">
                <i class="fas fa-sync me-2"></i> Clear Search
            </a>
        </div>
    @endif

</div>
@endsection
