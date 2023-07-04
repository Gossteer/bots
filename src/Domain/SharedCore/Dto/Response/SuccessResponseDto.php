<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(description: "Результат api")]
class SuccessResponseDto implements JsonResponseDto
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly bool $success = true
    ) {
    }
}
