<?php

namespace App\Http\Requests\Build;

use Illuminate\Foundation\Http\FormRequest;

class BuildUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Authorize the request if the user owns the build or has necessary permissions
        // This can be customized further if roles/permissions are implemented
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
            'build_name' => ['required', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:255'],
            'build_note' => ['nullable', 'string', 'max:1000'],
            'published' => 'nullable|boolean', // Use 'published' instead of 'is_published'
        ];
    }

    /**
     * Customize the validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'build_name.required' => 'The build name is required.',
            'build_name.string' => 'The build name must be a valid string.',
            'build_name.max' => 'The build name cannot exceed 255 characters.',
            'tag.string' => 'The tag must be a valid string.',
            'tag.max' => 'The tag cannot exceed 255 characters.',
            'build_note.string' => 'The build note must be a valid string.',
            'build_note.max' => 'The build note cannot exceed 1000 characters.',
            'published.boolean' => $this->has('is_published') ? 1 : 0, // Convert to 1 (checked) or 0 (unchecked)
        ];
    }
}
