<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{

    public function handle($request, Closure $next)
    {
        // Log::info('Admin Middleware Check', [
        //     'path' => $request->path(),
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent()
        // ]);

        if (!Auth::guard('admin')->check()) {
            // Log::warning('Unauthorized Admin Access Attempt', [
            //     'path' => $request->path(),
            //     'ip_address' => $request->ip(),
            //     'user_agent' => $request->userAgent()
            // ]);

            return redirect()->route('admin.login')->withErrors(['accessDenied' => 'Please log in as an admin.']);
        }

        // Log::debug('Admin Access Granted', [
        //     'path' => $request->path(),
        //     'admin_id' => Auth::guard('admin')->id()
        // ]);

        return $next($request);
    }
}
