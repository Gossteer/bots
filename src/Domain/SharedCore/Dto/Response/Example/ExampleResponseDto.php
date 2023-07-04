<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto\Response\Example;

use App\Domain\SharedCore\Dto\Response\JsonResponseDto;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(title: "ExampleResponseDto", description: "Пример ResponseDto")]
class ExampleResponseDto implements JsonResponseDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $content
    ) {
    }
}
