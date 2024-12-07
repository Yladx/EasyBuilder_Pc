<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerSupply extends Model
{
    use HasFactory;

    protected $table = 'power_supplies'; // Specify the table name

    protected $fillable = [
        'name',
        'type',
        'efficiency',
        'wattage',
        'modular',
        'color',
        'max_tdp',
        'image',
    ];

}
