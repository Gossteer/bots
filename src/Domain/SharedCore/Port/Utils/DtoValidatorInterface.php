<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Port\Utils;

use App\Domain\SharedCore\Dto\Request\JsonRequestDto;

interface DtoValidatorInterface
{
    public function validateJsonDto(JsonRequestDto $jsonDto): void;
}
