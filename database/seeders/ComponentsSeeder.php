<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComponentsSeeder extends Seeder
{
    public function run(): void
    {
        // Motherboards Seeding
        $motherboards = [
            [
                'name' => 'ASUS ROG Strix B550-F Gaming',
                'socket' => 'AM4',
                'form_factor' => 'ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 4.0, SATA III',
                'sata_connectors' => 6,
                'pcie_slots' => 3,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
    
                'image' => 'component_images/motherboards/motherboards-1.png'
            ],
            [
                'name' => 'MSI MPG Z690 Carbon WiFi',
                'socket' => 'LGA1700',
                'form_factor' => 'ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 5.0, SATA III',
                'sata_connectors' => 6,
                'pcie_slots' => 4,
                'ram_generation' => 'DDR5',
                'color' => 'Black',
              
                'image' => 'component_images/motherboards/motherboards-2.png'
            ],
            [
                'name' => 'Gigabyte B660M DS3H AX DDR4',
                'socket' => 'LGA1700',
                'form_factor' => 'Micro-ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 4.0, SATA III',
                'sata_connectors' => 4,
                'pcie_slots' => 2,
                'ram_generation' => 'DDR4',
                'color' => 'Blue',
              
                'image' => 'component_images/motherboards/motherboards-3.png'
            ],
            [
                'name' => 'ASRock X570 Phantom Gaming 4',
                'socket' => 'AM4',
                'form_factor' => 'ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 4.0, SATA III',
                'sata_connectors' => 6,
                'pcie_slots' => 3,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
      
                'image' => 'component_images/motherboards/motherboards-4.png'
            ],
            [
                'name' => 'ASUS Prime B660M-A DDR4',
                'socket' => 'LGA1700',
                'form_factor' => 'Micro-ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 4.0, SATA III',
                'sata_connectors' => 4,
                'pcie_slots' => 2,
                'ram_generation' => 'DDR4',
                'color' => 'White',

                'image' => 'component_images/motherboards/motherboards-5.png'
            ],
            [
                'name' => 'MSI MAG B550 TOMAHAWK',
                'socket' => 'AM4',
                'form_factor' => 'ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 4.0, SATA III',
                'sata_connectors' => 6,
                'pcie_slots' => 3,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
      
                'image' => 'component_images/motherboards/motherboards-6.png'
            ],
            [
                'name' => 'Gigabyte Z690 AORUS Elite AX DDR4',
                'socket' => 'LGA1700',
                'form_factor' => 'ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 5.0, SATA III',
                'sata_connectors' => 6,
                'pcie_slots' => 4,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
         
                'image' => 'component_images/motherboards/motherboards-7.png'
            ],
            [
                'name' => 'ASRock B550M PRO4',
                'socket' => 'AM4',
                'form_factor' => 'Micro-ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 4.0, SATA III',
                'sata_connectors' => 4,
                'pcie_slots' => 2,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
  
                'image' => 'component_images/motherboards/motherboards-8.png'
            ],
            [
                'name' => 'ASUS TUF Gaming B550M-PLUS',
                'socket' => 'AM4',
                'form_factor' => 'Micro-ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 4.0, SATA III',
                'sata_connectors' => 4,
                'pcie_slots' => 2,
                'ram_generation' => 'DDR4',
                'color' => 'Black',

                'image' => 'component_images/motherboards/motherboards-9.png'
            ],
            [
                'name' => 'MSI PRO Z690-A DDR4',
                'socket' => 'LGA1700',
                'form_factor' => 'ATX',
                'max_memory' => 128,
                'memory_slots' => 4,
                'ram_slots' => 4,
                'storage_interface' => 'PCIe 5.0, SATA III',
                'sata_connectors' => 6,
                'pcie_slots' => 3,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
  
                'image' => 'component_images/motherboards/motherboards-10.png'
            ]
        ];
        DB::table('motherboards')->insert($motherboards);

        // CPUs Seeding
        $cpus = [
            [
                'name' => 'AMD Ryzen 7 5800X',
                'socket' => 'AM4',
                'core_count' => 8,
                'core_clock' => 3.8,
                'boost_clock' => 4.7,
                'tdp' => 105,
                'graphics' => null,
                'smt' => true,
                'image' => 'component_images\cpus\cpus-1.png'
            ],
            [
                'name' => 'Intel Core i7-12700K',
                'socket' => 'LGA1700',
                'core_count' => 12,
                'core_clock' => 3.6,
                'boost_clock' => 5.0,
                'tdp' => 125,
                'graphics' => 'Intel UHD Graphics 770',
                'smt' => true,
                'image' => 'component_images/cpus/cpus-2.png'
            ],
            [
                'name' => 'AMD Ryzen 5 5600X',
                'socket' => 'AM4',
                'core_count' => 6,
                'core_clock' => 3.7,
                'boost_clock' => 4.6,
                'tdp' => 65,
                'graphics' => null,
                'smt' => true,
                'image' => 'component_images/cpus/cpus-3.png'
            ],
            [
                'name' => 'Intel Core i5-12600K',
                'socket' => 'LGA1700',
                'core_count' => 10,
                'core_clock' => 3.7,
                'boost_clock' => 4.9,
                'tdp' => 125,
                'graphics' => 'Intel UHD Graphics 770',
                'smt' => true,
                'image' => 'component_images/cpus/cpus-4.png'
            ],
            [
                'name' => 'AMD Ryzen 9 5950X',
                'socket' => 'AM4',
                'core_count' => 16,
                'core_clock' => 3.4,
                'boost_clock' => 4.9,
                'tdp' => 105,
                'graphics' => null,
                'smt' => true,
                'image' => 'component_images/cpus/cpus-5.png'
            ],
            [
                'name' => 'Intel Core i9-12900K',
                'socket' => 'LGA1700',
                'core_count' => 16,
                'core_clock' => 3.2,
                'boost_clock' => 5.2,
                'tdp' => 125,
                'graphics' => 'Intel UHD Graphics 770',
                'smt' => true,
                'image' => 'component_images/cpus/cpus-6.png'
            ],
            [
                'name' => 'AMD Ryzen 5 3600X',
                'socket' => 'AM4',
                'core_count' => 6,
                'core_clock' => 3.8,
                'boost_clock' => 4.4,
                'tdp' => 95,
                'graphics' => null,
                'smt' => true,
                'image' => 'component_images/cpus/cpus-7.png'
            ],
            [
                'name' => 'Intel Core i3-12100F',
                'socket' => 'LGA1700',
                'core_count' => 4,
                'core_clock' => 3.3,
                'boost_clock' => 4.3,
                'tdp' => 58,
                'graphics' => null,
                'smt' => true,
                'image' => 'component_images/cpus/cpus-8.png'
            ],
            [
                'name' => 'AMD Ryzen 7 3700X',
                'socket' => 'AM4',
                'core_count' => 8,
                'core_clock' => 3.6,
                'boost_clock' => 4.4,
                'tdp' => 65,
                'graphics' => null,
                'smt' => true,
                'image' => 'component_images/cpus/cpus-9.png'
            ],
            [
                'name' => 'Intel Core i5-11600K',
                'socket' => 'LGA1200',
                'core_count' => 6,
                'core_clock' => 3.9,
                'boost_clock' => 4.9,
                'tdp' => 125,
                'graphics' => 'Intel UHD Graphics 750',
                'smt' => true,
                'image' => 'component_images/cpus/cpus-10.png'
            ]
        ];
        DB::table('cpus')->insert($cpus);

        // RAMs Seeding
        $rams = [
            [
                'name' => 'G.Skill Ripjaws V DDR4',
                'speed_ddr_version' => 4,
                'speed_mhz' => 3600,
                'modules' => 2,
                'module_size' => 16,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
                'first_word_latency' => 16,
                'cas_latency' => 16,
                'tdp' => 5,
                'image' => 'component_images/rams/rams-1.png'
            ],
            [
                'name' => 'Corsair Vengeance RGB Pro SL',
                'speed_ddr_version' => 5,
                'speed_mhz' => 5600,
                'modules' => 2,
                'module_size' => 16,
                'ram_generation' => 'DDR5',
                'color' => 'White',
                'first_word_latency' => 36,
                'cas_latency' => 36,
                'tdp' => 6,
                'image' => 'component_images/rams/rams-2.png'
            ],
            [
                'name' => 'Crucial Ballistix ',
                'speed_ddr_version' => 4,
                'speed_mhz' => 3200,
                'modules' => 2,
                'module_size' => 8,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
                'first_word_latency' => 16,
                'cas_latency' => 16,
                'tdp' => 4,
                'image' => 'component_images/rams/rams-3.png'
            ],
            [
                'name' => 'Team T-Force Vulcan Z ',
                'speed_ddr_version' => 4,
                'speed_mhz' => 3600,
                'modules' => 2,
                'module_size' => 16,
                'ram_generation' => 'DDR4',
                'color' => 'Red',
                'first_word_latency' => 18,
                'cas_latency' => 18,
                'tdp' => 5,
                'image' => 'component_images/rams/rams-4.png'
            ],
            [
                'name' => 'Corsair Dominator Platinum RGB',
                'speed_ddr_version' => 5,
                'speed_mhz' => 6000,
                'modules' => 4,
                'module_size' => 16,
                'ram_generation' => 'DDR5',
                'color' => 'Black',
                'first_word_latency' => 36,
                'cas_latency' => 36,
                'tdp' => 8,
                'image' => 'component_images/rams/rams-5.png'
            ],
            [
                'name' => 'Kingston FURY Beast ',
                'speed_ddr_version' => 4,
                'speed_mhz' => 3200,
                'modules' => 2,
                'module_size' => 8,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
                'first_word_latency' => 16,
                'cas_latency' => 16,
                'tdp' => 4,
                'image' => 'component_images/rams/rams-6.png'
            ],
            [
                'name' => 'G.Skill Trident Z5 RGB',
                'speed_ddr_version' => 5,
                'speed_mhz' => 5600,
                'modules' => 2,
                'module_size' => 16,
                'ram_generation' => 'DDR5',
                'color' => 'Silver',
                'first_word_latency' => 36,
                'cas_latency' => 36,
                'tdp' => 6,
                'image' => 'component_images/rams/rams-7.png'
            ],
            [
                'name' => 'Patriot Viper Steel 32GB (2x16GB) DDR4-3200',
                'speed_ddr_version' => 4,
                'speed_mhz' => 3200,
                'modules' => 2,
                'module_size' => 16,
                'ram_generation' => 'DDR4',
                'color' => 'Gray',
                'first_word_latency' => 16,
                'cas_latency' => 16,
                'tdp' => 5,
                'image' => 'component_images/rams/rams-8.png'
            ],
            [
                'name' => 'TEAMGROUP T-Create Expert ',
                'speed_ddr_version' => 4,
                'speed_mhz' => 3600,
                'modules' => 2,
                'module_size' => 32,
                'ram_generation' => 'DDR4',
                'color' => 'White',
                'first_word_latency' => 18,
                'cas_latency' => 18,
                'tdp' => 6,
                'image' => 'component_images/rams/rams-9.png'
            ],
            [
                'name' => 'Corsair Vengeance LPX',
                'speed_ddr_version' => 4,
                'speed_mhz' => 2666,
                'modules' => 2,
                'module_size' => 8,
                'ram_generation' => 'DDR4',
                'color' => 'Black',
                'first_word_latency' => 16,
                'cas_latency' => 16,
                'tdp' => 3,
                'image' => 'component_images/rams/rams-10.png'
            ]
        ];
        DB::table('rams')->insert($rams);

        // GPUs Seeding
        $gpus = [
            [
                'name' => 'NVIDIA GeForce RTX 3070 Ti',
                'chipset' => 'NVIDIA GA104',
                'memory' => 8,
                'core_clock' => 1.58,
                'boost_clock' => 1.77,
                'pcie_slots_required' => 2,
                'color' => 'Silver',
                'length' => 285,
                'tdp' => 290,
                'image' => 'component_images/gpus/gpus-1.png'
            ],
            [
                'name' => 'AMD Radeon RX 6800 XT',
                'chipset' => 'AMD Navi 21',
                'memory' => 16,
                'core_clock' => 1.82,
                'boost_clock' => 2.25,
                'pcie_slots_required' => 2,
                'color' => 'Black',
                'length' => 267,
                'tdp' => 300,
                'image' => 'component_images/gpus/gpus-2.png'
            ],
            [
                'name' => 'NVIDIA GeForce RTX 3060 Ti',
                'chipset' => 'NVIDIA GA104',
                'memory' => 8,
                'core_clock' => 1.67,
                'boost_clock' => 1.77,
                'pcie_slots_required' => 2,
                'color' => 'Silver',
                'length' => 242,
                'tdp' => 200,
                'image' => 'component_images/gpus/gpus-3.png'
            ],
            [
                'name' => 'AMD Radeon RX 6700 XT',
                'chipset' => 'AMD Navi 22',
                'memory' => 12,
                'core_clock' => 2.32,
                'boost_clock' => 2.58,
                'pcie_slots_required' => 2,
                'color' => 'Black',
                'length' => 267,
                'tdp' => 230,
                'image' => 'component_images/gpus/gpus-4.png'
            ],
            [
                'name' => 'NVIDIA GeForce RTX 3080',
                'chipset' => 'NVIDIA GA102',
                'memory' => 10,
                'core_clock' => 1.44,
                'boost_clock' => 1.71,
                'pcie_slots_required' => 2,
                'color' => 'Silver',
                'length' => 285,
                'tdp' => 320,
                'image' => 'component_images/gpus/gpus-5.png'
            ],
            [
                'name' => 'AMD Radeon RX 6600 XT',
                'chipset' => 'AMD Navi 23',
                'memory' => 8,
                'core_clock' => 1.96,
                'boost_clock' => 2.36,
                'pcie_slots_required' => 2,
                'color' => 'Black',
                'length' => 235,
                'tdp' => 160,
                'image' => 'component_images/gpus/gpus-6.png'
            ],
            [
                'name' => 'NVIDIA GeForce RTX 3050',
                'chipset' => 'NVIDIA GA106',
                'memory' => 8,
                'core_clock' => 1.55,
                'boost_clock' => 1.78,
                'pcie_slots_required' => 2,
                'color' => 'Silver',
                'length' => 235,
                'tdp' => 130,
                'image' => 'component_images/gpus/gpus-7.png'
            ],
            [
                'name' => 'AMD Radeon RX 6500 XT',
                'chipset' => 'AMD Navi 24',
                'memory' => 4,
                'core_clock' => 2.22,
                'boost_clock' => 2.82,
                'pcie_slots_required' => 1,
                'color' => 'Black',
                'length' => 200,
                'tdp' => 107,
                'image' => 'component_images/gpus/gpus-8.png'
            ],
            [
                'name' => 'NVIDIA GeForce RTX 3090',
                'chipset' => 'NVIDIA GA102',
                'memory' => 24,
                'core_clock' => 1.40,
                'boost_clock' => 1.70,
                'pcie_slots_required' => 3,
                'color' => 'Silver',
                'length' => 313,
                'tdp' => 350,
                'image' => 'component_images/gpus/gpus-9.png'
            ],
            [
                'name' => 'AMD Radeon RX 6950 XT',
                'chipset' => 'AMD Navi 21',
                'memory' => 16,
                'core_clock' => 1.72,
                'boost_clock' => 2.31,
                'pcie_slots_required' => 2,
                'color' => 'Black',
                'length' => 267,
                'tdp' => 335,
                'image' => 'component_images/gpus/gpus-10.png'
            ]
        ];
        DB::table('gpus')->insert($gpus);

        // Storages Seeding
        $storages = [
            [
                'name' => 'Samsung 980 PRO 1TB PCIe 4.0 NVMe SSD',
                'storage_type' => 'SSD',
                'capacity' => 1000,
                'drive_type' => 'NVMe',
                'cache' => 1024,
                'form_factor' => 'M.2',
                'interface' => 'PCIe 4.0',
                'tdp' => 5,
                'image' => 'component_images/storages/storages-1.png'
            ],
            [
                'name' => 'Western Digital Blue SN570 1TB NVMe SSD',
                'storage_type' => 'SSD',
                'capacity' => 1000,
                'drive_type' => 'NVMe',
                'cache' => 512,
                'form_factor' => 'M.2',
                'interface' => 'PCIe 3.0',
                'tdp' => 3,
                'image' => 'component_images/storages/storages-2.png'
            ],
            [
                'name' => 'Crucial P5 Plus 2TB PCIe 4.0 NVMe SSD',
                'storage_type' => 'SSD',
                'capacity' => 2000,
                'drive_type' => 'NVMe',
                'cache' => 2048,
                'form_factor' => 'M.2',
                'interface' => 'PCIe 4.0',
                'tdp' => 6,
                'image' => 'component_images/storages/storages-3.png'
            ],
            [
                'name' => 'Seagate FireCuda 530 500GB NVMe SSD',
                'storage_type' => 'SSD',
                'capacity' => 500,
                'drive_type' => 'NVMe',
                'cache' => 512,
                'form_factor' => 'M.2',
                'interface' => 'PCIe 4.0',
                'tdp' => 4,
                'image' => 'component_images/storages/storages-4.png'
            ],
            [
                'name' => 'Samsung 870 EVO 1TB SATA SSD',
                'storage_type' => 'SSD',
                'capacity' => 1000,
                'drive_type' => 'SATA',
                'cache' => 512,
                'form_factor' => '2.5',
                'interface' => 'SATA III',
                'tdp' => 3,
                'image' => 'component_images/storages/storages-5.png'
            ],
            [
                'name' => 'WD Black SN850 2TB PCIe 4.0 NVMe SSD',
                'storage_type' => 'SSD',
                'capacity' => 2000,
                'drive_type' => 'NVMe',
                'cache' => 2048,
                'form_factor' => 'M.2',
                'interface' => 'PCIe 4.0',
                'tdp' => 7,
                'image' => 'component_images/storages/storages-6.png'
            ],
            [
                'name' => 'Crucial MX500 2TB SATA SSD',
                'storage_type' => 'SSD',
                'capacity' => 2000,
                'drive_type' => 'SATA',
                'cache' => 1024,
                'form_factor' => '2.5',
                'interface' => 'SATA III',
                'tdp' => 3,
                'image' => 'component_images/storages/storages-7.png'
            ],
            [
                'name' => 'Sabrent Rocket 4 Plus 1TB PCIe 4.0 NVMe SSD',
                'storage_type' => 'SSD',
                'capacity' => 1000,
                'drive_type' => 'NVMe',
                'cache' => 1024,
                'form_factor' => 'M.2',
                'interface' => 'PCIe 4.0',
                'tdp' => 5,
                'image' => 'component_images/storages/storages-8.png'
            ],
            [
                'name' => 'Toshiba P300 4TB 7200RPM HDD',
                'storage_type' => 'HDD',
                'capacity' => 4000,
                'drive_type' => 'SATA',
                'cache' => 128,
                'form_factor' => '3.5',
                'interface' => 'SATA III',
                'tdp' => 8,
                'image' => 'component_images/storages/storages-9.png'
            ],
            [
                'name' => 'Seagate BarraCuda 2TB 7200RPM HDD',
                'storage_type' => 'HDD',
                'capacity' => 2000,
                'drive_type' => 'SATA',
                'cache' => 256,
                'form_factor' => '3.5',
                'interface' => 'SATA III',
                'tdp' => 6,
                'image' => 'component_images/storages/storages-10.png'
            ]
        ];
        DB::table('storages')->insert($storages);

        // Power Supplies Seeding
        $power_supplies = [
            [
                'name' => 'Corsair RM850x (2021)',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 850,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 750,
                'image' => 'component_images/power_supplies/power_supplies-1.png'
            ],
            [
                'name' => 'EVGA SuperNOVA 750 G5',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 750,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 650,
                'image' => 'component_images/power_supplies/power_supplies-2.png'
            ],
            [
                'name' => 'Seasonic FOCUS GX-750',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 750,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 700,
                'image' => 'component_images/power_supplies/power_supplies-3.png'
            ],
            [
                'name' => 'Corsair RM1000x (2021)',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 1000,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 900,
                'image' => 'component_images/power_supplies/power_supplies-4.png'
            ],
            [
                'name' => 'be quiet! Dark Power Pro 12',
                'type' => 'ATX',
                'efficiency' => '80+ Titanium',
                'wattage' => 1200,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 1000,
                'image' => 'component_images/power_supplies/power_supplies-5.png'
            ],
            [
                'name' => 'NZXT C850',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 850,
                'modular' => 'Full',
                'color' => 'White',
                'max_tdp' => 750,
                'image' => 'component_images/power_supplies/power_supplies-6.png'
            ],
            [
                'name' => 'Thermaltake Toughpower GF1 750W',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 750,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 650,
                'image' => 'component_images/power_supplies/power_supplies-7.png'
            ],
            [
                'name' => 'Cooler Master V850 Gold',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 850,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 750,
                'image' => 'component_images/power_supplies/power_supplies-8.png'
            ],
            [
                'name' => 'ASUS ROG Thor 850W Platinum',
                'type' => 'ATX',
                'efficiency' => '80+ Platinum',
                'wattage' => 850,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 800,
                'image' => 'component_images/power_supplies/power_supplies-9.png'
            ],
            [
                'name' => 'Gigabyte P850GM',
                'type' => 'ATX',
                'efficiency' => '80+ Gold',
                'wattage' => 850,
                'modular' => 'Full',
                'color' => 'Black',
                'max_tdp' => 750,
                'image' => 'component_images/power_supplies/power_supplies-10.png'
            ]
        ];
        DB::table('power_supplies')->insert($power_supplies);

        // Computer Cases Seeding
        $computer_cases = [
            [
                'name' => 'Fractal Design Meshify 2 Compact',
                'form_factor' => 'ATX',
                'color' => 'Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 41.8,
                'internal_35_bays' => 2,
                'gpu_length_limit' => 360,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-1.png'
            ],
            [
                'name' => 'NZXT H510',
                'form_factor' => 'ATX',
                'color' => 'Matte White',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 33.4,
                'internal_35_bays' => 2,
                'gpu_length_limit' => 305,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-2.png'
            ],
            [
                'name' => 'Corsair 4000D Airflow',
                'form_factor' => 'ATX',
                'color' => 'Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 42.5,
                'internal_35_bays' => 2,
                'gpu_length_limit' => 360,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-3.png'
            ],
            [
                'name' => 'Lian Li Lancool II Mesh',
                'form_factor' => 'ATX',
                'color' => 'Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 45.2,
                'internal_35_bays' => 3,
                'gpu_length_limit' => 420,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-4.png'
            ],
            [
                'name' => 'Phanteks Eclipse P400A',
                'form_factor' => 'ATX',
                'color' => 'Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 39.5,
                'internal_35_bays' => 2,
                'gpu_length_limit' => 320,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-5.png'
            ],
            [
                'name' => 'Cooler Master NR600',
                'form_factor' => 'ATX',
                'color' => 'Black',
                'side_panel_material' => 'Mesh',
                'external_volume' => 37.2,
                'internal_35_bays' => 2,
                'gpu_length_limit' => 330,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-6.png'
            ],
            [
                'name' => 'be quiet! Pure Base 500DX',
                'form_factor' => 'ATX',
                'color' => 'Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 44.5,
                'internal_35_bays' => 2,
                'gpu_length_limit' => 380,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-7.png'
            ],
            [
                'name' => 'Thermaltake View 51 RGB',
                'form_factor' => 'Full Tower',
                'color' => 'Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 62.5,
                'internal_35_bays' => 4,
                'gpu_length_limit' => 480,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-8.png'
            ],
            [
                'name' => 'Fractal Design Define 7 XL',
                'form_factor' => 'Full Tower',
                'color' => 'Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 70.2,
                'internal_35_bays' => 6,
                'gpu_length_limit' => 420,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-9.png'
            ],
            [
                'name' => 'NZXT H710i',
                'form_factor' => 'ATX',
                'color' => 'Matte Black',
                'side_panel_material' => 'Tempered Glass',
                'external_volume' => 45.8,
                'internal_35_bays' => 3,
                'gpu_length_limit' => 360,
                'psu_form_factor' => 'ATX',
                'image' => 'component_images/computer_cases/computer_cases-10.png'
            ]
        ];
        DB::table('computer_cases')->insert($computer_cases);
    }
}