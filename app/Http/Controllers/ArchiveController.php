<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    /**
     * Display the archive page.
     */
    public function index()
    {
        $issues = \App\Models\Issue::with('editorial', 'papers')
            ->orderBy('year', 'desc')
            ->orderBy('volume', 'desc')
            ->orderBy('number', 'desc')
            ->paginate(20);
            
        $years = \App\Models\Issue::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        return view('public.archive', compact('issues', 'years'));
    }
}