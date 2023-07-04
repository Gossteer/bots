<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto\Response;

use App\Domain\SharedCore\Types\ErrorType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Класс стандартного сообщения об ошибке
 */
#[OA\Schema(title: "ErrorMessage", description: "Сообщение об ошибке")]
class ErrorMessage implements JsonResponseDto
{
    public function __construct(
        #[OA\Property(title: "Текст сообщения", description: "Текст сообщения")]
        #[Assert\NotBlank]
        public readonly string $text = "",
        #[OA\Property(title: "Заголовок сообщения", description: "Заголовок сообщения")]
        #[Assert\NotBlank]
        public readonly string $title = "Ошибка",
        #[OA\Property(
            title: "Тип сообщения",
            description: "Тип сообщения",
            type: "string",
            default: ErrorType::User,
        )]
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [ErrorType::class, 'cases'])]
        public readonly ErrorType $type = ErrorType::User,
        /**
         * @var null|ValidationError[]
         */
        #[OA\Property(
            title: "Ошибки валидации",
            description: "Ошибки валидации",
            type: "array",
            items: new OA\Items(ref: new Model(type: ValidationError::class)),
            minItems: 0,
            nullable: true,
        )]
        public readonly ?array $validationErrors = null,
    ) {
    }
}
