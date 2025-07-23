<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PermissionRepository;
use App\Models\Permission;

class PermissionService
{
    public function __construct(
        protected PermissionRepository $permissionRepository
    ) {}

    public function list(int $perPage = 15)
    {
        return $this->permissionRepository->all($perPage);
    }

    public function create(array $data): Permission
    {
        return $this->permissionRepository->create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        return $this->permissionRepository->update($permission, $data);
    }

    public function delete(Permission $permission): ?bool
    {
        return $this->permissionRepository->delete($permission);
    }
}
