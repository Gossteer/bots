<?php

declare(strict_types=1);

namespace App\Repository\Telegram;

use App\Entity\Telegram\TelegramBotGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TelegramBotGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramBotGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramBotGroup[]    findAll()
 * @method TelegramBotGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelegramBotGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramBotGroup::class);
    }
}
