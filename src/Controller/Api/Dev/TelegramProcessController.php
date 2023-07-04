<?php

declare(strict_types=1);

namespace App\Controller\Api\Dev;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use App\Domain\SharedCore\Dto\Response\Example\ExampleResponseDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "dev")]
#[Route(path: "/telegramProcess")]
class TelegramProcessController extends AbstractController
{
    #[OA\Get(operationId: "start_telegram_process", summary: "Запуск команды getUpdate")]
    #[OA\Response(
        response: 200,
        description: "getUpdate команда успешно запущена",
        content: new OA\JsonContent(
            ref: new Model(type: ExampleResponseDto::class)
        )
    )]
    #[OA\Response(
        response: 500,
        description: "Ошибка при запуске",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/start-telegram-process", name: "start-telegram-process", methods: [Request::METHOD_GET])]
    public function rabbitExample(KernelInterface $kernel): void
    {
        // будущая заготовка, если будут идеи
    }
}
