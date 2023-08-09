<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Enums\UserGender;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255', 'required'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id), 'required'],
            'gender' => ['required', new Enum(UserGender::class)],
        ];
    }
}
