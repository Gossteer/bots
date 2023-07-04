<?php

declare(strict_types=1);

namespace App\Repository\Telegram;

use App\Entity\Telegram\TelegramMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TelegramMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramMessage[]    findAll()
 * @method TelegramMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelegramMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramMessage::class);
    }
}
