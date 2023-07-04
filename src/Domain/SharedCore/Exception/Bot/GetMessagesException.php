<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Bot;

class GetMessagesException extends BotException
{
    public function __construct(
        string $message = "Ошибка получение сообщений для команды",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
