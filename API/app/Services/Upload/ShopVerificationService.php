<?php

declare(strict_types=1);

namespace App\Services\Upload;

use App\Enums\StatusEnum;
use App\Models\ShopVerification;
use App\Models\User;
use App\Repositories\ShopVerificationRepository;
use App\Services\Upload\CloudinaryService;
use Illuminate\Http\UploadedFile;

class ShopVerificationService
{
    public function __construct(
        protected CloudinaryService $cloudinaryService,
        protected ShopVerificationRepository $shopVerificationRepository,
    ) {}

    public function upload(User $user, UploadedFile $file): string
    {
        $folder = 'verifications/shop_' . $user->id;
        return $this->cloudinaryService->uploadImage($file, $folder);
    }

    public function submitVerification (User $user, array $data):ShopVerification{
        $shop = $user->shop;

        $frontUrl = null;
        $backUrl = null;

         if (isset($data['document_front_url']) && $data['document_front_url'] instanceof UploadedFile) {
            $frontUrl = $this->upload($user, $data['document_front_url']);
        }

        if (isset($data['document_back_url']) && $data['document_back_url'] instanceof UploadedFile) {
            $backUrl = $this->upload($user, $data['document_back_url']);
        }

        return $this->shopVerificationRepository->create([
            'shop_id'             => $shop->id,
            'document_type'       => $data['document_type'],
            'document_front_url'  => $frontUrl,
            'document_back_url'   => $backUrl,
            'status'              => StatusEnum::PENDING->value,
        ]);
    }
}
