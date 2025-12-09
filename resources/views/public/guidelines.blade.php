@extends('layouts.public')

@section('title', 'Author Guidelines - ' . config('app.name'))
@section('description', 'Guidelines for authors submitting manuscripts to our journal')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-5 fw-bold mb-3">Author Guidelines</h1>
            <p class="lead">
                Please follow these guidelines when preparing and submitting your manuscript 
                to ensure a smooth review and publication process.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Sidebar Navigation -->
            <div class="sticky-top" style="top: 20px;">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i> Contents</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="#scope" class="list-group-item list-group-item-action">
                                Scope & Focus
                            </a>
                            <a href="#submission" class="list-group-item list-group-item-action">
                                Submission Process
                            </a>
                            <a href="#formatting" class="list-group-item list-group-item-action">
                                Formatting Requirements
                            </a>
                            <a href="#ethics" class="list-group-item list-group-item-action">
                                Publication Ethics
                            </a>
                            <a href="#review" class="list-group-item list-group-item-action">
                                Review Process
                            </a>
                            <a href="#publication" class="list-group-item list-group-item-action">
                                Publication Process
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <a href="{{ route('author.papers.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-paper-plane me-2"></i> Submit Paper
                        </a>
                        <small class="text-muted">
                            Ready to submit? Use our online submission system
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <!-- Main Content -->
            <div class="card">
                <div class="card-body">
                    <!-- Scope & Focus -->
                    <section id="scope" class="mb-5">
                        <h2 class="mb-3">Scope & Focus</h2>
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
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            We welcome interdisciplinary research that bridges multiple fields of study.
                        </div>
                    </section>

                    <!-- Submission Process -->
                    <section id="submission" class="mb-5">
                        <h2 class="mb-3">Submission Process</h2>
                        
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

                    <!-- Formatting Requirements -->
                    <section id="formatting" class="mb-5">
                        <h2 class="mb-3">Formatting Requirements</h2>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Element</th>
                                        <th>Requirements</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Language</td>
                                        <td>English (British or American)</td>
                                    </tr>
                                    <tr>
                                        <td>File Format</td>
                                        <td>PDF only</td>
                                    </tr>
                                    <tr>
                                        <td>Page Size</td>
                                        <td>A4 (210 × 297 mm)</td>
                                    </tr>
                                    <tr>
                                        <td>Margins</td>
                                        <td>2.5 cm on all sides</td>
                                    </tr>
                                    <tr>
                                        <td>Font</td>
                                        <td>Times New Roman, 12 pt</td>
                                    </tr>
                                    <tr>
                                        <td>Line Spacing</td>
                                        <td>1.5 lines</td>
                                    </tr>
                                    <tr>
                                        <td>Maximum Length</td>
                                        <td>8000 words (including references)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h5 class="mt-4">Manuscript Structure</h5>
                        <p>Papers should generally include the following sections:</p>
                        <ol>
                            <li>Title (concise and informative)</li>
                            <li>Abstract (200-300 words)</li>
                            <li>Keywords (4-6 terms)</li>
                            <li>Introduction</li>
                            <li>Literature Review</li>
                            <li>Methodology</li>
                            <li>Results</li>
                            <li>Discussion</li>
                            <li>Conclusion</li>
                            <li>References</li>
                            <li>Appendices (if needed)</li>
                        </ol>
                    </section>

                    <!-- Publication Ethics -->
                    <section id="ethics" class="mb-5">
                        <h2 class="mb-3">Publication Ethics</h2>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            All authors must adhere to our publication ethics policy.
                        </div>
                        
                        <h5>Author Responsibilities</h5>
                        <ul>
                            <li>Original work: Manuscript must be original and not published elsewhere</li>
                            <li>Authorship: All authors must have contributed significantly</li>
                            <li>Disclosure: Conflicts of interest must be declared</li>
                            <li>Data integrity: Data must be accurate and reproducible</li>
                            <li>Copyright: Authors retain copyright but grant publishing rights</li>
                        </ul>
                        
                        <h5>Plagiarism Policy</h5>
                        <p>We use plagiarism detection software. Manuscripts with significant plagiarism will be rejected.</p>
                    </section>

                    <!-- Review Process -->
                    <section id="review" class="mb-5">
                        <h2 class="mb-3">Review Process</h2>
                        
                        <p>We follow a double-blind peer review process:</p>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <div class="display-6 text-primary mb-3">1-3 days</div>
                                        <h6>Initial Screening</h6>
                                        <small class="text-muted">Editor checks for completeness and scope</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="display-6 text-warning mb-3">2-4 weeks</div>
                                        <h6>Peer Review</h6>
                                        <small class="text-muted">Review by 2-3 experts in the field</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <div class="display-6 text-success mb-3">1-2 weeks</div>
                                        <h6>Decision</h6>
                                        <small class="text-muted">Editor makes final decision</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5>Possible Decisions</h5>
                        <ul>
                            <li><strong>Accept:</strong> Paper accepted as is</li>
                            <li><strong>Minor Revision:</strong> Accept after minor changes</li>
                            <li><strong>Major Revision:</strong> Re-review after major changes</li>
                            <li><strong>Reject:</strong> Paper not suitable for publication</li>
                        </ul>
                    </section>

                    <!-- Publication Process -->
                    <section id="publication">
                        <h2 class="mb-3">Publication Process</h2>
                        
                        <h5>After Acceptance</h5>
                        <ol>
                            <li>Submit final manuscript with revisions</li>
                            <li>Sign copyright agreement</li>
                            <li>Pay publication fees (if applicable)</li>
                            <li>Receive proofs for final check</li>
                            <li>Paper assigned to an issue</li>
                            <li>Published online with DOI</li>
                        </ol>
                        
                        <h5>Open Access Policy</h5>
                        <p>{{ config('app.name') }} is an open access journal. All published articles are freely available to read, download, and share.</p>
                        
                        <div class="alert alert-success">
                            <h6><i class="fas fa-question-circle me-2"></i> Need Help?</h6>
                            <p class="mb-0">
                                Contact our editorial office at 
                                <a href="mailto:editorial@jurnalku.com" class="text-decoration-none">editorial@jurnalku.com</a>
                                for any questions about the submission process.
                            </p>
                        </div>
                    </section>
                </div>
                
                <div class="card-footer text-center">
                    <a href="{{ route('author.papers.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i> Begin Submission
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    section {
        scroll-margin-top: 20px;
    }
    
    .list-group-item.active {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .sticky-top {
        z-index: 1020;
    }
</style>

@push('scripts')
<script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Update active nav item
                document.querySelectorAll('.list-group-item').forEach(item => {
                    item.classList.remove('active');
                });
                this.classList.add('active');
            }
        });
    });
    
    // Update active nav based on scroll position
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section');
        const navItems = document.querySelectorAll('.list-group-item');
        
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            if (window.pageYOffset >= sectionTop) {
                currentSection = '#' + section.getAttribute('id');
            }
        });
        
        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === currentSection) {
                item.classList.add('active');
            }
        });
    });
</script>
@endpush
@endsection