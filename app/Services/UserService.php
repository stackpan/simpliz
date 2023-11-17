<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserRole;
use App\Dto\UserUpdateDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserService
{

    public function getExamineers(int $perPage = 15): LengthAwarePaginator;

    public function create(string $name, string $email, string $password, int $gender, UserRole $role = UserRole::Examinee): string;

    public function update(User $user, UserUpdateDto $dto): bool;

    public function delete(string $userId): bool;

}
