<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Bot;

class MessengerUserException extends BotException
{
    public function __construct(
        string $message = "Ошибка при работе с пользователем",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
