<?php

namespace App\Http\Controllers\Admin\Manager\Build;

use App\Http\Controllers\Admin\Manager\AdminController;
use App\Models\Build;
use App\Http\Requests\Ads\StoreAdRequest;
use App\Http\Requests\Ads\ToggleAdRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ManageBuildController extends Controller
{
    public function indexBuilds()
    {
        try {
            Log::info('Builds Management Page Access', [
                'admin_id' => Auth::id(),
                'admin_email' => Auth::user()->email ?? 'N/A',
                'timestamp' => now()
            ]);

            // Fetch all builds with related data (eager load relationships)
            $builds = Build::with(['cpu', 'gpu', 'motherboard', 'storage', 'powerSupply', 'pcCase', 'user'])->get();

            Log::debug('Builds Retrieved', [
                'total_builds' => $builds->count()
            ]);

            // Attach RAM details for each build
            foreach ($builds as $build) {
                $build->rams = $build->getRams(); // Use the `getRams` method to fetch associated RAMs
            }

            // Fetch total unique view counts for each build (based on user_id)
            $buildViews = ActivityLog::where('action', 'view')
                ->select('build_id', ActivityLog::raw('COUNT(DISTINCT user_id) as total_views')) // Count distinct user views
                ->groupBy('build_id')
                ->pluck('total_views', 'build_id'); // Create a key-value pair of build_id => total_views

            Log::debug('Build Views Retrieved', [
                'total_build_views' => $buildViews->count()
            ]);

            // Attach the total unique views to each build
            foreach ($builds as $build) {
                $build->total_views = $buildViews[$build->id] ?? 0; // Default to 0 if no views are logged
            }

            // Fetch recommended builds (those without a user_id) and calculate their average ratings
            $recommendedBuilds = Build::withAvg('ratings', 'rating')
                ->whereNull('user_id')
                ->get();

            Log::debug('Recommended Builds Retrieved', [
                'total_recommended_builds' => $recommendedBuilds->count()
            ]);

            foreach ($recommendedBuilds as $build) {
                $build->rams = $build->getRams();
                $build->total_views = $buildViews[$build->id] ?? 0; // Attach unique views for recommended builds
            }

            // Fetch user-created builds and calculate their average ratings
            $userBuilds = Build::withAvg('ratings', 'rating')
                ->whereNotNull('user_id')
                ->get();

            Log::debug('User Builds Retrieved', [
                'total_user_builds' => $userBuilds->count()
            ]);

            foreach ($userBuilds as $build) {
                $build->rams = $build->getRams();
                $build->total_views = $buildViews[$build->id] ?? 0; // Attach unique views for user-created builds
            }

            // Statistics for build counts
            $buildCount = $builds->count();
            $userBuildsCount = Build::whereNotNull('user_id')->count();
            $recommendedBuildsCount = Build::whereNull('user_id')->count();

            Log::debug('Build Statistics Retrieved', [
                'total_builds' => $buildCount,
                'user_builds_count' => $userBuildsCount,
                'recommended_builds_count' => $recommendedBuildsCount
            ]);

            // Handle AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'builds' => $builds,
                    'recommendedBuilds' => $recommendedBuilds,
                    'userBuilds' => $userBuilds,
                    'statistics' => [
                        'totalBuilds' => $buildCount,
                        'userBuildsCount' => $userBuildsCount,
                        'recommendedBuildsCount' => $recommendedBuildsCount,
                    ],
                ]);
            }

            // For regular requests, return the view with all builds and statistics
            return view('admin.content.manage-build', [
                'builds' => $builds,
                'recommendedBuilds' => $recommendedBuilds,
                'userBuilds' => $userBuilds,
                'statistics' => [
                    'totalBuilds' => $buildCount,
                    'userBuildsCount' => $userBuildsCount,
                    'recommendedBuildsCount' => $recommendedBuildsCount,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error Accessing Builds Management Page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => Auth::id()
            ]);

            return back()->with('error', 'Unable to load builds management page.');
        }
    }
}
