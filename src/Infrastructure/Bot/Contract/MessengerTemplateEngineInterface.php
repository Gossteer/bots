<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;

interface MessengerTemplateEngineInterface
{
    /**
     * Установить пришедшее от пользователя сообщение
     */
    public function setMessengerUpdate(MessengerUpdateInterface $messengerUpdate): void;

    /**
     * Собранные сообщения на отправка
     *
     * @param MessengerMessageDto[] $messengerMessagesDto
     */
    public function setMessengerMessagesDto(array $messengerMessagesDto): void;

    /**
     * Применение шаблонизатора
     *
     * @return MessengerMessageDto[]
     * @throws NotDefineMessageMessengerException
     */
    public function applyTemplate(): array;
}
