<?php

declare(strict_types=1);

namespace App\Repository\Vk;

use App\Entity\Vk\VkMessengerMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VkMessengerMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method VkMessengerMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method VkMessengerMethod[]    findAll()
 * @method VkMessengerMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VkMessengerMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VkMessengerMethod::class);
    }
}
