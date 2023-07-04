<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Domain\SharedCore\Exception\Bot\CommandNotFoundException;
use App\Domain\SharedCore\Exception\Bot\MessengerUserException;
use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;

interface MessengerHandlerInterface
{
    /**
     * Обработка пришедшего сообщения из мессенджера
     *
     * @throws CommandNotFoundException
     * @throws MessengerUserException
     * @throws NotDefineMessageMessengerException
     * @throws BotNotFoundException
     */
    public function handle(MessengerUpdateInterface $messengerUpdate): void;
}
