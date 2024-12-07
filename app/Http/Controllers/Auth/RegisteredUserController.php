<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Log::info('User Registration Attempt', [
            //     'email' => $request->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            $request->validate([
                'name' => [
                    'required', 
                    'string', 
                    'max:20'
                ],
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'email' => [
                    'required', 
                    'string', 
                    'email', 
                    'max:255', 
                    'unique:'.User::class
                ],
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                ]
            ], [
                'name.max' => 'Name must not exceed 20 characters.',
                'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.'
            ]);

            try {
                $user = User::create([
                    'name' => $request->name,
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // Log::info('User Registration Successful', [
                //     'user_id' => $user->id,
                //     'email' => $user->email,
                //     'registration_method' => 'standard',
                //     'timestamp' => now()
                // ]);

                event(new Registered($user));

                // Log the registration activity
                DB::table('activity_logs')->insert([
                    'user_id' => $user->id,
                    'activity_timestamp' => now(),
                    'action' => 'create',
                    'type' => 'user',
                    'activity' => 'User registered',
                    'activity_details' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return redirect()->route('login')
                    ->with('success', 'Registration successful! Please check your email to verify your account before logging in.');
            } catch (\Exception $createException) {
                // Log::error('User Creation Failed', [
                //     'error' => $createException->getMessage(),
                //     'trace' => $createException->getTraceAsString(),
                //     'email' => $request->email,
                //     'ip_address' => $request->ip()
                // ]);

                return back()->withErrors([
                    'email' => 'Unable to create user account.'
                ])->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Log::warning('User Registration Validation Failed', [
            //     'errors' => $validationException->errors(),
            //     'email' => $request->email,
            //     'ip_address' => $request->ip(),
            //     'timestamp' => now()
            // ]);

            throw $validationException;
        } catch (\Exception $e) {
            // Log::error('Unexpected Error During User Registration', [
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
