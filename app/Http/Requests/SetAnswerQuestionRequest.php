<?php

namespace App\Http\Requests;

use App\Rules\InQuestion;
use Illuminate\Foundation\Http\FormRequest;

class SetAnswerQuestionRequest extends FormRequest
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
        $question = $this->route('question');

        return [
            'optionId' => ['required', 'uuid', 'exists:options,id', new InQuestion($question)],
        ];
    }
}
