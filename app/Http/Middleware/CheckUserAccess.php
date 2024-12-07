<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckUserAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Log::info('User Access Check Middleware Triggered', [
        //     'path' => $request->path(),
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'authenticated' => auth()->check(),
        //     'session_role' => Session::get('role')
        // ]);

        if (auth()->check() && Session::get('role') === 'user') {
            Log::warning('User Attempted to Access Admin Area', [
                'path' => $request->path(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $script = '<script>
                Swal.fire({
                    title: "Access Denied!",
                    text: "You do not have permission to access the admin area.",
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonText: "Go Back",
                    confirmButtonColor: "#d33",
                    allowOutsideClick: false,
                    backdrop: `
                        rgba(0,0,0,0.7)
                        center
                        no-repeat
                    `,
                    allowEscapeKey: false,
                    allowEnterKey: false                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/";
                    }
                }); 
            </script>';

            if ($request->ajax()) {
                Log::debug('Denied AJAX Request to Admin Area', [
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'error' => true,
                    'message' => 'Access Denied',
                    'html' => $script
                ], 403);
            }

            Log::debug('Denied Web Request to Admin Area', [
                'user_id' => auth()->id()
            ]);

            return response()->view('errors.403', ['script' => $script]);
        }

        // Log::debug('User Access Check Passed', [
        //     'path' => $request->path(),
        //     'authenticated' => auth()->check()
        // ]);

        return $next($request);
    }
}
