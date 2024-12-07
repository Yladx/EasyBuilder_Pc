<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    // Define the table name (optional, Laravel assumes plural table name)
    protected $table = 'rate';

    // Define the fillable fields (if you are allowing mass assignment)
    protected $fillable = [
        'build_id',
        'user_id',
        'rating',
        'rated_at'
    ];

    // Define the inverse relationship with Build
    public function build()
    {
        return $this->belongsTo(Build::class, 'build_id');
    }

    // Define the inverse relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
