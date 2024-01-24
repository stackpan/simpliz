<?php

namespace App\Services\Impl;

use App\Data\LoginDto;
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


    public function authenticate(LoginDto $loginDto): string
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

        return $this->tokenRepository->generate($authenticatable, 'authentication', [$scope]);
    }

    public function logout(Authenticatable $authenticatable): void
    {
        $this->tokenRepository->deleteByName($authenticatable, 'authentication');
    }
}
