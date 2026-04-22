<?php

declare(strict_types=1);

namespace Iriven\Fluxon\Domain\Constraint;

use Iriven\Fluxon\Domain\Contract\ConstraintInterface;

final class MaxFileSize implements ConstraintInterface
{
    use TranslatableConstraintMessageTrait;
    public function __construct(
        private readonly int $maxBytes,
        private readonly string $message = 'The uploaded file is too large.',
    ) {
    }

    /** @param array<string, mixed> $context @return array<int, string> */
    public function validate(mixed $value, array $context = []): array
    {
        if (!is_array($value)) {
            return [];
        }

        $size = $value['size'] ?? null;
        if (!is_int($size)) {
            return [];
        }

        return $size <= $this->maxBytes ? [] : [$this->messageFromContext($context, 'max_file_size.invalid', $this->message, ['max_bytes' => $this->maxBytes])];
    }
}
