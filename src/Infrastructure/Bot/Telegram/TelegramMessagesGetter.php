<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\CommandNotFoundException;
use App\Entity\Telegram\TelegramCommand;
use App\Infrastructure\Bot\Contract\MessengerMessagesGetterInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessageDto;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;
use App\Repository\Telegram\TelegramCommandRepository;
use Doctrine\ORM\EntityManagerInterface;

class TelegramMessagesGetter implements MessengerMessagesGetterInterface
{
    public function __construct(
        private readonly EntityManagerInterface $defaultEntityManager
    ) {
    }

    public function neededSkipWaitingMessages(string $command, BotDto $botDto): bool
    {
        return $this->getCommand($command, $botDto)?->isNeededSkipWaitMessages() ?? false;
    }

    /**
     * @throws CommandNotFoundException
     */
    protected function getCommand(string $command, BotDto $botDto): ?TelegramCommand
    {
        /** @var TelegramCommandRepository $commandRepository */
        $commandRepository = $this->defaultEntityManager->getRepository(TelegramCommand::class);
        try {
            $command = $commandRepository->getMessengerCommandByName($command, $botDto);
        } catch (\Throwable $th) {
            throw new CommandNotFoundException(previous: $th);
        }
        return $command;
    }

    public function getMessages(string $command, BotDto $botDto): array
    {
        $messages = [];
        $command = $this->getCommand($command, $botDto);
        if (!$command) {
            return $messages;
        }

        foreach ($command->getTelegramCommandMessage() as $commandMessage) {
            $message = $commandMessage->getTelegramMessage();
            $messages[] = new MessengerMessageDto(
                new MessageDto(
                    $message->getId(),
                    $message->getTelegramMessengerMethod()->getName(),
                    $message->getData()
                ),
                MessengerTypeEnum::Telegram,
                $commandMessage->isMustWait(),
                $commandMessage->getWaitSeconds()
            );
        }
        return $messages;
    }
}
