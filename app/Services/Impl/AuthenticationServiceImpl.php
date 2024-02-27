<?php

namespace App\Services\Impl;

use App\Data\LoginDto;
use App\Data\TokenDto;
use App\Exceptions\WrongCredentialsException;
use App\Models\Participant;
use App\Models\Proctor;
use App\Repositories\TokenRepository;
use App\Services\AuthenticationService;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthenticationServiceImpl implements AuthenticationService
{
    public function __construct(
        private readonly TokenRepository $tokenRepository,
    )
    {
    }


    public function authenticate(LoginDto $loginDto): TokenDto
    {
        if (!auth()->attempt((array) $loginDto)) {
            throw new WrongCredentialsException();
        }

        $authenticatable = auth()->user();

        $this->tokenRepository->deleteByName($authenticatable, 'authentication');

        switch ($authenticatable->accountable_type) {
            case Proctor::class:
                $scope = 'proctor';
                break;
            case Participant::class:
                $scope = 'participant';
                break;
        }

        $newAccessToken = $this->tokenRepository->generate($authenticatable, 'authentication', [$scope]);

        return new TokenDto(
            $newAccessToken->plainTextToken,
            $newAccessToken->accessToken->expires_at,
            $newAccessToken->accessToken->abilities,
        );
    }

    public function logout(Authenticatable $authenticatable): void
    {
        $this->tokenRepository->deleteByName($authenticatable, 'authentication');
    }
}
