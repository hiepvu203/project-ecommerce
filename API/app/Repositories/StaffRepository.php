<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffRepository
{
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function findRoleByName(string $roleName): ?Role
    {
        return Role::where('name', $roleName)->first();
    }

    public function assignRoleToUser(int $userId, int $roleId): UserRole
    {
        return UserRole::create([
            'user_id' => $userId,
            'role_id' => $roleId,
            'shop_id' => null,
        ]);
    }

    public function getSystemStaffRoles(): array
    {
        return [
            'super_admin',
            'content_moderator',
            'finance_admin',
            'support_admin'
        ];
    }

    public function getSystemStaff(int $perPage = 10): LengthAwarePaginator
    {
        $roleNames = $this->getSystemStaffRoles();
        $staffRoleIds = Role::whereIn('name', $roleNames)->pluck('id');
        $staffUserIds = UserRole::whereIn('role_id', $staffRoleIds)
            ->whereNull('shop_id')
            ->pluck('user_id')
            ->unique();

        return User::whereIn('id', $staffUserIds)
            ->with('roles')
            ->paginate($perPage);
    }
}
