<?php

namespace Tests\Utils;

use Tests\TestCase;
use App\Models\User;

class Auth {

    public static function userLogin(TestCase $testCase, User $user, string $password = 'password'): void
    {
        $testCase->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
    }

}
