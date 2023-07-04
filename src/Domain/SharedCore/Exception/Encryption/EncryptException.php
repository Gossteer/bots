<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Encryption;

class EncryptException extends EncryptionException
{
    public function __construct(
        string $message = "Ошибка шифрования",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
