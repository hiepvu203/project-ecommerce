<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Services\PermissionService;
use App\Models\Permission;
use App\Helpers\ApiResponse;
use App\Http\Requests\Admin\PermissionUpdateRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    public function index()
    {
        return ApiResponse::success($this->permissionService->list(), 'Get list successfully!', 200, [], PermissionResource::class);
    }

    public function store(PermissionRequest $request)
    {
        $permission = $this->permissionService->create($request->validated());
        return ApiResponse::success(['permission' => new PermissionResource($permission)], 'Permission created successfully.', 201);
    }

    public function update(PermissionUpdateRequest $request, Permission $id)
    {
        $updatePermission = $this->permissionService->update($id, $request->validated());
        return ApiResponse::success(['permission' => new PermissionResource($updatePermission)], 'Permission updated successfully.');
    }

    public function destroy(Permission $id)
    {
        $this->permissionService->delete($id);
        return ApiResponse::success(null, 'Permission deleted successfully.');
    }
}
