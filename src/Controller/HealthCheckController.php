<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[OA\Tag(name: "health-check")]
#[Route(path: "/healthCheck")]
class HealthCheckController extends AbstractController
{
    #[OA\Get(operationId: "readiness", summary: "Проверка готовности")]
    #[OA\Response(response: 204, description: "Готов к работе, траффик можно направлять")]
    #[OA\Response(
        response: 500,
        description: "Не готов к работе, траффик нельзя направлять",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/readiness", name: "readiness-probe", methods: [Request::METHOD_GET])]
    public function readinessProbeAction(): void
    {
    }

    #[OA\Get(operationId: "liveness", summary: "Жив ли сервис?")]
    #[OA\Response(response: 204, description: "Сервис жив, не требуется перезапуск")]
    #[OA\Response(
        response: 500,
        description: "Сервис требует перезапуска",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/liveness", name: "liveness-probe", methods: [Request::METHOD_GET])]
    public function livenessProbeAction(): void
    {
    }

    #[OA\Get(operationId: "telegramBotCa_db_connection", summary: "Есть ли подключение к базе")]
    #[OA\Response(response: 204, description: "Подключение есть")]
    #[OA\Response(
        response: 500,
        description: "Не удалось подключиться",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/telegramBotCaDbConnection", name: "telegramBotCa-db-connection-probe", methods: [Request::METHOD_GET])]
    public function checkTelegramBotCaDBProbeAction(EntityManagerInterface $defaultEntityManager): void
    {
        if ($defaultEntityManager->getConnection()->connect() !== true) {
            throw new \Exception();
        }
    }

    #[OA\Get(operationId: "redis_connection", summary: "Есть ли подключение к redis")]
    #[OA\Response(response: 204, description: "Подключение есть")]
    #[OA\Response(
        response: 500,
        description: "Не удалось подключиться",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/redisConnection", name: "redis-connection-probe", methods: [Request::METHOD_GET])]
    public function checkRedisProbeAction(CacheInterface $cache): void
    {
        $testData = 'test' . uuid_create(UUID_TYPE_RANDOM);
        $cache->get($testData, function (ItemInterface $item) use ($testData) {
            $item->expiresAfter(2);
            return $testData;
        });

        // Проверяем, что данный сохранились
        /** @var null|string $testDataFromRedis */
        $testDataFromRedis = $cache->get($testData, function () {
            return null;
        });

        if ($testDataFromRedis !== $testData) {
            throw new \Exception();
        }
    }
}
