<?php

namespace App\Repositories\Impl;

use App\Repositories\TokenRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class TokenRepositoryImpl implements TokenRepository
{
    public function generate(Authenticatable $authenticatable, string $name, ?array $scopes = null, ?int $expirationInMinutes = null): string
    {
        if (!$scopes) $scopes = ['*'];
        if (!$expirationInMinutes) $expirationInMinutes = now()->addMinutes(config('sanctum.expiration'));

        return $authenticatable->createToken($name, $scopes, $expirationInMinutes)->plainTextToken;
    }

    public function deleteByName(Authenticatable $authenticatable, string $name): void
    {
        $authenticatable->tokens()->where('name', $name)->delete();
    }
}
