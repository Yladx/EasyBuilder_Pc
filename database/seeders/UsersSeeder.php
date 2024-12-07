<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\ComputerCase;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing users to prevent duplicates
        DB::table('users')->truncate();

        $users = [
            [
                'name' => 'John Doe',
                'fname' => 'John',
                'lname' => 'Doe',
                'email' => 'john.doe@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Jane Smith',
                'fname' => 'Jane',
                'lname' => 'Smith',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('users')->insert($users);

        // Create activity logs for user account creation
        $activityLogs = [];
        foreach ($users as $index => $user) {
            $userId = DB::table('users')->where('email', $user['email'])->value('id');
            $activityLogs[] = [
                'user_id' => $userId,
                'build_id' => null,
                'activity_timestamp' => Carbon::now(),
                'action' => 'create',
                'type' => 'user',
                'activity' => 'Account Created',
                'activity_details' => 'New user account registered',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        DB::table('activity_logs')->insert($activityLogs);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}