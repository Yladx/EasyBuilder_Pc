<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function adshow()
    {
        // Commented out Laravel logging for advertisement display
        // Uncomment if detailed logging is required
        // \Illuminate\Support\Facades\Log::info('Home Page Advertisements Loaded', [
        //     'total_advertisements' => Advertisement::where('advertise', true)->count()
        // ]);

        // Fetch advertisements where advertise is true
        $advertisements = Advertisement::where('advertise', true)->get();

        // Return the guest view with advertisements
        return view('home', compact('advertisements'));
    }
}
