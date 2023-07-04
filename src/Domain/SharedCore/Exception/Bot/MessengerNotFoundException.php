<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Bot;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;

class MessengerNotFoundException extends BotException
{
    public function __construct(
        MessengerTypeEnum $typeEnum,
        string $message = "Мессенджер не определен",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message . ': ' . $typeEnum->value, $code, $previous);
    }
}
