<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateStaffRequest;
use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Services\StaffService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct(
        protected StaffService $staffService
    ) {}
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

    public function index ()
    {
        return ApiResponse::success($this->staffService->getSystemStaff(), 'System staff list', 200);
    }
}
