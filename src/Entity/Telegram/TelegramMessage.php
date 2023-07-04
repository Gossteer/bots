<?php

declare(strict_types=1);

namespace App\Entity\Telegram;

use App\Controller\Admin\AbstractCrudController;
use App\Repository\Telegram\TelegramMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: TelegramMessageRepository::class)]
#[ORM\Table(options: [
    'comment' => 'сообщения для телеграмма',
])]
class TelegramMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    /**
     * @var Collection<int, TelegramCommandMessage>
     */
    #[ORM\OneToMany(
        mappedBy: 'telegramMessage',
        targetEntity: TelegramCommandMessage::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $telegramCommandMessages;

    /**
     * @var array<string, mixed>
     */
    #[ORM\Column(type: Types::JSON, options: [
        'jsonb' => true,
        'comment' => 'тело сообщения',
    ])]
    private array $data;

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

    #[ORM\ManyToOne(targetEntity: TelegramMessengerMethod::class, inversedBy: 'telegramMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private TelegramMessengerMethod $telegramMessengerMethod;

    public function __construct()
    {
        $this->telegramCommandMessages = new ArrayCollection();
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
            $telegramCommandMessages->setTelegramMessage($this);
        }
        return $this;
    }

    public function removeTelegramCommandMessage(
        TelegramCommandMessage $telegramCommandMessages
    ): self {
        $this->telegramCommandMessages->removeElement($telegramCommandMessages);
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getTelegramMessengerMethod(): TelegramMessengerMethod
    {
        return $this->telegramMessengerMethod;
    }

    public function setTelegramMessengerMethod(TelegramMessengerMethod $telegramMessengerMethod): self
    {
        $this->telegramMessengerMethod = $telegramMessengerMethod;
        return $this;
    }

    public function __toString(): string
    {
        return AbstractCrudController::makeShortText(json_encode($this->data, JSON_UNESCAPED_UNICODE)) . ' #' . $this->id;
    }
}
