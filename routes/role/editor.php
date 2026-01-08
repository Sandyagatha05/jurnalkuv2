<?php

use App\Http\Controllers\EditorialController;
use App\Http\Controllers\Editor\IssueController;
use App\Http\Controllers\Editor\PaperController;
use App\Http\Controllers\Editor\ReviewController;
use Illuminate\Support\Facades\Route;

// Editor Routes Group
Route::middleware(['auth', 'editor'])->prefix('editor')->name('editor.')->group(function () {
    
    // Editor Dashboard
// Editor Dashboard
    Route::get('/overdue', [ReviewController::class, 'overdue'])->name('overdue');
    Route::get('/dashboard', function () {
        // Ambil data untuk dashboard editor
        $stats = [
            'submitted' => \App\Models\Paper::where('status', 'submitted')->count(),
            'under_review' => \App\Models\Paper::where('status', 'under_review')->count(),
            'needs_decision' => \App\Models\Paper::where('status', 'under_review')
                ->has('reviewAssignments')
                ->count(),
            'active_issues' => \App\Models\Issue::where('status', 'published')->count(),
        ];
        
        $recentPapers = \App\Models\Paper::with('author')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $pendingReviews = \App\Models\ReviewAssignment::with(['paper', 'reviewer'])
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();
        
        return view('editor.dashboard', compact('stats', 'recentPapers', 'pendingReviews'));
    })->name('dashboard');
    
    // Issues Management
    Route::prefix('issues')->name('issues.')->group(function () {
        Route::get('/', [IssueController::class, 'index'])->name('index');
        Route::get('/create', [IssueController::class, 'create'])->name('create'); // ✅ ADA
        Route::post('/', [IssueController::class, 'store'])->name('store');
        Route::get('/{issue}', [IssueController::class, 'show'])->name('show');
        Route::get('/{issue}/edit', [IssueController::class, 'edit'])->name('edit');
        Route::put('/{issue}', [IssueController::class, 'update'])->name('update');
        Route::delete('/{issue}', [IssueController::class, 'destroy'])->name('destroy');
        
        // Publish/Unpublish
        Route::post('/{issue}/publish', [IssueController::class, 'publish'])->name('publish');
        Route::post('/{issue}/unpublish', [IssueController::class, 'unpublish'])->name('unpublish');
        Route::post('/{issue}/change-status', [IssueController::class, 'changeStatus'])->name('change-status');
        
        // Editorial Management
        Route::get('/{issue}/editorial', [IssueController::class, 'editorial'])->name('editorial');
        Route::post('/{issue}/editorial', [IssueController::class, 'storeEditorial'])->name('store-editorial');
        
        // Add/Remove Papers from Issue
        Route::post('/{issue}/add-paper', [IssueController::class, 'addPaper'])->name('add-paper');
        Route::patch('/{issue}/papers/{paper}', [IssueController::class, 'updatePaper'])->name('update-paper');
        Route::delete('/{issue}/remove-paper/{paper}', [IssueController::class, 'removePaper'])->name('remove-paper');
    });
    
    // Papers Management
    Route::prefix('papers')->name('papers.')->group(function () {
        Route::get('/', [PaperController::class, 'index'])->name('index');
        Route::get('/submitted', [PaperController::class, 'submitted'])->name('submitted');
        Route::get('/under-review', [PaperController::class, 'underReview'])->name('under-review');
        Route::get('/accepted', [PaperController::class, 'accepted'])->name('accepted');
        Route::get('/rejected', [PaperController::class, 'rejected'])->name('rejected');
        Route::get('/needs-revision', function () {
            $papers = \App\Models\Paper::whereIn('status', ['revision_minor', 'revision_major'])
                ->with('author')
                ->paginate(20);
            return view('editor.papers.needs-revision', compact('papers'));
        })->name('needs-revision');
        
        Route::get('/{paper}', [PaperController::class, 'show'])->name('show');
        
        // Assign Reviewers
        Route::get('/{paper}/assign-reviewers', [PaperController::class, 'assignReviewers'])->name('assign-reviewers');
        Route::post('/{paper}/assign-reviewers', [PaperController::class, 'storeAssignReviewers'])->name('store-assign-reviewers');
        
        // Decision
        Route::get('/{paper}/decision', [PaperController::class, 'decision'])->name('decision');
        Route::post('/{paper}/decision', [PaperController::class, 'storeDecision'])->name('store-decision');
        
        // Assign to Issue
        Route::post('/{paper}/assign-issue', [PaperController::class, 'assignIssue'])->name('assign-issue');
        
        // Change Status
        Route::post('/{paper}/status', [PaperController::class, 'updateStatus'])->name('update-status');
        
        // TAMBAHKAN INI: Create paper route (jika diperlukan)
        // Route::get('/create', [PaperController::class, 'create'])->name('create');
        // Route::post('/', [PaperController::class, 'store'])->name('store');
    });
    
        // Reviewers Management
    Route::prefix('reviewers')->name('reviewers.')->group(function () {
        Route::get('/', function () {
            $reviewers = \App\Models\User::role('reviewer')->withCount([
                'reviewAssignments as pending_assignments' => function($query) {
                    $query->where('status', 'pending');
                },
                'reviewAssignments as completed_assignments' => function($query) {
                    $query->where('status', 'completed');
                }
            ])->paginate(20);
            
            return view('editor.reviewers.index', compact('reviewers'));
        })->name('index');
        
        Route::get('/{reviewer}', function ($reviewerId) {
            $reviewer = \App\Models\User::with(['reviewAssignments.paper'])->findOrFail($reviewerId);
            return view('editor.reviewers.show', compact('reviewer'));
        })->name('show');
    });

    
    // Reviews Management
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/pending', [ReviewController::class, 'pending'])->name('pending');
        Route::get('/completed', [ReviewController::class, 'completed'])->name('completed');
        Route::get('/overdue', [ReviewController::class, 'overdue'])->name('overdue');
        Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
        
        // Review Assignment Management
        Route::post('/assignments/{assignment}/remind', function ($assignmentId) {
            // TODO: Implement reminder email
            $assignment = \App\Models\ReviewAssignment::findOrFail($assignmentId);
            // Send reminder email logic here
            
            return response()->json(['success' => true, 'message' => 'Reminder sent']);
        })->name('assignments.remind');
    });

    
});