<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Customer;

use App\Exceptions\BusinessException;
use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ForgotPasswordRequest;
use App\Http\Requests\Customer\RegisterRequest;
use App\Http\Requests\Customer\LoginRequest;
use App\Helpers\ApiResponse;
use App\Http\Requests\Customer\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/signup",
     *     summary="Đăng ký tài khoản mới",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Nguyen Van A"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đăng ký thành công, vui lòng xác thực email!"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ"
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        $user->sendEmailVerificationNotification();
        return ApiResponse::success(['user' => new UserResource($user)], 'Đăng ký thành công, vui lòng xác thực email!', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Đăng nhập",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful!"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Sai thông tin đăng nhập"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {

            $result = $this->authService->login($request->email, $request->password);
            return ApiResponse::success([
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'expires_in' => $result['expires_in'],
                'expired_at' => $result['expired_at'],
            ],
                'Login successful!');
        } catch (UnauthorizedException | BusinessException $e) {
            return ApiResponse::fail(null, $e->getMessage(), 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Đăng xuất",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful!"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Logout failed"
     *     )
     * )
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ApiResponse::success(null, 'Logout successful!');
        } catch (JWTException $e) {
            return ApiResponse::fail(null, 'Logout failed', 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/tokens/refresh",
     *     summary="Làm mới token",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Refresh token successful!"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Refresh token failed. Please try again!"
     *     )
     * )
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return ApiResponse::success(['token' => $newToken], 'Refresh token successful!');
        } catch (JWTException $e) {
            return ApiResponse::fail(null, 'Refresh token failed. Please try again!', 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/password/forgot",
     *     summary="Gửi email quên mật khẩu",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link sent successfully!"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to send password reset link."
     *     )
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? ApiResponse::success(null, 'Password reset link sent successfully!')
            : ApiResponse::fail(null, 'Failed to send password reset link.', 500);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/password/reset",
     *     summary="Đặt lại mật khẩu",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password","password_confirmation","token"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
     *             @OA\Property(property="token", type="string", example="reset-token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully!"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to reset password."
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'));
        return $result === Password::PASSWORD_RESET
            ? ApiResponse::success(null, 'Password reset successfully!')
            : ApiResponse::fail(null, 'Failed to reset password.', 500);
    }
}
