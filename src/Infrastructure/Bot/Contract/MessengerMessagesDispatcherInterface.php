<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;
use App\Infrastructure\Bot\Dto\MessengerUserDto;

interface MessengerMessagesDispatcherInterface
{
    /**
     * Кладем в очередь сообщения на отправку в мессенджеры
     *
     * @param MessengerMessageDto[] $messages
     */
    public function dispatchMessages(array $messages, MessengerUserDto $messengerUserDto, BotDto $botDto): void;

    /**
     * Удаление уже ожидающих отправки необязательных сообщений
     */
    public function skipNoMustMessage(MessengerUserDto $messengerUserDto, BotDto $botDto): void;
}
