<?php

namespace App\Http\Requests\Ads;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the user is authenticated and has an admin role
        return auth('admin')->check();
    }




    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'label' => 'required|string|max:255',
            'caption' => 'nullable|string',
            'access_link' => 'nullable|url',
            'brand' => 'nullable|string|max:255',
            'type' => 'required|in:video,image',
            'src' => 'nullable|file|mimes:jpeg,png,mp4|max:10240', // Max 10MB
            'advertise' => 'nullable|boolean',
        ];

    }
}
