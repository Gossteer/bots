<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(title: "Error", description: "Ошибка")]
class ValidationError implements JsonResponseDto
{
    #[OA\Property(title: "Path", description: "Поле, к которому относится ошибка")]
    private ?string $path;

    #[OA\Property(title: "Value", description: "Значение поля, которое вызвало ошибку")]
    private ?string $value;

    #[OA\Property(title: "TelegramMessage", description: "Текст ошибки")]
    private ?string $message;

    public function __construct(
        ?string $message = null,
        ?string $path = null,
        mixed $value = null
    ) {
        $this->setValue($value);
        $this->path = $path;
        $this->message = $message;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    private function setValue(mixed $value): ValidationError
    {
        if (null === $value) {
            $this->value = $value;
        } elseif (
            $value instanceof \Stringable
            || is_string($value)
            || is_float($value)
            || is_int($value)
        ) {
            $this->value = (string)$value;
        } elseif ($value instanceof \JsonSerializable) {
            $this->value = (string)json_encode($value);
        } elseif (is_bool($value)) {
            $this->value = $value ? "TRUE" : "FALSE";
        } elseif (is_array($value)) {
            $this->value = (string)json_encode($value);
        } else {
            $this->value = serialize($value);
        }
        return $this;
    }
}
