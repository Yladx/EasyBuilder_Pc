<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ActivityLogsSeeder;
use Database\Seeders\ComponentsSeeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\AdvertisementsSeeder;
use Database\Seeders\RecommendedBuildSeeder;
use Database\Seeders\UserBuildsSeeder;
use Database\Seeders\BuildRatingSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ComponentsSeeder::class,
            UsersSeeder::class,
            ActivityLogsSeeder::class,
            LearningModulesSeeder::class,
            
            AdvertisementsSeeder::class,
            RecommendedBuildSeeder::class,
            UserBuildsSeeder::class,
            BuildRatingSeeder::class,
        ]);
    }
}
