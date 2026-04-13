<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Validation;

final class ValidationError
{
    public function __construct(
        public readonly string $message,
        public readonly ?string $field = null
    ) {
    }
}
