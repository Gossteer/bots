<?php

declare(strict_types=1);

namespace App\Entity\Telegram;

use App\Repository\Telegram\TelegramCommandMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: TelegramCommandMessageRepository::class)]
#[ORM\Table(options: [
    'comment' => 'связь телеграмм команды и телеграмм сообщения',
])]
class TelegramCommandMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: TelegramCommand::class, inversedBy: 'telegramCommandMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private TelegramCommand $telegramCommand;

    #[ORM\ManyToOne(targetEntity: TelegramMessage::class, inversedBy: 'telegramCommandMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private TelegramMessage $telegramMessage;

    #[ORM\Column(type: Types::INTEGER, options: [
        'default' => 0,
        'comment' => 'Порядок отправки команд',
    ])]
    private int $sortOrder = 0;

    #[ORM\Column(type: Types::BOOLEAN, options: [
        'default' => false,
        'comment' => 'обязательно отправлять после ожидания',
    ])]
    private bool $mustWait = false;

    #[ORM\Column(type: Types::INTEGER, nullable: false, options: [
        'default' => 0,
        'comment' => 'время ожидание в секундах',
    ])]
    private int $waitSeconds = 0;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: [
        'comment' => 'Дата и время создания',
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    #[Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: [
        'comment' => 'Дата и время обновления',
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    #[Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTelegramCommand(): TelegramCommand
    {
        return $this->telegramCommand;
    }

    public function setTelegramCommand(TelegramCommand $telegramCommand): self
    {
        $this->telegramCommand = $telegramCommand;
        return $this;
    }

    public function getTelegramMessage(): TelegramMessage
    {
        return $this->telegramMessage;
    }

    public function setTelegramMessage(TelegramMessage $telegramMessage): self
    {
        $this->telegramMessage = $telegramMessage;
        return $this;
    }

    public function isMustWait(): bool
    {
        return $this->mustWait;
    }

    public function setMustWait(bool $mustWait): self
    {
        $this->mustWait = $mustWait;
        return $this;
    }

    public function getWaitSeconds(): int
    {
        return $this->waitSeconds;
    }

    public function setWaitSeconds(int $waitSeconds): self
    {
        $this->waitSeconds = $waitSeconds;
        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }
}
