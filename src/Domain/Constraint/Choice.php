<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Constraint;

use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;
use Iriven\PhpFormGenerator\Domain\Validation\ValidationError;

final class Choice implements ConstraintInterface
{
    /** @param array<int|string, mixed> $choices */
    public function __construct(
        private readonly array $choices,
        private readonly string $message = 'This value is not a valid choice.'
    ) {
    }

    public function validate(mixed $value, array $context = []): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        return in_array($value, array_values($this->choices), true)
            ? []
            : [new ValidationError($this->message)];
    }
}
