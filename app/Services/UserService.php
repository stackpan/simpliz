<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserService
{

    public function getExamineers(int $perPage = 15): LengthAwarePaginator;

    public function create(array $validated, UserRole $role = UserRole::Examinee): string;

    public function update(User $user, array $validated): bool;

    public function delete(string $userId): bool;

}
