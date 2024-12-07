<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Import the Authenticatable class
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable // Extend the Authenticatable class
{
    use Notifiable; // You can also use the Notifiable trait if you want notifications

    // Define the table associated with the model if it's not the plural form of the model name
    protected $table = 'admins'; // Assuming your admin table is named 'admins'

    // Specify the primary key if it's not the default 'id'
    protected $primaryKey = 'id'; // Change this if your primary key is different

    // Define fillable attributes
    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [

        'password',
        'remember_token',
    ];
    // If you're using timestamps in your admin table
    public $timestamps = true; // Ensure this is set correctly
}
