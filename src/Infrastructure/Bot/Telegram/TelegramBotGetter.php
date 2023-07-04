<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Entity\Telegram\TelegramBot;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class TelegramBotGetter implements BotGetterInterface
{
    private const TABLE_ALIAS = 'tb';
    private const TABLE_GROUP_ALIAS = 'tbg';

    public function __construct(
        private readonly EntityManagerInterface $defaultEntityManager,
        private readonly string $appDeployment
    ) {
    }

    public function getByHashSecretToken(string $hashSecretToken): BotDto
    {
        return $this->makeBotDto(
            $this->addBaseWhere(
                $this->defaultEntityManager->getRepository(TelegramBot::class)
                    ->createQueryBuilder(self::TABLE_ALIAS)
                    ->where(self::TABLE_ALIAS . '.id = :id')
                    ->setParameter('id', $hashSecretToken)
            )->getQuery()->getOneOrNullResult(),
            $hashSecretToken
        );
    }

    private function addBaseWhere(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder
            ->innerJoin(self::TABLE_ALIAS . '.telegramBotGroup', self::TABLE_GROUP_ALIAS)
            ->andWhere(self::TABLE_GROUP_ALIAS . '.isActive = true')
            ->andWhere(
                self::TABLE_ALIAS . '.environment = :environment OR ' . self::TABLE_ALIAS . '.environment IS NULL'
            )->andWhere(self::TABLE_ALIAS . '.isActive = true')
            ->setParameter('environment', $this->appDeployment);
    }

    public function getBotByDtoAndCommand(BotDto $botDto, string $command): BotDto
    {
        return $this->makeBotDto(
            $this->addBaseWhere(
                $this->defaultEntityManager->getRepository(TelegramBot::class)
                    ->createQueryBuilder(self::TABLE_ALIAS)
                    ->setParameter('commandName', $command)
                    ->andWhere(self::TABLE_ALIAS . '.secretToken = :secretToken')
                    ->setParameter('secretToken', $botDto->getSecretToken())
            )->innerJoin(self::TABLE_GROUP_ALIAS . '.telegramCommands', 'tgc')
                ->andWhere('tgc.name = :commandName')->getQuery()->getOneOrNullResult(),
            $botDto->getSecretToken()
        );
    }

    public function getBySecretToken(string $secretToken): BotDto
    {
        return $this->makeBotDto(
            $this->addBaseWhere(
                $this->defaultEntityManager->getRepository(TelegramBot::class)
                    ->createQueryBuilder(self::TABLE_ALIAS)
                    ->where(self::TABLE_ALIAS . '.id = :id')
                    ->setParameter('id', md5($secretToken))
            )->getQuery()->getOneOrNullResult(),
            $secretToken
        );
    }

    private function makeBotDto(?TelegramBot $telegramBot, string $token): BotDto
    {
        if (!$telegramBot) {
            throw new BotNotFoundException($token, MessengerTypeEnum::Telegram);
        } else {
            return new BotDto(
                $telegramBot->getId(),
                $telegramBot->getToken(),
                $telegramBot->getTelegramBotGroup()->getId(),
                $telegramBot->getName(),
                $telegramBot->getSecretToken()
            );
        }
    }

    public function getByEncryptSecretToken(string $encryptSecretToken): BotDto
    {
        return $this->makeBotDto(
            $this->addBaseWhere(
                $this->defaultEntityManager->getRepository(TelegramBot::class)
                    ->createQueryBuilder(self::TABLE_ALIAS)
                    ->where(self::TABLE_ALIAS . '.secretToken = :secretToken')
                    ->setParameter('secretToken', $encryptSecretToken)
            )->getQuery()->getOneOrNullResult(),
            $encryptSecretToken
        );
    }

    public function getAllBots(): array
    {
        /** @var TelegramBot[] $telegramBots */
        $telegramBots = $this->addBaseWhere(
            $this->defaultEntityManager->getRepository(TelegramBot::class)
                ->createQueryBuilder(self::TABLE_ALIAS)
        )->getQuery()->getResult();

        $telegramBotsDto = [];
        foreach ($telegramBots as $telegramBot) {
            $telegramBotsDto[] = new BotDto(
                $telegramBot->getId(),
                $telegramBot->getToken(),
                $telegramBot->getTelegramBotGroup()->getId(),
                $telegramBot->getName(),
                $telegramBot->getSecretToken()
            );
        }
        return $telegramBotsDto;
    }
}
