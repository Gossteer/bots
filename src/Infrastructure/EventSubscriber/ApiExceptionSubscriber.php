<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use App\Domain\SharedCore\Exception\BaseApiException;
use App\Domain\SharedCore\Types\ErrorType;
use App\Infrastructure\Responder\ApiResponder;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly ApiResponder $responder,
        private readonly string $appDeployment,
        private readonly int $debugException,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onApiException'],
        ];
    }

    public function onApiException(ExceptionEvent $event): void
    {
        //Иногда надо видеть что выбросилось без подавления (удобно смотреть в symfony-панель),
        //а лазить в логи контейнеров неудобно.
        //К тому же dev там много мусора пишет.
        //Можно передать заголовок X-DEBUG-EXCEPTION
        //или надолго включить у себя в .env.local DEBUG_EXCEPTION=1
        if (
            $this->appDeployment !== 'prod'
            && (
                $event->getRequest()->headers->has('X-DEBUG-EXCEPTION')
                || $this->debugException > 0
            )
        ) {
            return;
        }

        if (str_starts_with($event->getRequest()->getRequestUri(), '/internal/admin')) {
            //$this->processAdminException($event);
            return;
        }
        $exception = $event->getThrowable();
        $code = (int)($exception instanceof HttpException ? $exception->getStatusCode() : $exception->getCode());
        if ($code < Response::HTTP_MULTIPLE_CHOICES) { //значит тут не HTTP-код ошибки
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $event->setResponse($this->response($exception, $code));
    }

    private function getStdError(): ErrorMessage
    {
        return new ErrorMessage("Ошибка сервера", "Ошибка", ErrorType::System);
    }

    private function response(\Throwable $exception, int $code): JsonResponse
    {
        if ($exception instanceof BaseApiException) {
            $data = $exception->getMsg();
        } else {
            $data = new ErrorMessage($exception->getMessage(), "Ошибка", ErrorType::User);
        }

        if ($code >= Response::HTTP_INTERNAL_SERVER_ERROR) {
            $this->logger?->error(
                $exception->getMessage(),
                [
                    'data' => $data,
                    'exception' => $exception,
                ]
            );
            $data = $this->getStdError();
        }

        return $this->responder->validateResponse($data)
            ->createResponse(
                jsonResponseDto: $data,
                status: $code
            );
    }
}
