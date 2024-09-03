<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class UpdateBorrowRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Adjust this according to your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'due_date' => 'nullable|date|after_or_equal:borrowed_at|after_or_equal:today',
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
            'due_date' => 'Due Date',
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
            'due_date.date' => 'The Due Date must be a valid date.',
            'due_date.after_or_equal' => 'The Due Date must be after or equal to the Borrowed Date and today.',
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
    protected function failedValidation(Validator $validator)
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
    protected function passedValidation()
    {
        // Log the successful validation or perform any other post-validation actions
        Log::info('Borrow record update request validation passed.', [
            'user_id' => $this->user()->id,
            'borrow_record_id' => $this->route('borrow_record'),
        ]);
    }
}
