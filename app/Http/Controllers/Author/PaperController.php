<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaperController extends Controller
{
    /**
     * Display a listing of the author's papers.
     */
    public function index()
    {
        $papers = Paper::where('author_id', Auth::id())
            ->with(['issue', 'reviewAssignments.reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('author.papers.index', compact('papers'));
    }

    /**
     * Show the form for creating a new paper.
     */
    public function create()
    {
        return view('author.papers.create');
    }

    /**
     * Store a newly submitted paper.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|min:100',
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
     * Display the specified paper.
     */
    public function show(Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only view your own papers.');
        }
        
        $paper->load([
            'issue',
            'reviewAssignments.reviewer',
            'reviewAssignments.review' => function($query) {
                $query->where('is_confidential', false);
            },
        ]);
        
        return view('author.papers.show', compact('paper'));
    }

    /**
     * Show the form for editing the paper (before review).
     */
    public function edit(Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only edit your own papers.');
        }
        
        // Only allow editing if paper is still submitted (not under review)
        if ($paper->status !== 'submitted') {
            return redirect()->route('author.papers.show', $paper)
                ->with('error', 'Paper cannot be edited after review process has started.');
        }
        
        return view('author.papers.edit', compact('paper'));
    }

    /**
     * Update the specified paper.
     */
    public function update(Request $request, Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only edit your own papers.');
        }
        
        // Only allow editing if paper is still submitted
        if ($paper->status !== 'submitted') {
            return redirect()->route('author.papers.show', $paper)
                ->with('error', 'Paper cannot be edited after review process has started.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|min:100',
            'keywords' => 'required|string',
            'paper_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        
        $paper->update([
            'title' => $validated['title'],
            'abstract' => $validated['abstract'],
            'keywords' => $validated['keywords'],
        ]);
        
        // Update file if provided
        if ($request->hasFile('paper_file')) {
            // Delete old file
            if (Storage::disk('public')->exists($paper->file_path)) {
                Storage::disk('public')->delete($paper->file_path);
            }
            
            $file = $request->file('paper_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('papers', $filename, 'public');
            
            $paper->file_path = $path;
            $paper->original_filename = $file->getClientOriginalName();
            $paper->save();
        }
        
        return redirect()->route('author.papers.show', $paper)
            ->with('success', 'Paper updated successfully.');
    }

    /**
     * Remove the specified paper.
     */
    public function destroy(Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only delete your own papers.');
        }
        
        // Only allow deletion if paper is still submitted
        if ($paper->status !== 'submitted') {
            return redirect()->route('author.papers.show', $paper)
                ->with('error', 'Paper cannot be deleted after review process has started.');
        }
        
        // Delete file
        if (Storage::disk('public')->exists($paper->file_path)) {
            Storage::disk('public')->delete($paper->file_path);
        }
        
        $paper->delete();
        
        return redirect()->route('author.papers.index')
            ->with('success', 'Paper deleted successfully.');
    }

    /**
     * Show form for submitting revision.
     */
    public function revision(Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only revise your own papers.');
        }
        
        // Only allow revision if paper needs revision
        if (!in_array($paper->status, ['revision_minor', 'revision_major'])) {
            return redirect()->route('author.papers.show', $paper)
                ->with('error', 'Paper does not require revision at this time.');
        }
        
        return view('author.papers.revision', compact('paper'));
    }

    /**
     * Submit revision.
     */
    public function submitRevision(Request $request, Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only revise your own papers.');
        }
        
        // Only allow revision if paper needs revision
        if (!in_array($paper->status, ['revision_minor', 'revision_major'])) {
            return redirect()->route('author.papers.show', $paper)
                ->with('error', 'Paper does not require revision at this time.');
        }
        
        $validated = $request->validate([
            'revision_file' => 'required|file|mimes:pdf|max:10240',
            'revision_notes' => 'nullable|string',
        ]);
        
        // Handle file upload
        if ($request->hasFile('revision_file')) {
            // Archive old file
            $oldPath = $paper->file_path;
            $newPath = 'archive/' . $paper->file_path . '_v' . ($paper->revision_count + 1);
            Storage::disk('public')->move($oldPath, $newPath);
            
            // Save new file
            $file = $request->file('revision_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('papers', $filename, 'public');
            
            // Update paper
            $paper->file_path = $path;
            $paper->original_filename = $file->getClientOriginalName();
            $paper->status = 'under_review'; // Send back to review
            $paper->revision_count += 1;
            $paper->save();
            
            return redirect()->route('author.papers.show', $paper)
                ->with('success', 'Revision submitted successfully. Paper will be reviewed again.');
        }
        
        return back()->with('error', 'File upload failed.');
    }

    /**
     * View reviews for paper.
     */
/**
 * View reviews for paper.
 */
    public function reviews(Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only view reviews for your own papers.');
        }
        
        $reviews = $paper->reviews()
            ->where('is_confidential', false)
            ->with(['assignment.reviewer'])
            ->get();
        
        return view('author.papers.reviews', compact('paper', 'reviews'));
    }

    /**
     * Download paper file.
     */
    public function download(Paper $paper)
    {
        // Authorization
        if ($paper->author_id !== Auth::id()) {
            abort(403, 'You can only download your own papers.');
        }
        
        if (!Storage::disk('public')->exists($paper->file_path)) {
            abort(404, 'File not found.');
        }
        
        return Storage::disk('public')->download($paper->file_path, $paper->original_filename);
    }
}