<?php

declare(strict_types=1);

namespace App\Repository\Vk;

use App\Entity\Vk\VkBotGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VkBotGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method VkBotGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method VkBotGroup[]    findAll()
 * @method VkBotGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VkBotGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VkBotGroup::class);
    }
}
