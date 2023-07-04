<?php

declare(strict_types=1);

namespace App\Infrastructure\Analytic\Handler;

interface AnalyticsHandlerInterface
{
    public function caBotMessageSent(): void;
}
