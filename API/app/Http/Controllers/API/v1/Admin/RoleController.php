<?php

declare(strict_types= 1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index()
    {
        return ApiResponse::success($this->roleService->list(), 'Get listed successfully!', 200, [], RoleResource::class);
    }

    public function store(RoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());
        return ApiResponse::success(['role' => new RoleResource($role)], 'Role created successfully!');
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $updatedRole = $this->roleService->update($role, $request->validated());
        return ApiResponse::success(['role' => new RoleResource($updatedRole)], 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $this->roleService->delete($role);
        return ApiResponse::success(null, 'Role deleted successfully!');
    }
}
