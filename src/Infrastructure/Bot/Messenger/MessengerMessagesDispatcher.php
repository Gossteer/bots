<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Messenger;

use App\Entity\MessageDelay;
use App\Infrastructure\Bot\Contract\MessengerMessagesDispatcherInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use App\Infrastructure\Bot\Dto\MessengerMessageDto;
use App\Infrastructure\Bot\Dto\MessengerUserDto;
use App\Operation\Message\MessengerMessage;
use App\Operation\Message\MessengerMessageDelay;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;

class MessengerMessagesDispatcher implements MessengerMessagesDispatcherInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly EntityManagerInterface $defaultEntityManager,
        private readonly string $databaseQueueTable
    ) {
    }

    public function skipNoMustMessage(MessengerUserDto $messengerUserDto, BotDto $botDto): void
    {
        $databaseQueueIds = $this->defaultEntityManager->getRepository(MessageDelay::class)
            ->createQueryBuilder('md')
            ->select('md.databaseQueueId')
            ->where('md.userId = :userId')
            ->andWhere('md.botId = :botId')
            ->setParameter('botId', $botDto->getId())
            ->setParameter('userId', $messengerUserDto->getId())
            ->getQuery()->getSingleColumnResult();
        if ($databaseQueueIds) {
            try {
                $this->defaultEntityManager->getConnection()->prepare(
                    sprintf(
                        "DELETE FROM %s WHERE id IN (%s)",
                        $this->databaseQueueTable,
                        implode(',', $databaseQueueIds)
                    )
                )->executeQuery()->fetchAllAssociative();
            } catch (\Throwable $th) {
                $this->logger?->debug($th->getMessage(), [
                    'exception' => $th,
                    'databaseQueueIds' => $databaseQueueIds,
                    'messengerUserDto' => $messengerUserDto,
                ]);
            }
        }

        $this->defaultEntityManager->getRepository(MessageDelay::class)
            ->createQueryBuilder('md')
            ->where('md.userId = :userId')
            ->andWhere('md.botId = :botId')
            ->setParameter('botId', $botDto->getId())
            ->setParameter('userId', $messengerUserDto->getId())
            ->delete()->getQuery()->execute();
    }

    /**
     * @param StampInterface[] $stamps
     */
    private function dispatch(MessengerMessage|MessengerMessageDelay $message, array $stamps = []): Envelope
    {
        return $this->bus->dispatch(
            $message,
            array_merge([new DispatchAfterCurrentBusStamp()], $stamps)
        );
    }

    public function dispatchMessages(array $messages, MessengerUserDto $messengerUserDto, BotDto $botDto): void
    {
        foreach ($messages as $message) {
            match (true) {
                $message->getWaitSeconds() > 0 && $message->isMustWait() => $this->dispatch(
                    new MessengerMessageDelay(
                        new MessengerMessage(
                            $message,
                            $messengerUserDto,
                            $botDto
                        )
                    ),
                    [$this->makeDelayStamp($message->getWaitSeconds())]
                ),
                $message->getWaitSeconds() > 0 => $this->addUserDelayMessage($message, $messengerUserDto, $botDto),
                default => $this->dispatch(
                    new MessengerMessage(
                        $message,
                        $messengerUserDto,
                        $botDto
                    )
                )
            };
        }
    }

    private function makeDelayStamp(int $seconds): DelayStamp
    {
        return new DelayStamp($seconds * 1000);
    }

    private function addUserDelayMessage(
        MessengerMessageDto $message,
        MessengerUserDto $messengerUserDto,
        BotDto $botDto
    ): Envelope {
        $envelope = $this->dispatch(
            new MessengerMessageDelay(
                new MessengerMessage(
                    $message,
                    $messengerUserDto,
                    $botDto
                )
            ),
            [$this->makeDelayStamp($message->getWaitSeconds())]
        );

        /** @var TransportMessageIdStamp|null $transportMessageIdStamp */
        $transportMessageIdStamp = $envelope->last(TransportMessageIdStamp::class);
        if ($transportMessageIdStamp) {
            $messageDelay = $this->defaultEntityManager->getRepository(MessageDelay::class)->findOneBy([
                'databaseQueueId' => (int)$transportMessageIdStamp->getId(),
                'userId' => $messengerUserDto->getId(),
                'botId' => $botDto->getId(),
            ]);

            if (!$messageDelay) {
                $messageDelay = new MessageDelay();
                $messageDelay->setBotId($botDto->getId());
                $messageDelay->setUserId($messengerUserDto->getId());
                $messageDelay->setDatabaseQueueId((int)$transportMessageIdStamp->getId());
                $this->defaultEntityManager->persist($messageDelay);
                $this->defaultEntityManager->flush();
            }
        }
        return $envelope;
    }
}
