<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing activity logs to prevent duplicates
        DB::table('activity_logs')->truncate();

        // Get all users
        $users = DB::table('users')->get();

        $activityLogs = [];
        foreach ($users as $user) {
            // Account Creation Log
            $activityLogs[] = [
                'user_id' => $user->id,
                'build_id' => null,
                'activity_timestamp' => $user->created_at,
                'action' => 'create',
                'type' => 'user',
                'activity' => 'Account Created',
                'activity_details' => 'New user account registered',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            // Sample additional logs for demonstration
            $activityLogs[] = [
                'user_id' => $user->id,
                'build_id' => null,
                'activity_timestamp' => Carbon::now(),
                'action' => 'login',
                'type' => 'user',
                'activity' => 'User Login',
                'activity_details' => 'Successful login attempt',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('activity_logs')->insert($activityLogs);
    }
}
