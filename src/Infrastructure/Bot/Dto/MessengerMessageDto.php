<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Dto;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;

class MessengerMessageDto
{
    public function __construct(
        private readonly MessageDto $messageDto,
        private readonly MessengerTypeEnum $messengerTypeEnum,
        private readonly bool $mustWait,
        private readonly int $waitSeconds
    ) {
    }

    /**
     * Не удалять из ожидания, даже после действия пользователя
     */
    public function isMustWait(): bool
    {
        return $this->mustWait;
    }

    /**
     * Задержка перед отправкой
     */
    public function getWaitSeconds(): int
    {
        return $this->waitSeconds;
    }

    public function getMessengerTypeEnum(): MessengerTypeEnum
    {
        return $this->messengerTypeEnum;
    }

    public function getMessageDto(): MessageDto
    {
        return $this->messageDto;
    }
}
