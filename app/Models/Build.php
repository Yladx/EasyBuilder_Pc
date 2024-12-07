<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;

    protected $table = 'builds'; // Specify the table name

    protected $fillable = [
        'user_id',
        'build_name',
        'build_note',
        'tag',
        'cpu_id',
        'gpu_id',
        'motherboard_id',
        'ram_id', // JSON column for multiple RAMs
        'storage_id',
        'power_supply_id',
        'case_id',
        'accessories',
        'image',
        'published',
        'created_at',
        'updated_at',
    ];

    // Cast `ram_id` to an array for easy manipulation
    protected $casts = [
        'ram_id' => 'array', // Automatically cast JSON column to an array
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cpu()
    {
        return $this->belongsTo(Cpu::class, 'cpu_id');
    }

    public function gpu()
    {
        return $this->belongsTo(Gpu::class, 'gpu_id');
    }

    public function motherboard()
    {
        return $this->belongsTo(Motherboard::class, 'motherboard_id');
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function powerSupply()
    {
        return $this->belongsTo(PowerSupply::class, 'power_supply_id');
    }

    public function pcCase()
    {
        return $this->belongsTo(ComputerCase::class, 'case_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rate::class, 'build_id');
    }

    // Custom method to fetch RAMs from the `ram_id` JSON column
    public function getRams()
    {
        // Ensure `ram_id` is an array
        $ramIds = $this->ram_id ?: [];

        if (!is_array($ramIds)) {
            $ramIds = json_decode($ramIds, true) ?: []; // Decode JSON string if necessary
        }

        // Count occurrences of each RAM ID
        $counts = array_count_values($ramIds);

        // Fetch unique RAMs and attach counts
        $rams = \App\Models\Ram::whereIn('id', array_keys($counts))->get();
        $rams->each(function ($ram) use ($counts) {
            $ram->count = $counts[$ram->id] ?? 0; // Ensure count is set properly
        });

        return $rams;
    }

    // Method to update the `ram_id` JSON column
    public function updateRams(array $ramIds)
    {
        $this->ram_id = $ramIds; // Assign array directly; it will be cast to JSON
        $this->save();
    }
}
