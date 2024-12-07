<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(Request $request): View
    {
        try {
            $user = $request->user();

            // Log::info('Confirm Password Page Access', [
            //     'user_id' => $user->id,
            //     'email' => $user->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            return view('auth.confirm-password');
        } catch (\Exception $e) {
            // Log::error('Error Accessing Confirm Password Page', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'ip_address' => $request->ip()
            // ]);

            return back()->withErrors([
                'error' => 'Unable to access password confirmation page.'
            ]);
        }
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Log::info('Password Confirmation Attempt', [
            //     'user_id' => $user->id,
            //     'email' => $user->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            $request->validate([
                'password' => ['required', 'current_password'],
            ]);

            try {
                $request->session()->put('auth.password_confirmed_at', time());

                // Log::info('Password Confirmation Successful', [
                //     'user_id' => $user->id,
                //     'email' => $user->email,
                //     'ip_address' => $request->ip(),
                //     'timestamp' => now()
                // ]);

                // Log password confirmation activity
                DB::table('activity_logs')->insert([
                    'user_id' => $user->id,
                    'activity_timestamp' => now(),
                    'action' => 'confirm_password',
                    'type' => 'user',
                    'activity' => 'Password Confirmed',
                    'activity_details' => 'Successfully verified and confirmed account password security',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return redirect()->intended(route('home', absolute: false));
            } catch (\Exception $confirmException) {
                // Log::error('Password Confirmation Process Failed', [
                //     'error' => $confirmException->getMessage(),
                //     'trace' => $confirmException->getTraceAsString(),
                //     'user_id' => $user->id,
                //     'email' => $user->email,
                //     'ip_address' => $request->ip()
                // ]);

                return back()->withErrors([
                    'password' => 'Unable to confirm password.'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Log::warning('Password Confirmation Validation Failed', [
            //     'errors' => $validationException->errors(),
            //     'user_id' => $request->user()->id,
            //     'email' => $request->user()->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            return back()->withErrors([
                'password' => 'Incorrect password.'
            ])->withInput();
        } catch (\Exception $e) {
            // Log::error('Unexpected Error During Password Confirmation', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'ip_address' => $request->ip()
            // ]);

            return back()->withErrors([
                'error' => 'An unexpected error occurred.'
            ]);
        }
    }
}
