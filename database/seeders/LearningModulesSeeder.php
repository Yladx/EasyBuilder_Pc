<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LearningModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear existing learning modules to prevent duplicates
        DB::table('learning_modules')->truncate();

        $learningModules = [
            [
                'id' => 1,
                'tag' => 'PC BUILDING',
                'title' => 'How to Install a Motherboard',
                'description' => 'Step-by-step guide to properly installing a motherboard in your PC case.',
                'Information' => '<h3>Installing the Motherboard</h3>
<p>Installing the motherboard is a critical step that requires precision and care. Begin by gently lowering the motherboard into the case, ensuring the IO Shield is properly aligned if it was installed separately. Confirm that the screw holes on the motherboard align with the standoffs in the case. Once everything is properly positioned, use the appropriate screws to secure the motherboard firmly in place, but avoid overtightening to prevent damage. Completing this step provides a stable foundation for the rest of the components.</p>
<hr>
<h3>Steps and Tips for Installing a Motherboard</h3>
<h4>Preparing the Case</h4>
<ol>
<li>
<p><strong>Check Motherboard Standoffs</strong>:</p>
<ul>
<li>Ensure all the standoffs are installed in the case.</li>
<li>Align every hole in the motherboard with the pre-installed standoffs to prevent damage.</li>
</ul>
</li>
<li>
<p><strong>Install the IO Shield</strong>:</p>
<ul>
<li>Most motherboards come with an IO Shield that is not pre-installed.</li>
<li>Place and secure the IO Shield in the case before proceeding with other components.</li>
<li>Skip this step if your motherboard has a pre-installed IO Shield.</li>
</ul>
</li>
</ol>
<h4>Installing the Motherboard</h4>
<ol start="3">
<li>
<p><strong>Position the Motherboard</strong>:</p>
<ul>
<li>Gently lower the motherboard into the case.</li>
<li>Align the IO Shield (if applicable) and the screw holes on the motherboard with the standoffs in the case.</li>
</ul>
</li>
<li>
<p><strong>Secure the Motherboard</strong>:</p>
<ul>
<li>Use screws to firmly attach the motherboard to the standoffs.</li>
</ul>
</li>
</ol>
<p>&nbsp;</p>',
                'video_src' => 'videos/ykOI8MWU5nW5c6iwuMFQZUdStiGNbCPnIJt1DheV.mp4',
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-29 23:52:49'
            ],
            [
                'id' => 2,
                'tag' => 'PC BUILDING',
                'title' => 'How to Install a CPU',
                'description' => 'Comprehensive tutorial on safely installing a processor in your motherboard.',
                'Information' => '<h3>Installing a CPU</h3>
<p>Installing a CPU is a straightforward process if you follow these essential steps carefully. Begin by locating the LGA 1200 socket on your motherboard. Open the protective plate using the lever, but keep the bracket in place. Align the CPU chip with the notches on the socket, ensuring the triangle on the CPU matches the triangle on the motherboard\'s socket corner. Gently place the CPU into the socket without applying pressure. Once positioned, secure it by locking the lever in place. Although you might hear slight noises as the pins adjust, this is completely normal. Completing this step sets the stage for the rest of your build.</p>
<h3>&nbsp;</h3>
<h3>Steps and Tips for Installing a CPU</h3>
<h4>&nbsp;</h4>
<h4>Preparing the Motherboard</h4>
<ol>
<li><strong>Retain the Protective Plate</strong>:
<ul>
<li>The plate protects the delicate pins in the socket.</li>
<li>Do not discard it; keep it in case you need to RMA your motherboard.</li>
</ul>
</li>
</ol>
<h4>Installing the CPU</h4>
<ol start="2">
<li>
<p><strong>Open the Socket</strong>:</p>
<ul>
<li>Use the lever to lift the protective plate.</li>
<li>Leave the bracket on to guide CPU installation.</li>
</ul>
</li>
<li>
<p><strong>Align the CPU</strong>:</p>
<ul>
<li>Identify the notches on the sides of the CPU and the triangle on the bottom-left corner.</li>
<li>Match these with the corresponding markers on the socket.</li>
<li>Gently place the CPU into the socket without applying pressure.</li>
</ul>
</li>
<li>
<p><strong>Lock the CPU in Place</strong>:</p>
<ul>
<li>Secure the CPU by lowering and locking the lever.</li>
<li>You may hear pin adjustments as the lever locks; this is normal.</li>
</ul>
</li>
</ol>
<h4>Finalizing the Installation</h4>
<ol start="5">
<li>
<p><strong>Remove the Bracket</strong>:</p>
<ul>
<li>Once the CPU is locked, the bracket will pop out automatically.</li>
<li>This indicates the CPU is securely installed.</li>
</ul>
</li>
<li>
<p><strong>Continue with the Build</strong>:</p>
<ul>
<li>After installing the CPU, proceed to install other components onto the motherboard.</li>
</ul>
</li>
</ol>',
                'video_src' => 'videos/ZH4BxSUVUih9O1jvmQg7w5r3tsURPWoMhfVy3t5Z.mp4',
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-29 23:56:13'
            ],
            [
                'id' => 3,
                'tag' => 'PC BUILDING',
                'title' => 'How to Install RAM Modules',
                'description' => 'Complete guide to correctly installing memory modules in your computer.',
                'Information' => '<h3>Installing RAM Modules</h3>
<p>Installing or reseating RAM is a straightforward task that requires careful handling to ensure optimal system performance and avoid damaging components. Begin by shutting down your system and unplugging the power cable. Discharge any static electricity by pressing the power button after unplugging and touching the metal part of the case. This step ensures the safe handling of sensitive components. RAM sticks should be aligned with the slot key to prevent incorrect installation. Once aligned, apply even pressure until the RAM clicks into place. Regular maintenance, such as cleaning slots with compressed air, can enhance system reliability.</p>
<hr>
<h3>Steps and Tips for Installing RAM</h3>
<h4>Preparing the System</h4>
<ol>
<li>
<p><strong>Power Down and Discharge</strong>:</p>
<ul>
<li>Shut off the system and unplug the power cable.</li>
<li>Press the power button to discharge residual electricity.</li>
<li>Touch the metal part of the case to neutralize static electricity.</li>
</ul>
</li>
<li>
<p><strong>Remove Existing RAM</strong>:</p>
<ul>
<li>Unclip the sides of the RAM slot.</li>
<li>Gently pull the RAM stick straight up to remove it.</li>
<li>Repeat for all installed RAM sticks if necessary.</li>
</ul>
</li>
</ol>
<h4>Installing the RAM</h4>
<ol start="3">
<li>
<p><strong>Align the RAM Stick</strong>:</p>
<ul>
<li>Identify the key (notch) in the middle of the RAM stick.</li>
<li>Match it with the slot\'s key to ensure correct orientation.</li>
</ul>
</li>
<li>
<p><strong>Insert the RAM</strong>:</p>
<ul>
<li>Gently place the RAM stick into the slot.</li>
<li>Apply even pressure on both ends until it clicks into place.</li>
</ul>
</li>
<li>
<p><strong>Secure the RAM</strong>:</p>
<ul>
<li>Ensure the clips on the sides of the slot automatically lock onto the RAM stick.</li>
</ul>
</li>
</ol>
<h4>Post-Installation Checks</h4>
<ol start="6">
<li>
<p><strong>Verify Installation in BIOS</strong>:</p>
<ul>
<li>Access the BIOS to confirm the RAM is detected.</li>
<li>Adjust memory timings if necessary for optimal performance.</li>
</ul>
</li>
<li>
<p><strong>Check RAM in the OS</strong>:</p>
<ul>
<li>Use your operating system to verify the total RAM recognized.</li>
</ul>
</li>
<li>
<p><strong>Perform Maintenance</strong>:</p>
<ul>
<li>Use compressed air to clean the slots before inserting RAM to remove debris or dust.</li>
</ul>
</li>
</ol>
<p>&nbsp;</p>',
                'video_src' => 'videos/CGV0rmKUkL9BdzVVSxzp6iMBSIqLvDZHw4gx064M.mp4',
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-30 00:00:38'
            ],
            [
                'id' => 4,
                'tag' => 'PC BUILDING',
                'title' => 'How to Install a Power Supply Unit',
                'description' => 'Comprehensive instructions for mounting and connecting a power supply in your PC case.',
                'Information' => 'Step-by-step process of PSU installation, cable management, and proper connections.',
                'video_src' => null,
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-29 23:25:59'
            ],
            [
                'id' => 5,
                'tag' => 'PC BUILDING',
                'title' => 'How to Install PC Case Fans',
                'description' => 'Detailed guide to installing and optimizing airflow with case fans.',
                'Information' => 'Techniques for mounting case fans, understanding airflow direction, and creating optimal cooling configuration.',
                'video_src' => null,
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-29 23:25:59'
            ],
            [
                'id' => 6,
                'tag' => 'TROUBLESHOOTING AND OS INSTALLATION',
                'title' => 'Windows Installation Guide',
                'description' => 'Step-by-step guide to installing Windows operating system on your new PC.',
                'Information' => 'Creating bootable USB, BIOS settings, installation process, and initial setup.',
                'video_src' => null,
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-29 23:25:59'
            ],
            [
                'id' => 7,
                'tag' => 'TROUBLESHOOTING AND OS INSTALLATION',
                'title' => 'Common PC Troubleshooting Techniques',
                'description' => 'Learn how to diagnose and resolve common PC hardware and software issues.',
                'Information' => 'Troubleshooting boot problems, blue screens, driver issues, and performance optimization.',
                'video_src' => null,
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-29 23:25:59'
            ],
            [
                'id' => 8,
                'tag' => 'TROUBLESHOOTING AND OS INSTALLATION',
                'title' => 'Driver Management and Updates',
                'description' => 'Master the art of managing and updating device drivers for optimal system performance.',
                'Information' => 'Finding, installing, and updating drivers for various hardware components.',
                'video_src' => null,
                'created_at' => '2024-11-29 23:25:59',
                'updated_at' => '2024-11-29 23:25:59'
            ]
        ];

        DB::table('learning_modules')->insert($learningModules);
    }
}
