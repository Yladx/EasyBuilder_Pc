<?php

namespace App\Http\Controllers\Admin\Manager\Activitylog;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecentActivity extends Controller
{
    public function getActivityLogs()
    {
        $logs = ActivityLog::with('user') // Assuming ActivityLog has a 'user' relation
        ->orderBy('activity_timestamp', 'desc')
        ->get();

    return $logs; // This will be a collection of objects
}

    public function getRecentActivities()
    {
        try {
            $recentActivities = DB::table('activity_logs')
                ->orderBy('activity_timestamp', 'DESC')
                ->take(10)
                ->get();

            return response()->json([
                'success' => true,
                'recentActivities' => $recentActivities
            ]);
        } catch (\Exception $e) {
            // Log::error('Error Fetching Recent Activities', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch recent activities'
            ], 500);
        }
    }
}
