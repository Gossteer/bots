<?php

declare(strict_types=1);

namespace App\Entity\Telegram;

use App\Repository\Telegram\TelegramBotGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: TelegramBotGroupRepository::class)]
#[ORM\UniqueConstraint(columns: ['name'])]
#[ORM\Table(options: [
    'comment' => 'группа телеграмм ботов',
])]
class TelegramBotGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255, options: [
        'comment' => 'название группы',
    ])]
    private string $name;

    /**
     * @var Collection<int, TelegramCommand>
     */
    #[ORM\OneToMany(
        mappedBy: 'telegramBotGroup',
        targetEntity: TelegramCommand::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $telegramCommands;

    #[ORM\Column(
        type: Types::BOOLEAN,
        options: [
            'default' => false,
            'comment' => 'Признак активности группы',
        ]
    )]
    private bool $isActive = false;

    /**
     * @var Collection<int, TelegramBot>
     */
    #[ORM\OneToMany(
        mappedBy: 'telegramBotGroup',
        targetEntity: TelegramBot::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $telegramBots;

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

    public function __construct()
    {
        $this->telegramCommands = new ArrayCollection();
        $this->telegramBots = new ArrayCollection();
    }

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, TelegramCommand>
     */
    public function getTelegramCommands(): Collection
    {
        return $this->telegramCommands;
    }

    public function addTelegramCommand(TelegramCommand $telegramCommand): self
    {
        if (!$this->telegramCommands->contains($telegramCommand)) {
            $this->telegramCommands[] = $telegramCommand;
            $telegramCommand->setTelegramBotGroup($this);
        }
        return $this;
    }

    public function removeTelegramCommand(TelegramCommand $telegramCommand): self
    {
        $this->telegramCommands->removeElement($telegramCommand);
        return $this;
    }

    /**
     * @return Collection<int, TelegramBot>
     */
    public function getTelegramBots(): Collection
    {
        return $this->telegramBots;
    }

    public function addTelegramBot(TelegramBot $telegramBot): self
    {
        if (!$this->telegramBots->contains($telegramBot)) {
            $this->telegramBots[] = $telegramBot;
            $telegramBot->setTelegramBotGroup($this);
        }
        return $this;
    }

    public function removeTelegramBot(TelegramBot $telegramBot): self
    {
        $this->telegramBots->removeElement($telegramBot);
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
