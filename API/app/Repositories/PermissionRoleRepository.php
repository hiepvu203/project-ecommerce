<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PermissionRole;

class PermissionRoleRepository
{
    public function all(int $perPage = 15)
    {
        return PermissionRole::paginate($perPage);
    }

    public function find(int $id): ?PermissionRole
    {
        return PermissionRole::find($id);
    }

    public function create(array $data): PermissionRole
    {
        return PermissionRole::create($data);
    }

    public function update(PermissionRole $permissionRole, array $data): PermissionRole
    {
        $permissionRole->update($data);
        return $permissionRole->refresh();
    }

    public function delete(PermissionRole $permissionRole): ?bool
    {
        return $permissionRole->delete();
    }
}
