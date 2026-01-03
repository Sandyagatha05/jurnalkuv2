@extends('layouts.public')

@section('title', 'Home - ' . config('app.name'))
@section('description', 'Welcome to Jurnalku - Academic Journal Management System')

@section('content')

@php
    $issues = App\Models\Issue::with('editorial')
        ->withCount('papers') // hanya ini yang dibutuhkan
        ->published()
        ->orderBy('year', 'desc')
        ->orderBy('volume', 'desc')
        ->orderBy('number', 'desc')
        ->take(3)
        ->get();
@endphp

<div class="journal-container py-5">

    <!-- Hero Section -->
    <section class="position-relative mb-5" style="border-radius: 1rem; overflow: visible;">
        <!-- Smooth Gradient Background -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="
            background: linear-gradient(135deg, #193366 0%, #253d6c 40%, #193366 100%);
            border-radius: 1rem;
        "></div>

        <!-- Subtle Pattern (lebih halus) -->
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="
            background-image: url(&quot;data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 5c2.2 0 4 1.8 4 4s-1.8 4-4 4-4-1.8-4-4 1.8-4 4-4zm50 30c2.2 0 4 1.8 4 4s-1.8 4-4 4-4-1.8-4-4 1.8-4 4-4z' fill='%23ffffff' fill-opacity='0.08'/%3E%3C/svg%3E&quot;);
        "></div>

        <!-- Konten -->
        <div class="journal-container position-relative py-5 py-md-6" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Badge -->
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-4" style="
                        background: rgba(255, 209, 102, 0.15);
                        border: 1px solid rgba(255, 209, 102, 0.3);
                    ">
                        <i class="fas fa-book-open fa-xs" style="color: #FFD166;"></i>
                        <span class="fw-medium" style="font-size: 0.875rem; color: #FFD166;">Accredited Scientific Journal</span>
                    </div>

                    <!-- Title -->
                    <h1 class="fw-bold mb-3" style="font-size: 2.25rem; line-height: 1.3; color: white; text-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                        IMPORTANT!
                        <span class="d-block mt-1" style="font-size: 1.5rem; font-weight: 600; color: #FFD166;">Paper Registration → $150</span>
                        <span class="d-block mt-1" style="font-size: 1.5rem; font-weight: 600; color: #FFD166;">Conference Date → 2 July 2025</span>
                    </h1>

                    <!-- Description -->
                    <p class="mb-4" style="font-size: 1.1rem; color: rgba(255,255,255,0.85); max-width: 100%;">
                        Jurnalku provides open access to the publication and dissemination of high-quality scientific research across a wide range of disciplines.
                    </p>

                    <!-- CTAs -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 fw-semibold rounded"
                                style="background-color: #FFD166; color: #193366; text-decoration: none; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-4 py-2 fw-semibold rounded"
                                style="background-color: #FFD166; color: #193366; text-decoration: none; font-size: 1rem; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                <i class="fas fa-user-plus me-2"></i> Get Started
                            </a>
                            <a href="{{ route('login') }}" class="px-4 py-2 fw-semibold rounded"
                                style="background-color: transparent; border: 1px solid rgba(255,255,255,0.5); color: white; text-decoration: none; font-size: 1rem;">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </a>
                        @endauth
                    </div>

                    <!-- Stats -->
                    <div class="d-flex gap-4">
                        @foreach([['val' => '500+', 'label' => 'Published Articles'],
                                ['val' => '120+', 'label' => 'Active Authors'],
                                ['val' => '7', 'label' => 'Published Volumes']] as $stat)
                            <div class="text-center">
                                <div class="fs-3 fw-bold" style="color: #FFD166;">{{ $stat['val'] }}</div>
                                <div class="text-white" style="font-size: 0.875rem; opacity: 0.9;">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </section>

    

    <!-- Features -->
    <section class="mb-5">
        <h2 class="text-center fw-bold mb-5">Key Features</h2>
        <div class="row g-4">
            @foreach([['icon' => 'fa-file-upload', 'title' => 'Easy Submission', 'desc' => 'Submit your papers easily with our streamlined submission system.'],
                     ['icon' => 'fa-search', 'title' => 'Peer Review', 'desc' => 'Robust double-blind peer review process with expert reviewers.'],
                     ['icon' => 'fa-book-open', 'title' => 'Issue Management', 'desc' => 'Organize and publish journal issues with editorial content.']] as $item)
                <div class="col-md-4">
                    <div class="h-100 p-4 text-center" style="background-color: white; border-radius: 0.75rem; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: all 0.3s;">
                        <div class="mb-3">
                            <i class="fas {{ $item['icon'] }} fa-3x" style="color: var(--primary-color);"></i>
                        </div>
                        <h4 class="fw-bold mb-2">{{ $item['title'] }}</h4>
                        <p class="text-muted" style="color: var(--foreground); opacity: 0.75;">{{ $item['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Latest Issues -->
    <section>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Latest Issues</h2>
            @if($issues->count() > 0)
                <a href="{{ route('issues.index') }}" class="px-3 py-1 rounded" style="background-color: var(--primary-color); color: white; text-decoration: none; font-size: 0.875rem;">
                    View All Issues
                </a>
            @endif
        </div>

        @if($issues->count() > 0)
            <div class="row g-4">
                @foreach($issues as $issue)
                    <div class="col-md-4">
                        <div class="h-100" style="background-color: white; border-radius: 0.75rem; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                            <!-- Issue Header -->
                            <div class="p-4" style="background-color: var(--primary-color); color: white; border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="fas fa-book-open fa-sm" style="color: var(--secondary-color);"></i>
                                    <span style="color: var(--secondary-color); font-size: 0.875rem;">Latest Issue</span>
                                </div>
                                <h3 class="fw-bold mb-1" style="font-size: 1.25rem;">
                                    Volume {{ $issue->volume }}, No. {{ $issue->number }} ({{ $issue->year }})
                                </h3>
                                <p class="mb-0" style="opacity: 0.8; font-size: 0.875rem;">{{ $issue->title }}</p>
                                <div class="d-flex gap-3 mt-2" style="opacity: 0.6; font-size: 0.75rem;">
                                    <span><i class="fas fa-file-alt me-1"></i> {{ $issue->papers_count ?? 0 }} Articles</span>
                                    <span><i class="fas fa-users me-1"></i> {{ $issue->papers_count ?? 0 }} Authors</span>
                                </div>
                            </div>

                            <!-- Editorial -->
                            @if($issue->editorial)
                                <div class="p-4" style="background-color: var(--muted); border-bottom: 1px solid var(--border);">
                                    <span class="fw-medium text-uppercase" style="color: var(--secondary-color); font-size: 0.75rem;">Editorial</span>
                                    <h4 class="fw-bold mt-1" style="font-size: 1rem;">{{ $issue->editorial->title }}</h4>
                                    <p class="mb-0 mt-1" style="font-size: 0.875rem; color: var(--foreground); opacity: 0.8;">by {{ $issue->editorial->author->name ?? 'Editorial Board' }}</p>
                                </div>
                            @endif

                            <!-- Articles Preview -->
                            <div class="p-4">
                                <h4 class="fw-bold mb-3" style="font-size: 1.125rem; color: var(--foreground);">Articles in This Issue</h4>
                                @php
                                    $papers = $issue->papers ?? collect();
                                @endphp
                                @if($papers->count() > 0)
                                    @foreach($papers->take(3) as $index => $paper)
                                        <div class="d-flex gap-3 p-3 mb-3 rounded-2"
                                            style="background-color: white; border: 1px solid var(--border); transition: box-shadow 0.2s;">
                                            <!-- Nomor -->
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center"
                                                style="width: 2.25rem; height: 2.25rem; background-color: rgba(25, 51, 102, 0.08); color: var(--primary-color); border-radius: 0.5rem; font-weight: 600; font-size: 1rem;">
                                                {{ $index + 1 }}
                                            </div>
                                            <!-- Konten -->
                                            <div class="flex-grow-1">
                                                <h5 class="fw-semibold mb-1" style="font-size: 1rem; color: var(--foreground); line-height: 1.4;">
                                                    {{ $paper->title }}
                                                </h5>
                                                <p class="mb-0" style="font-size: 0.875rem; color: var(--foreground); opacity: 0.75;">
                                                    by {{ $paper->author->name ?? 'Unknown Author' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($papers->count() > 3)
                                        <p class="text-center text-muted mb-3" style="font-size: 0.875rem;">
                                            +{{ $papers->count() - 3 }} more articles
                                        </p>
                                    @endif
                                @else
                                    <p class="text-muted" style="font-size: 0.875rem;">No articles available in this issue yet.</p>
                                @endif

                                <div class="text-center mt-3">
                                    <a href="{{ route('issues.show', $issue) }}" class="px-4 py-2 rounded fw-medium"
                                    style="background-color: var(--primary-color); color: white; text-decoration: none; font-size: 0.875rem; display: inline-block;">
                                        View All Articles
                                        <i class="fas fa-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info d-flex align-items-center" style="background-color: var(--muted); border-color: var(--border);">
                <i class="fas fa-info-circle me-2"></i> No published issues yet.
            </div>
        @endif
    </section>

</div>
@endsection