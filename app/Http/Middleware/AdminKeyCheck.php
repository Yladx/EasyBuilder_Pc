<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminKeyCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log::info('Admin Key Check Middleware Triggered', [
        //     'path' => $request->path(),
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'session_id' => $request->session()->getId()
        // ]);

        if ($request->session()->has('admin_key_verified')) {
            // Log::debug('Admin Key Already Verified', [
            //     'session_id' => $request->session()->getId()
            // ]);
            return $next($request);
        }

        if ($request->is('admin/verify-admin-key')) {
            $key = $request->input('key');
            $valid = $key === env('ADMIN_KEY');
            
            if ($valid) {
                // Log::info('Admin Key Verification Successful', [
                //     'ip_address' => $request->ip(),
                //     'user_agent' => $request->userAgent()
                // ]);
                $request->session()->put('admin_key_verified', true);
                return response()->json(['success' => true]);
            }
            
            // Log::warning('Failed Admin Key Verification Attempt', [
            //     'ip_address' => $request->ip(),
            //     'user_agent' => $request->userAgent()
            // ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid admin key'
            ], 401);
        }

        // Get the original response
        $response = $next($request);
        
        // Get the content
        $content = $response->getContent();
        
        // Add our SweetAlert script just before the closing body tag
        $script = <<<HTML
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Admin Access Required",
                    text: "Please enter the admin key (6 digits) to proceed",
                    input: "password",
                    inputPlaceholder: "Enter 6-digit admin key",
                    inputAttributes: {
                        autocapitalize: "off",
                        autocorrect: "off",
                        maxlength: "6",
                        minlength: "6",
                        pattern: "[0-9]*"
                    },
                    showCancelButton: false,
                    confirmButtonText: "Submit",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: true,
                    backdrop: `rgba(0,0,0,0.7)`,
                    preConfirm: (key) => {
                        if (!/^\d{6}$/.test(key)) {
                            Swal.showValidationMessage('Please enter a 6-digit number');
                            return false;
                        }
                        return fetch("/admin/verify-admin-key", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ key: key })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || "Invalid admin key");
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(error.message);
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            });
        </script>
        HTML;
        
        // Insert the script before </body>
        $content = str_replace('</body>', $script . '</body>', $content);
        
        // Set the modified content
        $response->setContent($content);
        
        return $response;
    }
}
