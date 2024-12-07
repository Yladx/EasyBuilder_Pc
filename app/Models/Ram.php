<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ram extends Model
{
    use HasFactory;

    protected $table = 'rams'; // Specify the table name

    protected $fillable = [
        'name',
        'speed_ddr_version',
        'speed_mhz',
        'modules',
        'module_size',
        'ram_generation',
        'color',
        'first_word_latency',
        'cas_latency',
        'tdp',
        'image',
    ];
}
