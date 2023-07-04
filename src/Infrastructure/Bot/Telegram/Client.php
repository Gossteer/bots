<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

class Client extends \TelegramBot\Api\Client
{
    protected const HEADER_SECRET_TOKEN = 'X-Telegram-Bot-Api-Secret-Token';

    public function __construct(
        string $token,
        ?string $trackerToken = null,
    ) {
        parent::__construct($token, $trackerToken);
    }

    /**
     * Получение секретного токена из хедера реквеста
     *
     * @throws \Exception
     * @see https://core.telegram.org/bots/api#setwebhook
     */
    public static function checkSecretToken(): string
    {
        return getallheaders()[self::HEADER_SECRET_TOKEN]
            ?? throw new \Exception('Ключ проверки не передан');
    }
}
