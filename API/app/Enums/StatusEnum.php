<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case LOCKED = 'locked';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
