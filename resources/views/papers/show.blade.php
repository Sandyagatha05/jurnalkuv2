@extends('layouts.public')

@section('title', $paper->title . ' - ' . config('app.name'))
@section('description', Str::limit($paper->abstract, 160))

@section('content')

<!-- ================= HERO ================= -->
<section class="issue-hero">
    <div class="container">
        <nav class="breadcrumb breadcrumb-dark mb-3">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('papers.index') }}">Papers</a>
            <span>/</span>
            <span>{{ Str::limit($paper->title, 50) }}</span>
        </nav>

        <div class="issue-hero-meta">
            <i class="fas fa-file-alt"></i>
            <span>Published Paper</span>
        </div>

        <h1 class="issue-hero-title">{{ $paper->title }}</h1>

        <div class="issue-hero-info">
            <span>
                <i class="fas fa-user"></i>
                {{ $paper->author->name }}
            </span>
            <span>
                <i class="far fa-calendar"></i>
                {{ $paper->published_at->format('F d, Y') }}
            </span>
            <span>
                <i class="fas fa-book"></i>
                @if($paper->issue)
                    Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }}
                @else
                    No Issue
                @endif
            </span>
        </div>
    </div>
</section>

<!-- ================= CONTENT ================= -->
<section class="container journal-section">
    <div class="journal-wrapper">

        <!-- ================= PAPER INFO CARDS ================= -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="journal-card slide-up">
                    <h5>Author Information</h5>
                    <p><strong>{{ $paper->author->name }}</strong></p>
                    <p class="text-muted">
                        {{ $paper->author->institution }}
                        @if($paper->author->department)
                            , {{ $paper->author->department }}
                        @endif
                    </p>
                    @if($paper->author->email)
                        <p>
                            <i class="fas fa-envelope me-1"></i>
                            <a href="mailto:{{ $paper->author->email }}">{{ $paper->author->email }}</a>
                        </p>
                    @endif
                    @if($paper->author->orcid_id || $paper->author->google_scholar_id)
                        <div class="mt-2 d-flex gap-2 flex-wrap">
                            @if($paper->author->orcid_id)
                                <a href="https://orcid.org/{{ $paper->author->orcid_id }}" target="_blank" class="btn btn-sm btn-primary-soft btn-lift">
                                    <i class="fab fa-orcid me-1"></i> ORCID
                                </a>
                            @endif
                            @if($paper->author->google_scholar_id)
                                <a href="https://scholar.google.com/citations?user={{ $paper->author->google_scholar_id }}" target="_blank" class="btn btn-sm btn-secondary-soft btn-lift">
                                    <i class="fab fa-google me-1"></i> Google Scholar
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="journal-card slide-up">
                    <h5>Paper Information</h5>
                    <p><strong>Status:</strong> Published</p>
                    <p><strong>Submission:</strong> {{ $paper->submitted_at->format('F d, Y') }}</p>
                    <p><strong>Publication:</strong> {{ $paper->published_at->format('F d, Y') }}</p>
                    @if($paper->revision_count > 0)
                        <p><strong>Revisions:</strong> {{ $paper->revision_count }}</p>
                    @endif
                    @if($paper->issue)
                        <p><strong>Published in:</strong> 
                            <a href="{{ route('issues.show', $paper->issue) }}">
                                Vol. {{ $paper->issue->volume }}, No. {{ $paper->issue->number }}
                            </a>
                        </p>
                    @endif
                    @if($paper->doi)
                        <p><strong>DOI:</strong> <a href="https://doi.org/{{ $paper->doi }}" target="_blank">{{ $paper->doi }}</a></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= ABSTRACT ================= -->
        <div class="journal-card slide-up mb-5">
            <h5>Abstract</h5>
            <p>{{ $paper->abstract }}</p>
            @if($paper->keywords)
                <div class="article-keywords mt-3">
                    @foreach(explode(',', $paper->keywords) as $kw)
                        <span class="keyword-badge">{{ trim($kw) }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- ================= DOWNLOAD & CITATION ================= -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="journal-card slide-up text-center">
                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                    <h6>Full Text PDF</h6>
                    <p class="text-muted">Download the complete paper</p>
                    <a href="{{ route('papers.download', $paper) }}" class="btn btn-primary-soft btn-lift w-100 mb-2">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                    <small class="text-muted d-block">File: {{ $paper->original_filename }}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="journal-card slide-up">
                    <h6>Citation (APA)</h6>
                    <textarea class="form-control mb-2" rows="3" readonly>
{{ $paper->author->name }} ({{ $paper->published_at->format('Y') }}). {{ $paper->title }}. 
{{ config('app.name') }}{{ $paper->issue ? ', ' . $paper->issue->volume . '(' . $paper->issue->number . ')' : '' }}, 
{{ $paper->page_from ? $paper->page_from . '-' . $paper->page_to : '' }}. 
{{ $paper->doi ? 'https://doi.org/' . $paper->doi : '' }}
                    </textarea>
                    <button class="btn btn-secondary-soft w-100 btn-lift" onclick="copyCitation()">
                        <i class="fas fa-copy me-1"></i> Copy Citation
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= NAVIGATION ================= -->
        <div class="text-center mt-5">
            @if($previousPaper)
                <a href="{{ route('papers.show', $previousPaper) }}" class="btn btn-primary-soft btn-lift me-2">
                    <i class="fas fa-arrow-left me-1"></i> Previous Paper
                </a>
            @endif
            <a href="{{ route('papers.index') }}" class="btn btn-secondary-soft btn-lift me-2">
                <i class="fas fa-list me-1"></i> All Papers
            </a>
            @if($nextPaper)
                <a href="{{ route('papers.show', $nextPaper) }}" class="btn btn-primary-soft btn-lift">
                    Next Paper <i class="fas fa-arrow-right ms-1"></i>
                </a>
            @endif
        </div>

    </div>
</section>

<!-- ================= STYLES ================= -->
<style>
.issue-hero {
    background: var(--primary-color);
    color: #fff;
    padding: 4rem 0;
}
.breadcrumb-dark a { color: rgba(255,255,255,.7); text-decoration:none; }
.breadcrumb-dark span { margin:0 .4rem; opacity:.6; }

.issue-hero-meta { display:flex; gap:.5rem; font-size:.9rem; margin-bottom:.5rem; }
.issue-hero-title { font-weight:700; font-size:2.2rem; margin-bottom:.5rem; }
.issue-hero-info { margin-top:1rem; display:flex; gap:1.5rem; opacity:.85; flex-wrap:wrap; }

.journal-section { padding:4rem 0; }
.journal-wrapper { max-width:900px; margin:auto; }

.journal-card {
    background:#fff;
    border-radius:14px;
    padding:1.75rem;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
    margin-bottom:1.75rem;
}

.article-keywords { display:flex; gap:.4rem; flex-wrap:wrap; margin-top:1rem; }
.keyword-badge { font-size:.8rem; background:#eee; padding:.25rem .6rem; border-radius:999px; }

.btn-primary-soft { background: var(--primary-color); color:#fff; }
.btn-secondary-soft { background:#f1f3f5; color:#333; }
.btn-primary-soft:hover, .btn-secondary-soft:hover { filter:brightness(.95); }

.btn-lift { transition: all .2s ease; }
.btn-lift:hover { transform: translateY(-2px); box-shadow:0 6px 15px rgba(0,0,0,.12); }

.fade-in { animation:fade .5s ease both; }
.slide-up { animation:slide .5s ease both; }

@keyframes fade { from { opacity:0; } to { opacity:1; } }
@keyframes slide { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
</style>

@push('scripts')
<script>
function copyCitation() {
    const citationText = document.querySelector('textarea').value;
    navigator.clipboard.writeText(citationText)
        .then(() => alert('Citation copied to clipboard!'))
        .catch(err => console.error('Failed to copy: ', err));
}
</script>
@endpush

@endsection
