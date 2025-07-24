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

/**
 * @OA\Tag(
 *     name="Admin / Decentralization / Permission-Role",
 *     description="Link/unlink permissions to roles"
 * )
 */
class PermissionRoleController extends Controller
{
    public function __construct(
        protected PermissionRoleService $permissionRoleService
    ) {}

    /**
     * List all permission-role assignments
     *
     * @OA\Get(
     *     path="/api/v1/admin/decentralization/permission-roles",
     *     operationId="listPermissionRoles",
     *     tags={"Admin / Decentralization / Permission-Role"},
     *     summary="Get list of permission-role links",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Get list successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PermissionRoleResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ApiResponse::success($this->permissionRoleService->list(), 'Get list successfully!', 200, [], PermissionRoleResource::class);
    }

    /**
     * Create a new permission-role link
     *
     * @OA\Post(
     *     path="/api/v1/admin/decentralization/permission-roles",
     *     operationId="storePermissionRole",
     *     tags={"Admin / Decentralization / Permission-Role"},
     *     summary="Link a permission to a role",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PermissionRoleRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="PermissionRole created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="permission_role", ref="#/components/schemas/PermissionRoleResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(PermissionRoleRequest $request)
    {
        $permissionRole = $this->permissionRoleService->create($request->validated());
        return ApiResponse::success(['permission_role' => new PermissionRoleResource($permissionRole)], 'PermissionRole created successfully.', 201);
    }

    /**
     * Update a permission-role link
     *
     * @OA\Put(
     *     path="/api/v1/admin/decentralization/permission-roles/{id}",
     *     operationId="updatePermissionRole",
     *     tags={"Admin / Decentralization / Permission-Role"},
     *     summary="Update an existing permission-role link",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PermissionRoleUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="PermissionRole updated successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="permission_role", ref="#/components/schemas/PermissionRoleResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Link not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(PermissionRoleUpdateRequest $request, PermissionRole $id)
    {
        $updatePermissionRole = $this->permissionRoleService->update($id, $request->validated());
        return ApiResponse::success(['permission_role' => new PermissionRoleResource($updatePermissionRole)], 'PermissionRole updated successfully.');
    }

    /**
     * Delete a permission-role link
     *
     * @OA\Delete(
     *     path="/api/v1/admin/decentralization/permission-roles/{id}",
     *     operationId="deletePermissionRole",
     *     tags={"Admin / Decentralization / Permission-Role"},
     *     summary="Remove a permission-role link",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="PermissionRole deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Link not found")
     * )
     */
    public function destroy(PermissionRole $id)
    {
        $this->permissionRoleService->delete($id);
        return ApiResponse::success(null, 'PermissionRole deleted successfully.');
    }
}
