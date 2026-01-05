@extends('layouts.public')

@section('title', 'Author Guidelines - ' . config('app.name'))
@section('description', 'Guidelines for authors submitting manuscripts to our journal')

@section('content')
<div class="journal-container py-5">

    <!-- PAGE HEADER -->
    <div class="text-center mb-5">
        <span class="badge mb-3 fs-6 px-3 py-2"
              style="background: var(--secondary-color); color: var(--foreground);">
            For Authors
        </span>
        <h1 class="fw-bold mb-3" style="font-size:2.6rem; color:var(--primary-color);">
            Author Guidelines
        </h1>
        <p class="mx-auto text-muted" style="max-width:720px; font-size:1.1rem;">
            Please follow these guidelines when preparing and submitting your manuscript 
            to ensure a smooth review and publication process.
        </p>
    </div>

    <div class="row g-4">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <div class="sticky-top" style="top:100px;">

                <!-- CONTENT NAV -->
                <div class="card mb-4">
                    <div class="card-body p-0">
                        <div class="px-4 py-3 border-bottom">
                            <h6 class="fw-semibold mb-0" style="color:var(--primary-color);">
                                <i class="fas fa-list me-2"></i> Contents
                            </h6>
                        </div>

                        <div class="list-group list-group-flush guideline-nav">
                            <a href="#scope" class="list-group-item">Scope & Focus</a>
                            <a href="#submission" class="list-group-item">Submission Process</a>
                            <a href="#formatting" class="list-group-item">Formatting Requirements</a>
                            <a href="#ethics" class="list-group-item">Publication Ethics</a>
                            <a href="#review" class="list-group-item">Review Process</a>
                            <a href="#publication" class="list-group-item">Publication Process</a>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('author.papers.create') }}"
                           class="btn btn-lift w-100 mb-2"
                           style="background:var(--primary-color); color:white;">
                            <i class="fas fa-paper-plane me-2"></i> Submit Paper
                        </a>
                        <small class="text-muted">
                            Ready to submit? Use our online submission system
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body p-4 p-lg-5">

                    <!-- Scope -->
                    <section id="scope" class="content-section">
                        <h2 class="section-title">Scope & Focus</h2>
                        <p>{{ config('app.name') }} publishes original research articles, review papers, and case studies in the following areas:</p>

                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li>Computer Science & Information Technology</li>
                                    <li>Engineering & Technology</li>
                                    <li>Natural Sciences</li>
                                    <li>Medical & Health Sciences</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li>Social Sciences & Humanities</li>
                                    <li>Business & Management</li>
                                    <li>Education & Teaching</li>
                                    <li>Interdisciplinary Studies</li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            We welcome interdisciplinary research that bridges multiple fields of study.
                        </div>
                    </section>

                    <!-- Submission -->
                    <section id="submission" class="content-section">
                        <h2 class="section-title">Submission Process</h2>

                        <h5>1. Prepare Your Manuscript</h5>
                        <p>Ensure your manuscript follows our formatting guidelines before submission.</p>

                        <h5>2. Online Submission</h5>
                        <p>Submit through our online system:</p>
                        <ol>
                            <li>Register/Login to your account</li>
                            <li>Click "Submit New Paper"</li>
                            <li>Fill in manuscript details</li>
                            <li>Upload manuscript file (PDF)</li>
                            <li>Complete submission checklist</li>
                            <li>Submit for review</li>
                        </ol>

                        <h5>3. Required Documents</h5>
                        <ul>
                            <li>Manuscript in PDF format</li>
                            <li>Cover letter (optional)</li>
                            <li>Author disclosure forms</li>
                            <li>Supplementary materials (if any)</li>
                        </ul>
                    </section>

                    <!-- Formatting -->
                    <section id="formatting" class="content-section">
                        <h2 class="section-title">Formatting Requirements</h2>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Element</th>
                                        <th>Requirements</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>Language</td><td>English (British or American)</td></tr>
                                    <tr><td>File Format</td><td>PDF only</td></tr>
                                    <tr><td>Page Size</td><td>A4 (210 × 297 mm)</td></tr>
                                    <tr><td>Margins</td><td>2.5 cm on all sides</td></tr>
                                    <tr><td>Font</td><td>Times New Roman, 12 pt</td></tr>
                                    <tr><td>Line Spacing</td><td>1.5 lines</td></tr>
                                    <tr><td>Maximum Length</td><td>8000 words (including references)</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Ethics -->
                    <section id="ethics" class="content-section">
                        <h2 class="section-title">Publication Ethics</h2>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            All authors must adhere to our publication ethics policy.
                        </div>
                    </section>

                    <!-- Review -->
                    <section id="review" class="content-section">
                        <h2 class="section-title">Review Process</h2>

                        <div class="row g-4 my-4">
                            <div class="col-md-4">
                                <div class="review-card border-primary">
                                    <div class="review-time text-primary">1–3 days</div>
                                    <h6>Initial Screening</h6>
                                    <small>Editor checks for completeness and scope</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="review-card border-warning">
                                    <div class="review-time text-warning">2–4 weeks</div>
                                    <h6>Peer Review</h6>
                                    <small>Review by 2–3 experts</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="review-card border-success">
                                    <div class="review-time text-success">1–2 weeks</div>
                                    <h6>Decision</h6>
                                    <small>Final editorial decision</small>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Publication -->
                    <section id="publication" class="content-section mb-0">
                        <h2 class="section-title">Publication Process</h2>

                        <div class="alert alert-success">
                            <h6 class="mb-1">
                                <i class="fas fa-question-circle me-2"></i> Need Help?
                            </h6>
                            <p class="mb-0">
                                Contact our editorial office at
                                <a href="mailto:editorial@jurnalku.com">editorial@jurnalku.com</a>
                            </p>
                        </div>
                    </section>

                </div>

                <div class="card-footer text-center">
                    <a href="{{ route('author.papers.create') }}"
                       class="btn btn-lift btn-lg"
                       style="background:var(--primary-color); color:white;">
                        <i class="fas fa-paper-plane me-2"></i> Begin Submission
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-section {
    margin-bottom: 4rem;
}

.section-title {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1.2rem;
}

.guideline-nav .list-group-item {
    border: none;
    padding: .75rem 1.25rem;
    transition: all .2s ease;
}

.guideline-nav .list-group-item:hover,
.guideline-nav .list-group-item.active {
    background: #19336613;
    color: var(--primary-color);
    font-weight: 600;
}

.review-card {
    border:1px solid var(--border);
    border-radius: .75rem;
    padding: 1.5rem;
    text-align:center;
    height:100%;
}

.review-time {
    font-size:1.5rem;
    font-weight:700;
    margin-bottom:.5rem;
}

section {
    scroll-margin-top: 120px;
}
</style>
@endsection
