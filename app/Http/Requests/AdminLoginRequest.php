<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'adminUsername' => 'required|string',
            'adminPassword' => 'required|string|min:5',
            'remember' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'adminUsername.required' => 'The username is required.',
            'adminPassword.required' => 'The password is required.',
            'adminPassword.min' => 'The password must be at least a5 characters.',
        ];
    }
}
