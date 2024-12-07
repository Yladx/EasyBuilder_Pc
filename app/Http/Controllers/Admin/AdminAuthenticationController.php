<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class AdminAuthenticationController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // Check if the admin is already logged in
        if (Auth::guard('admin')->check()) {
            // Redirect to the admin dashboard if already authenticated
            return redirect()->route('admin.dashboard')->with('error', 'You are already logged in!');
        }

        // Show the admin login page if not logged in
        return view('admin.adminlogin');
    }


    /**
     * Handle an incoming authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('adminUsername', 'adminPassword');

        // Check if admin account exists before attempting login
        if (!DB::table('admins')->where('username', $credentials['adminUsername'])->exists()) {
            // Log::error('Admin account not found.', ['username' => $credentials['adminUsername']]);
            return back()->withErrors(['loginError' => 'Admin account not found.']);
        }

        // Check for an existing admin session
        $existingSession = DB::table('sessions')->where('role', 'admin')->first();
        if ($existingSession) {
            // Logout the previous admin by removing their session
            DB::table('sessions')->where('id', $existingSession->id)->delete();

            // Optionally notify the previous admin about the logout
            $previousAdminId = $existingSession->user_id;
            if ($previousAdminId) {
                DB::table('admin_activity_logs')->insert([
                    'admin_id' => $previousAdminId,
                    'activity' => 'Logged out due to another admin login.',
                    'activity_timestamp' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Log::info('Previous admin logged out.', ['admin_id' => $previousAdminId, 'session_id' => $existingSession->id]);
            }
        }

        // Attempt authentication
        if (Auth::guard('admin')->attempt([
            'username' => $credentials['adminUsername'],
            'password' => $credentials['adminPassword']
        ], $request->remember)) {
            $adminId = Auth::guard('admin')->id();
            $sessionId = session()->getId();

            // Log::info('Admin login successful.', ['admin_id' => $adminId, 'session_id' => $sessionId]);

            // Set the session role to 'admin'
            session(['role' => 'admin']);
            session()->save();

            // Update the session role in the database
            $updated = DB::table('sessions')
                ->where('id', $sessionId)
                ->update(['role' => 'admin']);

            // Log if the database update was successful
            if ($updated) {
                // Log::info('Session role updated in the database.', ['session_id' => $sessionId, 'role' => 'admin']);
            } else {
                // Log::error('Failed to update session role in the database.', ['session_id' => $sessionId]);
            }

            // Log activity
            DB::table('admin_activity_logs')->insert([
                'admin_id' => $adminId,
                'activity' => 'Login',
                'activity_timestamp' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log::info('Admin activity log created.', ['admin_id' => $adminId]);

            return redirect()->intended(route('admin.dashboard'))->with('success', 'You have successfully logged in!');
        }

        // Log::error('Admin login failed due to invalid credentials.', ['username' => $credentials['adminUsername']]);

        return back()->withErrors(['loginError' => 'Invalid credentials provided.'])->withInput();
    }




    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Check if there's an active session
            if ($request->session()->has('role')) {
                // Log out the admin
                Auth::guard('admin')->logout();

                // Invalidate the session
                $request->session()->invalidate();

                // Regenerate the session token to prevent reuse
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
            }

            // If no session exists, just redirect back to login
            return redirect()->route('admin.login');
            
        } catch (\Exception $e) {
            // Log the error but don't expose it to the user
            // Log::error('Error during admin logout: ' . $e->getMessage());
            
            // Safely redirect back to login
            return redirect()->route('admin.login');
        }
    }
}
