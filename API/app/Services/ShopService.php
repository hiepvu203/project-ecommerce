<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\ShopRepository;
use App\Services\Upload\ShopImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ShopService
{
    public function __construct(
        protected ShopRepository $shopRepository,
        protected ShopImageService $shopImageService
    ) {}

    public function register(array $data, User $user): Shop
    {
        if ($this->shopRepository->findByOwner($user)) {
            throw new \Exception('Account already owns a shop.');
        }

        if (isset($data['logo_url']) && $data['logo_url'] instanceof UploadedFile) {
            $data['logo_url'] = $this->shopImageService->upload($user, $data['logo_url'], 'logo');
        }

        if (isset($data['cover_image_url']) && $data['cover_image_url'] instanceof UploadedFile) {
            $data['cover_image_url'] = $this->shopImageService->upload($user, $data['cover_image_url'], 'cover');
        }

        $data['owner_id'] = $user->id;
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['status'] = StatusEnum::PENDING->value;

        return $this->shopRepository->create($data);
    }

    public function update(array $data, Shop $shop, User $user): Shop
    {
        if (isset($data['logo_url']) && $data['logo_url'] instanceof UploadedFile) {
            $data['logo_url'] = $this->shopImageService->upload($user, $data['logo_url'], 'logo');
        }

        if (isset($data['cover_image_url']) && $data['cover_image_url'] instanceof UploadedFile) {
            $data['cover_image_url'] = $this->shopImageService->upload($user, $data['cover_image_url'], 'cover');
        }

        return $this->shopRepository->update($shop, $data);
    }

    public function lockShop(Shop $shop, User $user): Shop
    {
        $shop->status = StatusEnum::LOCKED;
        $shop->save();

        $user->type = User::TYPE_CUSTOMER;
        $user->save();

        // (Tùy chọn) Xóa hoặc cập nhật role shop_owner trong bảng user_roles nếu bạn dùng RBAC

        return $shop->refresh();
    }

    public function unlockShop(Shop $shop): Shop
    {
        $shop->status = StatusEnum::ACTIVE->value;
        $shop->save();

        $user = $shop->owner;
        $user->type = User::TYPE_SHOP_OWNER;
        $user->save();

        return $shop->refresh();
    }
}
