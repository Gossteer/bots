<?php

declare(strict_types=1);

namespace App\Entity\Telegram;

use App\Repository\Telegram\TelegramMessageMethodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: TelegramMessageMethodRepository::class)]
#[ORM\Table(options: [
    'comment' => 'метод api телеграмма',
])]
#[ORM\UniqueConstraint(columns: ['name'])]
class TelegramMessengerMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, options: [
        'comment' => 'название метода',
    ])]
    private string $name;

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @var Collection<int, TelegramMessage>
     */
    #[ORM\OneToMany(
        mappedBy: 'telegramMessengerMethod',
        targetEntity: TelegramMessage::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $telegramMessages;

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
        $this->telegramMessages = new ArrayCollection();
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

    /**
     * @return Collection<int, TelegramMessage>
     */
    public function getMessages(): Collection
    {
        return $this->telegramMessages;
    }

    public function addMessage(TelegramMessage $message): self
    {
        if (!$this->telegramMessages->contains($message)) {
            $this->telegramMessages[] = $message;
            $message->setTelegramMessengerMethod($this);
        }
        return $this;
    }

    public function removeMessage(TelegramMessage $message): self
    {
        $this->telegramMessages->removeElement($message);
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
}
