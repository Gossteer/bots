<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Dto;

class MessengerUserDto
{
    public function __construct(
        private readonly string $id,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
