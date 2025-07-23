<?php

declare(strict_types=1);

namespace App\Services\Upload;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CloudinaryService
{
    public function __construct(protected Cloudinary $cloudinary) {}

    public function uploadImage(UploadedFile $file, string $folder = 'uploads'): string
    {
        $publicId = $folder . '/' . Str::uuid();

        $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'public_id' => $publicId,
            'overwrite' => true,
            'folder' => $folder,
        ]);

        return $result['secure_url'] ?? '';
    }

    public function deleteImage(string $imageUrl): void
    {
        $path = parse_url($imageUrl, PHP_URL_PATH);
        $parts = explode('/', $path);
        $startIndex = array_search('upload', $parts) + 1;
        $relevantParts = array_slice($parts, $startIndex);
        // $filename = end($parts);
        // $folder = $parts[count($parts) - 2];
        $publicId = implode('/', array_slice($relevantParts, 0, -1)) . '/' . pathinfo(end($relevantParts), PATHINFO_FILENAME);

        $this->cloudinary->uploadApi()->destroy($publicId, [
            'invalidate' => true,
        ]);
    }
}
