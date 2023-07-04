<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Encryption;

class DecryptException extends EncryptionException
{
    public function __construct(
        string $message = "Ошибка расшифровки",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
