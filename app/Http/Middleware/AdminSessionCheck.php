<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminSessionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log::info('Admin Session Check Middleware Triggered', [
        //     'path' => $request->path(),
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'session_role' => $request->session()->get('role')
        // ]);

        // Check if there's a session with role = admin
        if ($request->session()->has('role') && $request->session()->get('role') === 'admin') {
            // Log::debug('Admin Session Detected', [
            //     'path' => $request->path(),
            //     'session_id' => $request->session()->getId()
            // ]);

            // If the request is for the home page, block access
            if ($request->is('login') || $request->is('register')|| $request->is('')) {
                // Log::warning('Admin Attempted to Access User Home Page', [
                // Log::warning('Admin Attempted to Access User Home Page', [
                //     'ip_address' => $request->ip(),
                //     'user_agent' => $request->userAgent()
                // ]);

                $html = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Access Denied</title>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <style>
                        body {
                            margin: 0;
                            padding: 0;
                            background: rgba(0, 0, 0, 0.4);
                        }
                        .page-content {
                            filter: blur(8px);
                            pointer-events: none;
                            user-select: none;
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            z-index: -1;
                        }
                    </style>
                </head>
                <body>
                    <div class="page-content">
                        ' . $next($request)->getContent() . '
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            let timerInterval;
                            Swal.fire({
                                title: "Access Denied",
                                html: "You do not have permission to access this page.<br>Redirecting in <b></b> seconds",
                                icon: "error",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    const timer = Swal.getPopup().querySelector("b");
                                    timerInterval = setInterval(() => {
                                        timer.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
                                    }, 100);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                    window.location.href = "/admin/dashboard";
                                }
                            });
                        });
                    </script>
                </body>
                </html>';

                return response($html);
            }
        }

        return $next($request);
    }
}
