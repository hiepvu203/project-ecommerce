<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

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

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        $user->sendEmailVerificationNotification();
        return ApiResponse::success(['user' => new UserResource($user)], 'Đăng ký thành công, vui lòng xác thực email!', 201);
    }

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

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ApiResponse::success(null, 'Logout successful!');
        } catch (JWTException $e) {
            return ApiResponse::fail(null, 'Logout failed', 401);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return ApiResponse::success(['token' => $newToken], 'Refresh token successful!');
        } catch (JWTException $e) {
            return ApiResponse::fail(null, 'Refresh token failed. Please try again!', 401);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? ApiResponse::success(null, 'Password reset link sent successfully!')
            : ApiResponse::fail(null, 'Failed to send password reset link.', 500);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'));
        return $result === Password::PASSWORD_RESET
            ? ApiResponse::success(null, 'Password reset successfully!')
            : ApiResponse::fail(null, 'Failed to reset password.', 500);
    }
}
