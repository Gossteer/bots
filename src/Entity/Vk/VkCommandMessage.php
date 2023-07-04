<?php

declare(strict_types=1);

namespace App\Entity\Vk;

use App\Repository\Vk\VkCommandMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: VkCommandMessageRepository::class)]
#[ORM\Table(options: [
    'comment' => 'Связь команды и сообщения',
])]
class VkCommandMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false
    )]
    private int $id;

    #[ORM\ManyToOne(
        targetEntity: VkCommand::class,
        inversedBy: 'vkCommandMessages'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private VkCommand $vkCommand;

    #[ORM\ManyToOne(
        targetEntity: VkMessage::class,
        inversedBy: 'vkCommandMessages'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private VkMessage $vkMessage;

    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false,
        options: [
            'default' => 0,
            'comment' => 'Порядок отправки команд',
        ]
    )]
    private int $sortOrder = 0;

    #[ORM\Column(
        type: Types::BOOLEAN,
        nullable: false,
        options: [
            'default' => false,
            'comment' => 'обязательно отправлять после ожидания',
        ]
    )]
    private bool $mustWait = false;

    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false,
        options: [
            'default' => 0,
            'comment' => 'Время ожидание в секундах',
        ]
    )]
    private int $waitSeconds = 0;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        options: [
            'comment' => 'дата и время создания',
            'default' => 'CURRENT_TIMESTAMP',
        ]
    )]
    #[Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        options: [
            'comment' => 'Дата и время обновления',
            'default' => 'CURRENT_TIMESTAMP',
        ]
    )]
    #[Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getVkCommand(): VkCommand
    {
        return $this->vkCommand;
    }

    public function setVkCommand(VkCommand $command): self
    {
        $this->vkCommand = $command;

        return $this;
    }

    public function getVkMessage(): VkMessage
    {
        return $this->vkMessage;
    }

    public function setVkMessage(VkMessage $vkMessage): self
    {
        $this->vkMessage = $vkMessage;

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
}
