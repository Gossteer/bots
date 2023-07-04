<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MessageDelayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: MessageDelayRepository::class)]
#[ORM\UniqueConstraint(columns: ['user_id', 'database_queue_id'])]
#[ORM\Table(options: [
    'comment' => 'отложенные сообщения, которые нужно удалить при действие пользователя',
])]
class MessageDelay
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255, options: [
        'comment' => 'id пользователя мессенджера',
    ])]
    private string $userId;

    #[ORM\Column(
        type: Types::STRING,
        length: 255,
        nullable: true,
        options: [
            'comment' => 'id бота',
        ]
    )]
    private ?string $botId = null;

    #[ORM\Column(type: Types::INTEGER, options: [
        'comment' => 'id отложенной задачи',
    ])]
    private int $databaseQueueId;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: [
        'comment' => 'Дата и время создания',
    ])]
    #[Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getDatabaseQueueId(): int
    {
        return $this->databaseQueueId;
    }

    public function setDatabaseQueueId(int $databaseQueueId): self
    {
        $this->databaseQueueId = $databaseQueueId;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBotId(): ?string
    {
        return $this->botId;
    }

    public function setBotId(string $botId): self
    {
        $this->botId = $botId;
        return $this;
    }
}
