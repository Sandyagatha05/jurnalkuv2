@extends('layouts.app')

@section('page-title', 'Submit Review')
@section('page-description', 'Submit review for assigned paper')

@section('page-actions')
    <a href="{{ route('reviewer.assignments.show', $assignment) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Assignment
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Submit Review for: {{ $paper->title }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <h6><i class="fas fa-info-circle me-2"></i> Review Guidelines</h6>
                    <p class="mb-0">
                        Please provide comprehensive feedback for both the editor and author. 
                        Your review should be constructive and professional. 
                        <strong>Due Date: {{ $assignment->due_date->format('F d, Y') }}</strong>
                    </p>
                </div>
                
                <form action="{{ route('reviewer.assignments.submit-review', $assignment) }}" method="POST" onsubmit="return handleSubmit(event)">
                    @csrf
                    
                    <!-- Review form sama seperti di edit.blade.php -->
                    <!-- Copy form dari edit.blade.php tanpa value attributes -->
                    
                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" name="action" value="draft" class="btn btn-secondary" data-confirm="Save this review as a draft?">
                            <i class="fas fa-save me-2"></i> Save Draft
                        </button>
                        <button type="submit" name="action" value="submit" class="btn btn-primary" data-confirm="Are you sure you want to submit this review?">
                            <i class="fas fa-paper-plane me-2"></i> Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Include paper info card dari edit.blade.php -->
    </div>
</div>

<script>
let clickedSubmitButton = null;

// capture which button was clicked
document.addEventListener('click', function (e) {
    if (e.target.matches('button[type="submit"]')) {
        clickedSubmitButton = e.target;
    }
});

function handleSubmit(event) {
    event.preventDefault();

    const message = clickedSubmitButton?.dataset.confirm
        ?? 'Are you sure you want to proceed?';

    customConfirm(message).then(result => {
        if (result) {
            event.target.submit();
        }
    });

    return false;
}
</script>

@endsection