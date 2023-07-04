<?php

declare(strict_types=1);

namespace App\Entity\Vk;

use App\Repository\Vk\VkBotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: VkBotRepository::class)]
#[ORM\UniqueConstraint(columns: ['access_key'])]
#[ORM\UniqueConstraint(columns: ['secret_token'])]
#[ORM\UniqueConstraint(columns: ['name'])]
#[ORM\Table(options: [
    'comment' => 'Список VK ботов',
])]
class VkBot
{
    #[ORM\Id]
    #[ORM\Column(
        type: Types::TEXT,
        nullable: false,
        options: [
            'comment' => 'Идентификатор бота',
        ]
    )]
    private string $id;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: false,
        options: [
            'comment' => 'Ключ доступа в сообщество вконтакте',
        ],
    )]
    private string $accessKey;

    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false,
        options: [
            'comment' => 'Идентификатор сообщества вконтакте',
        ]
    )]
    private int $groupId;

    #[ORM\ManyToOne(
        targetEntity: VkBotGroup::class,
        inversedBy: 'vkBots'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private VkBotGroup $vkBotGroup;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true,
        options: [
            'comment' => 'Секретный ключ',
        ]
    )]
    private ?string $secretToken = null;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true,
        options: [
            'comment' => 'Строка подтверждения',
        ]
    )]
    private ?string $confirmationToken = null;

    #[ORM\Column(
        type: Types::TEXT,
        options: [
            'comment' => 'Наименование бота',
        ]
    )]
    private string $name;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true,
        options: [
            'comment' => 'Окружение, в котором бот должен работать. Если null, то работает везде',
        ]
    )]
    private ?string $environment = null;

    #[ORM\Column(
        type: Types::BOOLEAN,
        nullable: false,
        options: [
            'default' => false,
            'comment' => 'Признак активности бота',
        ]
    )]
    private bool $isActive = false;

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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function setAccessKey(string $accessKey): self
    {
        $this->accessKey = $accessKey;

        return $this;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getVkBotGroup(): VkBotGroup
    {
        return $this->vkBotGroup;
    }

    public function getBotGroupId(): int
    {
        return $this->vkBotGroup->getId();
    }

    public function setBotGroupId(int $groupId): self
    {
        $this->vkBotGroup->setId($groupId);
        return $this;
    }

    public function setVkBotGroup(VkBotGroup $vkBotGroup): self
    {
        $this->vkBotGroup = $vkBotGroup;

        return $this;
    }

    public function getSecretToken(): ?string
    {
        return $this->secretToken;
    }

    public function setSecretToken(string $secretToken): self
    {
        $this->secretToken = $secretToken;

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

    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    public function setEnvironment(?string $environment): self
    {
        $this->environment = $environment;

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
}
