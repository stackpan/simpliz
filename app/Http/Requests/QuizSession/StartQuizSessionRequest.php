<?php

namespace App\Http\Requests\QuizSession;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\UserAlreadyTakeQuizException;

class StartQuizSessionRequest extends FormRequest
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
            'quizId' => 'required|UUID|exists:quizzes,id',
        ];
    }

    public function ensureUserIsNotInAQuizSession()
    {
        $user = auth()->user();
        $lastQuizSession = $user->getLastQuizSession();

        if ($lastQuizSession) {
            throw new UserAlreadyTakeQuizException($user, $lastQuizSession);
        }
    }
}
