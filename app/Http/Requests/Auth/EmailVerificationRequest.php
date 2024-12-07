<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Fulfill the email verification request.
     */
    public function fulfill(): void
    {
        $user = $this->user();
        
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $user->email_verified_at = now();
            $user->save();
            
            event(new Verified($user));
        }
    }
}
