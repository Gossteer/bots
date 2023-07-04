<?php

declare(strict_types=1);

namespace App\Entity\Vk;

use App\Controller\Admin\AbstractCrudController;
use App\Repository\Vk\VkMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: VkMessageRepository::class)]
#[ORM\Table(options: [
    'comment' => 'Сообщения для вконтакте',
])]
class VkMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(
        type: Types::INTEGER,
        nullable: false
    )]
    private int $id;

    /**
     * @var Collection<int, VkCommandMessage>
     */
    #[ORM\OneToMany(
        mappedBy: 'vkMessage',
        targetEntity: VkCommandMessage::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $vkCommandMessages;

    #[ORM\ManyToOne(
        targetEntity: VkMessengerMethod::class,
        inversedBy: 'vkdMessages'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private VkMessengerMethod $vkMessengerMethod;

    /**
     * @var array<string,string>
     */
    #[ORM\Column(
        type: Types::JSON,
        nullable: false,
        options: [
            'jsonb' => true,
            'comment' => 'тело сообщения',
        ]
    )]
    private array $data;

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

    public function __construct()
    {
        $this->vkCommandMessages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVkMessengerMethod(): VkMessengerMethod
    {
        return $this->vkMessengerMethod;
    }

    public function setVkMessengerMethod(VkMessengerMethod $vkMessengerMethod): self
    {
        $this->vkMessengerMethod = $vkMessengerMethod;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string,string> $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

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
        return AbstractCrudController::makeShortText(json_encode($this->data, JSON_UNESCAPED_UNICODE)) . ' #' . $this->id;
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
            $commandMessage->setVkMessage($this);
        }
        return $this;
    }

    public function removeVkCommandMessages(VkCommandMessage $commandMessage): self
    {
        $this->vkCommandMessages->removeElement($commandMessage);
        return $this;
    }
}
