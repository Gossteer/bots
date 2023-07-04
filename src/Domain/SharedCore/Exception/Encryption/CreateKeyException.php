<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception\Encryption;

class CreateKeyException extends EncryptionException
{
    public function __construct(
        string $message = "Ошибка генерации ключа шифрования",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
