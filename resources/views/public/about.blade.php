@extends('layouts.public')

@section('title', 'About Us')
@section('description', 'Learn about our journal, mission, and editorial team')

@section('content')

<style>
.about-card {
    background:white;
    border:1px solid var(--border);
    border-radius:.75rem;
    box-shadow:0 4px 6px rgba(0,0,0,.05);
    transition:.25s ease;
}
.about-card:hover {
    transform:translateY(-4px);
    box-shadow:0 12px 28px rgba(0,0,0,.1);
}

.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    line-height: 1;
}

.icon-circle i {
    line-height: 1;
    margin: 0;
}

.process-step {
    text-align:center;
    padding:1.5rem;
    border-radius:.75rem;
    transition:.25s ease;
}
.process-step:hover {
    background:var(--muted);
}
</style>

<div class="journal-container py-5">

    <!-- HERO -->
    <section class="position-relative mb-5" style="border-radius:1rem;overflow:hidden;">
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="background:linear-gradient(135deg,#193366 0%,#253d6c 45%,#193366 100%);">
        </div>

        <div class="position-relative p-5 text-white text-center">
            <h1 class="fw-bold mb-3" style="font-size:2.5rem;">
                About Jurnalku
            </h1>
            <p class="lead mb-0" style="opacity:.9;max-width:42rem;margin:auto;">
                A peer-reviewed academic journal dedicated to advancing knowledge 
                through rigorous research and scholarly publication.
            </p>
        </div>
    </section>

    <!-- MISSION & VISION -->
    <section class="mb-5">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="about-card h-100 p-4 text-center">
                    <div class="icon-circle mb-4"
                         style="background:var(--primary-color);">
                        <i class="fas fa-bullseye fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Our Mission</h3>
                    <p class="text-muted mb-0">
                        To provide a platform for high-quality scholarly research, 
                        fostering academic discourse and contributing to the advancement 
                        of knowledge across various disciplines.
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="about-card h-100 p-4 text-center">
                    <div class="icon-circle mb-4"
                         style="background:#28a745;">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Our Vision</h3>
                    <p class="text-muted mb-0">
                        To become a leading international journal recognized for 
                        its academic excellence, rigorous peer-review process, 
                        and contribution to global scholarly community.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- EDITORIAL PROCESS -->
    <section class="mb-5">
        <h2 class="fw-bold text-center mb-5">Our Editorial Process</h2>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="process-step">
                    <div class="icon-circle mb-3"
                         style="background:var(--primary-color);">
                        <i class="fas fa-file-upload fa-lg"></i>
                    </div>
                    <h5 class="fw-semibold">Submission</h5>
                    <p class="text-muted">
                        Authors submit manuscripts through our online system
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="process-step">
                    <div class="icon-circle mb-3"
                         style="background:#FFD166;color:#193366;">
                        <i class="fas fa-search fa-lg"></i>
                    </div>
                    <h5 class="fw-semibold">Peer Review</h5>
                    <p class="text-muted">
                        Double-blind review by experts in the field
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="process-step">
                    <div class="icon-circle mb-3"
                         style="background:#0dcaf0;">
                        <i class="fas fa-gavel fa-lg"></i>
                    </div>
                    <h5 class="fw-semibold">Decision</h5>
                    <p class="text-muted">
                        Editorial decision based on reviewer recommendations
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="process-step">
                    <div class="icon-circle mb-3"
                         style="background:#28a745;">
                        <i class="fas fa-book fa-lg"></i>
                    </div>
                    <h5 class="fw-semibold">Publication</h5>
                    <p class="text-muted">
                        Accepted papers published in regular issues
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="about-card overflow-hidden">
                    <div class="p-4 text-white"
                         style="background:var(--primary-color);">
                        <h3 class="mb-0">
                            <i class="fas fa-envelope me-2"></i> Contact Us
                        </h3>
                    </div>

                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h5 class="fw-semibold">Mailing Address</h5>
                                <p class="text-muted mb-0">
                                    {{ config('app.name') }} Editorial Office<br>
                                    123 Academic Street<br>
                                    University District<br>
                                    Jakarta 12345, Indonesia
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h5 class="fw-semibold">Contact Information</h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope me-2"></i> contact@jurnalku.com<br>
                                    <i class="fas fa-phone me-2"></i> +62 123 456 789<br>
                                    <i class="fas fa-globe me-2"></i> www.jurnalku.com
                                </p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="text-center text-muted">
                            For submission inquiries: submissions@jurnalku.com<br>
                            For review inquiries: review@jurnalku.com<br>
                            For general inquiries: info@jurnalku.com
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
