<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto\Request\Command;

use App\Domain\SharedCore\Dto\Request\JsonRequestDto;
use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(description: "Сообщение о запуске команды")]
class CallCommandDto implements JsonRequestDto
{
    public function __construct(
        #[OA\Property(title: "id пользователя из мессенджера")]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1)]
        private readonly string $userId,
        #[OA\Property(title: "Хеш бота")]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1)]
        private readonly string $botHash,
        #[OA\Property(title: "тип мессенджера", type: 'string')]
        #[Assert\Choice(callback: [MessengerTypeEnum::class, 'cases'])]
        #[Assert\NotBlank]
        private readonly MessengerTypeEnum $messengerTypeEnum,
        #[OA\Property(title: "команда")]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1)]
        private readonly string $command
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getMessengerTypeEnum(): MessengerTypeEnum
    {
        return $this->messengerTypeEnum;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getBotHash(): string
    {
        return $this->botHash;
    }
}
