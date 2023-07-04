<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Factory;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Encryption\DecryptException;
use App\Infrastructure\Bot\Contract\MessengerSenderInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Telegram\BotApi;
use App\Infrastructure\Bot\Vk\BotApi as VkBotApi;
use App\Infrastructure\Encryption\EncryptionInterface;

class BotSenderFactory
{
    public function __construct(
        private readonly EncryptionInterface $encryption
    ) {
    }

    /**
     * @throws DecryptException
     */
    public function getBotHandler(MessengerTypeEnum $typeEnum, BotDto $botDto): MessengerSenderInterface
    {
        return $this->getBotApiClient(
            $typeEnum,
            $this->encryption->decrypt($botDto->getToken())
        );
    }

    public function getBotApiClient(MessengerTypeEnum $typeEnum, string $token): MessengerSenderInterface
    {
        return match ($typeEnum) {
            MessengerTypeEnum::Telegram => new BotApi($token),
            MessengerTypeEnum::Vk => new VkBotApi($token)
        };
    }
}
