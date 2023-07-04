<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception;

use App\Domain\SharedCore\Dto\Response\ValidationError;
use App\Domain\SharedCore\Types\ErrorType;
use Symfony\Component\HttpFoundation\Response;

class ValidationException extends BaseApiException
{
    /**
     * @param ValidationError[] $validationErrors
     */
    public function __construct(
        array $validationErrors,
        int $code = Response::HTTP_BAD_REQUEST,
    ) {
        parent::__construct(
            $this->message(
                "Ошибки при заполнении полей",
                "Ошибка",
                ErrorType::From,
                $validationErrors
            ),
            $code
        );
    }
}
