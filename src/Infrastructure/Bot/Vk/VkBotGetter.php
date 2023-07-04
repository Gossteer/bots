<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Vk;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Entity\Vk\VkBot;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Bot\Dto\BotDto;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class VkBotGetter implements BotGetterInterface
{
    private const TABLE_ALIAS = 'vk';
    private const TABLE_GROUP_ALIAS = 'vkg';

    public function __construct(
        private readonly EntityManagerInterface $defaultEntityManager,
        private readonly string $appDeployment
    ) {
    }

    public function getBySecretToken(string $secretToken): BotDto
    {
        return $this->makeBotDto(
            $this->addBaseWhere(
                $this->defaultEntityManager->getRepository(VkBot::class)
                    ->createQueryBuilder(self::TABLE_ALIAS)
                    ->where(self::TABLE_ALIAS . '.secretToken = :secretToken')
                    ->setParameter('secretToken', $secretToken)
            )->getQuery()->getOneOrNullResult(),
            $secretToken
        );
    }

    public function getByEncryptSecretToken(string $encryptSecretToken): BotDto
    {
        return $this->getBySecretToken($encryptSecretToken);
    }

    public function getBotByDtoAndCommand(BotDto $botDto, string $command): BotDto
    {
        $query = $this->addBaseWhere(
            $this->defaultEntityManager->getRepository(VkBot::class)
                ->createQueryBuilder(self::TABLE_ALIAS)
                ->setParameter('commandName', $command)
                ->andWhere(self::TABLE_ALIAS . '.secretToken = :secretToken')
                ->setParameter('secretToken', $botDto->getSecretToken())
        )->innerJoin(self::TABLE_GROUP_ALIAS . '.vkCommands', 'vkc')
            ->andWhere('vkc.name = :commandName')->getQuery();

        return $this->makeBotDto(
            $query->getOneOrNullResult(),
            $botDto->getSecretToken()
        );
    }

    public function getAllBots(): array
    {
        /** @var \App\Entity\Vk\VkBot[] $vkBots */
        $vkBots = $this->addBaseWhere(
            $this->defaultEntityManager->getRepository(VkBot::class)
                ->createQueryBuilder(self::TABLE_ALIAS)
        )->getQuery()->getResult();

        $vkBotsDto = [];
        foreach ($vkBots as $vkBot) {
            $vkBotsDto[] = new BotDto(
                $vkBot->getId(),
                $vkBot->getAccessKey(),
                $vkBot->getVkBotGroup()->getId(),
                $vkBot->getName(),
                $vkBot->getSecretToken()
            );
        }
        return $vkBotsDto;
    }

    public function getByHashSecretToken(string $hashSecretToken): BotDto
    {
        return $this->getBySecretToken($hashSecretToken);
    }

    private function addBaseWhere(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder
            ->innerJoin(self::TABLE_ALIAS . '.vkBotGroup', self::TABLE_GROUP_ALIAS)
            ->andWhere(self::TABLE_GROUP_ALIAS . '.isActive = true')
            ->andWhere(
                self::TABLE_ALIAS . '.environment = :environment OR ' . self::TABLE_ALIAS . '.environment IS NULL'
            )->andWhere(self::TABLE_ALIAS . '.isActive = true')
            ->setParameter('environment', $this->appDeployment);
    }

    private function makeBotDto(?VkBot $vkBot, ?string $token): BotDto
    {
        if (!$vkBot) {
            throw new BotNotFoundException($token, MessengerTypeEnum::Vk);
        }
        return new BotDto(
            $vkBot->getId(),
            $vkBot->getAccessKey(),
            $vkBot->getVkBotGroup()->getId(),
            $vkBot->getName(),
            $vkBot->getSecretToken()
        );
    }
}
