<?php

declare(strict_types=1);

namespace App\Operation\MessageHandler;

use App\Operation\Message\ExampleMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ExampleHandler
{
    public function __invoke(ExampleMessage $message): void
    {
        var_dump($message->getContent());
    }
}
