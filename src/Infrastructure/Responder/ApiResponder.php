<?php

declare(strict_types=1);

namespace App\Infrastructure\Responder;

use App\Domain\SharedCore\Dto\HasIdInterface;
use App\Domain\SharedCore\Dto\HasUuidInterface;
use App\Domain\SharedCore\Dto\Response\JsonResponseDto;
use App\Domain\SharedCore\Exception\ValidationException;
use App\Domain\SharedCore\Exception\ValidatorRuntimeException;
use App\Infrastructure\Utils\ViolationsListParser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\RuntimeException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiResponder
{
    /**
     * @param Serializer $serializer
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly ViolationsListParser $violationListParser,
        private readonly string $appDeployment
    ) {
    }

    /**
     * @return $this
     * @throws ValidationException
     * @throws ValidatorRuntimeException
     */
    public function validateResponse(JsonResponseDto $jsonResponseDto): self
    {
        if ($this->appDeployment !== 'prod') {
            try {
                $validationResult = $this->validator->validate($jsonResponseDto);
            } catch (RuntimeException $e) {
                throw new ValidatorRuntimeException($e->getMessage());
            }
            if ($validationResult->count() > 0) {
                throw new ValidationException($this->violationListParser->parseToApiErrList($validationResult));
            }
        }

        return $this;
    }

    /**
     * @param mixed[] $serializerContext
     * @param mixed[] $headers
     */
    public function createResponse(
        JsonResponseDto $jsonResponseDto,
        int $status = Response::HTTP_OK,
        array $serializerContext = [],
        array $headers = []
    ): JsonResponse {
        $baseContext = [
            JsonEncode::OPTIONS => JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['__initializer__', '__cloner__', '__isInitialized__'],
            DateTimeNormalizer::FORMAT_KEY => DATE_ATOM,
        ];
        $baseContext[AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER] = function ($object) {
            $id = null;

            if ($object instanceof HasIdInterface) {
                $id = $object->getId();
            }

            if ($object instanceof HasUuidInterface) {
                $id = $object->getUuid();
            }
            return $id;
        };

        $serializerContext = array_replace_recursive($baseContext, $serializerContext);

        $response = $this->serializer->serialize(
            $jsonResponseDto,
            JsonEncoder::FORMAT,
            $serializerContext
        );

        return new JsonResponse($response, $status, $headers, true);
    }

    /**
     * Создание респонса без тела ответа.
     *
     * @param mixed[] $headers
     */
    public function createEmptyResponse(int $status = Response::HTTP_NO_CONTENT, array $headers = []): Response
    {
        return new Response(status: $status, headers: $headers);
    }
}
