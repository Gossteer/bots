<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Vk;

use App\Entity\Vk\UserVk;
use App\Infrastructure\Bot\Contract\MessengerCheckUserInterface;
use App\Infrastructure\Bot\Dto\MessengerUserDto;
use Doctrine\ORM\EntityManagerInterface;

class VkUserChecker implements MessengerCheckUserInterface
{
    public function __construct(
        private readonly EntityManagerInterface $defaultEntityManager
    ) {
    }

    public function checkUser(MessengerUserDto $messengerUserDto): bool
    {
        $userVk = $this->defaultEntityManager->find(UserVk::class, $messengerUserDto->getId());
        if (!$userVk) {
            $userVk = new UserVk();
            $userVk->setId((int)$messengerUserDto->getId());
        }

        $this->defaultEntityManager->persist($userVk);
        $this->defaultEntityManager->flush();
        return true;
    }
}
