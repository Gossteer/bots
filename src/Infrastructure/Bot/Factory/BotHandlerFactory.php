<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Factory;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\MessengerNotFoundException;
use App\Infrastructure\Bot\Contract\MessengerHandlerInterface;
use App\Infrastructure\Bot\Telegram\TelegramHandler;
use App\Infrastructure\Bot\Vk\VkHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class BotHandlerFactory implements ServiceSubscriberInterface
{
    public function __construct(
        private readonly ContainerInterface $locator
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws MessengerNotFoundException
     */
    public function getBotHandler(MessengerTypeEnum $typeEnum): MessengerHandlerInterface
    {
        /** @phpstan-ignore-next-line */
        if ($this->locator->has($typeEnum->value)) {
            $messenger = $this->locator->get($typeEnum->value);
            if ($messenger instanceof MessengerHandlerInterface) {
                return $messenger;
            }
        }
        throw new MessengerNotFoundException($typeEnum);
    }

    public static function getSubscribedServices(): array
    {
        return [
            MessengerTypeEnum::Telegram->value => TelegramHandler::class,
            MessengerTypeEnum::Vk->value => VkHandler::class,
        ];
    }
}
