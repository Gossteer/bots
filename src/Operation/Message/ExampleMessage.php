<?php

declare(strict_types=1);

namespace App\Operation\Message;

class ExampleMessage
{
    public function __construct(
        private readonly string $content
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
