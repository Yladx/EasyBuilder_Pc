<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Log::info('Password Reset Link Request', [
            //     'email' => $request->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            $request->validate([
                'email' => ['required', 'email'],
            ]);

            try {
                // We will send the password reset link to this user. Once we have attempted
                // to send the link, we will examine the response then see the message we
                // need to show to the user. Finally, we'll send out a proper response.
                $status = Password::sendResetLink(
                    $request->only('email')
                );

                if ($status == Password::RESET_LINK_SENT) {
                    // Log::info('Password Reset Link Sent Successfully', [
                    //     'email' => $request->email,
                    //     'ip_address' => $request->ip(),
                    //     'timestamp' => now()
                    // ]);

                    // Find the user by email
                    $user = \App\Models\User::where('email', $request->email)->first();

                    if ($user) {
                        // Log the password reset request activity
                        DB::table('activity_logs')->insert([
                            'user_id' => $user->id,
                            'activity_timestamp' => now(),
                            'action' => 'request',
                            'type' => 'user',
                            'activity' => 'Password Reset Link',
                            'activity_details' => 'Initiated password recovery process for account security',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    return back()->with('status', __($status));
                }

                // Log::warning('Password Reset Link Request Failed', [
                //     'status' => $status,
                //     'email' => $request->email,
                //     'ip_address' => $request->ip(),
                //     'timestamp' => now()
                // ]);

                return back()->withErrors(['email' => __($status)]);
            } catch (\Exception $resetLinkException) {
                // Log::error('Password Reset Link Process Failed', [
                //     'error' => $resetLinkException->getMessage(),
                //     'trace' => $resetLinkException->getTraceAsString(),
                //     'email' => $request->email,
                //     'ip_address' => $request->ip()
                // ]);

                return back()->withErrors([
                    'email' => 'Unable to send password reset link.'
                ])->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Log::warning('Password Reset Link Validation Failed', [
            //     'errors' => $validationException->errors(),
            //     'email' => $request->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            throw $validationException;
        } catch (\Exception $e) {
            // Log::error('Unexpected Error During Password Reset Link Request', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'email' => $request->email,
            //     'ip_address' => $request->ip()
            // ]);

            return back()->withErrors([
                'email' => 'An unexpected error occurred.'
            ])->withInput();
        }
    }
}
