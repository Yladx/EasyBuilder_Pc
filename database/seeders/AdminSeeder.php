<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
   

        // Create admin record
        DB::table('admins')->insert([
            'username' => 'admin',
            'password' => Hash::make('10121418'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
