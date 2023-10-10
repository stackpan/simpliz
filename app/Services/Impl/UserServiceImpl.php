<?php

namespace App\Services\Impl;

use App\Models\User;
use App\Enums\UserRole;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class UserServiceImpl implements UserService {

    public function getExamineers(int $perPage = 15): LengthAwarePaginator
    {
        return User::select('id', 'name', 'email', 'gender')
            ->whereRole(UserRole::Examinee)
            ->paginate($perPage);
    }

    public function create(array $validated, UserRole $role = UserRole::Examinee): string
    {
        extract($validated);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'gender' => $gender,
            'role' => $role,
        ]);

        return $user->id;
    }

    public function update(User $user, array $validated): bool
    {
        extract($validated);

        $data = collect([
            'name' => $name,
            'email' => $email,
            'gender' => $gender,
        ]);

        if ($password !== null) {
            $data->put('password', $password);
        }

        $user = $user->fill($data->toArray())->save();

        return true;
    }

}
