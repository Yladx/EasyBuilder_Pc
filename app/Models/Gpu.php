<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gpu extends Model
{
    use HasFactory;

    protected $table = 'gpus'; // Specify the table name

    protected $fillable = [
        'name',
        'chipset',
        'memory',
        'core_clock',
        'boost_clock',
        'pcie_slots_required',
        'color',
        'length',
        'tdp',
        'image',
    ];
}
