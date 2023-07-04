<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Dto;

class BotDto
{
    /**
     * @param string      $id                - бота в нашей системе
     * @param string      $token             - зашифрованный токен бота
     * @param int         $groupId           - группа бота
     * @param string|null $secretToken       - зашифрованный секретный токен бота
     */
    public function __construct(
        private readonly string $id,
        private readonly string $token,
        private readonly int $groupId,
        private readonly string $botName,
        private readonly ?string $secretToken = null
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getSecretToken(): ?string
    {
        return $this->secretToken;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getBotName(): string
    {
        return $this->botName;
    }
}
