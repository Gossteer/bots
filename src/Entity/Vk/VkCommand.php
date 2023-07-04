<?php

declare(strict_types=1);

namespace App\Entity\Vk;

use App\Repository\Vk\VkCommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: VkCommandRepository::class)]
#[ORM\Table(options: [
    'comment' => 'Команды для VK ботов',
])]
class VkCommand
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false
    )]
    private int $id;

    #[ORM\ManyToOne(
        targetEntity: VkBotGroup::class,
        inversedBy: 'vkCommands'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private VkBotGroup $vkBotGroup;

    #[ORM\Column(
        type: Types::STRING,
        length: 255,
        nullable: false,
        options: [
            'comment' => 'Текст команды',
        ]
    )]
    private string $name;

    #[ORM\Column(
        type: Types::BOOLEAN,
        nullable: false,
        options: [
            'comment' => 'Признак активности команды',
        ]
    )]
    private bool $isActive;

    #[ORM\Column(
        type: Types::BOOLEAN,
        nullable: false,
        options: [
            'comment' => 'Нужно ли удалять уже ожидающие отправки необязательные сообщения',
        ]
    )]
    private bool $isNeededSkipWaitMessages;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        nullable: false,
        options: [
            'comment' => 'Дата и время создания',
            'default' => 'CURRENT_TIMESTAMP',
        ]
    )]
    #[Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        nullable: false,
        options: [
            'comment' => 'Дата и время обновления',
            'default' => 'CURRENT_TIMESTAMP',
        ]
    )]
    #[Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    /**
     * @var Collection<int, VkCommandMessage>
     */
    #[ORM\OneToMany(
        mappedBy: 'vkCommand',
        targetEntity: VkCommandMessage::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $vkCommandMessages;

    public function __construct()
    {
        $this->vkCommandMessages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVkBotGroup(): VkBotGroup
    {
        return $this->vkBotGroup;
    }

    public function setVkBotGroup(VkBotGroup $botGroup): self
    {
        $this->vkBotGroup = $botGroup;

        return $this;
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

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsNeededSkipWaitMessages(): bool
    {
        return $this->isNeededSkipWaitMessages;
    }

    public function setIsNeededSkipWaitMessages(bool $isNeededSkipWaitMessages): self
    {
        $this->isNeededSkipWaitMessages = $isNeededSkipWaitMessages;

        return $this;
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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            'c: %s, b: %s',
            $this->name,
            $this->vkBotGroup->getName()
        ) . ' #' . $this->id;
    }

    /**
     * @return Collection<int, VkCommandMessage>
     */
    public function getVkCommandMessages(): Collection
    {
        return $this->vkCommandMessages;
    }

    public function addVkCommandMessages(VkCommandMessage $commandMessage): self
    {
        if (!$this->vkCommandMessages->contains($commandMessage)) {
            $this->vkCommandMessages[] = $commandMessage;
            $commandMessage->setVkCommand($this);
        }
        return $this;
    }

    public function removeVkCommandMessages(VkCommandMessage $commandMessage): self
    {
        $this->vkCommandMessages->removeElement($commandMessage);
        return $this;
    }
}
