<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class BorrowRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:books,id',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     * 
     * @return array
     */
    public function attributes(): array
    {
        return [
            'book_id' => 'Book ID',
        ];
    }

    /**
     * Get custom validation messages.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'book_id.required' => 'The Book ID field is required.',
            'book_id.exists' => 'The selected Book ID is invalid. Please choose a valid book.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * 
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     * 
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed. Please correct the following errors:',
            'errors' => $errors,
        ], 422));
    }

    /**
     * Perform actions after the request passes validation.
     * 
     * @return void
     */
    protected function passedValidation(): void
    {
        Log::info('Borrow record request validation passed.', [
            'user_id' => $this->user()->id,
            'book_id' => $this->input('book_id'),
        ]);
    }
}
