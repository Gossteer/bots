<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Vk;

use App\Infrastructure\Bot\Contract\MessengerSenderInterface;
use VK\Client\VKApiClient;

class BotApi implements MessengerSenderInterface
{
    private VKApiClient $api;

    public function __construct(
        private readonly string $accessKey
    ) {
        $this->api = new VKApiClient();
    }

    public function send(string $method, array $data): array|bool
    {
        $this->api->messages()->send($this->accessKey, $data);
        return true;
    }
}
