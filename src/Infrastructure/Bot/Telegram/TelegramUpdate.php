<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;
use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerUserDto;
use TelegramBot\Api\Types\Message;

class TelegramUpdate implements MessengerUpdateInterface
{
    protected MessengerUserDto $messengerUserDto;

    public function __construct(
        private readonly Update $update,
        private readonly BotDto $botDto
    ) {
    }

    public function getText(): ?string
    {
        return $this->getMessage()->getText();
    }

    public function getCallbackData(): ?string
    {
        return $this->update->getCallbackQuery()?->getData();
    }

    public function getMessageId(): string
    {
        return (string)$this->getMessage()->getMessageId();
    }

    public function getMessengerType(): MessengerTypeEnum
    {
        return MessengerTypeEnum::Telegram;
    }

    /**
     * @throws NotDefineMessageMessengerException
     */
    protected function getMessage(): Message
    {
        return match (true) {
            $this->update->getMessage() !== null => $this->update->getMessage(),
            $this->update->getCallbackQuery() !== null => $this->update->getCallbackQuery()->getMessage(),
            default => throw new NotDefineMessageMessengerException()
        };
    }

    /**
     * @throws NotDefineMessageMessengerException
     */
    protected function getFrom(): int
    {
        return match (true) {
            $this->update->getMessage() !== null => $this->update->getMessage()->getFrom()->getId(),
            $this->update->getCallbackQuery() !== null => $this->update->getCallbackQuery()->getFrom()->getId(),
            default => throw new NotDefineMessageMessengerException()
        };
    }

    public function getMessengerUserDto(): MessengerUserDto
    {
        return $this->messengerUserDto ??= new MessengerUserDto(
            (string)$this->getFrom()
        );
    }

    public function getBotDto(): BotDto
    {
        return $this->botDto;
    }
}
