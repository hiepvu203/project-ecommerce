<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepo
    ) {}

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepo->createCustomer($data);
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password))
            throw new UnauthorizedException('Incorrect email or password.');

        if (!$user->hasVerifiedEmail())
            throw new BusinessException('Email not verified.');

        $ttl = JWTAuth::factory()->getTTL();
        $token = JWTAuth::fromUser($user);

        $expiresIn = $ttl * 60;
        $expiredAt = now()->addMinutes($ttl)->timestamp;

        return [
            'user' => $user,
            'token' => $token,
            'expires_in' => $expiresIn,
            'expired_at' => $expiredAt,
        ];
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function sendResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    public function resetPassword(array $data): string
    {
        return Password::reset(
            $data,
            function ($user, string $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
    }
}
