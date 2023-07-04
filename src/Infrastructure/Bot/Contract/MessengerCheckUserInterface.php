<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Infrastructure\Bot\Dto\MessengerUserDto;

interface MessengerCheckUserInterface
{
    /**
     * Проверка пользователя мессенджера
     */
    public function checkUser(MessengerUserDto $messengerUserDto): bool;
}
