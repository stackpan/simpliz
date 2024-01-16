<?php

namespace App\Http\Requests\Proctor;

use App\Enums\Color;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property-read string $name
 * @property-read string|null $description
 * @property-read int duration
 * @property-read int|null $max_attempts
 * @property-read int $color
 */
class GeneralQuizEditorRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['string', 'nullable'],
            'duration' => ['required', 'integer'],
            'max_attempts' => ['integer'],
            'color' => ['required', Rule::enum(Color::class)]
        ];
    }
}
