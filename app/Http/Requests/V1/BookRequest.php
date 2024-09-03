<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class BookRequest extends FormRequest
{

    /**
     * Define custom field names for validation error messages.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'book name',
            'author' => 'author name',
            'description' => 'book description',
            'published_at' => 'publication date',
            'category_id' => 'category',
        ];
    }

    /**
     * Define custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Please provide the book name.',
            'author.required' => 'Please provide the author name.',
            'description.max' => 'The description may not be longer than 255 characters.',
            'published_at.required' => 'The publication date is required.',
            'published_at.date' => 'The publication date must be a valid date.',
            'published_at.before_or_equal' => 'The publication date cannot be a future date.',
            'category_id.required' => 'Please select a category for the book.',
            'category_id.exists' => 'The selected category is invalid.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $errors,
        ], 422));
    }

    /**
     * Perform actions after validation passes.
     *
     * @return void
     */
    protected function passedValidation()
    {
        Log::info('Book request validated successfully', $this->validated());
    }

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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'published_at' => 'required|date|before_or_equal:today',
            'category_id' => 'required|exists:categories,id', 
        ];
    }
}
