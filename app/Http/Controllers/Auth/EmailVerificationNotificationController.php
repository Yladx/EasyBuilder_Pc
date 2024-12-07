<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Log::info('Email Verification Notification Request', [
            //     'user_id' => $user->id,
            //     'email' => $user->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            if ($user->hasVerifiedEmail()) {
                // Log::info('Email Already Verified - Notification Not Sent', [
                //     'user_id' => $user->id,
                //     'email' => $user->email,
                //     'timestamp' => now()
                // ]);

                return redirect()->intended(route('home', absolute: false));
            }

            try {
                $user->sendEmailVerificationNotification();

                // Log::info('Email Verification Notification Sent', [
                //     'user_id' => $user->id,
                //     'email' => $user->email,
                //     'ip_address' => $request->ip(),
                //     'timestamp' => now()
                // ]);

                // Log email verification notification activity
                DB::table('activity_logs')->insert([
                    'user_id' => $user->id,
                    'activity_timestamp' => now(),
                    'action' => 'request_verification',
                    'type' => 'user',
                    'activity' => 'Email verification notification sent',
                    'activity_details' => json_encode([
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return back()->with('status', 'verification-link-sent');
            } catch (\Exception $notificationException) {
                // Log::error('Email Verification Notification Failed', [
                //     'error' => $notificationException->getMessage(),
                //     'trace' => $notificationException->getTraceAsString(),
                //     'user_id' => $user->id,
                //     'email' => $user->email,
                //     'ip_address' => $request->ip()
                // ]);

                return back()->withErrors([
                    'email' => 'Unable to send verification notification.'
                ]);
            }
        } catch (\Exception $e) {
            // Log::error('Unexpected Error During Email Verification Notification', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'ip_address' => $request->ip()
            // ]);

            return back()->withErrors([
                'email' => 'An unexpected error occurred.'
            ]);
        }
    }
}
