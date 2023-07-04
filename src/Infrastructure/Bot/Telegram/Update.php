<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

use TelegramBot\Api\Types\CallbackQuery;
use TelegramBot\Api\Types\Message;

class Update extends \TelegramBot\Api\Types\Update
{
    public function getCallbackQuery(): ?CallbackQuery
    {
        return parent::getCallbackQuery();
    }

    public function getMessage(): ?Message
    {
        return parent::getMessage();
    }
}
