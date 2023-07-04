<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Port\Utils;

interface IUuidService
{
    public function getUuid(): string;

    public function getUuidWithoutDashes(): string;
}
