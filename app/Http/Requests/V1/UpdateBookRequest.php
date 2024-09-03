<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:255',
            'published_at' => 'sometimes|date|before_or_equal:today',
            'category_id' => 'sometimes|exists:categories,id',
        ];
    }
}