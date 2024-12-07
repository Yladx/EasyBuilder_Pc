<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Build;
use App\Models\User;

class BuildRatingSeeder extends Seeder
{
    public function run(): void
    {
        // Get all recommended builds (builds with 'recommended' tag)
        $recommendedBuilds = DB::table('builds')
            ->where('tag', 'like', '%recommended%')
            ->get();

        // Get regular users (excluding admin)
        $users = DB::table('users')
            ->where('id', '>', 1) // Excluding admin user
            ->get();

        // Array of possible ratings
        $ratings = [4, 4.5, 5]; // High ratings for recommended builds

        foreach ($recommendedBuilds as $build) {
            // Each build gets ratings from different users, up to maximum available users
            $maxPossibleRatings = min(5, $users->count());
            $numberOfRatings = rand(min(3, $maxPossibleRatings), $maxPossibleRatings);
            $selectedUsers = $users->random($numberOfRatings);

            foreach ($selectedUsers as $user) {
                DB::table('rate')->insert([
                    'build_id' => $build->id,
                    'user_id' => $user->id,
                    'rating' => $ratings[array_rand($ratings)],
                    'rated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Add activity log for the rating
                DB::table('activity_logs')->insert([
                    'user_id' => $user->id,
                    'build_id' => $build->id,
                    'activity_timestamp' => now(),
                    'action' => 'rate',
                    'type' => 'build',
                    'activity' => 'User rated a recommended build',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
