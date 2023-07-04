<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Domain\SharedCore\Dto\Request\Command\CallCommandDto;
use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use App\Domain\SharedCore\Dto\Response\JsonResponseDto;
use App\Domain\SharedCore\Dto\Response\SuccessResponseDto;
use App\Domain\SharedCore\Exception\Bot\BotNotFoundException;
use App\Domain\SharedCore\Exception\UserNotFoundException;
use App\Infrastructure\Bot\BotClient\BotGetter;
use App\Infrastructure\Bot\External\ExternalUpdate;
use App\Operation\Message\MessengerUpdate;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "messenger")]
#[OA\Tag(name: "v1")]
#[Route(path: "/command")]
class CommandController extends AbstractController
{
    public function __construct(
        private readonly BotGetter $botGetter
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws BotNotFoundException
     */
    #[OA\Post(
        operationId: "call-command",
        summary: "Запуск команды"
    )]
    #[OA\RequestBody(
        description: "Данные о команде",
        content: new OA\JsonContent(
            ref: new Model(type: CallCommandDto::class)
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Успешно",
        content: new OA\JsonContent(
            ref: new Model(type: SuccessResponseDto::class)
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Ошибка при отправке сообщения",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[OA\Response(
        response: 500,
        description: "Ошибка при отправке сообщения",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/call", name: "call-command", methods: [Request::METHOD_POST])]
    public function call(MessageBusInterface $bus, CallCommandDto $callCommandDto): JsonResponseDto
    {
        try {
            $bot = $this->botGetter->getBotByDtoAndCommand(
                $callCommandDto->getMessengerTypeEnum(),
                $this->botGetter->getByHashSecretToken(
                    $callCommandDto->getMessengerTypeEnum(),
                    $callCommandDto->getBotHash()
                ),
                $callCommandDto->getCommand()
            );
        } catch (BotNotFoundException $botNotFoundException) {
            throw new HttpException(400, 'Бот или команда не найдена', $botNotFoundException);
        }

        $externalUpdate = new ExternalUpdate(
            $callCommandDto->getUserId(),
            $callCommandDto->getMessengerTypeEnum(),
            $callCommandDto->getCommand(),
            $bot
        );

        $bus->dispatch(
            new MessengerUpdate(
                $externalUpdate
            )
        );
        return new SuccessResponseDto();
    }
}
