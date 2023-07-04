<?php

declare(strict_types=1);

namespace App\Infrastructure\Utils;

use App\Domain\SharedCore\Dto\Response\ValidationError;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsListParser
{
    /**
     * @return ValidationError[]
     */
    public function parseToApiErrList(ConstraintViolationListInterface $list): array
    {
        $errors = [];
        foreach ($list as $item) {
            $errors[] = new ValidationError(
                (string)$item->getMessage(),
                $item->getPropertyPath(),
                $item->getInvalidValue()
            );
        }
        return $errors;
    }
}
