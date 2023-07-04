<?php

declare(strict_types=1);

namespace App\Domain\SharedCore\Dto\Response;

/**
 * Интерфейс-маркер для ArgumentResolver'а
 * Если Dto имплементит этот интерфейс (считай - имеет этот тип) -
 * она обрабатывается стандартным ArgumentResolver'ом
 */
interface JsonResponseDto
{
}
