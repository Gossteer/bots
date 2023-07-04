<?php

declare(strict_types=1);

namespace App\Infrastructure\Utils;

use App\Domain\SharedCore\Enum\MessengerTypeEnum;
use App\Domain\SharedCore\Exception\UserNotFoundException;
use App\Entity\Telegram\UserTg;
use App\Entity\Vk\UserVk;
use Doctrine\ORM\EntityManagerInterface;

class UserGetter
{
    public function __construct(
        private readonly EntityManagerInterface $defaultEntityManager
    ) {
    }

    /**
     * Получение пользователя телеграмма по id
     *
     * @throws UserNotFoundException
     */
    public function getUserTg(string $userId): UserTg
    {
        $user = $this->defaultEntityManager->find(UserTg::class, $userId);
        if (!$user) {
            throw new UserNotFoundException($userId, MessengerTypeEnum::Telegram);
        }

        return $user;
    }

    /**
     * @throws \App\Domain\SharedCore\Exception\UserNotFoundException
     */
    public function getUserVk(int $userVk): UserVk
    {
        $user = $this->defaultEntityManager->find(UserVk::class, $userVk);
        if (!$user) {
            throw new UserNotFoundException((string)$userVk, MessengerTypeEnum::Vk);
        }
        return $user;
    }
}
