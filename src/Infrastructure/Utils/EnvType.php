<?php

declare(strict_types=1);

namespace App\Infrastructure\Utils;

class EnvType
{
    public const PROD = 'prod',
    STAGE = 'stage',
    TEST = 'test',
    LOCAL = 'local',
    DEV = 'dev';
}
