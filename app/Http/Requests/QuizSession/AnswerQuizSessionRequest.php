<?php

namespace App\Http\Requests\QuizSession;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AnswerQuizSessionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'userOptionId' => 'required|UUID|exists:user_options,id',
            'optionId' => 'required|UUID|exists:options,id',
            'questionPage' => 'required|numeric',
        ];
    }
}
