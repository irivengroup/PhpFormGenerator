<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Constraint;

use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;

final class Range implements ConstraintInterface
{
    use TranslatableConstraintMessageTrait;
    public function __construct(
        private readonly int|float $min,
        private readonly int|float $max,
        private readonly string $message = 'This value is out of range.',
    ) {
    }

    /** @param array<string, mixed> $context @return array<int, string> */
    public function validate(mixed $value, array $context = []): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (!is_numeric($value)) {
            return [$this->messageFromContext($context, 'range.invalid', $this->message)];
        }

        $numeric = (float) $value;

        return $numeric >= (float) $this->min && $numeric <= (float) $this->max ? [] : [$this->messageFromContext($context, 'range.invalid', $this->message, ['min' => $this->min, 'max' => $this->max])];
    }
}
