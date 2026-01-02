<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user();
        $user->load(['papers', 'reviewAssignments', 'managedIssues']);
        
        // Get role-specific data
        $roleData = $this->getRoleSpecificData($user);
        
        return view('profile.show', compact('user', 'roleData'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'institution' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'biography' => ['nullable', 'string'],
            'orcid_id' => ['nullable', 'string', 'max:255'],
            'google_scholar_id' => ['nullable', 'string', 'max:255'],
            'scopus_id' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $path = $request->file('photo')->store('profile-photos', 'public');
            $validated['photo'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto()
    {
        $user = Auth::user();
        
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }
        
        $user->photo = null;
        $user->save();

        return back()->with('success', 'Profile photo removed.');
    }

    /**
     * Get role-specific data for profile.
     */
    private function getRoleSpecificData(User $user)
    {
        $data = [];
        
        if ($user->hasRole('author')) {
            $data['papers_count'] = $user->papers()->count();
            $data['published_papers'] = $user->papers()->where('status', 'published')->count();
            $data['citations'] = 0; // You can implement citation tracking
        }
        
        if ($user->hasRole('reviewer')) {
            $data['total_reviews'] = $user->reviews()->count();
            $data['pending_reviews'] = $user->reviewAssignments()->where('status', 'pending')->count();
            $data['avg_review_time'] = $this->calculateAverageReviewTime($user);
        }
        
        if ($user->hasRole('editor')) {
            $data['issues_managed'] = $user->managedIssues()->count();
            $data['papers_processed'] = $user->assignedReviews()->count();
        }
        
        if ($user->hasRole('admin')) {
            $data['total_users'] = User::count();
            $data['system_status'] = 'Active';
        }
        
        return $data;
    }

    /**
     * Calculate average review time for reviewer.
     */
    private function calculateAverageReviewTime(User $user)
    {
        $completedAssignments = $user->reviewAssignments()
            ->where('status', 'completed')
            ->whereNotNull('completed_date')
            ->get();
        
        if ($completedAssignments->isEmpty()) {
            return 0;
        }
        
        $totalDays = 0;
        foreach ($completedAssignments as $assignment) {
            $days = $assignment->assigned_date->diffInDays($assignment->completed_date);
            $totalDays += $days;
        }
        
        return round($totalDays / $completedAssignments->count(), 1);
    }
}