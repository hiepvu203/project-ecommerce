<?php

declare(strict_types=1);

namespace App\Services\Upload;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Services\Upload\CloudinaryService;

class ShopImageService
{
    public function __construct(protected CloudinaryService $cloudinaryService) {}

    public function upload(User $user, UploadedFile $file, string $type): string
    {
        $folder = "shops/shop_{$user->id}/{$type}";
        return $this->cloudinaryService->uploadImage($file, $folder);
    }
}
