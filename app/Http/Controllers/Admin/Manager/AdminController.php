<?php

namespace App\Http\Controllers\Admin\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Manager\Activitylog\RecentActivity;
use App\Http\Controllers\Admin\Manager\Ads\ManageAdsController;
use App\Http\Controllers\Admin\Manager\Build\ManageBuildController;
use App\Http\Controllers\Admin\Manager\Modules\ManageModuleController;
use App\Http\Controllers\Admin\Manager\User\ManageUserController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            // Log::info('Admin Dashboard Access', [
            //     'user_id' => Auth::id(),
            //     'user_email' => Auth::user()->email ?? 'N/A',
            //     'ip_address' => request()->ip(),
            //     'timestamp' => now()
            // ]);

            // Fetch activity log data grouped by date
            $activityData = ActivityLog::select(
                DB::raw('DATE(activity_timestamp) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotIn('action', ['login', 'logout'])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

            // Log::debug('Dashboard Statistics', [
            //     'activity_data' => $activityData
            // ]);

            // Count sessions by role
            $guestSessionsCount = DB::table('sessions')
            ->where('role', 'guest')
            ->count();

            $userSessionsCount = DB::table('sessions')
            ->where('role', 'user')
            ->count();

            // Log::debug('Session Statistics', [
            //     'guest_sessions_count' => $guestSessionsCount,
            //     'user_sessions_count' => $userSessionsCount
            // ]);

            return view('admin.dashboard', compact('activityData',  'guestSessionsCount', 'userSessionsCount'));
        } catch (\Exception $e) {
            // Log::error('Error Accessing Admin Dashboard', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'user_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load dashboard. Please try again.');
        }
    }

    public function getSessionCounts()
    {
        // Log::info('getSessionCounts method called');
        try {
            $sessionCounts = [
                'guest_sessions' => DB::table('sessions')->where('role', 'guest')->count(),
                'user_sessions' => DB::table('sessions')->where('role', 'user')->count(),
            ];

            // Log::info('Session counts retrieved', ['sessionCounts' => $sessionCounts]);

            return response()->json([
                'success' => true,
                'sessionCounts' => $sessionCounts
            ]);
        } catch (\Exception $e) {
            // Log::error('Error Fetching Session Counts', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch session counts'
            ], 500);
        }
    }
}
