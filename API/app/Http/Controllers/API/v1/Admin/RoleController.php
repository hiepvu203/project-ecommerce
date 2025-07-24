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

/**
 * @OA\Tag(
 *     name="Admin / Decentralization / Roles",
 *     description="Admin-only endpoints to manage system roles"
 * )
 */
class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    /**
     * List all roles
     *
     * @OA\Get(
     *     path="/api/v1/admin/decentralization/roles",
     *     operationId="listRoles",
     *     tags={"Admin / Decentralization / Roles"},
     *     summary="Get list of all roles",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Get listed successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/RoleResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ApiResponse::success($this->roleService->list(), 'Get listed successfully!', 200, [], RoleResource::class);
    }

    /**
     * Create a new role
     *
     * @OA\Post(
     *     path="/api/v1/admin/decentralization/roles",
     *     operationId="storeRole",
     *     tags={"Admin / Decentralization / Roles"},
     *     summary="Store a new role",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoleRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role created successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="role", ref="#/components/schemas/RoleResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(RoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());
        return ApiResponse::success(['role' => new RoleResource($role)], 'Role created successfully!');
    }

    /**
     * Update a role
     *
     * @OA\Put(
     *     path="/api/v1/admin/decentralization/roles/{role}",
     *     operationId="updateRole",
     *     tags={"Admin / Decentralization / Roles"},
     *     summary="Update an existing role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoleUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role updated successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="role", ref="#/components/schemas/RoleResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Role not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $updatedRole = $this->roleService->update($role, $request->validated());
        return ApiResponse::success(['role' => new RoleResource($updatedRole)], 'Role updated successfully!');
    }

    /**
     * Delete a role
     *
     * @OA\Delete(
     *     path="/api/v1/admin/decentralization/roles/{role}",
     *     operationId="deleteRole",
     *     tags={"Admin / Decentralization / Roles"},
     *     summary="Delete a role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Role deleted successfully!")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function destroy(Role $role)
    {
        $this->roleService->delete($role);
        return ApiResponse::success(null, 'Role deleted successfully!');
    }
}
