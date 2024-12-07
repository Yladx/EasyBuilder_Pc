<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Log::info('Password Reset Attempt', [
            //     'email' => $request->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            $request->validate([
                'token' => ['required'],
                'email' => 'required|email',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            try {
                // Reset password using built-in Laravel password broker
                $status = Password::reset(
                    $request->only('email', 'password', 'password_confirmation', 'token'),
                    function ($user) use ($request) {
                        $user->forceFill([
                            'password' => Hash::make($request->password),
                            'remember_token' => Str::random(60),
                        ])->save();

                        DB::table('activity_logs')->insert([
                            'user_id' => $user->id,
                            'activity_timestamp' => now(),
                            'action' => 'update',
                            'type' => 'user',
                            'activity' => 'Password Reset',
                            'activity_details' => 'Successfully updated account password for enhanced security',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        event(new PasswordReset($user));
                    }
                );

                // Handle password reset status
                if ($status == Password::PASSWORD_RESET) {
                    return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please log in.');
                }

                // Log::warning('Password Reset Failed', [
                //     'status' => $status,
                //     'email' => $request->email,
                //     'ip_address' => $request->ip(),
                //     'timestamp' => now()
                // ]);

                return back()->withErrors(['email' => [__($status)]]);
            } catch (\Exception $resetException) {
                // Log::error('Password Reset Process Failed', [
                //     'error' => $resetException->getMessage(),
                //     'trace' => $resetException->getTraceAsString(),
                //     'email' => $request->email,
                //     'ip_address' => $request->ip()
                // ]);

                return back()->withErrors([
                    'email' => 'Unable to reset password.'
                ])->withInput();
            }
        } catch (ValidationException $validationException) {
            // Log::warning('Password Reset Validation Failed', [
            //     'errors' => $validationException->errors(),
            //     'email' => $request->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            throw $validationException;
        } catch (\Exception $e) {
            // Log::error('Unexpected Error During Password Reset', [
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
