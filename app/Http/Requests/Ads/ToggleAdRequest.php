<?php

namespace App\Http\Requests\Ads;

use Illuminate\Foundation\Http\FormRequest;

class ToggleAdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

        /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'advertise' => 'required|boolean',
        ];
    }
}
