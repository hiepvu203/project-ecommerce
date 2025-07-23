<?php

declare(strict_types=1);

namespace App\Services\Upload;

use App\Services\Upload\CloudinaryService;
use Illuminate\Http\UploadedFile;

class ProductImageService
{
    public function __construct(protected CloudinaryService $cloudinaryService) {}

   public function upload(int $productId, UploadedFile $file): string
    {
        $folder = 'products/' . $productId;
        return $this->cloudinaryService->uploadImage($file, $folder);
    }
}
