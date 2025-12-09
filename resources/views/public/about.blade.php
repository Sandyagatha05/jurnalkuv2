@extends('layouts.public')

@section('title', 'About Us - ' . config('app.name'))
@section('description', 'Learn about our journal, mission, and editorial team')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 fw-bold mb-4">About {{ config('app.name') }}</h1>
            <p class="lead">
                A peer-reviewed academic journal dedicated to advancing knowledge 
                through rigorous research and scholarly publication.
            </p>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-primary">
                <div class="card-body text-center p-4">
                    <i class="fas fa-bullseye fa-3x text-primary mb-4"></i>
                    <h3 class="card-title mb-3">Our Mission</h3>
                    <p class="card-text">
                        To provide a platform for high-quality scholarly research, 
                        fostering academic discourse and contributing to the advancement 
                        of knowledge across various disciplines.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-success">
                <div class="card-body text-center p-4">
                    <i class="fas fa-eye fa-3x text-success mb-4"></i>
                    <h3 class="card-title mb-3">Our Vision</h3>
                    <p class="card-text">
                        To become a leading international journal recognized for 
                        its academic excellence, rigorous peer-review process, 
                        and contribution to global scholarly community.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Editorial Process -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-5">Our Editorial Process</h2>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-file-upload fa-2x"></i>
                        </div>
                        <h5>Submission</h5>
                        <p class="text-muted">Authors submit manuscripts through our online system</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                        <h5>Peer Review</h5>
                        <p class="text-muted">Double-blind review by experts in the field</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-gavel fa-2x"></i>
                        </div>
                        <h5>Decision</h5>
                        <p class="text-muted">Editorial decision based on reviewer recommendations</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <h5>Publication</h5>
                        <p class="text-muted">Accepted papers published in regular issues</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-envelope me-2"></i> Contact Us</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Mailing Address</h5>
                            <p class="text-muted">
                                {{ config('app.name') }} Editorial Office<br>
                                123 Academic Street<br>
                                University District<br>
                                Jakarta 12345, Indonesia
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Contact Information</h5>
                            <p class="text-muted">
                                <i class="fas fa-envelope me-2"></i> contact@jurnalku.com<br>
                                <i class="fas fa-phone me-2"></i> +62 123 456 789<br>
                                <i class="fas fa-globe me-2"></i> www.jurnalku.com
                            </p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            For submission inquiries: submissions@jurnalku.com<br>
                            For review inquiries: review@jurnalku.com<br>
                            For general inquiries: info@jurnalku.com
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection