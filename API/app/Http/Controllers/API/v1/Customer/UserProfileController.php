<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Customer;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ChangePasswordRequest;
use App\Http\Requests\Customer\UpdateUserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use App\Services\Upload\UserAvatarUploadService;
use App\Services\UserProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function __construct(
        protected UserProfileService $userProfileService
    ) {}
    public function getProfile(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        if (!$profile)
            $profile = $this->userProfileService->createProfile($user);
        return ApiResponse::success(['profile' => new UserProfileResource($profile->load('user'))], 'Get profile successful!', 200);
    }

    public function update(UpdateUserProfileRequest $request)
    {
        try {
            $user = Auth::user();

            /** @var UserProfile $profile */
            $profile = $user->profile ?: new UserProfile(['user_id' => $user->id]);

            $data = array_filter($request->validated(), function ($value, $key) use ($request) {
                return $request->has($key) && $value !== null;
            }, ARRAY_FILTER_USE_BOTH);

            $profile = $this->userProfileService->updateProfile($user, $data);

            return ApiResponse::success(['profile' => new UserProfileResource($profile->load('user'))], 'Updated profile successful!', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update profile: ' . $e->getMessage(), 500, null);
        }
    }

    public function uploadAvatar(Request $request, UserAvatarUploadService $uploadService)
    {
        try{
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $user = Auth::guard('api')->user();
            $url = $uploadService->upload($user, $request->file('image'));

            return ApiResponse::success(['avatar_url' => $url], 'Avatar uploaded successfully!', 200);
        }catch (\Exception $e) {
            return ApiResponse::fail(null, $e->getMessage(), 400);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $success = $this->userProfileService->changePassword( $user , $request->old_password , $request->new_password);
        if (!$success)
            return ApiResponse::fail(null, 'Old password is incorrect.', 401);
        return ApiResponse::success('Password changed successfully!');
    }
}
