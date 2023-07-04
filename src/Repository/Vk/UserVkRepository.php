<?php

declare(strict_types=1);

namespace App\Repository\Vk;

use App\Entity\Vk\UserVk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserVk|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVk|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserVk[]    findAll()
 * @method UserVk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVk::class);
    }
}
