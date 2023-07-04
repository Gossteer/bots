<?php

declare(strict_types=1);

namespace App\Infrastructure\Encryption;

use App\Domain\SharedCore\Exception\Encryption\CreateKeyException;
use App\Domain\SharedCore\Exception\Encryption\DecryptException;
use App\Domain\SharedCore\Exception\Encryption\EncryptException;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Key;

class CryptoEncryption implements EncryptionInterface
{
    private Key $key;

    public function __construct(
        private readonly string $keyToken
    ) {
    }

    /**
     * @throws EnvironmentIsBrokenException
     * @throws BadFormatException
     */
    private function getKey(): Key
    {
        return $this->key ??= Key::loadFromAsciiSafeString($this->keyToken);
    }

    /**
     * @throws EnvironmentIsBrokenException
     * @throws BadFormatException
     */
    private function makeKey(string $key): Key
    {
        return Key::loadFromAsciiSafeString($key);
    }

    /**
     * @throws CreateKeyException
     */
    public function createNewRandomKey(): string
    {
        try {
            return Key::createNewRandomKey()->saveToAsciiSafeString();
        } catch (\Throwable $th) {
            throw new CreateKeyException(previous: $th);
        }
    }

    /**
     * @throws EncryptException
     */
    public function encrypt(string $text, ?string $key = null): string
    {
        try {
            return Crypto::encrypt(
                $text,
                $key ? $this->makeKey($key) : $this->getKey()
            );
        } catch (\Throwable $th) {
            throw new EncryptException(previous: $th);
        }
    }

    /**
     * @throws DecryptException
     */
    public function decrypt(string $text, ?string $key = null): string
    {
        try {
            return Crypto::decrypt(
                $text,
                $key ? $this->makeKey($key) : $this->getKey()
            );
        } catch (\Throwable $th) {
            throw new DecryptException(previous: $th);
        }
    }
}
