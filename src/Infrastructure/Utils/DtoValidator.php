<?php

declare(strict_types=1);

namespace App\Infrastructure\Utils;

use App\Domain\SharedCore\Dto\Request\JsonRequestDto;
use App\Domain\SharedCore\Exception\ValidationException;
use App\Domain\SharedCore\Port\Utils\DtoValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoValidator implements DtoValidatorInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private ViolationsListParser $violationsListParser,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function validateJsonDto(JsonRequestDto $jsonDto): void
    {
        $violationList = $this->validator->validate($jsonDto);
        if ($violationList->count() > 0) {
            throw new ValidationException($this->violationsListParser->parseToApiErrList($violationList));
        }
    }
}
