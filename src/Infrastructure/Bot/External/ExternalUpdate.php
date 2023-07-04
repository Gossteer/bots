<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\External;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerUserDto;

class ExternalUpdate implements MessengerUpdateInterface
{
    protected MessengerUserDto $messengerUserDto;

    public function __construct(
        private readonly string $userId,
        private readonly MessengerTypeEnum $messengerTypeEnum,
        private readonly string $command,
        private readonly BotDto $botDto
    ) {
    }

    public function getText(): ?string
    {
        return $this->command;
    }

    public function getCallbackData(): ?string
    {
        return $this->command;
    }

    public function getMessageId(): string
    {
        return '';
    }

    public function getMessengerType(): MessengerTypeEnum
    {
        return $this->messengerTypeEnum;
    }

    public function getMessengerUserDto(): MessengerUserDto
    {
        return $this->messengerUserDto ??= new MessengerUserDto(
            $this->userId
        );
    }

    public function getBotDto(): BotDto
    {
        return $this->botDto;
    }
}
