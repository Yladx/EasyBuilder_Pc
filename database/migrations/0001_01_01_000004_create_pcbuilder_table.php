<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create Motherboards table
        Schema::create('motherboards', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the motherboard'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('name')->comment('Name of the motherboard');
            $table->string('socket', 50)->comment('CPU socket type (e.g., LGA1200, AM4)');      // Socket type (e.g., LGA1200)
            $table->string('form_factor', 20)->comment('Motherboard form factor (e.g., ATX, mATX)'); // Form factor (e.g., ATX, mATX)
            $table->integer('max_memory')->comment('Maximum total memory supported (in GB)');     // Max memory (GB)
            $table->integer('memory_slots')->comment('Total number of memory slots available');   // Number of memory slots
            $table->integer('ram_slots')->nullable()->comment('Number of RAM slots'); // Number of RAM slots
             $table->string('storage_interface', 50)->comment('Primary storage interface type (SATA, PCIe)'); // Interface for storage (e.g., SATA, PCIe)
            $table->integer('sata_connectors')->comment('Number of SATA ports for storage devices'); // Number of SATA connectors for storage
            $table->integer('pcie_slots')->comment('Total number of PCIe expansion slots');      // Number of PCIe slots
            $table->string('ram_generation', 20)->comment('Compatible RAM generation (DDR4, DDR5)'); // RAM generation (e.g., DDR4)
            $table->string('color', 50)->nullable()->comment('Motherboard color');
            $table->string('image')->default('')->comment('Path or URL to motherboard image');
             $table->timestamps();
        });


        // Create CPUs table
        Schema::create('cpus', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the CPU'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('name')->comment('Name of the CPU');
            $table->string('socket', 50)->comment('CPU socket type compatible with motherboards');      // Socket type
            $table->integer('core_count')->comment('Number of CPU cores');      // Core count
            $table->decimal('core_clock', 4, 2)->comment('Base core clock speed (GHz)'); // Core clock (GHz)
            $table->decimal('boost_clock', 4, 2)->nullable()->comment('Maximum boost clock speed (GHz)'); // Boost clock (GHz)
            $table->integer('tdp')->comment('Thermal Design Power of the CPU (Watts)');             // TDP (W)
            $table->string('graphics', 50)->nullable()->comment('Integrated graphics processor name'); // Integrated graphics
            $table->boolean('smt')->nullable()->comment('Simultaneous Multithreading (Hyperthreading) support');         // SMT support
            $table->string('image')->nullable()->comment('Path or URL to CPU image');
            $table->timestamps();
        });

        // Create RAMs table
        Schema::create('rams', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the RAM'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('name')->comment('Name of the RAM module');
            $table->integer('speed_ddr_version')->comment('DDR generation version (4, 5)'); // DDR version (e.g., DDR4, DDR5)
            $table->integer('speed_mhz')->comment('RAM clock speed in MHz');        // RAM speed (MHz)
            $table->integer('modules')->comment('Number of RAM modules in the kit');          // Number of modules
            $table->integer('module_size')->comment('Size of each RAM module (GB)');      // Size of each module (GB)
            $table->string('ram_generation', 20)->comment('RAM generation matching motherboard requirements'); // Match with motherboard
            $table->string('color', 50)->nullable()->comment('Color of the RAM modules');
            $table->decimal('first_word_latency', 5, 2)->nullable()->comment('First Word Latency in nanoseconds'); // First Word Latency (ns)
            $table->decimal('cas_latency', 5, 2)->nullable()->comment('CAS Latency timing');        // CAS Latency
            $table->integer('tdp')->nullable()->comment('Thermal Design Power of RAM (Watts)'); // TDP for RAM
            $table->string('image')->nullable()->comment('Path or URL to RAM image');
            $table->timestamps();
        });

        // Create GPUs table
        Schema::create('gpus', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the GPU'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('name')->comment('Name of the graphics card');
            $table->string('chipset', 50)->comment('GPU chipset manufacturer and model');      // Chipset
            $table->integer('memory')->comment('Graphics memory size (GB)');          // Memory (GB)
            $table->decimal('core_clock', 5, 2)->comment('Base GPU core clock speed (MHz)'); // Core clock (MHz)
            $table->decimal('boost_clock', 5, 2)->nullable()->comment('Maximum GPU boost clock speed (MHz)'); // Boost clock (MHz)
            $table->integer('pcie_slots_required')->comment('Number of PCIe slots the GPU occupies'); // Number of PCIe slots required by GPU
            $table->string('color', 50)->nullable()->comment('Color of the graphics card');
            $table->integer('length')->nullable()->comment('Physical length of the GPU (mm)'); // Length (mm)
            $table->integer('tdp')->comment('Thermal Design Power of the GPU (Watts)');               // TDP for the GPU
            $table->string('image')->nullable()->comment('Path or URL to GPU image');
            $table->timestamps();
        });

        // Create Storages table
        Schema::create('storages', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the storage device'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('name')->comment('Name of the storage device');
            $table->string('storage_type')->comment('Type of storage (SSD, HDD, NVMe)');     // Storage type (e.g., SSD, HDD)
            $table->integer('capacity')->comment('Storage capacity in GB');        // Capacity (GB)
            $table->string('drive_type', 50)->nullable()->comment('Specific drive type or HDD RPM');   // SSD or RPM of HDD
            $table->integer('cache')->nullable()->comment('Storage device cache size (MB)');           // Cache (MB)
            $table->string('form_factor', 50)->nullable()->comment('Physical form factor (M.2, 2.5, 3.5)');  // M.2 or HDD size (inches)
            $table->string('interface', 50)->nullable()->comment('Storage interface type (SATA, PCIe)');    // PCIe/SATA interface
            $table->integer('tdp')->nullable()->comment('Thermal Design Power of storage device (Watts)'); // TDP for storage
            $table->string('image')->nullable()->comment('Path or URL to storage device image');
            $table->timestamps();
        });

        // Create PowerSupplies table
        Schema::create('power_supplies', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the power supply'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('name')->comment('Name of the power supply unit');
            $table->string('type', 20)->comment('Power supply form factor (ATX, SFX)');       // ATX/SFX/etc.
            $table->string('efficiency', 20)->nullable()->comment('Power efficiency rating (80+ Bronze, Gold)');  // Efficiency rating
            $table->integer('wattage')->comment('Maximum power output in Watts');       // Wattage (W)
            $table->string('modular', 10)->nullable()->comment('Modularity type (Full, Semi, None)');    // Full/Semi/false modularity
            $table->string('color', 50)->nullable()->comment('Color of the power supply');
            $table->integer('max_tdp')->nullable()->comment('Maximum total system power draw supported'); // Maximum TDP supported by the PSU
            $table->string('image')->nullable()->comment('Path or URL to power supply image');
            $table->timestamps();
        });

        // Create Cases table
        Schema::create('computer_cases', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the computer case'); // AUTO_INCREMENT PRIMARY KEY
            $table->string('name')->comment('Name of the computer case');
            $table->string('form_factor', 20)->comment('Case form factor compatible with motherboards'); // Form factor to match with motherboard
            $table->string('color', 50)->nullable()->comment('Color of the computer case');
            $table->integer('psu_wattage')->nullable()->comment('Wattage of included power supply (if any)');  // Wattage of included power supply (W)
            $table->string('side_panel_material', 50)->nullable()->comment('Material of the side panel'); // Side panel material
            $table->decimal('external_volume', 5, 2)->nullable()->comment('External case volume in liters'); // External volume (L)
            $table->integer('internal_35_bays')->nullable()->comment('Number of 3.5" internal drive bays');       // Number of internal 3.5" bays
            $table->integer('gpu_length_limit')->nullable()->comment('Maximum GPU length supported'); // Limit for GPU length
            $table->string('psu_form_factor', 20)->nullable()->comment('Compatible power supply form factor'); // PSU form factor to match
            $table->string('image')->nullable()->comment('Path or URL to case image');
            $table->timestamps();
        });

        // Create builds table
        Schema::create('builds', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the PC build');
            $table->string('user_id')->nullable()->comment('ID of the user who created the build'); // Changed to varchar to store string user IDs
            $table->string('build_name')->comment('Name of the PC build');
            $table->text('build_note')->nullable()->comment('Additional notes about the build');
            $table->string('tag')->comment('Tag or category for the build');
            $table->unsignedBigInteger('cpu_id')->comment('Foreign key referencing selected CPU');
            $table->unsignedBigInteger('gpu_id')->comment('Foreign key referencing selected GPU');
            $table->unsignedBigInteger('motherboard_id')->comment('Foreign key referencing selected motherboard');
            $table->json('ram_id')->nullable()->comment('JSON array of selected RAM module IDs'); // RAM IDs stored as JSON array
            $table->unsignedBigInteger('storage_id')->comment('Foreign key referencing selected storage device');
            $table->unsignedBigInteger('power_supply_id')->comment('Foreign key referencing selected power supply');
            $table->unsignedBigInteger('case_id')->comment('Foreign key referencing selected computer case');
            $table->text('accessories')->nullable()->comment('Additional accessories or components');
            $table->string('image')->default('default.png')->comment('Image representing the build');
            $table->boolean('published')->default(false)->comment('Whether the build is publicly visible');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('cpu_id')->references('id')->on('cpus')->onDelete('cascade');
            $table->foreign('gpu_id')->references('id')->on('gpus')->onDelete('cascade');
            $table->foreign('motherboard_id')->references('id')->on('motherboards')->onDelete('cascade');
            $table->foreign('storage_id')->references('id')->on('storages')->onDelete('cascade');
            $table->foreign('power_supply_id')->references('id')->on('power_supplies')->onDelete('cascade');
            $table->foreign('case_id')->references('id')->on('computer_cases')->onDelete('cascade');
        });

        // Create rate table
        Schema::create('rate', function (Blueprint $table) {
            $table->id()->comment('Unique identifier for the rating'); // AUTO_INCREMENT PRIMARY KEY column named 'id'
            $table->foreignId('build_id')->constrained('builds')->onDelete('cascade')->comment('Foreign key referencing the rated build');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Foreign key referencing the user who rated');
            $table->integer('rating')->check('rating >= 1 AND rating <= 5')->comment('Rating value between 1 and 5'); // Rating check constraint
            $table->timestamp('rated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Timestamp of the rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate');
        Schema::dropIfExists('builds');
        Schema::dropIfExists('motherboards');
        Schema::dropIfExists('cpus');
        Schema::dropIfExists('rams');
        Schema::dropIfExists('gpus');
        Schema::dropIfExists('storages');
        Schema::dropIfExists('power_supplies');
        Schema::dropIfExists('computer_cases');
    }
};
