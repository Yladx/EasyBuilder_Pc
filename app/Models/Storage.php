<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    protected $table = 'storages'; // Specify the table name

    protected $fillable = [
        'name',
        'storage_type',
        'capacity',
        'drive_type',
        'cache',
        'form_factor',
        'interface',
        'tdp',
        'image',
    ];
}
