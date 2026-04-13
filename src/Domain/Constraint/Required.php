<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Constraint;

use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;

final class Required implements ConstraintInterface
{
    public function __construct(private readonly string $message = 'This value is required.')
    {
    }

    public function validate(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [$this->message];
        }

        return [];
    }
}
