<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRoleRequest;
use App\Services\PermissionRoleService;
use App\Models\PermissionRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\Admin\PermissionRoleUpdateRequest;
use App\Http\Resources\PermissionRoleResource;

class PermissionRoleController extends Controller
{
    public function __construct(
        protected PermissionRoleService $permissionRoleService
    ) {}

    public function index()
    {
        return ApiResponse::success($this->permissionRoleService->list(), 'Get list successfully!', 200, [], PermissionRoleResource::class);
    }

    public function store(PermissionRoleRequest $request)
    {
        $permissionRole = $this->permissionRoleService->create($request->validated());
        return ApiResponse::success(['permission_role' => new PermissionRoleResource($permissionRole)], 'PermissionRole created successfully.', 201);
    }

    public function update(PermissionRoleUpdateRequest $request, PermissionRole $id)
    {
        $updatePermissionRole = $this->permissionRoleService->update($id, $request->validated());
        return ApiResponse::success(['permission_role' => new PermissionRoleResource($updatePermissionRole)], 'PermissionRole updated successfully.');
    }

    public function destroy(PermissionRole $id)
    {
        $this->permissionRoleService->delete($id);
        return ApiResponse::success(null, 'PermissionRole deleted successfully.');
    }
}
