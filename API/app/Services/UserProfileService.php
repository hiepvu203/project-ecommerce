<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserProfileRepository;
use Illuminate\Support\Facades\Hash;

class UserProfileService
{
    public function __construct(
        protected UserProfileRepository $profileRepo
    ) {}

    public function getUserWithProfile(User $user): User
    {
        return $this->profileRepo->getUserWithProfile($user->id);
    }

    public function createProfile(User $user)
    {
        return $this->profileRepo->updateOrCreate($user->id, [
            'user_id' => $user->id,
        ]);
    }

    public function updateProfile(User $user, array $data)
    {
        return $this->profileRepo->updateOrCreate($user->id, $data);
    }

    public function changePassword($user, string $oldPassword, string $newPassword): bool
    {
        if (!Hash::check($oldPassword, $user->password)) {
            return false;
        }

        $user->password = $newPassword;
        $user->save();
        return true;
    }
}
