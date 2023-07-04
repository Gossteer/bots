<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\TemplateEngine;

class BaseTemplateEngine extends MessengerTemplateEngine
{
    public const USER_ID = '{user_id}';

    protected function searchReplace(): array
    {
        return [
            self::USER_ID,
        ];
    }

    protected function dataReplace(): array
    {
        return [
            $this->messengerUpdate->getMessengerUserDto()->getId(),
        ];
    }
}
