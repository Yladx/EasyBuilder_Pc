<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Client;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'string', function($attribute, $value, $fail) {
                // If the value is empty or null, explicitly fail validation
                if (empty($value)) {
                    $fail('Please complete the reCAPTCHA verification.');
                    return;
                }

                try {
                    $client = new Client();
                    $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                        'form_params' => [
                            'secret' => env('RECAPTCHA_SECRET_KEY'),
                            'response' => $value,
                            'remoteip' => $this->ip()
                        ]
                    ]);
                    
                    $body = json_decode((string)$response->getBody());
                    
                    if (!$body->success) {
                        $fail('reCAPTCHA verification failed. Please try again.');
                    }
                } catch (\Exception $e) {
                    // If there's an error with the reCAPTCHA verification, fail validation
                    $fail('Unable to verify reCAPTCHA. Please try again.');
                }
            }],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ];
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

   public function authenticate()
   {
    
       // Find the user by email
       $user = \App\Models\User::where('email', $this->input('email'))->first();
       
       // Validate user existence
       if (!$user) {
           throw \Illuminate\Validation\ValidationException::withMessages([
               'warning' => 'No account found with this email address.',
           ]);
       }
       
       // Check login attempt limit
       $cacheKey = 'login_attempts_' . $this->input('email');
       $attempts = \Illuminate\Support\Facades\Cache::get($cacheKey, 0);
       
       if ($attempts >= 4) {
           // Lock out the user for 2 minutes
           throw \Illuminate\Validation\ValidationException::withMessages([
               'error' => 'Too many login attempts. Please try again in 2 minutes.',
           ]);
       }
       
       // Verify password using Hash::check
       if (!\Illuminate\Support\Facades\Hash::check($this->input('password'), $user->password)) {
           // Increment login attempts
           $cacheKey = 'login_attempts_' . $this->input('email');
           $attempts = \Illuminate\Support\Facades\Cache::get($cacheKey, 0);
           $newAttempts = $attempts + 1;
           
           // Calculate remaining attempts
           $remainingAttempts = 4 - $newAttempts;
           
           \Illuminate\Support\Facades\Cache::put(
               $cacheKey, 
               $newAttempts, 
               now()->addMinutes(2)
           );
           
           // Customize error message based on remaining attempts
           $errorMessage = $remainingAttempts > 0
               ? "Incorrect password. {$remainingAttempts} attempt(s) left."
               : "Too many login attempts. Please try again in 2 minutes.";
           
           throw \Illuminate\Validation\ValidationException::withMessages([
               'error' => $errorMessage,
           ]);
       }
       
       // Reset login attempts on successful login
       \Illuminate\Support\Facades\Cache::forget($cacheKey);
       
       // Check email verification status
       if (!$user->hasVerifiedEmail()) {
           // Send email verification notification
           $user->sendEmailVerificationNotification();
           
           throw \Illuminate\Validation\ValidationException::withMessages([
               'status' => 'You Need to Verify Your Email Address to Continue. Please verify your email address. We have emailed you a verification link  to your email address associated with this account.',
           ]);
       }
       
       // Attempt to log in
       $credentials = $this->only('email', 'password');
       $remember = $this->boolean('remember');
       
       if (!\Illuminate\Support\Facades\Auth::attempt($credentials, $remember)) {
           throw \Illuminate\Validation\ValidationException::withMessages([
               'error' => 'Unable to log in. Please contact support.',
           ]);
       }
       
       return $user;
   }
}
