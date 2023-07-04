<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Bot\Telegram\BotApi;
use App\Infrastructure\Bot\Telegram\Client;
use App\Infrastructure\Bot\Telegram\TelegramUpdate;
use App\Infrastructure\Bot\Telegram\Update;
use App\Infrastructure\Encryption\EncryptionInterface;
use App\Operation\Message\MessengerUpdate;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "telegram")]
#[OA\Tag(name: "v1")]
#[Route(path: "/telegram/webhook")]
class TelegramWebhookController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly BotGetterInterface $telegramBotGetter,
        private readonly EncryptionInterface $encryption
    ) {
    }

    #[OA\Post(operationId: "telegram_webhook_handle", summary: "Обработка сообщений от telegram")]
    #[OA\Response(
        response: 200,
        description: "Прослушка успешно запущена",
        content: new OA\MediaType(
            mediaType: 'text/html',
            example: 'true'
        )
    )]
    #[OA\Response(
        response: 500,
        description: "Ошибка при запуске",
        content: new OA\JsonContent(
            ref: new Model(type: ErrorMessage::class)
        )
    )]
    #[Route(path: "/handle", name: "telegram-webhook-handle", methods: [Request::METHOD_POST])]
    public function handle(MessageBusInterface $bus): Response
    {
        try {
            $secretToken = Client::checkSecretToken();
            $botDto = $this->telegramBotGetter->getBySecretToken($secretToken);
            $botClient = new Client(
                $this->encryption->decrypt($botDto->getToken())
            );
            $data = BotApi::jsonValidate($botClient->getRawBody(), true);

            if ($data) {
                $bus->dispatch(
                    new MessengerUpdate(
                        new TelegramUpdate(
                            Update::fromResponse($data),
                            $botDto
                        )
                    )
                );
            }
        } catch (\Throwable $th) {
            $this->logger?->error($th->getMessage(), [
                'exception' => $th,
            ]);
        }

        return new Response('true');
    }
}
