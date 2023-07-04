<?php

declare(strict_types=1);

namespace App\Operation\MessageHandler;

use App\Domain\SharedCore\Exception\Bot\MessengerNotFoundException;
use App\Infrastructure\Bot\Factory\BotSenderFactory;
use App\Operation\Message\MessengerMessage;
use App\Operation\Message\MessengerMessageDelay;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidJsonException;

#[AsMessageHandler]
class MessengerMessageHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly BotSenderFactory $botSenderFactory
    ) {
    }

    /**
     * Отправка сообщений в мессенджеры
     *
     * @throws MessengerNotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws HttpException
     * @throws InvalidJsonException
     * @throws \Throwable
     */
    public function __invoke(MessengerMessage $messengerMessage): void
    {
        $this->logger?->info('Начали отправку сообщения в очередь', [
            'messengerMessage' => $messengerMessage,
        ]);

        try {
            $messengerMessageDto = $messengerMessage->getMessengerMessageDto();
            $messageDto = $messengerMessageDto->getMessageDto();
            $this->botSenderFactory
                ->getBotHandler(
                    $messengerMessageDto->getMessengerTypeEnum(),
                    $messengerMessage->getBotDto()
                )
                ->send(
                    $messageDto->getMethod(),
                    $messageDto->getData()
                );
        } catch (\Throwable $th) {
            $this->logger?->error($th->getMessage(), [
                'exception' => $th,
                'messengerMessage' => $messengerMessage,
            ]);
            throw $th;
        }
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws InvalidJsonException
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws HttpException
     * @throws \Throwable
     * @throws MessengerNotFoundException
     */
    public function sendDelayMessage(MessengerMessageDelay $messengerMessage): void
    {
        $this($messengerMessage->getMessengerMessage());
    }

    /**
     * @return iterable<array<string, string>|string>
     */
    public static function getHandledMessages(): iterable
    {
        yield MessengerMessageDelay::class => [
            'method' => 'sendDelayMessage',
        ];
        yield MessengerMessage::class;
    }
}
