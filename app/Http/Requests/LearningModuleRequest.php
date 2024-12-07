<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LearningModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'tag' => 'nullable|string',
            'new_tag' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_src' => 'nullable|file|mimetypes:video/mp4,video/x-matroska,video/x-ms-wmv,video/x-flv|max:102400', // Limit 100MB
            'information' => 'required|string',
        ];
    }
}
