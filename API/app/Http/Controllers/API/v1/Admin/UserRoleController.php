<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRoleRequest;
use App\Services\UserRoleService;
use App\Models\UserRole;
use App\Helpers\ApiResponse;
use App\Http\Requests\Admin\UserRoleUpdateRequest;
use App\Http\Resources\UserRoleResource;

class UserRoleController extends Controller
{
    public function __construct(
        protected UserRoleService $userRoleService
    ) {}

    public function index()
    {
        return ApiResponse::success($this->userRoleService->list(), 'Get list successfully!', 200, [], UserRoleResource::class);
    }

    public function store(UserRoleRequest $request)
    {
        $userRole = $this->userRoleService->create($request->validated());
        return ApiResponse::success(['user_role' => new UserRoleResource($userRole)], 'UserRole created successfully.', 201);
    }

    public function update(UserRoleUpdateRequest $request, UserRole $id)
    {
        $updateUserRole = $this->userRoleService->update($id, $request->validated());
        return ApiResponse::success(['user_role' => new UserRoleResource($updateUserRole)], 'UserRole updated successfully.');
    }

    public function destroy(UserRole $id)
    {
        $this->userRoleService->delete($id);
        return ApiResponse::success(null, 'UserRole deleted successfully.');
    }
}
