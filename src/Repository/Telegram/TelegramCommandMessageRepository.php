<?php

declare(strict_types=1);

namespace App\Repository\Telegram;

use App\Entity\Telegram\TelegramCommandMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TelegramCommandMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramCommandMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramCommandMessage[]    findAll()
 * @method TelegramCommandMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelegramCommandMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramCommandMessage::class);
    }

    public function remove(TelegramCommandMessage $commandMessage, bool $flush = true): void
    {
        $this->_em->remove($commandMessage);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
