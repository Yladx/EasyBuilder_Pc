<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ComputerCase;

class UserBuildsSeeder extends Seeder
{
    public function run(): void
    {
        // John Doe's Builds
        DB::table('builds')->insert([
            [
                'user_id' => 2, // John Doe
                'build_name' => 'My Gaming Setup',
                'build_note' => 'Personal gaming rig for streaming',
                'tag' => 'Gaming',
                'cpu_id' => 4, // AMD Ryzen 7 7700X
                'gpu_id' => 4, // NVIDIA GeForce RTX 4070
                'motherboard_id' => 1, // ASUS ROG Strix B550-F Gaming
                'ram_id' => json_encode([3, 3]), // 2x G.Skill Trident Z RGB DDR4-3600
                'storage_id' => 3, // Crucial P3 1TB
                'power_supply_id' => 4, // EVGA SuperNOVA 750 GT
                'case_id' => 3, // Phanteks Eclipse P400A
                'image' => ComputerCase::find(3)->image, // Add case image

                'accessories' => 'RGB fans, Gaming Mouse',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, // John Doe
                'build_name' => 'Work From Home Setup',
                'build_note' => 'Quiet and efficient office build',
                'tag' => 'Office',
                'cpu_id' => 5, // Intel Core i5-13600K
                'gpu_id' => 7, // Integrated Graphics
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([2, 2]), // 2x Kingston FURY Beast DDR4-3200
                'storage_id' => 4, // WD Blue SN570 1TB
                'power_supply_id' => 5, // Corsair CX650M
                'case_id' => 5, // NZXT H510
                'image' => ComputerCase::find(5)->image, // Add case image

                'accessories' => 'Webcam, Microphone',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Jane Smith's Builds
        DB::table('builds')->insert([
            [
                'user_id' => 3, // Jane Smith
                'build_name' => 'College Workstation',
                'build_note' => 'Perfect for assignments and light gaming',
                'tag' => 'School',
                'cpu_id' => 5, // Intel Core i5-13600K
                'gpu_id' => 6, // NVIDIA GeForce RTX 3060
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([2, 2]), // 2x Kingston FURY Beast DDR4-3200
                'storage_id' => 3, // Crucial P3 1TB
                'power_supply_id' => 4, // EVGA SuperNOVA 750 GT
                'case_id' => 4, // Fractal Design Meshify C
                'image' => ComputerCase::find(4)->image, // Add case image

                'accessories' => 'WiFi card, Webcam for online classes',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3, // Jane Smith
                'build_name' => 'Budget Gaming PC',
                'build_note' => 'Affordable gaming build for casual gaming',
                'tag' => 'Gaming',
                'cpu_id' => 6, // Intel Core i3-13100
                'gpu_id' => 6, // NVIDIA GeForce RTX 3060
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([1, 1]), // 2x Crucial Ballistix DDR4-3000
                'storage_id' => 5, // Kingston NV2 500GB
                'power_supply_id' => 6, // EVGA 600 BR
                'case_id' => 5, // NZXT H510
                'image' => ComputerCase::find(5)->image, // Add case image

                'accessories' => 'Basic cooling, Budget gaming peripherals',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Builds from deleted users (user_id is null)
        DB::table('builds')->insert([
            [
                'user_id' => 'Deleted',
                'build_name' => 'Ultimate RGB Gaming Build',
                'build_note' => 'Maximum performance with RGB everywhere',
                'tag' => 'Gaming',
                'cpu_id' => 2, // Intel Core i9-13900K
                'gpu_id' => 2, // NVIDIA GeForce RTX 4090
                'motherboard_id' => 2, // MSI MPG Z690 Carbon WiFi
                'ram_id' => json_encode([4, 4]), // 2x Corsair Vengeance RGB DDR5-6000
                'storage_id' => 2, // Samsung 990 Pro 2TB
                'power_supply_id' => 3, // Corsair RM1000x
                'case_id' => 2, // Lian Li PC-O11 Dynamic
                'image' => ComputerCase::find(2)->image, // Add case image

                'accessories' => 'Custom water cooling, RGB strips, RGB fans',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 'Deleted',
                'build_name' => 'Content Creator Workstation',
                'build_note' => 'Optimized for video editing and 3D rendering',
                'tag' => 'Office',
                'cpu_id' => 2, // Intel Core i9-13900K
                'gpu_id' => 3, // NVIDIA GeForce RTX 4080
                'motherboard_id' => 2, // MSI MPG Z690 Carbon WiFi
                'ram_id' => json_encode([4, 4, 4, 4]), // 4x Corsair Vengeance RGB DDR5-6000
                'storage_id' => 2, // Samsung 990 Pro 2TB
                'power_supply_id' => 3, // Corsair RM1000x
                'case_id' => 4, // Fractal Design Meshify C
                'image' => ComputerCase::find(4)->image, // Add case image

                'accessories' => 'Professional grade cooling, Sound dampening',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' =>'Deleted',
                'build_name' => 'Compact Student Setup',
                'build_note' => 'Space-efficient build for dorm rooms',
                'tag' => 'School',
                'cpu_id' => 5, // Intel Core i5-13600K
                'gpu_id' => 5, // NVIDIA GeForce RTX 3070
                'motherboard_id' => 3, // Gigabyte B660M DS3H AX DDR4
                'ram_id' => json_encode([3, 3]), // 2x G.Skill Trident Z RGB DDR4-3600
                'storage_id' => 3, // Crucial P3 1TB
                'power_supply_id' => 5, // Corsair CX650M
                'case_id' => 5, // NZXT H510
                'image' => ComputerCase::find(5)->image, // Add case image

                'accessories' => 'Compact keyboard, Wireless mouse, Portable monitor',
                'published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}