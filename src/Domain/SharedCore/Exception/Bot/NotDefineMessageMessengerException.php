<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Bot;

class NotDefineMessageMessengerException extends BotException
{
    public function __construct(
        string $message = "Сообщение не определено",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
