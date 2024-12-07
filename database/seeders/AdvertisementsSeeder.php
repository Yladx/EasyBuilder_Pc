<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdvertisementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing advertisements to prevent duplicates
        DB::table('advertisements')->truncate();

        $advertisements = [
            [
                'label' => 'AMD Ryzen™ 7000 Series Processors',
                'caption' => 'AMD Ryzen™ 7000 Series processors provide creators with the amazing performance they need to get more done.',
                'access_link' => 'https://www.amd.com/en/partner/articles/amd-ryzen-7000-series-desktop-processors.html',
                'brand' => 'AMD',
                'type' => 'video',
                'advertise' => 1,
                'src' => 'ads/ad_674aafa5c9b37.mp4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'sort' => 0
            ],
            [
                'label' => 'Introducing 13th Gen Intel Core Processors for Desktop | Intel',
                'caption' => 'Ascend leaderboards, create worlds, and crunch numbers—all at once. Get next-generation performance for everything you do with the 13th Gen Intel® Core™ processors.',
                'access_link' => 'https://intel.ly/3IX1bN2',
                'brand' => 'Intel',
                'type' => 'video',
                'advertise' => 0,
                'src' => 'ads/ad_674ab291c4ace.mp4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'sort' => 0
            ]
        ];

        DB::table('advertisements')->insert($advertisements);
    }
}
