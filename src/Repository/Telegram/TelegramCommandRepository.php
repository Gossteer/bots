<?php

declare(strict_types=1);

namespace App\Repository\Telegram;

use App\Entity\Telegram\TelegramCommand;
use App\Infrastructure\Bot\Dto\BotDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TelegramCommand|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramCommand|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramCommand[]    findAll()
 * @method TelegramCommand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelegramCommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramCommand::class);
    }

    /**
     * Получение команды и ее сообщений
     *
     * @throws NonUniqueResultException
     */
    public function getMessengerCommandByName(string $command, BotDto $botDto): ?TelegramCommand
    {
        return $this->createQueryBuilder('tc')
            ->addSelect('tbg')
            ->join('tc.telegramBotGroup', 'tbg')
            ->addSelect('tcm')
            ->join('tc.telegramCommandMessages', 'tcm')
            ->addSelect('tm')
            ->join('tcm.telegramMessage', 'tm')
            ->addSelect('tmm')
            ->join('tm.telegramMessengerMethod', 'tmm')
            ->where('tc.name = :tcName')
            ->andWhere('tc.isActive = true')
            ->andWhere('tbg.id = :tbgId')
            ->orderBy('tcm.sortOrder')
            ->setParameters([
                'tcName' => $command,
                'tbgId' => $botDto->getGroupId(),
            ])->getQuery()->getOneOrNullResult();
    }
}
