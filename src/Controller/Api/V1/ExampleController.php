<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use App\Domain\SharedCore\Dto\Response\Example\ExampleResponseDto;
use App\Domain\SharedCore\Dto\Response\JsonResponseDto;
use App\Operation\Message\ExampleMessage;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "example")]
#[OA\Tag(name: "v1")]
#[Route(path: "/example")]
class ExampleController extends AbstractController
{
    #[OA\Get(operationId: "rabbit_example", summary: "Отправка сообщения в очередь")]
    #[OA\Response(
        response: 200,
        description: "Сообщение отправлено",
        content: new OA\JsonContent(
            ref: new Model(type: ExampleResponseDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Ошибка при отправке сообщения",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/rabbit", name: "rabbit-example", methods: [Request::METHOD_GET])]
    public function rabbitExample(MessageBusInterface $bus): JsonResponseDto
    {
        $content = 'Look! I created a message!';
        $bus->dispatch(new ExampleMessage($content));
        return new ExampleResponseDto($content);
    }
}
