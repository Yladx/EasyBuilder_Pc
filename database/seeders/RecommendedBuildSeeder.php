<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ComputerCase;

class RecommendedBuildSeeder extends Seeder
{
    public function run(): void
    {
        // Gaming Builds
        $builds = [
            [
                'user_id' => null,
                'build_name' => 'High-End Gaming Build',
                'build_note' => 'Perfect for AAA gaming and streaming',
                'tag' => 'Recommended,Gaming',
                'cpu_id' => 2, // Intel Core i9-13900K
                'gpu_id' => 2, // NVIDIA GeForce RTX 4090
                'motherboard_id' => 2, // MSI MPG Z690 Carbon WiFi
                'ram_id' => json_encode([4, 4]), // 2x Corsair Vengeance RGB DDR5-6000
                'storage_id' => 2, // Samsung 990 Pro 2TB
                'power_supply_id' => 3, // Corsair RM1000x
                'case_id' => 2, // Lian Li PC-O11 Dynamic
                'image' => ComputerCase::find(2)->image, // Add case image
                'accessories' => 'RGB fans, AIO cooling',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => null,
                'build_name' => 'Mid-Range Gaming Build',
                'build_note' => 'Great 1440p gaming experience',
                'tag' => 'Recommended,Gaming',
                'cpu_id' => 4, // AMD Ryzen 7 7700X
                'gpu_id' => 4, // NVIDIA GeForce RTX 4070
                'motherboard_id' => 1, // ASUS ROG Strix B550-F Gaming
                'ram_id' => json_encode([3, 3]), // 2x G.Skill Trident Z RGB DDR4-3600
                'storage_id' => 3, // Crucial P3 1TB
                'power_supply_id' => 4, // EVGA SuperNOVA 750 GT
                'case_id' => 3, // Phanteks Eclipse P400A
                'image' => ComputerCase::find(3)->image, // Add case image
                'accessories' => 'RGB fans',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('builds')->insert($builds);

        // Office Builds
        $officeBuilds = [
            [
                'user_id' => null,
                'build_name' => 'Professional Office Build',
                'build_note' => 'Ideal for productivity and multitasking',
                'tag' => 'Recommended,Office',
                'cpu_id' => 5, // Intel Core i5-13600K
                'gpu_id' => 6, // NVIDIA GeForce RTX 3060
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([2, 2]), // 2x Kingston FURY Beast DDR4-3200
                'storage_id' => 4, // WD Blue SN570 1TB
                'power_supply_id' => 5, // Corsair CX650M
                'case_id' => 4, // Fractal Design Meshify C
                'image' => ComputerCase::find(4)->image, // Add case image
                'accessories' => 'WiFi card',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => null,
                'build_name' => 'Budget Office Build',
                'build_note' => 'Cost-effective solution for office work',
                'tag' => 'Recommended,Office',
                'cpu_id' => 6, // Intel Core i3-13100
                'gpu_id' => 7, // Integrated Graphics
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([1, 1]), // 2x Crucial Ballistix DDR4-3000
                'storage_id' => 5, // Kingston NV2 500GB
                'power_supply_id' => 6, // EVGA 600 BR
                'case_id' => 5, // NZXT H510
                'image' => ComputerCase::find(5)->image, // Add case image
                'accessories' => 'Basic cooling',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('builds')->insert($officeBuilds);

        // School Builds
        $schoolBuilds = [
            [
                'user_id' => null,
                'build_name' => 'Student All-rounder Build',
                'build_note' => 'Perfect for students who need gaming and productivity',
                'tag' => 'Recommended,School',
                'cpu_id' => 5, // Intel Core i5-13600K
                'gpu_id' => 5, // NVIDIA GeForce RTX 3070
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([2, 2]), // 2x Kingston FURY Beast DDR4-3200
                'storage_id' => 3, // Crucial P3 1TB
                'power_supply_id' => 4, // EVGA SuperNOVA 750 GT
                'case_id' => 4, // Fractal Design Meshify C
                'image' => ComputerCase::find(4)->image, // Add case image
                'accessories' => 'WiFi card, webcam',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => null,
                'build_name' => 'Budget Student Build',
                'build_note' => 'Affordable build for students',
                'tag' => 'Recommended,School',
                'cpu_id' => 6, // Intel Core i3-13100
                'gpu_id' => 6, // NVIDIA GeForce RTX 3060
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([1, 1]), // 2x Crucial Ballistix DDR4-3000
                'storage_id' => 5, // Kingston NV2 500GB
                'power_supply_id' => 6, // EVGA 600 BR
                'case_id' => 5, // NZXT H510
                'image' => ComputerCase::find(5)->image, // Add case image
                'accessories' => 'Basic cooling, budget webcam',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('builds')->insert($schoolBuilds);
    }
}