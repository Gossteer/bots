<?php

declare(strict_types=1);

namespace App\Repository\Telegram;

use App\Entity\Telegram\UserTg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTg|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTg|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTg[]    findAll()
 * @method UserTg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTg::class);
    }
}
