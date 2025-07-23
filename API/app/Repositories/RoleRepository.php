<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function all(int $perPage = 15)
    {
        return Role::paginate($perPage);
    }

    public function find(int $id): ?Role
    {
        return Role::find($id);
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role->refresh();
    }

    public function delete(Role $role): ?bool
    {
        return $role->delete();
    }
}
