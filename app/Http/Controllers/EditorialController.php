<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorialController extends Controller
{
    /**
     * Show the form for creating/editing an editorial.
     */
    public function create(Issue $issue)
    {
        // Authorization: Only editors can create editorials
        if (!Auth::user()->hasRole('editor') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $editorial = $issue->editorial ?? new Editorial();
        
        return view('editorials.create', compact('issue', 'editorial'));
    }

    /**
     * Store a newly created editorial or update existing.
     */
    public function store(Request $request, Issue $issue)
    {
        // Authorization
        if (!Auth::user()->hasRole('editor') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);
        
        // Create or update editorial
        $editorial = $issue->editorial ?? new Editorial();
        $editorial->fill($validated);
        $editorial->issue_id = $issue->id;
        $editorial->author_id = Auth::id();
        
        if ($request->has('is_published') && $request->is_published) {
            $editorial->published_date = now();
        }
        
        $editorial->save();
        
        return redirect()->route('issues.show', $issue)
            ->with('success', 'Editorial saved successfully.');
    }

    /**
     * Show the form for editing the specified editorial.
     */
    public function edit(Editorial $editorial)
    {
        // Authorization
        if (!Auth::user()->hasRole('editor') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('editorials.edit', compact('editorial'));
    }

    /**
     * Update the specified editorial in storage.
     */
    public function update(Request $request, Editorial $editorial)
    {
        // Authorization
        if (!Auth::user()->hasRole('editor') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);
        
        $editorial->update($validated);
        
        if ($request->has('is_published') && $request->is_published && !$editorial->published_date) {
            $editorial->published_date = now();
            $editorial->save();
        }
        
        return redirect()->route('issues.show', $editorial->issue)
            ->with('success', 'Editorial updated successfully.');
    }

    /**
     * Remove the specified editorial from storage.
     */
    public function destroy(Editorial $editorial)
    {
        // Authorization
        if (!Auth::user()->hasRole('editor') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $issue = $editorial->issue;
        $editorial->delete();
        
        return redirect()->route('issues.show', $issue)
            ->with('success', 'Editorial deleted successfully.');
    }
}