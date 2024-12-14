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
        <style>
            .otp-input-container {
                display: flex;
                justify-content: center;
                gap: 10px;
            }
            .otp-input {
                width: 50px;
                height: 50px;
                text-align: center;
                font-size: 24px;
                border: 1px solid #ccc;
                border-radius: 5px;
                -webkit-text-security: disc;
                text-security: disc;
            }
            .otp-input:focus {
                outline: none;
                border-color: #007bff;
                box-shadow: 0 0 5px rgba(0,123,255,0.5);
            }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Admin Access Required",
                    html: `
                        <div class="otp-input-container">
                            <input type="password" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="password" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="password" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="password" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="password" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="password" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                        </div>
                    `,
                    showCancelButton: false,
                    confirmButtonText: "Submit",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didRender: () => {
                        const inputs = document.querySelectorAll('.otp-input');
                        
                        inputs.forEach((input, index) => {
                            input.addEventListener('input', function() {
                                // Only allow numeric input
                                this.value = this.value.replace(/[^0-9]/g, '');
                                
                                // Auto move to next input if filled
                                if (this.value.length === 1 && index < inputs.length - 1) {
                                    inputs[index + 1].focus();
                                }
                            });

                            input.addEventListener('keydown', function(e) {
                                // Allow backspace to move back
                                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                                    inputs[index - 1].focus();
                                }
                            });
                        });

                        // Focus on first input
                        inputs[0].focus();
                    },
                    preConfirm: () => {
                        const inputs = document.querySelectorAll('.otp-input');
                        const key = Array.from(inputs).map(input => input.value).join('');
                        
                        // Validate 6-digit input
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
