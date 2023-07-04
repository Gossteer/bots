<?php

declare(strict_types=1);

namespace App\Entity\Telegram;

use App\Repository\Telegram\TelegramCommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: TelegramCommandRepository::class)]
#[ORM\UniqueConstraint(columns: ['telegram_bot_group_id', 'name'])]
#[ORM\Table(options: [
    'comment' => 'команды для телеграмм ботов',
])]
class TelegramCommand
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, options: [
        'comment' => 'название команды',
    ])]
    private string $name;

    #[ORM\Column(type: Types::BOOLEAN, options: [
        'default' => false,
        'comment' => 'признак активности команды',
    ])]
    private bool $isActive = false;

    #[ORM\Column(type: Types::BOOLEAN, options: [
        'default' => false,
        'comment' => 'нужно ли удалять уже ожидающие отправки необязательные сообщения',
    ])]
    private bool $isNeededSkipWaitMessages = false;

    /**
     * @var Collection<int, TelegramCommandMessage>
     */
    #[ORM\OneToMany(
        mappedBy: 'telegramCommand',
        targetEntity: TelegramCommandMessage::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $telegramCommandMessages;

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

    #[ORM\ManyToOne(targetEntity: TelegramBotGroup::class, inversedBy: 'telegramCommands')]
    #[ORM\JoinColumn(nullable: false)]
    private TelegramBotGroup $telegramBotGroup;

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

    public function __construct()
    {
        $this->telegramCommandMessages = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, TelegramCommandMessage>
     */
    public function getTelegramCommandMessage(): Collection
    {
        return $this->telegramCommandMessages;
    }

    public function addTelegramCommandMessage(TelegramCommandMessage $telegramCommandMessages): self
    {
        if (!$this->telegramCommandMessages->contains($telegramCommandMessages)) {
            $this->telegramCommandMessages[] = $telegramCommandMessages;
            $telegramCommandMessages->setTelegramCommand($this);
        }
        return $this;
    }

    public function removeTelegramCommandMessage(
        TelegramCommandMessage $telegramCommandMessages
    ): self {
        $this->telegramCommandMessages->removeElement($telegramCommandMessages);
        return $this;
    }

    public function isNeededSkipWaitMessages(): bool
    {
        return $this->isNeededSkipWaitMessages;
    }

    public function setIsNeededSkipWaitMessages(bool $isNeededSkipWaitMessages): self
    {
        $this->isNeededSkipWaitMessages = $isNeededSkipWaitMessages;
        return $this;
    }

    public function getTelegramBotGroup(): TelegramBotGroup
    {
        return $this->telegramBotGroup;
    }

    public function setTelegramBotGroup(TelegramBotGroup $telegramBotGroup): self
    {
        $this->telegramBotGroup = $telegramBotGroup;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            'c: %s, b: %s',
            $this->name,
            $this->telegramBotGroup->getName()
        ) . ' #' . $this->id;
    }
}
