<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 3;
    private const LOCKOUT_DURATION = 300; // 5 minutes in seconds

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
  public function store(LoginRequest $request): RedirectResponse
  {
      try {
          $user = $request->authenticate();
  
          $request->session()->regenerate();
          
          $sessionId = $request->session()->getId();

          // Set the session role to 'user'
          session(['role' => 'user']);
          session()->save();

          // Log user login activity
          DB::table('activity_logs')->insert([
              'user_id' => $user->id,
              'activity_timestamp' => now(),
              'action' => 'login',
              'type' => 'user',
              'activity' => 'User logged in successfully',
              'activity_details' => 'Logged in from ' . $request->ip(),
              'created_at' => now(),
              'updated_at' => now(),
          ]);

          // Comprehensive login logging
        //   Log::info('User Login Successful', [
        //       'user_id' => $user->id,
        //       'email' => $user->email,
        //       'session_id' => $sessionId,
        //       'ip_address' => $request->ip(),
        //       'user_agent' => $request->userAgent(),
        //       'timestamp' => now(),
        //       'login_method' => 'form'
        //   ]);

          // Update the session role in the database
          $updated = DB::table('sessions')
              ->where('id', $sessionId)
              ->update(['role' => 'user']);

        //   // Debug log for session update
        //   Log::debug('Session Role Database Update', [
        //       'session_id' => $sessionId,
        //       'rows_updated' => $updated,
        //       'user_id' => $user->id
        //   ]);
              
          return redirect()->intended(route('home', absolute: false))
              ->with('success', 'Login successful! Welcome back.');
            
      } catch (\Illuminate\Validation\ValidationException $e) {
          $errors = $e->errors();
          
          // Log login validation failure
        //   Log::warning('Login Validation Failed', [
        //       'errors' => $errors,
        //       'email' => $request->input('email', 'N/A'),
        //       'ip_address' => $request->ip(),
        //       'user_agent' => $request->userAgent()
        //   ]);

          // Determine the message type and content
          $messageType = key($errors);
          $messageContent = is_array($errors[$messageType]) 
              ? $errors[$messageType][0] 
              : $errors[$messageType];
          
          // Return back with the appropriate message
          return redirect()->back()
              ->withInput($request->only('email'))
              ->with($messageType, $messageContent);
      } catch (\Exception $e) {
          // Log unexpected login error
        //   Log::error('Unexpected Login Error', [
        //       'error' => $e->getMessage(),
        //       'trace' => $e->getTraceAsString(),
        //       'email' => $request->input('email', 'N/A'),
        //       'ip_address' => $request->ip(),
        //       'user_agent' => $request->userAgent()
        //   ]);

          return redirect()->back()
              ->withInput($request->only('email'))
              ->with('error', 'An unexpected error occurred. Please try again.');
      }
  }
 
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            if (Auth::check()) {
                $userId = Auth::id();
                $user = Auth::user();
                
                // Log activity before logging out
                DB::table('activity_logs')->insert([
                    'user_id' => $userId,
                    'activity_timestamp' => now(),
                    'action' => 'logout',
                    'type' => 'user',
                    'activity' => 'User logged out',  
                'activity_details' => 'Logged out from ' . $request->ip(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Log the logout
                // Log::info('User logged out', [
                //     'user_id' => $userId,
                //     'email' => $user->email
                // ]);
                
                // Clear user's session and authentication
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            return redirect('/');
            
        } catch (\Exception $e) {
            // Log::error('Logout error: ' . $e->getMessage());
            return redirect('/');
        }
    }
}
