@extends('layouts.app')

@section('title', 'Reviewer Guidelines')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Reviewer Guidelines</h4>
            <p class="text-muted mb-0">Guidelines for peer reviewers</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reviewer.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                
                <div class="alert alert-info d-flex align-items-center mb-5" role="alert">
                    <i class="fas fa-info-circle fa-lg me-3"></i>
                    <div>
                        These guidelines are designed to help you conduct fair, thorough, and constructive peer reviews.
                    </div>
                </div>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">1. Review Process Overview</h5>
                    <p class="text-secondary mb-3">The peer review process at {{ config('app.name') }} follows these steps:</p>
                    
                    <div class="bg-light rounded p-4">
                        <ol class="mb-0 fw-medium">
                            <li class="mb-2"><strong>Assignment:</strong> You receive an invitation to review a manuscript</li>
                            <li class="mb-2"><strong>Acceptance:</strong> Accept or decline the assignment within 3 days</li>
                            <li class="mb-2"><strong>Review Period:</strong> Complete the review within the specified timeframe (typically 2-4 weeks)</li>
                            <li class="mb-2"><strong>Submission:</strong> Submit your review through our online system</li>
                            <li><strong>Follow-up:</strong> Respond to any queries from the editor</li>
                        </ol>
                    </div>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">2. Review Criteria</h5>
                    <p class="text-secondary mb-3">Please evaluate manuscripts based on the following criteria:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-primary border-opacity-25 bg-primary bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-lightbulb me-2"></i>Originality & Significance</h6>
                                    <ul class="mb-0 small text-secondary">
                                        <li>Novelty of research</li>
                                        <li>Contribution to the field</li>
                                        <li>Importance of findings</li>
                                        <li>Advancement of knowledge</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border-success border-opacity-25 bg-success bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="fw-bold text-success mb-3"><i class="fas fa-microscope me-2"></i>Methodology & Analysis</h6>
                                    <ul class="mb-0 small text-secondary">
                                        <li>Appropriateness of methods</li>
                                        <li>Statistical analysis</li>
                                        <li>Data quality and validity</li>
                                        <li>Reproducibility of results</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-warning border-opacity-25 bg-warning bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="fw-bold text-warning text-dark mb-3"><i class="fas fa-pen-fancy me-2"></i>Clarity & Presentation</h6>
                                    <ul class="mb-0 small text-secondary">
                                        <li>Organization and structure</li>
                                        <li>Clarity of writing</li>
                                        <li>Quality of figures/tables</li>
                                        <li>Adherence to journal format</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border-info border-opacity-25 bg-info bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="fw-bold text-info text-dark mb-3"><i class="fas fa-book-open me-2"></i>References & Literature</h6>
                                    <ul class="mb-0 small text-secondary">
                                        <li>Appropriate citation of literature</li>
                                        <li>Adequacy of references</li>
                                        <li>Awareness of relevant work</li>
                                        <li>Acknowledgement of sources</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">3. Scoring System</h5>
                    <p class="text-secondary mb-3">Use the 5-point scale for each criterion:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="80">Score</th>
                                    <th width="150">Description</th>
                                    <th>Interpretation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-primary">5</td>
                                    <td class="fw-semibold">Excellent</td>
                                    <td>Outstanding quality, no improvements needed</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">4</td>
                                    <td class="fw-semibold">Good</td>
                                    <td>High quality, minor improvements suggested</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-secondary">3</td>
                                    <td class="fw-semibold">Average</td>
                                    <td>Acceptable with moderate revisions</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-warning">2</td>
                                    <td class="fw-semibold">Poor</td>
                                    <td>Major revisions required</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-danger">1</td>
                                    <td class="fw-semibold">Very Poor</td>
                                    <td>Fundamental flaws, not acceptable</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">4. Recommendation Options</h5>
                    <p class="text-secondary mb-3">Select one of the following recommendations:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card h-100 text-center border-success bg-light">
                                <div class="card-body">
                                    <div class="mb-3 text-success">
                                        <i class="fas fa-check-circle fa-3x"></i>
                                    </div>
                                    <h6 class="fw-bold">Accept</h6>
                                    <small class="text-muted d-block">Paper is suitable for publication as is</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card h-100 text-center border-warning bg-light">
                                <div class="card-body">
                                    <div class="mb-3 text-warning">
                                        <i class="fas fa-tools fa-3x"></i>
                                    </div>
                                    <h6 class="fw-bold">Minor Revision</h6>
                                    <small class="text-muted d-block">Accept after minor corrections</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card h-100 text-center border-warning bg-light">
                                <div class="card-body">
                                    <div class="mb-3 text-warning">
                                        <i class="fas fa-redo fa-3x"></i>
                                    </div>
                                    <h6 class="fw-bold">Major Revision</h6>
                                    <small class="text-muted d-block">Re-review after major changes</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card h-100 text-center border-danger bg-light">
                                <div class="card-body">
                                    <div class="mb-3 text-danger">
                                        <i class="fas fa-times-circle fa-3x"></i>
                                    </div>
                                    <h6 class="fw-bold">Reject</h6>
                                    <small class="text-muted d-block">Not suitable for publication</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">5. Writing Constructive Comments</h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i> Comments to Author</h6>
                                </div>
                                <div class="card-body">
                                    <p class="fw-bold mb-2 text-success">Do:</p>
                                    <ul class="mb-3 small">
                                        <li>Be specific and constructive</li>
                                        <li>Provide clear suggestions for improvement</li>
                                        <li>Focus on the work, not the author</li>
                                        <li>Point out strengths as well as weaknesses</li>
                                        <li>Use professional and respectful language</li>
                                    </ul>
                                    
                                    <p class="fw-bold mb-2 text-danger">Don't:</p>
                                    <ul class="mb-0 small">
                                        <li>Use inflammatory or personal comments</li>
                                        <li>Make demands without explanation</li>
                                        <li>Focus only on negative aspects</li>
                                        <li>Suggest impossible or unreasonable changes</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-shield me-2"></i> Comments to Editor</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2 text-muted small">These comments are confidential and will not be shared with the author:</p>
                                    <ul class="mb-3 small">
                                        <li>Concerns about ethical issues</li>
                                        <li>Suspected plagiarism or duplicate publication</li>
                                        <li>Conflicts of interest</li>
                                        <li>Additional context for your recommendation</li>
                                        <li>Suggestions for other reviewers</li>
                                    </ul>
                                    
                                    <div class="alert alert-warning d-flex align-items-center mb-0 p-2 small">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div><strong>Confidential:</strong> These comments are for the editor's eyes only.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">6. Ethical Guidelines</h5>
                    
                    <div class="alert alert-warning mb-4">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Important Ethical Considerations</h6>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100 bg-light border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold text-dark mb-3">Confidentiality</h6>
                                    <ul class="mb-0 small text-secondary">
                                        <li>Do not share the manuscript with others</li>
                                        <li>Do not use ideas from the manuscript for your own work</li>
                                        <li>Destroy or return the manuscript after review</li>
                                        <li>Do not contact the author directly</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 bg-light border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold text-dark mb-3">Conflict of Interest</h6>
                                    <ul class="mb-0 small text-secondary">
                                        <li>Declare any conflicts to the editor</li>
                                        <li>Do not review work from close colleagues</li>
                                        <li>Do not review work that directly competes with your own</li>
                                        <li>Do not review if you cannot be objective</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">7. Review Timeline</h5>
                    
                    <div class="row text-center g-3">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-white h-100">
                                <div class="display-6 fw-bold text-primary mb-2">3 days</div>
                                <h6 class="fw-bold">Response Time</h6>
                                <small class="text-muted">Accept or decline assignment</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-white h-100">
                                <div class="display-6 fw-bold text-warning mb-2">2-4 weeks</div>
                                <h6 class="fw-bold">Review Period</h6>
                                <small class="text-muted">Complete the review</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-white h-100">
                                <div class="display-6 fw-bold text-success mb-2">48 hours</div>
                                <h6 class="fw-bold">Extension Request</h6>
                                <small class="text-muted">Request more time if needed</small>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-question-circle fa-lg me-3"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Need Help?</h6>
                            <p class="mb-0 small">
                                If you have questions about the review process or need assistance, 
                                contact the editorial office at 
                                <a href="mailto:editorial@jurnalku.com" class="fw-bold text-success text-decoration-underline">editorial@jurnalku.com</a>
                            </p>
                        </div>
                    </div>
                </section>

                <div class="text-center mt-5 pt-4 border-top">
                    <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-tasks me-2"></i> Go to My Assignments
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection