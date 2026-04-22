<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Form;

final class FormSubmissionHelper
{
    /**
     * @param array<string, mixed> $payload
     */
    public static function payloadValue(array $payload, string $name): mixed
    {
        return $payload[$name] ?? null;
    }
}
