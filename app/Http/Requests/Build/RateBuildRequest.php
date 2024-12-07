<?php

namespace App\Http\Requests\Build;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RateBuildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure the user is authenticated
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|between:1,5', // Rating must be between 1 and 5
            'build_id' => 'required|exists:builds,id', // Build ID must exist in the builds table
        ];
    }

    /**
     * Customize error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'rating.required' => 'Please provide a rating.',
            'rating.integer' => 'The rating must be an integer.',
            'rating.between' => 'The rating must be between 1 and 5.',
            'build_id.required' => 'The build ID is required.',
            'build_id.exists' => 'The selected build does not exist.',
        ];
    }
}
