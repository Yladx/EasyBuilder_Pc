<?php

namespace App\Http\Controllers\Admin\Manager\User;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Manager\Activitylog\RecentActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    // Fetch all users
    public function indexUsers(Request $request)
    {
        try {
            // Log::info('User Management Page Access', [
            //     'admin_id' => Auth::id(),
            //     'admin_email' => Auth::user()->email ?? 'N/A',
            //     'search_query' => $request->input('search', 'N/A'),
            //     'timestamp' => now()
            // ]);

            // Fetch users with their build statistics
            $users = User::with(['builds' => function($query) {
                $query->select('user_id', 
                    DB::raw('COUNT(*) as total_builds'),
                    DB::raw('SUM(CASE WHEN published = 1 THEN 1 ELSE 0 END) as published_builds'),
                    DB::raw('SUM(CASE WHEN published = 0 THEN 1 ELSE 0 END) as unpublished_builds')
                )
                ->groupBy('user_id');
            }])->get();

            // Add total builds count to each user
            $users->transform(function ($user) {
                $buildStats = $user->builds->first() ?? (object)[
                    'total_builds' => 0,
                    'published_builds' => 0,
                    'unpublished_builds' => 0
                ];
                
                $user->total_builds = $buildStats->total_builds;
                $user->published_builds = $buildStats->published_builds;
                $user->unpublished_builds = $buildStats->unpublished_builds;
                
                return $user;
            });

            $logs = app(RecentActivity::class)->getActivityLogs();

            // Additional statistics
            $totalUsers = $users->count();
            $verifiedUsers = $users->whereNotNull('email_verified_at')->count();
            $unverifiedUsers = $totalUsers - $verifiedUsers;

            // Log::debug('User List Retrieved with Build Statistics', [
            //     'total_users' => $totalUsers,
            //     'total_builds' => $users->sum('total_builds'),
            //     'published_builds' => $users->sum('published_builds'),
            //     'unpublished_builds' => $users->sum('unpublished_builds'),
            //     'search_applied' => $request->has('search')
            // ]);

            // Check if the request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'users' => $users,
                    'logs' => $logs,
                    'userStats' => [
                        'total' => $totalUsers,
                        'verified' => $verifiedUsers,
                        'unverified' => $unverifiedUsers,
                    ],
                    'buildStats' => [
                        'total_builds' => $users->sum('total_builds'),
                        'published_builds' => $users->sum('published_builds'),
                        'unpublished_builds' => $users->sum('unpublished_builds'),
                    ]
                ]);
            }

            // Return view for non-AJAX requests
            return view('admin.content.manage-user-logs', [
                'users' => $users,
                'logs' => $logs,
                'userStats' => [
                    'total' => $totalUsers,
                    'verified' => $verifiedUsers,
                    'unverified' => $unverifiedUsers,
                ],
                'buildStats' => [
                    'total_builds' => $users->sum('total_builds'),
                    'published_builds' => $users->sum('published_builds'),
                    'unpublished_builds' => $users->sum('unpublished_builds'),
                ]
            ]);
        } catch (\Exception $e) {
            // Log::error('Error Accessing User Management Page', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id()
            // ]);

            return back()->with('error', 'Unable to load user management page.');
        }
    }


    public function deleteUser($id)
    {
        try {
            // Log::info('User Deletion Attempt', [
            //     'admin_id' => Auth::id(),
            //     'target_user_id' => $id,
            //     'timestamp' => now()
            // ]);

            $user = User::find($id);

            if (!$user) {
                // Log::error('User Not Found', [
                //     'error' => 'User not found',
                //     'admin_id' => Auth::id(),
                //     'target_user_id' => $id
                // ]);

                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

            $user->delete();

            // Log::info('User Deleted Successfully', [
            //     'deleted_user_id' => $id,
            //     'admin_id' => Auth::id()
            // ]);

            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            // Log::error('Error Deleting User', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'target_user_id' => $id
            // ]);

            return back()->with('error', 'Unable to delete user.');
        }
    }


    // Private method to fetch users from the database
    public function showUserInfo($id)
    {
        try {
            // Log::info('User Info Retrieval Attempt', [
            //     'admin_id' => Auth::id(),
            //     'target_user_id' => $id,
            //     'timestamp' => now()
            // ]);

            $user = User::with('activityLogs')->find($id);

            if (!$user) {
                // Log::error('User Not Found', [
                //     'error' => 'User not found',
                //     'admin_id' => Auth::id(),
                //     'target_user_id' => $id
                // ]);

                return response('<p>User not found</p>', 404); // Return HTML for the modal
            }

            // Log::debug('User Info Retrieved', [
            //     'user_id' => $id,
            //     'admin_id' => Auth::id()
            // ]);

            return view('admin.content.partials.user-info', compact('user'));
        } catch (\Exception $e) {
            // Log::error('Error Retrieving User Info', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'admin_id' => Auth::id(),
            //     'target_user_id' => $id
            // ]);

            return back()->with('error', 'Unable to load user info.');
        }
    }
}
