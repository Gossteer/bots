<?php

declare(strict_types=1);

namespace App\Entity\Vk;

use App\Repository\Vk\UserVkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: UserVkRepository::class)]
#[ORM\Table(options: [
    'comment' => 'Пользователи вконтакте',
])]
class UserVk
{
    #[ORM\Id]
    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false,
        options: [
            'comment' => 'Код пользователя вконтакте',
        ]
    )]
    private int $id;

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

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: [
        'comment' => 'Дата и время обновления',
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    #[Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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
}
