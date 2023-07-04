<?php

declare(strict_types=1);

namespace App\Infrastructure\Bot\Telegram;

use App\Entity\Telegram\UserTg;
use App\Infrastructure\Bot\Contract\MessengerCheckUserInterface;
use App\Infrastructure\Bot\Dto\MessengerUserDto;
use Doctrine\ORM\EntityManagerInterface;

class TelegramUserChecker implements MessengerCheckUserInterface
{
    public function __construct(
        private readonly EntityManagerInterface $defaultEntityManager
    ) {
    }

    public function checkUser(MessengerUserDto $messengerUserDto): bool
    {
        $userTg = $this->defaultEntityManager->find(UserTg::class, $messengerUserDto->getId());
        if (!$userTg) {
            $userTg = new UserTg();
            $userTg->setId($messengerUserDto->getId());
        }

        $this->defaultEntityManager->persist($userTg);
        $this->defaultEntityManager->flush();
        return true;
    }
}
