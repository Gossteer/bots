<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Domain\SharedCore\Exception\Bot\CommandNotFoundException;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;

interface MessengerMessagesGetterInterface
{
    /**
     * Получение сообщений по команде
     *
     * @return MessengerMessageDto[]
     * @throws CommandNotFoundException
     */
    public function getMessages(string $command, BotDto $botDto): array;

    /**
     * Необходимо ли удалять уже ожидающие отправки необязательные сообщения
     *
     * @throws CommandNotFoundException
     */
    public function neededSkipWaitingMessages(string $command, BotDto $botDto): bool;
}
