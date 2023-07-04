<?php

declare(strict_types=1);

namespace App\Entity\Vk;

use App\Repository\Vk\VkBotGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: VkBotGroupRepository::class)]
#[ORM\UniqueConstraint(columns: ['name'])]
#[ORM\Table(options: [
    'comment' => 'Группа VK ботов',
])]
class VkBotGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false
    )]
    private int $id;

    #[ORM\Column(
        type: Types::STRING,
        length: 255,
        options: [
            'comment' => 'Название группы',
        ]
    )]
    private string $name;

    #[ORM\Column(
        type: Types::BOOLEAN,
        nullable: false,
        options: [
            'comment' => 'Признак активности группы',
        ]
    )]
    private bool $isActive;

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
     * @var Collection<int, VkCommand>
     */
    #[ORM\OneToMany(
        mappedBy: 'vkBotGroup',
        targetEntity: VkCommand::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $vkCommands;

    /**
     * @var Collection<int, VkBot>
     */
    #[ORM\OneToMany(
        mappedBy: 'vkBotGroup',
        targetEntity: VkBot::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $vkBots;

    public function __construct()
    {
        $this->vkCommands = new ArrayCollection();
        $this->vkBots = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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
        return $this->name . ' #' . $this->id;
    }

    /**
     * @return Collection<int, VkCommand>
     */
    public function getVkCommands(): Collection
    {
        return $this->vkCommands;
    }

    public function addVkCommand(VkCommand $vkCommand): self
    {
        if (!$this->vkCommands->contains($vkCommand)) {
            $this->vkCommands[] = $vkCommand;
            $vkCommand->setVkBotGroup($this);
        }
        return $this;
    }

    public function removeVkCommand(VkCommand $vkCommand): self
    {
        $this->vkCommands->removeElement($vkCommand);
        return $this;
    }

    /**
     * @return Collection<int, VkBot>
     */
    public function getVkBots(): Collection
    {
        return $this->vkBots;
    }

    public function addVkBot(VkBot $vkBot): self
    {
        if (!$this->vkBots->contains($vkBot)) {
            $this->vkBots[] = $vkBot;
            $vkBot->setVkBotGroup($this);
        }
        return $this;
    }

    public function removeVkBot(VkBot $vkBot): self
    {
        $this->vkBots->removeElement($vkBot);
        return $this;
    }
}
