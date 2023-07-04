<?php

declare(strict_types=1);

namespace App\Infrastructure\Analytic\Client;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;

interface AnalyticsClientInterface
{
    /**
     * Отправка сообщения аналитики
     *
     * @param array<string, mixed> $data
     */
    public function send(array $data, string $eventId, MessengerTypeEnum $messengerTypeEnum): void;
}
