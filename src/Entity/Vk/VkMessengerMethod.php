<?php

declare(strict_types=1);

namespace App\Entity\Vk;

use App\Repository\Vk\VkMessengerMethodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: VkMessengerMethodRepository::class)]
#[ORM\Table(options: [
    'comment' => 'Метод api вконтакте',
])]
#[ORM\UniqueConstraint(columns: ['name'])]
class VkMessengerMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(
        type: Types::STRING,
        options: [
            'comment' => 'Наименование метода',
        ]
    )]
    private string $name;

    /**
     * @var Collection<int, VkMessage>
     */
    #[ORM\OneToMany(
        mappedBy: 'vkMessengerMethod',
        targetEntity: VkMessage::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $vkdMessages;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        options: [
            'comment' => 'Дата и время создания',
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

    public function __construct()
    {
        $this->vkdMessages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
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
        return $this->name;
    }

    /**
     * @return Collection<int, VkMessage>
     */
    public function getVkMessages(): Collection
    {
        return $this->vkdMessages;
    }

    public function addVkMessages(VkMessage $vkMessage): self
    {
        if (!$this->vkdMessages->contains($vkMessage)) {
            $this->vkdMessages[] = $vkMessage;
            $vkMessage->setVkMessengerMethod($this);
        }
        return $this;
    }

    public function removeVkMessages(VkMessage $vkMessage): self
    {
        $this->vkdMessages->removeElement($vkMessage);
        return $this;
    }
}
