<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MessageDelay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageDelay|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageDelay|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageDelay[]    findAll()
 * @method MessageDelay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageDelayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageDelay::class);
    }
}
