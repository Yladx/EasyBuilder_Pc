<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        try {
            $user = User::findOrFail($request->route('id'));

            // Remove automatic login
            // if (!Auth::check()) {
            //     Auth::login($user);
            // }

            // Log::info('Email Verification Attempt', [
            //     'user_id' => $user->id,
            //     'email' => $user->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            if ($user->hasVerifiedEmail()) {
                // Log::info('Email Already Verified', [
                //     'user_id' => $user->id,
                //     'email' => $user->email,
                //     'timestamp' => now()
                // ]);

                return redirect()->intended(route('home', absolute: false))
                    ->with('status', 'Your email is already verified.');
            }

            try {
                if ($user->markEmailAsVerified()) {
                    $user->email_verified_at = now();
                    $user->save();

                    event(new Verified($user));

                    Log::info('Email Verification Successful', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'ip_address' => $request->ip(),
                        'timestamp' => now()
                    ]);

                    // Log email verification activity
                    DB::table('activity_logs')->insert([
                        'user_id' => $user->id,
                        'activity_timestamp' => now(),
                        'action' => 'verify',
                        'type' => 'user',
                        'activity' => 'Email verified',
                        'activity_details' => 
                      'Email verified successfully',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    return redirect('/')->with('success', 'Email successfully verified.');
                } else {
                    return back()->with('error', 'An error occurred during email verification.');
                }
            } catch (\Exception $verificationException) {
                Log::error('Email Verification Process Failed', [
                    'error' => $verificationException->getMessage(),
                    'trace' => $verificationException->getTraceAsString(),
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip_address' => $request->ip()
                ]);

                return back()->with('error', 'An error occurred during email verification.');
            }
        } catch (\Exception $e) {
            Log::error('Unexpected Error During Email Verification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip_address' => $request->ip()
            ]);

            return back()->with('error', 'An unexpected error occurred.');
        }
    }
}
