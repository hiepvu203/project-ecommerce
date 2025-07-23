<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PermissionRoleRepository;
use App\Models\PermissionRole;

class PermissionRoleService
{
    public function __construct(
        protected PermissionRoleRepository $permissionRoleRepository
    ) {}

    public function list(int $perPage = 15)
    {
        return $this->permissionRoleRepository->all($perPage);
    }

    public function create(array $data): PermissionRole
    {
        return $this->permissionRoleRepository->create($data);
    }

    public function update(PermissionRole $permissionRole, array $data): PermissionRole
    {
        return $this->permissionRoleRepository->update($permissionRole, $data);
    }

    public function delete(PermissionRole $permissionRole): ?bool
    {
        return $this->permissionRoleRepository->delete($permissionRole);
    }
}
