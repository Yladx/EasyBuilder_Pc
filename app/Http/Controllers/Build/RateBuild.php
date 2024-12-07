<?php

namespace App\Http\Controllers\Build;

use App\Http\Controllers\Controller;
use App\Http\Requests\Build\RateBuildRequest;
use Illuminate\Http\Request;
use App\Models\Build;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Rate;

class RateBuild extends Controller
{
    public function store(RateBuildRequest $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the build being rated
        $build = Build::find($request->input('build_id'));

        // Create or update the rating
        Rate::updateOrCreate(
            [
                'build_id' => $request->input('build_id'),
                'user_id' => $user->id,
            ],
            [
                'rating' => $request->input('rating'),
                'rated_at' => now(),
            ]
        );

        // Log the rating activity
        // Commented out Laravel logging for build rating
        // Uncomment if detailed logging is required
        // \Illuminate\Support\Facades\Log::info('Build Rated', [
        //     'user_id' => $user->id,
        //     'build_id' => $build->id,
        //     'build_name' => $build->build_name,
        //     'rating' => $request->input('rating')
        // ]);

        DB::table('activity_logs')->insert([
            'user_id' => $user->id,
            'build_id' => $build->id,
            'activity_timestamp' => now(),
            'action' => 'rate',
            'type' => 'build',
            'activity' => 'User rated a build',
            'activity_details' =>   "User rated the build  {$build->build_name} [ {$build->id} ] with a rating of {$request->input('rating')}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Thank you for rating this build!');
    }

}
