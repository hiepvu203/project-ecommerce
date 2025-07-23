<?php

declare(strict_types=1);

namespace App\Enums;

enum DocumentTypeEnum: string
{
    case CCCD = 'CCCD';
    case BUSINESS_LICENSE = 'Giấy phép kinh doanh';
    case TAX_REGISTRATION = 'Đăng ký thuế';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
