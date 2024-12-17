<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     */public function destroy(Request $request): RedirectResponse
     {
         $request->validateWithBag('userDeletion', [
             'password' => ['required', 'current_password'],
         ]);
     
         $user = $request->user();
         
         try {
             \DB::beginTransaction();
             
             // Log the account deletion attempt
             Log::info('Account deletion initiated', [
                 'user_id' => $user->id,
                 'email' => $user->email,
                 'timestamp' => now()
             ]);
     
             // Mark builds with ratings as 'deleted'
             $updatedBuilds = Build::where('user_id', $user->id)
                 ->whereExists(function ($query) {
                     $query->select(\DB::raw(1))
                           ->from('rate')
                           ->whereColumn('rate.build_id', 'builds.id');
                 })
                 ->update([
                     'user_id' => DB::raw("'deleted'")
                 ]);
             
             // Log the number of builds marked as deleted
             Log::info('Builds marked as deleted', [
                 'user_id' => $user->id,
                 'builds_updated' => $updatedBuilds
             ]);
     
             // Delete builds without ratings
             $deletedBuilds = Build::where('user_id', $user->id)
                 ->whereNotExists(function ($query) {
                     $query->select(\DB::raw(1))
                           ->from('rate')
                           ->whereColumn('rate.build_id', 'builds.id');
                 })
                 ->delete();
     
             // Log the number of builds deleted
             Log::info('Builds without ratings deleted', [
                 'user_id' => $user->id,
                 'builds_deleted' => $deletedBuilds
             ]);
             
             Auth::logout();
             $user->delete();
     
             // Log successful account deletion
             Log::info('Account successfully deleted', [
                 'user_id' => $user->id,
                 'email' => $user->email
             ]);
     
             \DB::commit();
             
             $request->session()->invalidate();
             $request->session()->regenerateToken();
     
             return redirect('/')->with('success', 'Your account has been deleted successfully');
         } catch (\Exception $e) {
             \DB::rollBack();
             
             // Log the error
             Log::error('Account deletion failed', [
                 'user_id' => $user->id,
                 'email' => $user->email,
                 'error' => $e->getMessage(),
                 'trace' => $e->getTraceAsString()
             ]);
     
             return redirect()->back()->with('error', 'Failed to delete account. Please try again.');
         }
     }
}
