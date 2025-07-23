<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\UserProfile;

class UserProfileRepository
{
    public function findByUserId(int $userId): ?UserProfile
    {
        return UserProfile::where('user_id', $userId)->first();
    }

    public function updateOrCreate(int $userId, array $data): UserProfile
    {
        return UserProfile::updateOrCreate(['user_id' => $userId],$data);
    }

    public function getUserWithProfile(int $userId): User
    {
        return User::with('profile')->findOrFail($userId);
    }
}
