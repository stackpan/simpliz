<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Activity;
use App\Models\QuizUser;
use App\Models\QuizSession;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\{UserGender, UserRole};
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'gender' => UserGender::class,
        'role' => UserRole::class,
    ];

    /**
     * Get all of the activities for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * The quizzes that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class)
            ->using(QuizUser::class);
    }

    /**
     * Get all of the results for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function isAssignedTo(string $quizId): bool
    {
        $result = $this
            ->quizzes()
            ->where('quizzes.id', $quizId)
            ->first();

        return $result !== null;
    }

    public function getLastQuizSession(): ?QuizSession
    {
        return optional(
            $this->results()
                ->latest()
                ->has('quizSession')
                ->first())
            ->quizSession;
    }

}
