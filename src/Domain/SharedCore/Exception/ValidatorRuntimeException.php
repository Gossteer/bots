<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use Symfony\Component\HttpFoundation\Response;

class ValidatorRuntimeException extends BaseApiException
{
    public function __construct(string $message, int $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct(new ErrorMessage($message), $code);
    }
}
