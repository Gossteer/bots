<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto;

interface HasUuidInterface
{
    public function getUuid(): string;
}
