<?php

declare(strict_types=1);

namespace App\Infrastructure\Encryption;

use App\Domain\SharedCore\Exception\Encryption\CreateKeyException;
use App\Domain\SharedCore\Exception\Encryption\DecryptException;
use App\Domain\SharedCore\Exception\Encryption\EncryptException;

interface EncryptionInterface
{
    /**
     * Шифрование
     *
     * @throws EncryptException
     */
    public function encrypt(string $text, ?string $key = null): string;

    /**
     * Расшифровка
     *
     * @throws DecryptException
     */
    public function decrypt(string $text, ?string $key = null): string;

    /**
     * Создания случайного ключа шифрования
     *
     * @throws CreateKeyException
     */
    public function createNewRandomKey(): string;
}
