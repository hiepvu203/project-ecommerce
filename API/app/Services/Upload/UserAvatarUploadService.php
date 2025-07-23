<?php

declare(strict_types=1);

namespace App\Services\Upload;

use App\Models\User;
use App\Services\Upload\CloudinaryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UserAvatarUploadService
{
    public function __construct(protected CloudinaryService $cloudinaryService) {}

    public function upload(User $user, UploadedFile $file): string
    {
        $folder = 'avatars/user_' . $user->id;

        $this->deleteOldAvatar($user);

        $url = $this->cloudinaryService->uploadImage($file, $folder);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['avatar' => $url]
        );

        return $url;
    }

    protected function deleteOldAvatar(User $user): void
    {
        if ($user->profile && $user->profile->avatar) {
            try {
                $this->cloudinaryService->deleteImage($user->profile->avatar);
            } catch (\Exception $e) {
                Log::error('Failed to delete old avatar: ' . $e->getMessage());
            }
        }
    }
}
