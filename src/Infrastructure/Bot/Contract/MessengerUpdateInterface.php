<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerUserDto;

interface MessengerUpdateInterface
{
    /**
     * Получение теста сообщения
     *
     * @throws NotDefineMessageMessengerException
     */
    public function getText(): ?string;

    /**
     * Получение callback данных, если таковые есть
     */
    public function getCallbackData(): ?string;

    /**
     * Получение типа мессенджера
     */
    public function getMessengerType(): MessengerTypeEnum;

    /**
     * Получение id сообщения
     *
     * @throws NotDefineMessageMessengerException
     */
    public function getMessageId(): string;

    /**
     * Получение пользователя мессенджера
     *
     * @throws NotDefineMessageMessengerException
     */
    public function getMessengerUserDto(): MessengerUserDto;

    /**
     * Получение бота от которого пришло сообщение
     */
    public function getBotDto(): BotDto;
}
