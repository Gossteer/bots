<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Dto;

class MessageDto
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly int $messageId,
        private readonly string $method,
        private readonly array $data,
    ) {
    }

    /**
     * id сообщения из базы мессенджера
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * Метод api
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Тело запроса api
     *
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
