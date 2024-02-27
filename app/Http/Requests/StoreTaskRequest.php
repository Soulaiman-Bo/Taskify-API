<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' =>  'required|string|max:1000',
        ];
    }

    public function messages()
{
    return [
        'title.required' => 'The title field is required.',
        'title.string' => 'The title field must be a string.',
        'title.max' => 'The title field must not exceed 255 characters.',

        'body.required' => 'The body field is required.',
        'body.string' => 'The body field must be a string.',
        'body.max' => 'The body field must not exceed 1000 characters.',

       
    ];
}
}
