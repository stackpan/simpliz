<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface QuizService
{

    public function getAll(User $user): Collection;

    public function loadDetails(Quiz $quiz): Quiz;
}
