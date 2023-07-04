<?php

declare(strict_types=1);

namespace App\Operation\Message;

class MessengerMessageDelay
{
    public function __construct(
        private readonly MessengerMessage $messengerMessage
    ) {
    }

    public function getMessengerMessage(): MessengerMessage
    {
        return $this->messengerMessage;
    }
}
