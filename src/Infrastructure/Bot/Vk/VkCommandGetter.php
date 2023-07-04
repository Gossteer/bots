<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Vk;

use App\Infrastructure\Bot\Contract\MessengerCommandGetterInterface;
use App\Infrastructure\Bot\Contract\MessengerUpdateInterface;

class VkCommandGetter implements MessengerCommandGetterInterface
{
    private const REGEXP = '/^(?:@\w+\s)?\/([^\s@]+)(@\S+)?\s?(.*)$/';

    public function getCommand(MessengerUpdateInterface $messengerUpdate): ?string
    {
        $getters = [
            fn (MessengerUpdateInterface $messengerUpdate): ?string => $this->getCommandByPayloadData($messengerUpdate),
            fn (MessengerUpdateInterface $messengerUpdate): ?string => $this->getCommandByText($messengerUpdate),
            fn (MessengerUpdateInterface $messengerUpdate): ?string => $this->getLowerText($messengerUpdate),
        ];
        if (!$messengerUpdate->getText()) {
            return null;
        }

        foreach ($getters as $getter) {
            $command = $getter($messengerUpdate);
            if ($command) {
                break;
            }
        }
        return $command;
    }

    private function getCommandByPayloadData(MessengerUpdateInterface $messengerUpdate): ?string
    {
        return $messengerUpdate->getCallbackData();
    }

    /**
     * @throws \App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException
     */
    private function getCommandByText(MessengerUpdateInterface $messengerUpdate): ?string
    {
        $matches = [];
        preg_match(self::REGEXP, $messengerUpdate->getText(), $matches);

        return !empty($matches) ? $matches[1] : null;
    }

    /**
     * @throws \App\Domain\SharedCore\Exception\Bot\NotDefineMessageMessengerException
     */
    private function getLowerText(MessengerUpdateInterface $messengerUpdate): ?string
    {
        $text = $messengerUpdate->getText();
        return !empty($text) ? mb_strtolower($text) : null;
    }
}
