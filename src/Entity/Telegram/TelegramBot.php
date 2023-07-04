<?php

declare(strict_types=1);

namespace App\Entity\Telegram;

use App\Repository\Telegram\TelegramBotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: TelegramBotRepository::class)]
#[ORM\Table(options: [
    'comment' => 'Список телеграмм ботов',
])]
#[ORM\UniqueConstraint(columns: ['token'])]
#[ORM\UniqueConstraint(columns: ['secret_token'])]
#[ORM\UniqueConstraint(columns: ['name'])]
class TelegramBot
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING)]
    private string $id;

    #[ORM\Column(
        type: Types::BOOLEAN,
        options: [
            'default' => false,
            'comment' => 'Признак активности бота',
        ]
    )]
    private bool $isActive = false;

    #[ORM\Column(
        type: Types::TEXT,
        options: [
            'comment' => 'Токен для работы с ботом',
        ]
    )]
    private string $token;

    #[ORM\Column(
        type: Types::TEXT,
        options: [
            'comment' => 'Секретный токен, подтверждающий бота',
        ]
    )]
    private string $secretToken;

    #[ORM\Column(
        type: Types::TEXT,
        options: [
            'comment' => 'Наименование бота в телеграмме',
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

    #[ORM\ManyToOne(targetEntity: TelegramBotGroup::class, inversedBy: 'telegramBots')]
    #[ORM\JoinColumn(nullable: false)]
    private TelegramBotGroup $telegramBotGroup;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
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

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getSecretToken(): string
    {
        return $this->secretToken;
    }

    public function setSecretToken(string $secretToken): self
    {
        $this->secretToken = $secretToken;
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

    public function setUpdateAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function setEnvironment(?string $environment): self
    {
        $this->environment = $environment;
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
}
