<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UserRole;

class UserRoleRepository
{
    public function all(int $perPage = 15)
    {
        return UserRole::paginate($perPage);
    }

    public function find(int $id): ?UserRole
    {
        return UserRole::find($id);
    }

    public function create(array $data): UserRole
    {
        return UserRole::create($data);
    }

    public function update(UserRole $userRole, array $data): UserRole
    {
        $userRole->update($data);
        return $userRole->refresh();
    }

    public function delete(UserRole $userRole): ?bool
    {
        return $userRole->delete();
    }
}
