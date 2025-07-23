<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRoleRepository;
use App\Models\UserRole;

class UserRoleService
{
    public function __construct(
        protected UserRoleRepository $userRoleRepository
    ) {}

    public function list(int $perPage = 15)
    {
        return $this->userRoleRepository->all($perPage);
    }

    public function create(array $data): UserRole
    {
        return $this->userRoleRepository->create($data);
    }

    public function update(UserRole $userRole, array $data): UserRole
    {
        return $this->userRoleRepository->update($userRole, $data);
    }

    public function delete(UserRole $userRole): ?bool
    {
        return $this->userRoleRepository->delete($userRole);
    }
}
