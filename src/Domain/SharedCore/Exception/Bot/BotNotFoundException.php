<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Bot;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;

class BotNotFoundException extends BotException
{
    public function __construct(
        string $secretToken,
        MessengerTypeEnum $messengerTypeEnum,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            'Не найден ' . $messengerTypeEnum->value . 'bot с секретным ключом: ' . $secretToken,
            $code,
            $previous
        );
    }
}
