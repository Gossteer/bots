<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Exception;

use App\Domain\SharedCore\Dto\Response\ErrorMessage;
use App\Domain\SharedCore\Dto\Response\ValidationError;
use App\Domain\SharedCore\Types\ErrorType;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseApiException extends \Exception
{
    private ErrorMessage $msg;

    public function __construct(ErrorMessage $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->msg = $message;
        parent::__construct($this->msg->text, $code);
    }

    public function getMsg(): ErrorMessage
    {
        return $this->msg;
    }

    /**
     * @param null|ValidationError[] $validationErrors
     */
    protected function message(
        string $text,
        string $title = "Ошибка",
        ErrorType $type = ErrorType::User,
        ?array $validationErrors = null
    ): ErrorMessage {
        return new ErrorMessage($text, $title, $type, $validationErrors);
    }
}
