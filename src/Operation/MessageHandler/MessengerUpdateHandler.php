<?php

declare(strict_types=1);

namespace App\Operation\MessageHandler;

use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Domain\SharedCore\Exception\Bot\CommandNotFoundException;
use App\Domain\SharedCore\Exception\Bot\MessengerNotFoundException;
use App\Domain\SharedCore\Exception\Bot\MessengerUserException;
use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;
use App\Infrastructure\Bot\Factory\BotHandlerFactory;
use App\Operation\Message\MessengerUpdate;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MessengerUpdateHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly BotHandlerFactory $botHandlerFactory
    ) {
    }

    /**
     * Обработка сообщений из rabbit от мессенджера
     *
     * @throws CommandNotFoundException
     * @throws ContainerExceptionInterface
     * @throws MessengerNotFoundException
     * @throws MessengerUserException
     * @throws NotFoundExceptionInterface
     * @throws BotNotFoundException
     * @throws NotDefineMessageMessengerException
     * @throws \Throwable
     */
    public function __invoke(MessengerUpdate $messengerUpdate): void
    {
        $this->logger?->info('Начали обработку пришедшего сообщения', [
            'messengerUpdate' => $messengerUpdate,
        ]);

        $update = $messengerUpdate->getMessengerUpdate();
        try {
            $this->botHandlerFactory
                ->getBotHandler($update->getMessengerType())
                ->handle($update);
        } catch (\Throwable $th) {
            $this->logger?->error($th->getMessage(), [
                'exception' => $th,
                'messengerUpdate' => $messengerUpdate,
            ]);
            throw $th;
        }
    }
}
