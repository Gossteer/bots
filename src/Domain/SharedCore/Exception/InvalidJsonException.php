<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use Symfony\Component\HttpFoundation\Response;

class InvalidJsonException extends BaseApiException
{
    public function __construct(string $message)
    {
        parent::__construct(new ErrorMessage("Не верный JSON: $message"), Response::HTTP_BAD_REQUEST);
    }
}
