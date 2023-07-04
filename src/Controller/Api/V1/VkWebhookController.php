<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use App\Entity\Vk\VkBot;
use App\Infrastructure\Bot\Contract\BotGetterInterface;
use App\Infrastructure\Bot\Vk\VkUpdate;
use App\Operation\Message\MessengerUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: "vkontakte")]
#[OA\Tag(name: "v1")]
#[Route(path: "/vk/callback")]
class VkWebhookController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected const SECRET = 'secret',
    TYPE = 'type',
    OBJECT = 'object',
    CONFIRMATION = 'confirmation',
    MESSAGE_NEW = 'message_new',
    ERROR_FIND_BOT_ENTITY_ID = 'Error find bot entity {id=%d}';

    public function __construct(
        private readonly BotGetterInterface $vkBotGetter,
        protected readonly MessageBusInterface $bus,
        protected readonly EntityManagerInterface $defaultEntityManager
    ) {
    }

    #[OA\Post(operationId: "vk_callback_handle", summary: "Обработка сообщений от vkontakte")]
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
    #[Route(path: "/handle", name: "vk-callback-handle", methods: [Request::METHOD_POST])]
    public function handle(MessageBusInterface $bus): Response
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $secret = $request[self::SECRET] ?? null;
        if ($secret) {
            try {
                /** @var \App\Infrastructure\Bot\Dto\BotDto|null $bot */
                $bot = $this->vkBotGetter->getBySecretToken($secret);
                if ($bot) {
                    $type = $request[self::TYPE] ?? null;
                    $object = $request[self::OBJECT] ?? null;
                    switch ($type) {
                        case self::CONFIRMATION:
                            /** @var \App\Entity\Vk\VkBot|null $vkBot */
                            $vkBot = $this->defaultEntityManager->find(VkBot::class, $bot->getId());
                            if ($vkBot) {
                                return new Response($vkBot->getConfirmationToken());
                            } else {
                                $this->logger?->error(sprintf(self::ERROR_FIND_BOT_ENTITY_ID, $bot->getId()));
                            }
                            break;
                        case self::MESSAGE_NEW && !empty($object): // @phpstan-ignore-line
                            $this->bus->dispatch(
                                new MessengerUpdate(
                                    new VkUpdate($object, $bot)
                                )
                            );
                    }
                }
            } catch (\Throwable $e) {
                $this->logger?->error($e->getMessage());
            }
        }
        return new Response('ok');
    }
}
