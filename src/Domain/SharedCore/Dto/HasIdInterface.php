<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto;

interface HasIdInterface
{
    public function getId(): ?int;
}
