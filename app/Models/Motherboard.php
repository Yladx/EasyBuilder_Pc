<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motherboard extends Model
{
    use HasFactory;

    protected $table = 'motherboards'; // Specify the table name

    protected $fillable = [
        'name',
        'socket',
        'form_factor',
        'max_memory',
        'memory_slots',
        'storage_interface',
        'sata_connectors',
        'pcie_slots',
        'ram_generation',
        'color',
        'image',
        'tdp',
    ];
}
