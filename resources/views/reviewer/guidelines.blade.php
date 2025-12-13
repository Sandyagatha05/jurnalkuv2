@extends('layouts.app')

@section('page-title', 'Reviewer Guidelines')
@section('page-description', 'Guidelines for peer reviewers')

@section('page-actions')
    <a href="{{ route('reviewer.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-book me-2"></i> Reviewer Guidelines</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    These guidelines are designed to help you conduct fair, thorough, and constructive peer reviews.
                </div>

                <!-- Review Process -->
                <section class="mb-5">
                    <h5 class="text-primary mb-3">1. Review Process Overview</h5>
                    <p>The peer review process at {{ config('app.name') }} follows these steps:</p>
                    <ol>
                        <li><strong>Assignment:</strong> You receive an invitation to review a manuscript</li>
                        <li><strong>Acceptance:</strong> Accept or decline the assignment within 3 days</li>
                        <li><strong>Review Period:</strong> Complete the review within the specified timeframe (typically 2-4 weeks)</li>
                        <li><strong>Submission:</strong> Submit your review through our online system</li>
                        <li><strong>Follow-up:</strong> Respond to any queries from the editor</li>
                    </ol>
                </section>

                <!-- Review Criteria -->
                <section class="mb-5">
                    <h5 class="text-primary mb-3">2. Review Criteria</h5>
                    <p>Please evaluate manuscripts based on the following criteria:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <h6 class="text-primary">Originality & Significance</h6>
                                    <ul class="mb-0">
                                        <li>Novelty of research</li>
                                        <li>Contribution to the field</li>
                                        <li>Importance of findings</li>
                                        <li>Advancement of knowledge</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-success h-100">
                                <div class="card-body">
                                    <h6 class="text-success">Methodology & Analysis</h6>
                                    <ul class="mb-0">
                                        <li>Appropriateness of methods</li>
                                        <li>Statistical analysis</li>
                                        <li>Data quality and validity</li>
                                        <li>Reproducibility of results</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="card border-warning h-100">
                                <div class="card-body">
                                    <h6 class="text-warning">Clarity & Presentation</h6>
                                    <ul class="mb-0">
                                        <li>Organization and structure</li>
                                        <li>Clarity of writing</li>
                                        <li>Quality of figures/tables</li>
                                        <li>Adherence to journal format</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-info h-100">
                                <div class="card-body">
                                    <h6 class="text-info">References & Literature</h6>
                                    <ul class="mb-0">
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

                <!-- Scoring System -->
                <section class="mb-5">
                    <h5 class="text-primary mb-3">3. Scoring System</h5>
                    <p>Use the 5-point scale for each criterion:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Score</th>
                                    <th>Description</th>
                                    <th>Interpretation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>5</strong></td>
                                    <td>Excellent</td>
                                    <td>Outstanding quality, no improvements needed</td>
                                </tr>
                                <tr>
                                    <td><strong>4</strong></td>
                                    <td>Good</td>
                                    <td>High quality, minor improvements suggested</td>
                                </tr>
                                <tr>
                                    <td><strong>3</strong></td>
                                    <td>Average</td>
                                    <td>Acceptable with moderate revisions</td>
                                </tr>
                                <tr>
                                    <td><strong>2</strong></td>
                                    <td>Poor</td>
                                    <td>Major revisions required</td>
                                </tr>
                                <tr>
                                    <td><strong>1</strong></td>
                                    <td>Very Poor</td>
                                    <td>Fundamental flaws, not acceptable</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Recommendations -->
                <section class="mb-5">
                    <h5 class="text-primary mb-3">4. Recommendation Options</h5>
                    <p>Select one of the following recommendations:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card border-success h-100 text-center">
                                <div class="card-body">
                                    <div class="display-6 text-success mb-3">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h6>Accept</h6>
                                    <small class="text-muted">Paper is suitable for publication as is</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-warning h-100 text-center">
                                <div class="card-body">
                                    <div class="display-6 text-warning mb-3">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <h6>Minor Revision</h6>
                                    <small class="text-muted">Accept after minor corrections</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-warning h-100 text-center">
                                <div class="card-body">
                                    <div class="display-6 text-warning mb-3">
                                        <i class="fas fa-redo"></i>
                                    </div>
                                    <h6>Major Revision</h6>
                                    <small class="text-muted">Re-review after major changes</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-danger h-100 text-center">
                                <div class="card-body">
                                    <div class="display-6 text-danger mb-3">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <h6>Reject</h6>
                                    <small class="text-muted">Not suitable for publication</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Writing Comments -->
                <section class="mb-5">
                    <h5 class="text-primary mb-3">5. Writing Constructive Comments</h5>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-user-edit me-2"></i> Comments to Author</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Do:</strong></p>
                                    <ul>
                                        <li>Be specific and constructive</li>
                                        <li>Provide clear suggestions for improvement</li>
                                        <li>Focus on the work, not the author</li>
                                        <li>Point out strengths as well as weaknesses</li>
                                        <li>Use professional and respectful language</li>
                                    </ul>
                                    
                                    <p><strong>Don't:</strong></p>
                                    <ul>
                                        <li>Use inflammatory or personal comments</li>
                                        <li>Make demands without explanation</li>
                                        <li>Focus only on negative aspects</li>
                                        <li>Suggest impossible or unreasonable changes</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-secondary h-100">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0"><i class="fas fa-user-shield me-2"></i> Comments to Editor</h6>
                                </div>
                                <div class="card-body">
                                    <p>These comments are confidential and will not be shared with the author:</p>
                                    <ul>
                                        <li>Concerns about ethical issues</li>
                                        <li>Suspected plagiarism or duplicate publication</li>
                                        <li>Conflicts of interest</li>
                                        <li>Additional context for your recommendation</li>
                                        <li>Suggestions for other reviewers</li>
                                    </ul>
                                    
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Confidential:</strong> These comments are for the editor's eyes only.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Ethical Guidelines -->
                <section class="mb-5">
                    <h5 class="text-primary mb-3">6. Ethical Guidelines</h5>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i> Important Ethical Considerations</h6>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Confidentiality</h6>
                                    <ul class="mb-0">
                                        <li>Do not share the manuscript with others</li>
                                        <li>Do not use ideas from the manuscript for your own work</li>
                                        <li>Destroy or return the manuscript after review</li>
                                        <li>Do not contact the author directly</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Conflict of Interest</h6>
                                    <ul class="mb-0">
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

                <!-- Timeline -->
                <section class="mb-5">
                    <h5 class="text-primary mb-3">7. Review Timeline</h5>
                    
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <div class="display-6 text-primary mb-3">3 days</div>
                                    <h6>Response Time</h6>
                                    <small class="text-muted">Accept or decline assignment</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-body">
                                    <div class="display-6 text-warning mb-3">2-4 weeks</div>
                                    <h6>Review Period</h6>
                                    <small class="text-muted">Complete the review</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-success h-100">
                                <div class="card-body">
                                    <div class="display-6 text-success mb-3">48 hours</div>
                                    <h6>Extension Request</h6>
                                    <small class="text-muted">Request more time if needed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact -->
                <section>
                    <div class="alert alert-success">
                        <h6><i class="fas fa-question-circle me-2"></i> Need Help?</h6>
                        <p class="mb-0">
                            If you have questions about the review process or need assistance, 
                            contact the editorial office at 
                            <a href="mailto:editorial@jurnalku.com" class="text-decoration-none">editorial@jurnalku.com</a>
                        </p>
                    </div>
                </section>
            </div>
            
            <div class="card-footer text-center">
                <a href="{{ route('reviewer.assignments.pending') }}" class="btn btn-primary">
                    <i class="fas fa-tasks me-2"></i> Go to My Assignments
                </a>
            </div>
        </div>
    </div>
</div>
@endsection