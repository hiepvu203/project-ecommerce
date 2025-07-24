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

    /**
     * @OA\Get(
     *     path="/api/v1/customer/profile",
     *     summary="Lấy thông tin hồ sơ người dùng",
     *     tags={"Customer / Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin hồ sơ thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Get profile successful!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="profile",
     *                     ref="#/components/schemas/profile"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không xác thực"
     *     )
     * )
     */
    public function getProfile(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        if (!$profile)
            $profile = $this->userProfileService->createProfile($user);
        return ApiResponse::success(['profile' => new UserProfileResource($profile->load('user'))], 'Get profile successful!', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/customer/profile",
     *     summary="Cập nhật thông tin hồ sơ người dùng",
     *     tags={"Customer Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUserProfileRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật hồ sơ thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Updated profile successful!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="profile",
     *                     ref="#/components/schemas/profile"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Dữ liệu không hợp lệ"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không xác thực"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/customer/profile/avatar",
     *     summary="Upload avatar cho người dùng",
     *     tags={"Customer Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="Ảnh đại diện (jpg, jpeg, png, webp, max 2MB)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Upload avatar thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Avatar uploaded successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="avatar_url", type="string", example="https://.../avatar.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Upload thất bại"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Không xác thực"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/customer/profile/change-password",
     *     summary="Đổi mật khẩu người dùng",
     *     tags={"Customer Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"old_password","new_password"},
     *             @OA\Property(property="old_password", type="string", example="oldpassword123"),
     *             @OA\Property(property="new_password", type="string", example="newpassword456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đổi mật khẩu thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password changed successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Mật khẩu cũ không đúng hoặc không xác thực"
     *     )
     * )
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $success = $this->userProfileService->changePassword( $user , $request->old_password , $request->new_password);
        if (!$success)
            return ApiResponse::fail(null, 'Old password is incorrect.', 401);
        return ApiResponse::success('Password changed successfully!');
    }
}
