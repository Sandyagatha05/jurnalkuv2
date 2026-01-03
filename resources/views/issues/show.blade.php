@extends('layouts.public')

@section('title', $issue->title . ' - ' . config('app.name'))
@section('description', Str::limit($issue->description, 160))

@section('content')

<!-- ================= HERO ================= -->
<section class="issue-hero">
    <div class="container">
        <nav class="breadcrumb breadcrumb-dark mb-3">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('issues.index') }}">Archives</a>
            <span>/</span>
            <span>Vol. {{ $issue->volume }} No. {{ $issue->number }}</span>
        </nav>

        <div class="issue-hero-meta">
            <i class="fas fa-book-open"></i>
            <span>{{ $issue->title }}</span>
        </div>

        <h1 class="issue-hero-title">
            Volume {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }})
        </h1>

        <div class="issue-hero-info">
            <span>
                <i class="far fa-calendar"></i>
                {{ $issue->published_date->format('F Y') }}
            </span>
            <span>
                <i class="fas fa-file-alt"></i>
                {{ $issue->papers->count() }} articles
            </span>
        </div>
    </div>
</section>

<!-- ================= CONTENT ================= -->
<section class="container journal-section">
    <div class="journal-wrapper">

        <!-- ================= EDITORIAL ================= -->
        @if($issue->editorial && $issue->editorial->is_published)
            <section class="mb-5 fade-in">
                <span class="section-label">Editorial</span>

                <div class="editorial-box">
                    <h2 class="journal-title">
                        {{ $issue->editorial->title }}
                    </h2>
                    <p class="journal-meta">
                        by {{ $issue->editorial->author->name }}
                        — {{ $issue->editorial->author->institution }}
                    </p>

                    <div class="journal-text">
                        {!! nl2br(e($issue->editorial->content)) !!}
                    </div>
                </div>
            </section>
        @endif

        <!-- ================= ARTICLES HEADER ================= -->
        <div class="articles-header">
            <h2>Articles</h2>
            <div class="articles-line"></div>
            <span class="articles-count">
                {{ $issue->papers->count() }} articles
            </span>
        </div>

        <!-- ================= ARTICLES ================= -->
        <div class="article-list">
            @foreach($issue->papers as $index => $paper)
                <article class="journal-card slide-up"
                         style="animation-delay: {{ $index * 100 }}ms">

                    <a href="{{ route('papers.show', $paper) }}"
                       class="article-title">
                        {{ $paper->title }}
                    </a>

                    <div class="article-authors">
                        <i class="fas fa-user author-icon"></i>
                        {{ $paper->author->name }}
                        <span>({{ $paper->author->institution }})</span>
                    </div>

                    <p class="article-abstract">
                        {{ Str::limit($paper->abstract, 280) }}
                    </p>

                    @if($paper->keywords)
                        <div class="article-keywords">
                            @foreach(explode(',', $paper->keywords) as $kw)
                                <span class="keyword-badge">
                                    {{ trim($kw) }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="article-footer">
                        <div class="article-meta">
                            @if($paper->doi)
                                <span>DOI: {{ $paper->doi }}</span>
                            @endif
                            @if($paper->page_from && $paper->page_to)
                                <span>Pages {{ $paper->page_from }}–{{ $paper->page_to }}</span>
                            @endif
                        </div>

                        <div class="article-actions">
                            <a href="{{ route('papers.show', $paper) }}"
                               class="btn btn-primary-soft btn-lift">
                                Read
                            </a>
                            <a href="{{ route('papers.download', $paper) }}"
                               class="btn btn-secondary-soft btn-lift">
                                <i class="fas fa-download me-1"></i> PDF
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- ================= BACK ================= -->
        <div class="text-center mt-5">
            <a href="{{ route('issues.index') }}"
               class="btn btn-outline-secondary btn-lift">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Archives
            </a>
        </div>

    </div>
</section>

<!-- ================= STYLES ================= -->
<style>
/* HERO */

.section-label {
    display:inline-block;
    font-size:.75rem;
    text-transform:uppercase;
    letter-spacing:.08em;
    background:rgba(0,0,0,.05);
    padding:.3rem .8rem;
    border-radius:999px;
    margin-bottom:1rem;
}

.issue-hero {
    background: var(--primary-color);
    color: #fff;
    padding: 4rem 0;
}
.breadcrumb-dark a {
    color: rgba(255,255,255,.7);
    text-decoration:none;
}
.breadcrumb-dark span { margin: 0 .4rem; opacity:.6; }

.issue-hero-meta {
    display:flex;
    gap:.5rem;
    color:var(--secondary-color);
    font-size:.9rem;
    margin-bottom:.5rem;
}
.issue-hero-title {
    font-weight:700;
    font-size:2.2rem;
}
.issue-hero-info {
    margin-top:1rem;
    display:flex;
    gap:1.5rem;
    opacity:.85;
}

/* LAYOUT */
.journal-section { padding:4rem 0; }
.journal-wrapper { max-width:900px; margin:auto; }

/* EDITORIAL */
.editorial-box {
    background: hsl(var(--journal-cream) / .5);
    border: 1px solid #1932631f;
    background-color: #c4a86111;
    border-radius: 18px;
    padding: 2rem;
}

/* ARTICLES HEADER */
.articles-header {
    display:flex;
    align-items:center;
    gap:1rem;
    margin:3rem 0 2rem;
}
.articles-header h2 {
    margin:0;
    font-weight:600;
}
.articles-line {
    flex:1;
    height:1px;
    background:#ddd;
}
.articles-count {
    font-size:0.9rem;
    color:#666;
    white-space:nowrap;
}

/* CARDS */
.journal-card {
    background:#fff;
    border-radius:14px;
    padding:1.75rem;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
    margin-bottom:1.75rem;
}

.journal-title { font-size:1.25rem; font-weight:600; }

/* ARTICLE */
.article-title {
    font-size:1.2rem;
    font-weight:600;
    color:#111;
    text-decoration:none;
}
.article-title:hover {
    color:var(--primary-color);
}

.author-icon {
    margin-right: 5px;
}

.article-authors {
    font-size:.85rem;
    color:#666;
    margin-top:.5rem;
}
.article-abstract {
    margin-top:1rem;
    line-height:1.7;
    color:#444;
}

.article-keywords {
    margin-top:1rem;
    display:flex;
    gap:.4rem;
    flex-wrap:wrap;
}
.keyword-badge {
    font-size:.8rem;
    background:#eee;
    padding:.25rem .6rem;
    border-radius:999px;
}

.article-footer {
    margin-top:1.5rem;
    padding-top:1rem;
    border-top:1px solid #eee;
    display:flex;
    justify-content:space-between;
    gap:1rem;
    flex-wrap:wrap;
}

.article-meta { font-size:.85rem; color:#666; }

/* BUTTONS (HOME STYLE) */
.btn-primary-soft {
    background: var(--primary-color);
    color:#fff;
}
.btn-secondary-soft {
    background:#f1f3f5;
    color:#333;
}
.btn-primary-soft:hover,
.btn-secondary-soft:hover {
    filter:brightness(.95);
}

/* ANIMATION */
.btn-lift {
    transition: all .2s ease;
}
.btn-lift:hover {
    transform: translateY(-2px);
    box-shadow:0 6px 15px rgba(0,0,0,.12);
}

.fade-in {
    animation:fade .5s ease both;
}
.slide-up {
    animation:slide .5s ease both;
}

@keyframes fade {
    from { opacity:0; }
    to { opacity:1; }
}
@keyframes slide {
    from { opacity:0; transform:translateY(12px); }
    to { opacity:1; transform:none; }
}
</style>
@endsection
