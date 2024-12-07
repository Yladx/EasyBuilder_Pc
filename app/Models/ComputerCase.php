<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerCase extends Model
{
    use HasFactory;

    protected $table = 'computer_cases'; // Specify the table name

    protected $fillable = [
        'name',
        'form_factor',
        'color',
        'psu_wattage',
        'side_panel_material',
        'external_volume',
        'internal_35_bays',
        'gpu_length_limit',
        'psu_form_factor',
        'image',
    ];
}
