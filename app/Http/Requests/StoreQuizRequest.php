<?php

namespace App\Http\Requests;

use App\Enum\Color;
use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuizRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'numeric', 'integer', 'gte:1'],
            'maxAttempts' => ['nullable', 'numeric', 'integer', 'gte:1'],
            'color' => ['required', Rule::in(Color::getNames())],
        ];
    }
}
