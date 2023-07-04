<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidJsonException;

interface MessengerSenderInterface
{
    /**
     * Отправка сообщение в мессенджер
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>|bool
     * @throws InvalidJsonException
     * @throws Exception
     * @throws HttpException
     */
    public function send(string $method, array $data): array|bool;
}
