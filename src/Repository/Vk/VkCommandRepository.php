<?php

declare(strict_types=1);

namespace App\Repository\Vk;

use App\Entity\Vk\VkCommand;
use App\Infrastructure\Bot\Dto\BotDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VkCommand|null find($id, $lockMode = null, $lockVersion = null)
 * @method VkCommand|null findOneBy(array $criteria, array $orderBy = null)
 * @method VkCommand[]    findAll()
 * @method VkCommand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VkCommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VkCommand::class);
    }

    /**
     * Получение команды и ее сообщений
     *
     * @throws NonUniqueResultException
     */
    public function getMessengerCommandByName(string $command, BotDto $botDto): ?VkCommand
    {
        return $this->createQueryBuilder('vc')
            ->addSelect('vbg')
            ->join('vc.vkBotGroup', 'vbg')
            ->addSelect('vcm')
            ->join('vc.vkCommandMessages', 'vcm')
            ->addSelect('vm')
            ->join('vcm.vkMessage', 'vm')
            ->addSelect('vmm')
            ->join('vm.vkMessengerMethod', 'vmm')
            ->where('vc.name = :vcName')
            ->andWhere('vc.isActive = true')
            ->andWhere('vbg.id = :vbgId')
            ->orderBy('vcm.sortOrder')
            ->setParameters([
                'vcName' => $command,
                'vbgId' => $botDto->getGroupId(),
            ])->getQuery()->getOneOrNullResult();
    }
}
