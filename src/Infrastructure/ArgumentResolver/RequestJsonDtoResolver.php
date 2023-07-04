<?php

declare(strict_types=1);

namespace App\Infrastructure\ArgumentResolver;

use App\Domain\SharedCore\Dto\Request\JsonRequestDto;
use App\Domain\SharedCore\Exception\BaseApiException;
use App\Domain\SharedCore\Exception\InvalidJsonException;
use App\Infrastructure\Utils\DtoValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RequestJsonDtoResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly DtoValidator $validator,
    ) {
    }

    public function supports(ArgumentMetadata $argument): bool
    {
        return is_subclass_of((string)$argument->getType(), JsonRequestDto::class, true);
    }

    /**
     * @return \Generator<JsonRequestDto>
     * @throws BaseApiException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$this->supports($argument)) {
            return [];
        }

        /** @var class-string $className */
        $className = $argument->getType();
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                $className,
                JsonEncoder::FORMAT,
                [
                    'not_normalizable_value_exceptions' => [],
                ] // Не хотим получать ошибки, о том, что нет обязательных полей в конструкторе
            );
        } catch (ExceptionInterface $exception) {
            throw new InvalidJsonException($exception->getMessage());
        }
        $this->validator->validateJsonDto($dto);
        yield $dto;
    }
}
