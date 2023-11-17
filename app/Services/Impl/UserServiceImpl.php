<?php

namespace App\Services\Impl;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserGender;
use App\Dto\UserUpdateDto;
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

    public function create(string $name, string $email, string $password, int $gender, UserRole $role = UserRole::Examinee): string
    {
        $hashedPassword = Hash::make($password);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($hashedPassword),
            'gender' => $gender,
            'role' => $role,
        ]);

        return $user->id;
    }

    public function update(User $user, UserUpdateDto $dto): bool
    {
        $data = collect([
            'name' => $dto->name,
            'email' => $dto->email,
            'gender' => $dto->gender,
        ]);

        if ($dto->password !== null) {
            $hashedPassword = Hash::make($dto->password);
            $data->put('password', $hashedPassword);
        }

        $user = $user->fill($data->toArray())->save();

        return $user->wasChange();
    }

    public function delete(string $userId): bool
    {
        $user = User::find($userId);

        return $user->delete();
    }

}
