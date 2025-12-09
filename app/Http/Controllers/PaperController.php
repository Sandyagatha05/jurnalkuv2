<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaperController extends Controller
{
    /**
     * Display a listing of papers (public).
     */
/**
 * Display a listing of papers (public).
 */
    public function index()
    {
        $query = Paper::with('author', 'issue')
            ->published()
            ->orderBy('published_at', 'desc');
        
        // Jika ada filter search
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('abstract', 'like', "%{$search}%")
                ->orWhere('keywords', 'like', "%{$search}%")
                ->orWhereHas('author', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        $papers = $query->paginate(15);
        
        return view('papers.index', compact('papers'));
    }

    /**
     * Show the form for creating a new paper.
     */
    public function create()
    {
        // Only authors can submit papers
        if (!Auth::user()->hasRole('author')) {
            abort(403, 'Only authors can submit papers.');
        }
        
        return view('papers.create');
    }

    /**
     * Store a newly submitted paper.
     */
    public function store(Request $request)
    {
        // Authorization
        if (!Auth::user()->hasRole('author')) {
            abort(403, 'Only authors can submit papers.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'paper_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);
        
        // Handle file upload
        if ($request->hasFile('paper_file')) {
            $file = $request->file('paper_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('papers', $filename, 'public');
            
            // Create paper
            $paper = Paper::create([
                'title' => $validated['title'],
                'abstract' => $validated['abstract'],
                'keywords' => $validated['keywords'],
                'author_id' => Auth::id(),
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
            
            return redirect()->route('author.papers.show', $paper)
                ->with('success', 'Paper submitted successfully! It will now go through the review process.');
        }
        
        return back()->with('error', 'File upload failed.');
    }

    /**
     * Display the specified paper (public).
     */
   /**
 * Display the specified paper (public).
 */
    public function show(Paper $paper)
    {
        // Show published papers to everyone
        // Show other papers only to author, editors, admins
        if (!$paper->isPublished()) {
            if (!Auth::check()) {
                abort(404);
            }
            
            $user = Auth::user();
            $isAuthor = $paper->author_id === $user->id;
            $isEditor = $user->hasRole('editor') || $user->hasRole('admin');
            $isReviewer = $paper->reviewers()->where('reviewer_id', $user->id)->exists();
            
            if (!$isAuthor && !$isEditor && !$isReviewer) {
                abort(403, 'You do not have permission to view this paper.');
            }
        }
        
        $paper->load(['author', 'issue', 'reviews' => function($query) {
            $query->where('is_confidential', false);
        }]);
        
        // Get next and previous papers in the same issue or all papers
        $previousPaper = Paper::published()
            ->where('id', '<', $paper->id)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextPaper = Paper::published()
            ->where('id', '>', $paper->id)
            ->orderBy('id', 'asc')
            ->first();
        
        return view('papers.show', compact('paper', 'previousPaper', 'nextPaper'));
    }
    /**
     * Download the paper file.
     */
    public function download(Paper $paper)
    {
        // Authorization check similar to show method
        if (!$paper->isPublished()) {
            if (!Auth::check()) {
                abort(404);
            }
            
            $user = Auth::user();
            $isAuthor = $paper->author_id === $user->id;
            $isEditor = $user->hasRole('editor') || $user->hasRole('admin');
            $isReviewer = $paper->reviewers()->where('reviewer_id', $user->id)->exists();
            
            if (!$isAuthor && !$isEditor && !$isReviewer) {
                abort(403, 'You do not have permission to download this paper.');
            }
        }
        
        if (!Storage::disk('public')->exists($paper->file_path)) {
            abort(404, 'File not found.');
        }
        
        return Storage::disk('public')->download($paper->file_path, $paper->original_filename);
    }

    /**
     * Update paper status (editor/admin only).
     */
    public function updateStatus(Request $request, Paper $paper)
    {
        // Authorization
        if (!Auth::user()->hasRole('editor') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:submitted,under_review,accepted,revision_minor,revision_major,rejected,published',
            'notes' => 'nullable|string',
        ]);
        
        $oldStatus = $paper->status;
        $paper->status = $validated['status'];
        
        // Set timestamps based on status
        if ($validated['status'] === 'under_review' && $oldStatus !== 'under_review') {
            $paper->reviewed_at = now();
        }
        
        if ($validated['status'] === 'published' && $oldStatus !== 'published') {
            $paper->published_at = now();
        }
        
        $paper->save();
        
        // Log activity here if needed
        
        return back()->with('success', 'Paper status updated successfully.');
    }
}