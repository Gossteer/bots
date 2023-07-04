<?php

declare(strict_types=1);

namespace App\Operation\Message;

use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;

class MessengerUpdate
{
    public function __construct(
        private readonly MessengerUpdateInterface $messengerUpdate
    ) {
    }

    public function getMessengerUpdate(): MessengerUpdateInterface
    {
        return $this->messengerUpdate;
    }
}
