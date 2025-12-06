<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('editor')) {
            return redirect()->route('editor.dashboard');
        } elseif ($user->hasRole('reviewer')) {
            return redirect()->route('reviewer.dashboard');
        } elseif ($user->hasRole('author')) {
            return redirect()->route('author.dashboard');
        }
        
        // Default fallback
        return view('dashboard');
    }
}