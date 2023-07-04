<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Types;

enum ErrorType: string implements \JsonSerializable
{
    case System = 'system';
    case User = 'user';
    case Toast = 'toast';
    case From = 'form';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
