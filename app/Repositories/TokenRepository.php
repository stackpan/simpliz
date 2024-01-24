<?php

namespace App\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;

interface TokenRepository
{
    public function generate(Authenticatable $authenticatable, string $name, ?array $scopes = null, ?int $expirationInMinutes = null): string;

    public function deleteByName(Authenticatable $authenticatable, string $name): void;
}
