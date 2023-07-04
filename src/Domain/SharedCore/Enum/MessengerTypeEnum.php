<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Enum;

enum MessengerTypeEnum: string
{
    case Telegram = 'telegram';
    case Vk = 'vk';
}
