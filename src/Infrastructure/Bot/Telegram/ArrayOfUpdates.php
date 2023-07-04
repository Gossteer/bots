<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

class ArrayOfUpdates extends \TelegramBot\Api\Types\ArrayOfUpdates
{
    /**
     * @param mixed[] $data
     * @return Update[]
     */
    public static function fromResponse($data): array
    {
        $arrayOfUpdates = [];
        foreach ($data as $update) {
            $arrayOfUpdates[] = Update::fromResponse($update);
        }
        return $arrayOfUpdates;
    }
}
