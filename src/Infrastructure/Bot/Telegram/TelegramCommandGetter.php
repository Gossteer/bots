<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

use App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException;
use App\Infrastructure\Bot\Contract\MessengerCommandGetterInterface;
use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;

class TelegramCommandGetter implements MessengerCommandGetterInterface
{
    private const REGEXP = '/^(?:@\w+\s)?\/([^\s@]+)(@\S+)?\s?(.*)$/';

    public function getCommand(MessengerUpdateInterface $messengerUpdate): ?string
    {
        $getters = [
            fn (MessengerUpdateInterface $messengerUpdate): ?string => $this->getCommandByCallbackData($messengerUpdate),
            fn (MessengerUpdateInterface $messengerUpdate): ?string => $this->getCommandByText($messengerUpdate),
        ];

        foreach ($getters as $getter) {
            $command = $getter($messengerUpdate);
            if ($command) {
                break;
            }
        }
        return $command;
    }

    private function getCommandByCallbackData(MessengerUpdateInterface $messengerUpdate): ?string
    {
        return $messengerUpdate->getCallbackData();
    }

    /**
     * @throws NotDefineMessageMessengerException
     */
    private function getCommandByText(MessengerUpdateInterface $messengerUpdate): ?string
    {
        if (!$messengerUpdate->getText()) {
            return null;
        }

        preg_match(self::REGEXP, $messengerUpdate->getText(), $matches);

        return !empty($matches) ? $matches[1] : null;
    }
}
