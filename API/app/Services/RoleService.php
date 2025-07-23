<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Models\Role;

class RoleService
{
    public function __construct(
        protected RoleRepository $roleRepository
    ) {}

    public function list(int $perPage = 15)
    {
        return $this->roleRepository->all($perPage);
    }

    public function create(array $data): Role
    {
        return $this->roleRepository->create($data);
    }

    public function update(Role $role, array $data): Role
    {
        return $this->roleRepository->update($role, $data);
    }

    public function delete(Role $role): ?bool
    {
        return $this->roleRepository->delete($role);
    }
}
