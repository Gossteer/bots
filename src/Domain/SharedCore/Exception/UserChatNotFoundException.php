<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Types\ErrorType;
use Symfony\Component\HttpFoundation\Response;

class UserChatNotFoundException extends BaseApiException
{
    public function __construct(
        string $userId,
        MessengerTypeEnum $messengerTypeEnum,
        int $code = Response::HTTP_BAD_REQUEST
    ) {
        $text = sprintf(
            'Чат с пользователем из %s с id = %s не найден',
            $messengerTypeEnum->value,
            $userId
        );

        parent::__construct(
            $this->message(
                $text,
                "Ошибка",
                ErrorType::From,
            ),
            $code
        );
    }
}
