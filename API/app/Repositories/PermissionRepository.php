<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository
{
    public function all(int $perPage = 15)
    {
        return Permission::paginate($perPage);
    }

    public function find(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);
        return $permission->refresh();
    }

    public function delete(Permission $permission): ?bool
    {
        return $permission->delete();
    }
}
