<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Vk;

use App\Infrastructure\Bot\Dto\BotDto;
use App\Operation\Message\MessengerUpdate;
use Symfony\Component\Messenger\MessageBusInterface;
use VK\CallbackApi\VKCallbackApiHandler;

class CallbackApiHandler extends VKCallbackApiHandler
{
    public function __construct(
        protected readonly MessageBusInterface $bus,
        private readonly BotDto $bot
    ) {
    }

    /**
     * @param array<string,string> $object
     */
    public function messageNew(int $groupId, ?string $secret, array $object): void
    {
        $this->bus->dispatch(
            new MessengerUpdate(
                new VkUpdate($object, $this->bot)
            )
        );
    }
}
