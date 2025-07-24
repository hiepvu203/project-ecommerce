<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateStaffRequest;
use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Services\StaffService;

/**
 * @OA\Tag(
 *     name="Admin / Staff",
 *     description="Admin-only endpoints to manage system staff accounts"
 * )
 */
class StaffController extends Controller
{
    public function __construct(
        protected StaffService $staffService
    ) {}

    /**
     * Create a new staff account
     *
     * @OA\Post(
     *     path="/api/v1/admin/staffs",
     *     operationId="storeStaff",
     *     tags={"Admin / Staff"},
     *     summary="Create a new staff user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateStaffRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Account created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Account created successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CreateStaffRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = $this->staffService->createStaff($validated);
            return ApiResponse::success(['user' => new UserResource($user)], 'Account created successfully.', 201);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * List all system staff
     *
     * @OA\Get(
     *     path="/api/v1/admin/staffs",
     *     operationId="listStaff",
     *     tags={"Admin / Staff"},
     *     summary="Get list of system staff",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="System staff list",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="System staff list"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index ()
    {
        return ApiResponse::success($this->staffService->getSystemStaff(), 'System staff list', 200);
    }
}
