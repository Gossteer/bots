<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Contract;

use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Infrastructure\Bot\Dto\BotDto;

interface BotGetterInterface
{
    /**
     * Получение бота по его секретному ключу
     *
     * @throws BotNotFoundException
     */
    public function getBySecretToken(string $secretToken): BotDto;

    /**
     * Получение бота по его зашифрованному секретному ключу
     *
     * @throws BotNotFoundException
     */
    public function getByEncryptSecretToken(string $encryptSecretToken): BotDto;

    /**
     * Получение бота с учетом его связи с командой
     *
     * @throws BotNotFoundException
     */
    public function getBotByDtoAndCommand(BotDto $botDto, string $command): BotDto;

    /**
     * Получить всех ботов
     *
     * @return BotDto[]
     */
    public function getAllBots(): array;

    /**
     * Получить бота по уже хешированному секретному токену
     *
     * @throws BotNotFoundException
     */
    public function getByHashSecretToken(string $hashSecretToken): BotDto;
}
