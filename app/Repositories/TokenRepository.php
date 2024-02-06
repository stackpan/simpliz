<?php

namespace App\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\NewAccessToken;

interface TokenRepository
{
    public function generate(Authenticatable $authenticatable, string $name, ?array $scopes = null, ?int $expirationInMinutes = null): NewAccessToken;

    public function deleteByName(Authenticatable $authenticatable, string $name): void;
}
