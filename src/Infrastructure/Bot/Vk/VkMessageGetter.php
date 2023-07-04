<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Vk;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\CommandNotFoundException;
use App\Entity\Vk\VkCommand;
use App\Infrastructure\Bot\Contract\MessengerMessagesGetterInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessageDto;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;
use Doctrine\ORM\EntityManagerInterface;

class VkMessageGetter implements MessengerMessagesGetterInterface
{
    public function __construct(
        private readonly EntityManagerInterface $defaultEntityManager
    ) {
    }

    public function getMessages(string $command, BotDto $botDto): array
    {
        $messages = [];
        $command = $this->getCommand($command, $botDto);
        if (!$command) {
            return $messages;
        }

        /** @var \App\Entity\Vk\VkCommandMessage $commandMessage */
        foreach ($command->getVkCommandMessages() as $commandMessage) {
            /** @var \App\Entity\Vk\VkMessage|null $message */
            $message = $commandMessage->getVkMessage();
            if ($message) {
                $messages[] = new MessengerMessageDto(
                    new MessageDto(
                        $message->getId(),
                        $message->getVkMessengerMethod()->getName(),
                        $message->getData()
                    ),
                    MessengerTypeEnum::Vk,
                    $commandMessage->isMustWait(),
                    $commandMessage->getWaitSeconds()
                );
            }
        }
        return $messages;
    }

    public function neededSkipWaitingMessages(string $command, BotDto $botDto): bool
    {
        return $this->getCommand($command, $botDto)?->getIsNeededSkipWaitMessages() ?? false;
    }

    /**
     * @throws \App\Domain\SharedCore\Exception\Bot\CommandNotFoundException
     */
    protected function getCommand(string $commandText, BotDto $botDto): ?VkCommand
    {
        /** @var \App\Repository\Vk\VkCommandRepository $commandRepository */
        $commandRepository = $this->defaultEntityManager->getRepository(VkCommand::class);
        try {
            $command = $commandRepository->getMessengerCommandByName($commandText, $botDto);
        } catch (\Throwable $th) {
            throw new CommandNotFoundException(previous: $th);
        }
        return $command;
    }
}
