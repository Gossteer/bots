<?php

declare(strict_types=1);

namespace App\Infrastructure\Sentry;

use App\Domain\SharedCore\Exception\BaseApiException;
use App\Infrastructure\Utils\EnvType;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\Severity;
use Symfony\Component\HttpFoundation\Response;

class BeforeSend
{
    public function getBeforeSend(string $appDeployment): callable
    {
        return function (Event $event, ?EventHint $hint) use ($appDeployment): ?Event {
            if ($appDeployment === EnvType::LOCAL) {
                return null;
            }

            if (
                $hint !== null
                && ($hint->exception instanceof BaseApiException)
                && $hint->exception->getCode() < Response::HTTP_INTERNAL_SERVER_ERROR
            ) {
                $event->setLevel(Severity::info());
            }
            return $event;
        };
    }
}
