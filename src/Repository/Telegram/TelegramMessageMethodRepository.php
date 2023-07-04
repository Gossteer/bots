<?php

declare(strict_types=1);

namespace App\Repository\Telegram;

use App\Entity\Telegram\TelegramMessengerMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TelegramMessengerMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramMessengerMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramMessengerMethod[]    findAll()
 * @method TelegramMessengerMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelegramMessageMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramMessengerMethod::class);
    }
}
