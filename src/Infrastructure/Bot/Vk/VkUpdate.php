<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Vk;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerUserDto;

class VkUpdate implements MessengerUpdateInterface
{
    protected const FROM_ID = 'from_id',
    ID = 'id',
    TEXT = 'text',
    PEER_ID = 'peer_id',
    PAYLOAD = 'payload',
    COMMAND = 'command';
    protected MessengerUserDto $messengerUserDto;

    /**
     * @param array<string,string>               $object
     */
    public function __construct(
        private readonly array $object,
        private readonly BotDto $botDto
    ) {
    }

    public function getText(): ?string
    {
        return $this->object[self::TEXT] ?? null;
    }

    public function getCallbackData(): ?string
    {
        $rawPayload = $this->object[self::PAYLOAD] ?? null;
        if (!empty($rawPayload)) {
            $payload = json_decode($rawPayload, true, 512, JSON_THROW_ON_ERROR);
            return $payload[self::COMMAND] ?? null;
        }
        return null;
    }

    public function getMessengerType(): MessengerTypeEnum
    {
        return MessengerTypeEnum::Vk;
    }

    public function getMessageId(): string
    {
        return (string)($this->object[self::ID] ?? '0');
    }

    public function getMessengerUserDto(): MessengerUserDto
    {
        return $this->messengerUserDto ??= new MessengerUserDto(
            (string)$this->object[self::PEER_ID]
        );
    }

    public function getBotDto(): BotDto
    {
        return $this->botDto;
    }
}
