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

/**
 * @OA\Tag(
 *     name="Admin / Decentralization / Permissions",
 *     description="Dành cho quản lý quyền truy cập hệ thống (Admin-only CRUD for system permissions)"
 * )
 */
class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    /**
     * List all permissions
     *
     * @OA\Get(
     *     path="/api/v1/admin/decentralization/permissions",
     *     operationId="listPermissions",
     *     tags={"Admin / Decentralization / Permissions"},
     *     summary="Trả về danh sách tất cả quyền truy cập hệ thống.",
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
     *                 @OA\Items(ref="#/components/schemas/PermissionResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ApiResponse::success($this->permissionService->list(), 'Get list successfully!', 200, [], PermissionResource::class);
    }

    /**
     * Create a new permission
     *
     * @OA\Post(
     *     path="/api/v1/admin/decentralization/permissions",
     *     operationId="storePermission",
     *     tags={"Admin / Decentralization / Permissions"},
     *     summary="Tạo một quyền truy cập mới",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PermissionRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="permission", ref="#/components/schemas/PermissionResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Validation error")
     * )
     */
    public function store(PermissionRequest $request)
    {
        $permission = $this->permissionService->create($request->validated());
        return ApiResponse::success(['permission' => new PermissionResource($permission)], 'Permission created successfully.', 201);
    }

    /**
     * Update a permission
     *
     * @OA\Put(
     *     path="/api/v1/admin/decentralization/permissions/{permission}",
     *     operationId="updatePermission",
     *     tags={"Admin / Decentralization / Permissions"},
     *     summary="Cập nhật một quyền truy cập",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PermissionUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission updated successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="permission", ref="#/components/schemas/PermissionResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Permission not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(PermissionUpdateRequest $request, Permission $id)
    {
        $updatePermission = $this->permissionService->update($id, $request->validated());
        return ApiResponse::success(['permission' => new PermissionResource($updatePermission)], 'Permission updated successfully.');
    }

    /**
     * Delete a permission
     *
     * @OA\Delete(
     *     path="/api/v1/admin/decentralization/permissions/{permission}",
     *     operationId="deletePermission",
     *     tags={"Admin / Decentralization / Permissions"},
     *     summary="Delete a permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="permission",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permission deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Permission not found")
     * )
     */
    public function destroy(Permission $id)
    {
        $this->permissionService->delete($id);
        return ApiResponse::success(null, 'Permission deleted successfully.');
    }
}
