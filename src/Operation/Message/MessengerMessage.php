<?php

declare(strict_types=1);

namespace App\Operation\Message;

use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;
use App\Infrastructure\Bot\Dto\MessengerUserDto;

class MessengerMessage
{
    public function __construct(
        protected readonly MessengerMessageDto $messengerMessageDto,
        protected readonly MessengerUserDto $messengerUserDto,
        protected readonly BotDto $botDto
    ) {
    }

    public function getMessengerUserDto(): MessengerUserDto
    {
        return $this->messengerUserDto;
    }

    public function getBotDto(): BotDto
    {
        return $this->botDto;
    }

    public function getMessengerMessageDto(): MessengerMessageDto
    {
        return $this->messengerMessageDto;
    }
}
