<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Build;

class ProfileController extends Controller
{

    /**
     * Display the user's profile form.
     */

    public function edit(Request $request): View
    {
        $userId = Auth::id();

        // Fetch the user's activity logs ordered by activity_timestamp
        $activities = DB::table('activity_logs')
            ->where('user_id', $userId)
            ->orderBy('activity_timestamp', 'desc')
            ->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'activities' => $activities,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Commented out Laravel logging for profile update
        // Uncomment if detailed logging is required
        // \Illuminate\Support\Facades\Log::info('Profile Updated', [
        //     'user_id' => $request->user()->id,
        //     'email' => $request->user()->email,
        //     'email_changed' => $request->user()->isDirty('email')
        // ]);

        // Log the profile update activity
        DB::table('activity_logs')->insert([
            'user_id' => $request->user()->id,
            'activity_timestamp' => now(),
            'action' => 'update',
            'type' => 'user',
            'activity' => 'Profile updated',
            'activity_details' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Redirect::route('profile.edit')->with('success', 'Profile successfully updated.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Check if the user has builds with ratings
        $hasRatedBuilds = \DB::table('rate')->where('user_id', $user->id)->exists();

        if ($hasRatedBuilds) {
            // Set user_id to 'deleted' for builds with ratings
            Build::where('user_id', $user->id)
                ->whereExists(function ($query) {
                    $query->select(\DB::raw(1))
                          ->from('rate')
                          ->whereColumn('rate.build_id', 'builds.id');
                })
                ->update(['user_id' => 'deleted']);
            
            // Delete builds without ratings
            Build::where('user_id', $user->id)
                ->whereNotExists(function ($query) {
                    $query->select(\DB::raw(1))
                          ->from('rate')
                          ->whereColumn('rate.build_id', 'builds.id');
                })
                ->delete();
        } else {
            // Delete all builds if none have ratings
            Build::where('user_id', $user->id)->delete();
        }

        // Delete the user's ratings
        \DB::table('rate')->where('user_id', $user->id)->delete();
        
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted successfully');
    }
}
