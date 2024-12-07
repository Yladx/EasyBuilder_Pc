<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpu extends Model
{
    use HasFactory;

    protected $table = 'cpus'; // Specify the table name

    protected $fillable = [
        'name',
        'socket',
        'core_count',
        'core_clock',
        'boost_clock',
        'tdp',
        'graphics',
        'smt',
        'image',
    ];
}
