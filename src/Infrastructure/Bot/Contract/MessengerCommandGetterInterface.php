<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;

interface MessengerCommandGetterInterface
{
    /**
     * Получить команду по пришедшему сообщению
     *
     * @throws NotDefineMessageMessengerException
     */
    public function getCommand(MessengerUpdateInterface $messengerUpdate): ?string;
}
