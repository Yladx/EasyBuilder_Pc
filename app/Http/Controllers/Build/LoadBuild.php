<?php

namespace App\Http\Controllers\Build;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Build;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class LoadBuild extends Controller
{
    public function fetchBuilds(Request $request, $tag = '')
    {
        if (Auth::check()) {
            // Fetch builds based on user login
            $query = Build::leftJoin('rate', 'builds.id', '=', 'rate.build_id')
                ->select(
                    'builds.id',
                    'builds.user_id',
                    'builds.tag',
                    'builds.build_name',
                    'builds.build_note',
                    'builds.image',
                    'builds.created_at',
                    DB::raw('AVG(rate.rating) as average_rating')
                )
                ->where('builds.published', true)
                ->groupBy(
                    'builds.id', 
                    'builds.user_id', 
                    'builds.tag', 
                    'builds.build_name', 
                    'builds.build_note', 
                    'builds.image',
                    'builds.created_at'
                );

            // Add search functionality
            $search = $request->input('search');
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('builds.build_name', 'like', "%{$search}%")
                      ->orWhere('builds.build_note', 'like', "%{$search}%")
                      ->orWhere('builds.tag', 'like', "%{$search}%");
                });
            }

            // Add sorting based on request
            $sort = $request->input('sort');
            $allowedSorts = ['rating', 'name', 'latest'];
            $sortDirection = 'desc';
            if (!in_array($sort, $allowedSorts)) {
                $sort = 'latest';
            }
            if ($sort === 'name') {
                $sortDirection = 'asc';
            }
            switch ($sort) {
                case 'rating':
                    $query->orderBy('average_rating', $sortDirection)
                          ->orderBy('builds.created_at', $sortDirection);
                    break;
                case 'name':
                    $query->orderBy('builds.build_name', $sortDirection);
                    break;
                default: // latest
                    $query->orderBy('builds.created_at', $sortDirection);
            }

            // Improved tag filtering
            if ($tag == '') {
                $builds = $query->paginate(12); // Fetch all builds
            } elseif (strtolower($tag) == 'recommended') {
                // Find builds with 'Recommended' in their tag
                $builds = $query->where('builds.tag', 'like', '%Recommended%')->paginate(12);
            } else {
                // Split tags and search for each tag
                $tags = explode(',', $tag);
                $builds = $query->where(function ($q) use ($tags) {
                    foreach ($tags as $t) {
                        $q->orWhere('builds.tag', 'like', '%' . trim($t) . '%');
                    }
                })->paginate(12);
            }

            return view('builds.build', compact('builds', 'tag', 'sort', 'search'));
        } else {
            // For guests, load recommended builds and highest rated user builds
            $recommendedBuilds = DB::table('builds')
                ->leftJoin('rate', 'builds.id', '=', 'rate.build_id')
                ->select(
                    'builds.id',
                    'builds.tag',
                    'builds.build_name',
                    'builds.build_note',
                    'builds.image',
                    'builds.user_id',
                    DB::raw('AVG(rate.rating) AS average_rating')
                )
                ->where('builds.published', 1)
                ->where('builds.tag', 'like', '%Recommended%')
                ->groupBy('builds.id', 'builds.tag', 'builds.build_name', 'builds.build_note', 'builds.image', 'builds.user_id')
                ->orderBy('average_rating', 'desc');

            // Get highest rated user builds per tag
            $userBuilds = DB::table('builds')
                ->leftJoin('rate', 'builds.id', '=', 'rate.build_id')
                ->select(
                    'builds.id',
                    'builds.tag',
                    'builds.build_name',
                    'builds.build_note',
                    'builds.image',
                    'builds.user_id',
                    DB::raw('AVG(rate.rating) AS average_rating')
                )
                ->where('builds.published', 1)
                ->whereNotNull('builds.user_id')
                ->groupBy('builds.id', 'builds.tag', 'builds.build_name', 'builds.build_note', 'builds.image', 'builds.user_id')
                ->havingRaw('AVG(rate.rating) >= 4.0') // Only get highly rated builds (4.0 or higher)
                ->orderBy('average_rating', 'desc');

            // Combine both queries and get unique builds per tag
            $builds = $recommendedBuilds->union($userBuilds)
                ->get()
                ->groupBy('tag')
                ->map(function ($tagGroup) {
                    // For each tag, get the highest rated build
                    return $tagGroup->sortByDesc('average_rating')->first();
                })
                ->values();

            return view('builds.build', compact('builds'));
        }
    }

    public function getbuildinfo(Request $request)
    {
        $id = $request->route('id'); // Fetch the ID securely using the route parameter

        // Fetch the build data with related parts
        $buildinfo = Build::with([
            'user',
            'cpu',
            'gpu',
            'motherboard',
            'storage',
            'powerSupply',
            'pcCase',
            'ratings',
        ])->findOrFail($id);

        // Fetch associated RAMs using the `getRams()` method
        $rams = $buildinfo->getRams();

        // Check if the user has already rated the build
        $userHasRated = false;
        $userRating = null;

        if (Auth::check() && Auth::user()->role !== 'admin') {
            // Check if the user has already rated the build
            $rating = $buildinfo->ratings()->where('user_id', Auth::id())->first();
            if ($rating) {
                $userHasRated = true;
                $userRating = $rating->rating;
            }

            // Log the activity if the user is authenticated and not an admin
            if (Auth::check() && Auth::user()->role !== 'admin') {
                // Commented out Laravel logging for build view
                // Uncomment if detailed logging is required
                // \Illuminate\Support\Facades\Log::info('Build Viewed', [
                //     'user_id' => Auth::id(),
                //     'build_id' => $buildinfo->id,
                //     'build_name' => $buildinfo->build_name
                // ]);

                DB::table('activity_logs')->insert([
                    'user_id' => Auth::id(),
                    'build_id' => $buildinfo->id,
                    'activity_timestamp' => now(),
                    'action' => 'view',
                    'type' => 'build',
                    'activity' => 'User viewed a build',
                    'activity_details' => "User viewed the build '{$buildinfo->build_name}' [ {$buildinfo->id} ]",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Fetch activity logs excluding those with action = 'view'
        $activityLogs = ActivityLog::where('build_id', $buildinfo->id)
            ->where('action', '!=', 'view') // Exclude 'view' actions
            ->orderBy('activity_timestamp', 'desc')
            ->get();

        // Calculate the average rating
        $averageRating = $buildinfo->ratings()->avg('rating');

        // Check for 'admin' parameter in the request
        if ($request->query('admin') === 'true') {
            // If the 'admin' parameter is present and true, return the admin-specific view
            return view('builds.data.buildinfo', compact('buildinfo', 'rams', 'averageRating', 'userHasRated', 'userRating', 'activityLogs'));
        }

        // Check for 'edit' parameter in the request
        if ($request->query('edit') === 'true') {
            // If the 'edit' parameter is present and true, return the edit view
            return view('builds.data.editbuild', compact('buildinfo', 'rams', 'averageRating', 'userHasRated', 'userRating', 'activityLogs'));
        }

        // Otherwise, return the build info view
        return view('builds.data.buildinfo', compact('buildinfo', 'rams', 'averageRating', 'userHasRated', 'userRating', 'activityLogs'));
    }

    public function loaduserbuilds(Request $request)
    {
        // Fetch all builds for the logged-in user
        $userbuilds = Build::with([
            'cpu',
            'gpu',
            'motherboard',
            'storage',
            'powerSupply',
            'pcCase',
            'ratings',
        ])->where('user_id', Auth::id())->get();

        // Attach RAMs to each build using the `getRams` method
        foreach ($userbuilds as $build) {
            $build->rams = $build->getRams(); // Fetch associated RAMs for the build
        }

        return view('builds.managebuild', compact('userbuilds'));
    }
}
