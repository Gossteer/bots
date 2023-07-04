<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Domain\SharedCore\Dto\Response\JsonResponseDto;
use App\Domain\SharedCore\Exception\ValidationException;
use App\Domain\SharedCore\Exception\ValidatorRuntimeException;
use App\Infrastructure\Responder\ApiResponder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ApiResponder $responder,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onBaseResponse'],
        ];
    }

    /**
     * @throws ValidationException
     * @throws ValidatorRuntimeException
     */
    public function onBaseResponse(ViewEvent $viewEvent): void
    {
        $response = $viewEvent->getControllerResult();
        if ($response === null) {
            $viewEvent->setResponse($this->responder->createEmptyResponse());
            return;
        }

        if ($response instanceof JsonResponseDto) {
            $viewEvent->setResponse(
                $this->responder->validateResponse($response)
                    ->createResponse($response)
            );
            return;
        }

        throw new \Exception('Необходимо при ответе использовать интерфейс JsonResponseDto|void|nul');
    }
}
