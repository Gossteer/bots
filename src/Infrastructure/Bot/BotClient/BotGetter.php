<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\BotClient;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Bot\Dto\BotDto;

class BotGetter
{
    public function __construct(
        private readonly BotGetterInterface $telegramBotGetter,
        private readonly BotGetterInterface $vkBotGetter
    ) {
    }

    /**
     * @throws BotNotFoundException
     */
    public function getBySecretToken(MessengerTypeEnum $messengerTypeEnum, string $secretToken): BotDto
    {
        return $this->getBot($messengerTypeEnum)->getBySecretToken($secretToken);
    }

    /**
     * @throws BotNotFoundException
     */
    public function getByHashSecretToken(MessengerTypeEnum $messengerTypeEnum, string $secretToken): BotDto
    {
        return $this->getBot($messengerTypeEnum)->getByHashSecretToken($secretToken);
    }

    /**
     * @throws BotNotFoundException
     */
    public function getByEncryptSecretToken(MessengerTypeEnum $messengerTypeEnum, string $encryptSecretToken): BotDto
    {
        return $this->getBot($messengerTypeEnum)->getByEncryptSecretToken($encryptSecretToken);
    }

    /**
     * @throws BotNotFoundException
     */
    public function getBotByDtoAndCommand(MessengerTypeEnum $messengerTypeEnum, BotDto $botDto, string $command): BotDto
    {
        return $this->getBot($messengerTypeEnum)->getBotByDtoAndCommand($botDto, $command);
    }

    /**
     * Получить всех ботов
     *
     * @return BotDto[]
     */
    public function getAllBots(MessengerTypeEnum $messengerTypeEnum): array
    {
        return $this->getBot($messengerTypeEnum)->getAllBots();
    }

    private function getBot(MessengerTypeEnum $messengerTypeEnum): BotGetterInterface
    {
        return match ($messengerTypeEnum) {
            MessengerTypeEnum::Telegram => $this->telegramBotGetter,
            MessengerTypeEnum::Vk => $this->vkBotGetter
        };
    }
}
