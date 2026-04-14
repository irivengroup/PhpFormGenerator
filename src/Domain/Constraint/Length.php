<?php

declare(strict_types=1);

namespace Iriven\PhpFormGenerator\Domain\Constraint;

use Iriven\PhpFormGenerator\Domain\Contract\ConstraintInterface;

final class Length implements ConstraintInterface
{
    use TranslatableConstraintMessageTrait;
    public function __construct(
        private readonly ?int $min = null,
        private readonly ?int $max = null,
        private readonly string $message = 'This value has an invalid length.',
    ) {
    }

    /** @param array<string, mixed> $context @return array<int, string> */
    public function validate(mixed $value, array $context = []): array
    {
        if ($value === null) {
            return [];
        }

        $length = mb_strlen((string) $value);

        if ($this->min !== null && $length < $this->min) {
            return [$this->messageFromContext($context, 'length.invalid', $this->message, ['min' => $this->min, 'max' => $this->max])];
        }

        if ($this->max !== null && $length > $this->max) {
            return [$this->messageFromContext($context, 'length.invalid', $this->message, ['min' => $this->min, 'max' => $this->max])];
        }

        return [];
    }
}
