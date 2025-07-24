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

/**
 * @OA\Tag(
 *     name="Admin / Decentralization / UserRole",
 *     description="Admin-only endpoints to manage user-role relations"
 * )
 */
class UserRoleController extends Controller
{
    public function __construct(
        protected UserRoleService $userRoleService
    ) {}

    /**
     * List all user roles
     *
     * @OA\Get(
     *     path="/api/v1/admin/decentralization/user-roles",
     *     operationId="getUserRoles",
     *     tags={"Admin / Decentralization / UserRole"},
     *     summary="Get list of user-role relations",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Get list successfully!"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/UserRoleResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ApiResponse::success($this->userRoleService->list(), 'Get list successfully!', 200, [], UserRoleResource::class);
    }

    /**
     * Create a new user-role relation
     *
     * @OA\Post(
     *     path="/api/v1/admin/decentralization/user-roles",
     *     operationId="storeUserRole",
     *     tags={"Admin / Decentralization / UserRole"},
     *     summary="Store a new user-role relation",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRoleRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="UserRole created successfully."),
     *             @OA\Property(property="data",
     *                 @OA\Property(property="user_role", ref="#/components/schemas/UserRoleResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(UserRoleRequest $request)
    {
        $userRole = $this->userRoleService->create($request->validated());
        return ApiResponse::success(['user_role' => new UserRoleResource($userRole)], 'UserRole created successfully.', 201);
    }

    /**
     * Update a user-role relation
     *
     * @OA\Put(
     *     path="/api/v1/admin/decentralization/user-role/{id}",
     *     operationId="updateUserRole",
     *     tags={"Admin / Decentralization / UserRole"},
     *     summary="Update a user-role relation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRoleUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="UserRole updated successfully."),
     *             @OA\Property(property="data",
     *                 @OA\Property(property="user_role", ref="#/components/schemas/UserRoleResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="UserRole not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UserRoleUpdateRequest $request, UserRole $id)
    {
        $updateUserRole = $this->userRoleService->update($id, $request->validated());
        return ApiResponse::success(['user_role' => new UserRoleResource($updateUserRole)], 'UserRole updated successfully.');
    }

    /**
     * Delete a user-role relation
     *
     * @OA\Delete(
     *     path="/api/v1/admin/decentralization/user-role/{id}",
     *     operationId="deleteUserRole",
     *     tags={"Admin / Decentralization / UserRole"},
     *     summary="Delete a user-role relation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="UserRole deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="UserRole not found")
     * )
     */
    public function destroy(UserRole $id)
    {
        $this->userRoleService->delete($id);
        return ApiResponse::success(null, 'UserRole deleted successfully.');
    }
}
