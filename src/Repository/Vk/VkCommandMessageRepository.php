<?php

declare(strict_types=1);

namespace App\Repository\Vk;

use App\Entity\Vk\VkCommandMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VkCommandMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method VkCommandMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method VkCommandMessage[]    findAll()
 * @method VkCommandMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VkCommandMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VkCommandMessage::class);
    }
}
